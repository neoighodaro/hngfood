<?php

namespace HNG\Http\Middleware;

use Closure;
use HNG\User;
use HNG\Traits\SlackResponse;
use Illuminate\Support\Facades\Gate;
use HNG\Http\Requests\FreeLunchGiveOutRequest;

class FreeLunchCommandVerifier {

    use SlackResponse;

    /**
     * Handle an incoming request.
     *
     * @param FreeLunchGiveOutRequest|\Illuminate\Http\Request $request
     * @param  \Closure                                        $next
     * @return mixed
     */
    public function handle(FreeLunchGiveOutRequest $request, Closure $next)
    {
        $from = User::fromSlackId($request->get('user_id'))->first();

        if ( ! $from OR ! Gate::forUser($from)->allows('free_lunch.grant')) {
            return $this->slackResponse("Sorry! You can't give free lunches.");
        }

        $username = $this->getUsernameFromRequest($request);

        if (empty($username) OR ! $to = User::whereUsername($username)->first()) {
            return $this->slackResponse("Oops! Can't find {$username} in your team!");
        }

        if ( ! $reason = $request->getFreeLunchReason()) {
            return $this->slackResponse("You have to tell what the free lunch is for!");
        }

        return $next($from, $to, $reason);
    }

    /**
     * Remove the @ from the username.
     *
     * @param $request
     * @return mixed
     */
    protected function getUsernameFromRequest($request)
    {
        return str_replace('@', '', $request->getFreeLunchReceiver());
    }
}
