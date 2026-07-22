<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string|int  ...$roles
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        if (! $request->user()) {
            return redirect()->route('login');
        }

        $allowedRoles = array_map('intval', $roles);

        if (! in_array((int) $request->user()->role_id, $allowedRoles, true)) {
            abort(403, 'Anda tidak memiliki hak akses untuk membuka halaman ini.');
        }

        return $next($request);
    }
}
