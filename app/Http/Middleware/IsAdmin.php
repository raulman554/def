<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;


class IsAdmin
{
    public function handle($request, Closure $next)
    {
        if (! Auth::guard('administrador')->check()) {
            return redirect()->route('admin.login');
        }
        return $next($request);
    }
}
