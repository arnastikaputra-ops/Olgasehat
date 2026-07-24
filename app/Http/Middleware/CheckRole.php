<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    /**
     * Handle an incoming request.
     * Usage: 'role:superadmin' or 'role:user' or 'role:pemiliklapangan'
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $role
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $role)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }

        $user = Auth::user();
        if ($user->role !== $role) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
