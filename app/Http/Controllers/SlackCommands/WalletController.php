<?php

namespace HNG\Http\Controllers\SlackCommands;

use HNG\User;
use HNG\Traits\SlackResponse;
use HNG\Http\Requests\SlackCommandRequest as Request;

class WalletController extends Controller {

    use SlackResponse;

    /**
     * WalletController constructor.
     */
    public function __construct()
    {
         $this->middleware(['SlackUserExists', 'WalletSlackSubCommandExists']);
    }

    /**
     * Route to the proper method.
     *
     * @param Request $request
     * @return mixed
     */
    public function router(Request $request)
    {
        $method = $request->slack('text');

        return $this->{$method}($request);
    }

    /**
     * Gets the users wallet balance.
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function balance(Request $request)
    {
        $user = User::whereSlackId($request->get('user_id'))->first();

        $attachments = [];

        if ($freelunches = $user->freelunches()->active($user->id)->count()) {
            $msg = sprintf(
                "You stud! You currently have %d free %s.",
                $freelunches,
                str_plural('lunch', $freelunches)
            );

            $attachments[]['text'] = $msg;
        }

        $message = "You have NGN{$user->wallet} in your wallet!";

        return $this->slackResponse($message, $attachments);
    }

    /**
     * Coming soon...
     *
     * @param  Request $request
     * @return \Illuminate\Http\Response
     */
    public function transfer(Request $request)
    {
        return $this->slackResponse("This feature is not available yet!");
    }
}
