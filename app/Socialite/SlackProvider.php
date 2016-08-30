<?php namespace HNG\Socialite;

use Illuminate\Http\Request;
use Laravel\Socialite\Two\User;
use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;

class SlackProvider extends AbstractProvider implements ProviderInterface
{
    /**
     * SlackProvider constructor.
     *
     * @param Request $request
     * @param string  $clientId
     * @param string  $clientSecret
     * @param string  $redirectUrl
     */
    public function __construct(Request $request, $clientId, $clientSecret, $redirectUrl)
    {
        $this->scopes = explode(',', env('SLACK_DEFAULT_PERMISSIONS'));

        parent::__construct($request, $clientId, $clientSecret, $redirectUrl);
    }

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

        return $response;
    }

    /**
     * {@inheritdoc}
     */
    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id'        => array_get($user, 'user.id'),
            'name'      => array_get($user, 'user.name'),
            'email'     => array_get($user, 'user.email'),
            'avatar'    => array_get($user, 'user.image_192'),
        ]);
    }
}