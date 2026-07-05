<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;

class CheckSuspended {
    public function handle(Request $request, Closure $next) {
        if (auth()->check() && auth()->user()->is_suspended) {
            auth()->logout();
            return redirect('/')->with('error', 'Akun kamu telah disuspend. Hubungi admin.');
        }
        return $next($request);
    }
}
