<?php
namespace HNG\Http\Middleware;

use Closure;
use HNG\Lunch\Timekeeper;

class VerifyValidOrderTime
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure                 $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ( ! (new Timekeeper)->isWithinLunchOrderHours()) {
            abort(403);
        }

        return $next($request);
    }
}