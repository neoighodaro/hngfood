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
        return [
            'text'          => $text,
            'attachments'   => $attachments,
            'response_type' => $private ? 'ephemeral' : 'in_channel',
        ];
    }

