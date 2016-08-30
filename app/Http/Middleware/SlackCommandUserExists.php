<?php

namespace HNG\Http\Middleware;

use Closure;
use HNG\User;

class SlackCommandUserExists
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
        if ( ! $user = User::whereSlackId($request->get('user_id'))->first()) {
            return [
                'response_type' => 'ephemeral',
                'text'          => 'Sorry! You are not a registered user.',
            ];
        }

        return $next($request);
    }
}
