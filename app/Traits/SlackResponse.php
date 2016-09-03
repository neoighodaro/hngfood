<?php 
namespace HNG\Traits;

trait SlackResponse{

/**
     * Return data in the slack response format.
     *
     * @param       $text
     * @param array $attachments
     * @param bool  $private
     * @return array
     */
    protected function slackResponse($text, array $attachments = [], $private = true)
    {
        if (is_array($text) AND (isset($text['text']) OR isset($text['attachments']))) {
            return $text;
        }

        return [
            'text'          => $text,
            'attachments'   => $attachments,
            'response_type' => $private ? 'ephemeral' : 'in_channel',
        ];
    }

}

