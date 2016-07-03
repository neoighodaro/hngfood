<?php

namespace HNG\Http\Middleware;

use Closure;
use HNG\Lunchbox;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class VerifyLunchboxID
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
        try {
            Lunchbox::select('id')->findOrFail($request->route('id'));
        } catch (ModelNotFoundException $e) {
            abort(404);
        }

        return $next($request);
    }
}
