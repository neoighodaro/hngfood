<?php

namespace HNG\Http\Middleware;

use Closure;

class SlackCommandVerifier
{
    private $slackTokens;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $slackTokens = env('SLACK_COMMAND_TOKENS');

        $slackTokens = ($slackTokens)? explode(',', $slackTokens) : $slackTokens;

        if(empty($slackTokens))
        {
            return [
                'response_type' => 'ephemeral',
                'text'          => 'This app grants no slack access.',
            ];
        }

        if(!in_array($request_token, $slackTokens))
        {
            return [
                'response_type' => 'ephemeral',
                'text'          => 'Unlawful slack command request!',
            ];
        }

        $request_token = $request->get('token');

        return $next($request);
    }
}
