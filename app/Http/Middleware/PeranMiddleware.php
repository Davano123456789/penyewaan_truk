<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class PeranMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $peran): Response
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Mapping peran string ke id
        $peranIds = [
            'admin' => 1,
            'client' => 2,
            'sopir' => 3,
        ];

        // Cek apakah peran valid
        if (!array_key_exists($peran, $peranIds)) {
            abort(403, 'Role tidak valid.');
        }

        // Cek apakah user memiliki peran yang sesuai
        if ($user->peran_id !== $peranIds[$peran]) {
            // Jika tidak sesuai, arahkan ke halaman yang sesuai dengan perannya atau 403
            if ($user->peran_id == 1) {
                return redirect()->route('dashboard');
            } elseif ($user->peran_id == 2) {
                return redirect()->route('penyewaan.index');
            } elseif ($user->peran_id == 3) {
                return redirect()->route('penugasan.index');
            }
            
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
