<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

/** Game penuh hanya untuk akun yang sudah bayar; sisanya diarahkan ke halaman beli. */
class PunyaAkses
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->user()?->punyaAkses()) {
            return $next($request);
        }

        return $request->expectsJson() ? response()->json(['message' => 'Akses belum aktif.'], 402) : redirect('/beli');
    }
}
