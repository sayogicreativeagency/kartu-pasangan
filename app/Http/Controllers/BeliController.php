<?php

namespace App\Http\Controllers;

use App\Enums\StatusBayar;
use App\Models\Pembayaran;
use App\Support\Qris;
use Illuminate\Http\Request;

/** Beli akses: tagihan dengan nominal unik → bayar QRIS → lapor → dicek admin di /admin. */
class BeliController extends Controller
{
    public function tampil(Request $request)
    {
        $user = $request->user();
        if ($user->punyaAkses()) {
            return redirect('/app');
        }

        // Satu tagihan terbuka per akun, nominalnya tetap supaya QR yang sudah dipindai tidak berubah.
        $tagihan = $user->pembayaran()->terbuka()->latest('id')->first()
            ?? $user->pembayaran()->create(['jenis' => 'sekali', 'nominal' => Pembayaran::nominalUnik(config('mesra.harga_sekali'))]);

        $qris = new Qris((string) config('mesra.qris'));

        return view('beli', [
            'tagihan' => $tagihan,
            'qris' => $qris->valid() ? $qris->denganNominal($tagihan->nominal) : null,
            'penerima' => $qris->valid() ? $qris->namaPenerima() : null,
        ]);
    }

    public function lapor(Request $request)
    {
        $data = $request->validate(['pengirim' => ['required', 'string', 'max:100']], [
            'pengirim.required' => 'Tulis nama pengirim di aplikasi pembayaranmu.',
        ]);

        $tagihan = $request->user()->pembayaran()->terbuka()->latest('id')->firstOrFail();
        $tagihan->update([...$data, 'status' => StatusBayar::Dilaporkan, 'dilaporkan_at' => now()]);

        return redirect('/beli')->with('status', 'Terima kasih, laporanmu sudah masuk. Akses aktif setelah pembayaran dicek admin.');
    }
}
