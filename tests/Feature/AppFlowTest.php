<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class AppFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_web_shows_landing_and_guest_app_goes_to_login(): void
    {
        $this->get('/')->assertOk()->assertSee('Kartu Pasangan');
        $this->get('/app')->assertRedirect('/login');
        $this->get('/login')->assertOk();
    }

    public function test_installed_app_starts_at_app_route(): void
    {
        $manifest = json_decode(file_get_contents(public_path('manifest.json')), true);

        $this->assertSame('/app', $manifest['start_url']);
    }

    public function test_remember_cookie_keeps_user_logged_in_after_session_is_gone(): void
    {
        $user = User::factory()->create();

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
        $user = User::factory()->create();
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

        $this->actingAs(User::factory()->create());
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
}
