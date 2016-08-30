<?php
namespace HNG\Http\Controllers\SlackCommands;

use HNG\Http\Controllers\Controller as BaseController;

class Controller extends BaseController {

    /**
     * Return data in the slack response format.
     *
     * @param       $text
     * @param array $attachments
     * @param bool  $private
     * @return array
     */
    public function slackResponse($text, array $attachments = [], $private = true)
    {
        return [
            'text'          => $text,
            'attachments'   => $attachments,
            'response_type' => $private ? 'ephemeral' : 'in_channel',
        ];
    }

}