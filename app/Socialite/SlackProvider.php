<?php namespace HNG\Socialite;

use Laravel\Socialite\Two\User;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;

class SlackProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * {@inheritdoc}
     */
    protected $scopes = [
        'identity.basic',
        'identity.team',
        'identity.email',
        'identity.avatar'
    ];

    /**
     * {@inheritdoc}
     */
    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase('https://slack.com/oauth/authorize', $state);
    }

    /**
     * {@inheritdoc}
     */
    protected function getTokenUrl()
    {
        return 'https://slack.com/api/oauth.access';
    }

    /**
     * {@inheritdoc}
     */
    protected function getUserByToken($token)
    {
        $options = ['headers' => ['Accept' => 'application/json']];
        $endpoint = 'https://slack.com/api/users.identity?token='.$token;

        $response = $this->getHttpClient()->get($endpoint, $options)->getBody()->getContents();
        $response = json_decode($response, true);

        $endpoint2 = 'https://slack.com/api/users.info?token='.$token.'&user='.$response['user']['id'];
        $response2 = $this->getHttpClient()->get($endpoint2, $options)->getBody()->getContents();

        $finalResponse = json_decode($response2, true);
        $finalResponse['team'] = $response['team'];

        return $finalResponse;
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id'        => array_get($user, 'user.id'),
            'username'  => array_get($user, 'user.name'),
            'name'      => array_get($user, 'user.profile.real_name_normalized'),
            'email'     => array_get($user, 'user.profile.email'),
            'avatar'    => array_get($user, 'user.profile.image_192'),
        ]);
    }
}