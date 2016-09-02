<?php

namespace HNG\Http\Middleware;

use Closure;
use HNG\User;
use HNG\Traits\SlackResponse;

class FreeLunchCommandVerifier
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
        //check that user has the right to give lunch

        if(!$from = User::whereSlackId($request->get('user_id'))->role(User::SUPERUSER)->first()) {
            return $this->slackResponse("Ahan! Bet you know you can not give free lunch");
        }

        if(!$to = User::whereUsername(str_replace('@', '', $request->getFreeLunchReceiver()))->first()) {
            return $this->slackResponse("Free lunch is for real users only!");
        }

        if(!$reason = $request->getFreeLunchReason()) {
            return $this->slackResponse("You have to tell what the free lunch is for!");
        }

        return $next($from, $to, $reason);
    }
}
