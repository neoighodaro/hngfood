<?php namespace HNG\Socialite;

use Laravel\Socialite\SocialiteManager;

class Socialite extends SocialiteManager
{
    public function createSlackDriver()
    {
        $config = $this->app['config']['services.slack'];

        return $this->buildProvider(SlackProvider::class, $config);
    }
}