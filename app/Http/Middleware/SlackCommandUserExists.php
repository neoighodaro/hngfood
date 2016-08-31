<?php

namespace HNG\Http\Middleware;

use Closure;
use HNG\User;
use HNG\Traits\SlackResponse;

class SlackCommandUserExists
{
    use SlackResponse;
    
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
            return $this->slackResponse('Sorry! You are not a registered user.');
        }

        return $next($request);
    }
}
