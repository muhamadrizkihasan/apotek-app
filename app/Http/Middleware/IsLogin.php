<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class IsLogin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            // Kalau Auth sudah mendeteksi ada riwayat login, maka dibolehkan akses route terkait
            return $next($request);
        } else {
            // Kalau gak ada, diarahkan ke halaman login balik
            return redirect()->route('login')->with('failed', 'Anda belum login!');
        }
    }
}
