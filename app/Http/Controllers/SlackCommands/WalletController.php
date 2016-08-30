<?php

namespace HNG\Http\Controllers\SlackCommands;

use HNG\User;
use HNG\Http\Requests\SlackCommandRequest as Request;

class WalletController extends Controller {

    /**
     * WalletController constructor.
     */
    public function __construct()
    {
        // @TODO Create Middlewares to verify the request...
        // $this->middleware('userExists')->only('balance');
        // $this->middleware('verifySlackRequest');
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

        // @TODO Random text depending on how much...
        $message = "You have NGN{$user->wallet} in your wallet!";

        $attachments = [];

        if ($freelunches = $user->freelunches()->active()->count()) {
            $attachments[]['text'] = "You currently have {$freelunches} free ".str_plural('lunch', $freelunches);
        }

        return $this->slackResponse($message, $attachments);
    }
}
