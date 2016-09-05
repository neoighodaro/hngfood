<?php

namespace HNG\Http\Middleware;

use Closure;
use HNG\User;
use HNG\Traits\SlackResponse;
use Illuminate\Support\Facades\Gate;

class FreeLunchCommandVerifier {

    use SlackResponse;

    /**
     * Handle an incoming request.
     *
     * @param FreeLunchGiveOutRequest|\Illuminate\Http\Request $request
     * @param  \Closure                                        $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $from = User::fromSlackId($request->get('user_id'))->first();

        if ( ! $from OR ! Gate::forUser($from)->allows('free_lunch.grant')) {
            return $this->slackResponse("Sorry! You can't give free lunches.");
        }

        $username = $this->getUsernameFromRequest($request);

        if (empty($username) OR ! $to = User::whereUsername($username)->first()) {
            return $this->slackResponse("Oops! Can't find {$username} in your team!");
        }

        if ( ! $reason = $this->getFreeLunchReason($request)) {
            return $this->slackResponse("You have to tell what the free lunch is for!");
        }
        
        return $next($from, $to, $reason);
    }

    /**
     * Get reciever username from text & remove the @ from the username.
     *
     * @param $request
     * @return string
     */
    private function getUsernameFromRequest($request)
    {
        preg_match('/\s*@\w+/', $request->get('text'), $receiver);

        return str_replace('@', '', array_get($receiver, 0, ''));
    }

    /**
     * Get freelunch reason from text.
     *
     * @param $request
     * @return string
     */
    private function getFreeLunchReason($request)
    {
        $reason = preg_replace('/\s*@\w+/', '', $request->get('text'));

        return trim($reason);
    }
}
