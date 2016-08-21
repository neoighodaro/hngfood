<?php

namespace HNG\Http\Middleware;

use Closure;

class VerifyAdminUser {

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $user = $request->user();

        if ( ! $user OR ! $user->hasRole('admin')) {
            return redirect(route('home'));
        }

        return $next($request);
    }
}
