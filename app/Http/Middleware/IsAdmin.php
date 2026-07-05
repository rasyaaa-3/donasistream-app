<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class IsAdmin {
    public function handle(Request $request, Closure $next) {
        // Belum login → ke halaman login
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Login tapi bukan admin → tolak
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak. Halaman ini khusus admin.');
        }

        return $next($request);
    }
}
