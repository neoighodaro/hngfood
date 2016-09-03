<?php

namespace HNG\Http\Middleware;

use Closure;
use HNG\Traits\SlackResponse;
use HNG\Http\Controllers\SlackCommands\WalletController;

class WalletSlackSubCommandExists {

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
        if ( ! method_exists(new WalletController, $request->get('text'))) {
            return $this->slackResponse("Invalid command! */wallet help* to get valid list of responses!");
        }

        return $next($request);
    }
}
