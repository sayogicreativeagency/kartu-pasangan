<?php

namespace Tests\Feature;

use App\Enums\StatusBayar;
use App\Models\Deck;
use App\Models\Pembayaran;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AppFlowTest extends TestCase
{
    use RefreshDatabase;

    private function lunas(?User $user = null): User
    {
        $user ??= User::factory()->create();
        $user->pembayaran()->create(['jenis' => 'sekali', 'nominal' => 70001])->setujui();

        return $user;
    }

    public function test_web_shows_landing_and_guest_app_goes_to_login(): void
    {
        $this->get('/')->assertOk()->assertSee('Jangan Ganggu')->assertSee('Malam berdua')->assertSee('18+')->assertSee('/register')->assertSee('/coba');
        $this->get('/coba')->assertOk()->assertSee('Kartu Pasangan');
        $this->get('/app')->assertRedirect('/login');
        $this->get('/login')->assertOk();
        $this->get('/index.html')->assertRedirect('/');
    }

    public function test_seo_basics(): void
    {
        $this->get('/')->assertSee('og:image', false)->assertSee('"@context":"https://schema.org"', false)->assertDontSee('noindex');
        $this->get('/login')->assertSee('noindex');
        $this->actingAs($this->lunas())->get('/app')->assertSee('noindex');
        $this->get('/robots.txt')->assertOk()->assertSee('Sitemap: '.url('/sitemap.xml'));
        $this->get('/sitemap.xml')->assertOk()->assertSee(url('/coba'));
    }

    public function test_installed_app_starts_at_app_route(): void
    {
        $manifest = json_decode(file_get_contents(public_path('manifest.json')), true);

        $this->assertSame('/app', $manifest['start_url']);
    }

    public function test_remember_cookie_keeps_user_logged_in_after_session_is_gone(): void
    {
        $user = $this->lunas();

        $login = $this->post('/login', ['email' => $user->email, 'password' => 'password']);
        $login->assertRedirect('/app');

        $name = Auth::guard()->getRecallerName();
        $cookie = collect($login->headers->getCookies())->first(fn ($c) => $c->getName() === $name);
        $this->assertNotNull($cookie, 'Login harus memasang remember cookie');
        $this->assertGreaterThan(now()->addDays(300)->timestamp, $cookie->getExpiresTime());
        $recaller = $login->getCookie($name, decrypt: true)->getValue();

        // HP dibuka lagi berbulan-bulan kemudian: sesi sudah hilang, tinggal remember cookie.
        DB::table('sessions')->delete();
        $this->flushSession();
        Auth::forgetGuards();

        $this->withCookie($name, $recaller)->get('/app')->assertOk();
        $this->assertAuthenticatedAs($user);
    }

    public function test_logged_in_user_skips_login_and_can_log_out(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/login')->assertRedirect('/app');
        $this->post('/logout')->assertRedirect('/login');
        $this->assertGuest();
    }

    public function test_register_creates_account_and_logs_in(): void
    {
        $this->post('/register', [
            'name' => 'Pasangan',
            'email' => 'pasangan@example.com',
            'password' => 'rahasia123',
            'password_confirmation' => 'rahasia123',
        ])->assertRedirect('/app');

        $this->assertAuthenticated();
    }

    public function test_state_round_trip_is_exact_and_encrypted_at_rest(): void
    {
        $user = $this->lunas();
        $state = '{"players":["",""],"custom":{},"refQ":"  ada spasi ","_at":1}';

        $this->actingAs($user)->get('/app/state')->assertOk()->assertHeader('X-CSRF-Token');
        $this->assertSame('null', $this->get('/app/state')->getContent());

        $this->call('PUT', '/app/state', content: $state)->assertNoContent();

        // `custom:{}` tidak boleh berubah jadi `[]`, string kosong & spasi tidak boleh dirapikan middleware.
        $this->assertSame($state, $this->get('/app/state')->getContent());
        $this->assertStringNotContainsString('players', DB::table('users')->where('id', $user->id)->value('game_state'));
    }

    public function test_state_rejects_non_object_and_guests(): void
    {
        $this->call('PUT', '/app/state', content: '{}', server: ['HTTP_ACCEPT' => 'application/json'])->assertUnauthorized();

        $this->actingAs($this->lunas());
        $this->call('PUT', '/app/state', content: '[1,2]')->assertStatus(422);
        $this->call('PUT', '/app/state', content: 'bukan json')->assertStatus(422);
        $this->call('PUT', '/app/state', content: '{"x":"'.str_repeat('a', 256 * 1024).'"}')->assertStatus(422);
    }

    public function test_expired_csrf_on_form_returns_to_login_instead_of_dead_end(): void
    {
        $this->withMiddleware();
        $this->app['env'] = 'local';   // VerifyCsrfToken melewati pengecekan di env testing

        $this->post('/login', ['email' => 'a@b.c', 'password' => 'x'])->assertRedirect('/login');
    }

    public function test_unpaid_account_goes_to_purchase_with_unique_amount(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->get('/app')->assertRedirect('/beli');
        $this->getJson('/app/state')->assertStatus(402);

        $beli = $this->get('/beli')->assertOk()->assertSee('Rp 70.');
        $tagihan = $user->pembayaran()->sole();
        $this->assertGreaterThan(70000, $tagihan->nominal);
        $this->assertLessThan(71000, $tagihan->nominal);
        $this->get('/beli');
        $this->assertSame(1, $user->pembayaran()->count(), 'Buka ulang halaman beli tidak boleh membuat tagihan baru');

        $this->post('/beli', ['pengirim' => 'John Doe'])->assertRedirect('/beli');
        $this->assertSame(StatusBayar::Dilaporkan, $tagihan->fresh()->status);
        $this->get('/app')->assertRedirect('/beli');

        $tagihan->fresh()->setujui();
        $this->get('/app')->assertOk();
        $this->get('/beli')->assertRedirect('/app');
    }

    public function test_subscription_payment_expires(): void
    {
        $user = User::factory()->create();
        $user->pembayaran()->create(['jenis' => 'langganan', 'bulan' => 1, 'nominal' => 30001])->setujui();
        $this->assertTrue($user->punyaAkses());

        $this->travel(32)->days();
        $this->assertFalse($user->fresh()->punyaAkses());
    }

    public function test_admin_panel_only_for_admin_emails(): void
    {
        config(['mesra.admin_emails' => ['admin@example.test']]);

        $this->get('/admin')->assertRedirect('/login');
        $this->actingAs($this->lunas())->get('/admin')->assertForbidden();

        $admin = User::factory()->create(['email' => 'admin@example.test']);
        $this->actingAs($admin)->get('/admin')->assertOk();
        $this->get('/admin/decks')->assertOk();
        $this->get('/admin/pembayarans')->assertOk();
        $this->get('/app')->assertOk();   // admin tidak perlu bayar
    }

    public function test_deck_admin_fills_decks_but_cannot_see_money_or_accounts(): void
    {
        config(['mesra.admin_emails' => ['admin@example.test']]);
        $super = User::factory()->create(['email' => 'admin@example.test']);

        $this->actingAs($super)->get('/admin/users')->assertOk();

        $nizar = User::factory()->create(['email' => 'deck@example.test']);
        $nizar->forceFill(['is_admin' => true])->save();

        $this->actingAs($nizar)->get('/admin/decks')->assertOk();
        $this->get('/admin/pembayarans')->assertForbidden();
        $this->get('/admin/users')->assertForbidden();
        $this->get('/app')->assertOk();
    }

    public function test_superadmin_creates_deck_admin_from_panel(): void
    {
        config(['mesra.admin_emails' => ['admin@example.test']]);
        $this->actingAs(User::factory()->create(['email' => 'admin@example.test']));

        \Livewire\Livewire::test(\App\Filament\Resources\Users\Pages\ManageUsers::class)
            ->callAction('create', data: ['name' => 'John Doe', 'email' => 'deck@example.test', 'password' => 'rahasia123', 'is_admin' => true])
            ->assertHasNoActionErrors();

        $akun = User::where('email', 'deck@example.test')->sole();
        $this->assertTrue($akun->isAdmin());
        $this->assertFalse($akun->isSuperadmin());
        $this->assertTrue(\Illuminate\Support\Facades\Hash::check('rahasia123', $akun->password));
    }

    public function test_register_cannot_grant_admin(): void
    {
        $this->post('/register', [
            'name' => 'Iseng', 'email' => 'iseng@example.com', 'is_admin' => 1,
            'password' => 'rahasia123', 'password_confirmation' => 'rahasia123',
        ]);

        $this->assertFalse(User::where('email', 'iseng@example.com')->sole()->isAdmin());
    }

    public function test_admin_decks_reach_the_game(): void
    {
        $acak = Deck::create(['judul' => 'Acak', 'jenis' => 'game', 'featured' => false]);
        $acak->cards()->create(['teks' => 'Tantangan acak', 'keterangan' => 'cara', 'detik' => 30]);

        $this->assertNull(Deck::untukGame()['game']['featured']);
        $this->get('/coba')->assertSee('Tantangan acak', false)->assertSee('const BATAS_COBA = 3', false);

        $kedua = Deck::create(['judul' => 'Kedua', 'jenis' => 'game', 'featured' => true, 'urutan' => 2]);
        $kedua->cards()->create(['teks' => 'Bukan yang ini', 'urutan' => 1]);
        $pertama = Deck::create(['judul' => 'Pertama', 'jenis' => 'game', 'featured' => true, 'urutan' => 1]);
        $pertama->cards()->createMany([['teks' => 'Kartu dua', 'urutan' => 2], ['teks' => 'Kartu satu', 'urutan' => 1]]);
        Deck::create(['judul' => 'Mati', 'jenis' => 'game', 'featured' => true, 'urutan' => 0, 'aktif' => false])
            ->cards()->create(['teks' => 'Deck nonaktif']);

        $this->assertSame(['Kartu satu', 'Kartu dua'], array_column(Deck::untukGame()['game']['featured'], 't'));
        $this->actingAs($this->lunas())->get('/app')->assertSee('const BATAS_COBA = 0', false);
    }
}
