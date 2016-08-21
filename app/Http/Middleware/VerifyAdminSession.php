<?php

namespace HNG\Http\Middleware;

use Closure;

class VerifyAdminSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ( ! session('administrator')) {
            return redirect(route('admin.login'));
        }

        return $next($request);
    }
}
