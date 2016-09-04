<?php namespace HNG\Socialite;

use Laravel\Socialite\SocialiteManager;

class Socialite extends SocialiteManager {

    /**
     * Create the slack driver for Laravel socialite.
     *
     * @return \Laravel\Socialite\Two\AbstractProvider
     */
    public function createSlackDriver()
    {
        $config = (array) option('SLACK_CREDENTIALS');

        return $this->buildProvider(SlackProvider::class, $config);
    }
}