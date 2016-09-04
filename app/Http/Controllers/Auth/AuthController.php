<?php

namespace HNG\Http\Controllers\Auth;

use HNG\User;
use Exception;
use GuzzleHttp\Client;
use HNG\Events\UserWasCreated;
use HNG\Http\Controllers\Controller;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    /**
     * AuthController constructor.
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');

        parent::__construct();
    }

    /**
     * Redirect the user to the Slack authentication page.
     *
     * @return Response
     */
    public function redirectToProvider()
    {
        return Socialite::driver('slack')->redirect();
    }

    /**
     * Obtain the user information from Slack.
     *
     * @return Response
     */
    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('slack')->user();

            if (array_get($user->user, 'team.domain') !== option('SLACK_CREDENTIALS.domain')) {
                throw new Exception("Invalid slack team.");
            }
        } catch (Exception $e) {
            return redirect()->home();
        }

        $authUser = $this->findOrCreateUser($user);

        auth()->login($authUser, true);

        if (strpos(auth()->user()->slack_scopes, 'users:read') === false) {
            $authUrl = 'https://slack.com/oauth/authorize?scope=users:read&'.
                'client_id='.option('SLACK_CREDENTIALS.client_id').'&'.
                'state='.$authUser->id.'&'.
                'redirect_uri='.urlencode(route('auth.slack.callback.user'));

            header('Location: '.$authUrl);
            exit;
        }

        return redirect()->home();
    }

    /**
     * Handle the callback when the users:* scope is being requested.
     *
     * @return Response
     */
    public function handleProviderCallbackUser()
    {
        // Required Validators!
        ($code  = request()->get('code'))  OR abort(403);
        ($state = request()->get('state')) OR abort(403);
        ($user  = User::find($state))      OR abort(403);

        // Get the access token...
        $response = (new Client)->request('GET', 'https://slack.com/api/oauth.access', [
            'query' => [
                'code'          => $code,
                'client_id'     => option('SLACK_CREDENTIALS.client_id'),
                'client_secret' => option('SLACK_CREDENTIALS.client_secret'),
                'redirect_uri'  => route('auth.slack.callback.user')
            ]
        ])->getBody()->getContents();

        $response = json_decode($response);

        if ($response->ok == true) {
            $userInfo = $this->getUserInfoFromSlack($response->user_id, $response->access_token);

            // Update the user object based on these details...
            $user->slack_scopes = $response->scope;
            $user->name         = $userInfo->user->profile->real_name_normalized;
            $user->username     = $userInfo->user->name;
            $user->save();

            auth()->login($user, true);
        }

        // Something probably went wrong somewhere...
        return redirect()->home();
    }

    /**
     * Log user Out.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        auth()->logout();

        session()->forget('administrator');

        return redirect()->route('guest.home');
    }

    /**
     * Return user if exists; create and return if doesn't
     *
     * @param  $user
     * @return User
     */
    private function findOrCreateUser($user)
    {
        if ($authUser = User::where('slack_id', $user->id)->first()) {
            // Update the user stuff from slack...
            $authUser->name     = $user->name;
            $authUser->avatar   = $user->avatar;
            $authUser->save();

            return $authUser;
        }

        $createdUser = User::create([
            'slack_id'     => $user->id,
            'name'         => $user->name,
            'email'        => $user->email,
            'avatar'       => $user->avatar,
        ]);

        event(new UserWasCreated($createdUser));

        return $createdUser;
    }

    /**
     * @param $slackUserId
     * @param $accessToken
     * @return mixed
     */
    protected function getUserInfoFromSlack($slackUserId, $accessToken)
    {
        // Get user details from the request...
        $userInfo = (new Client)->request('GET', 'https://slack.com/api/users.info', [
            'query' => [
                'user'  => $slackUserId,
                'token' => $accessToken,
            ]
        ])->getBody()->getContents();

        return json_decode($userInfo);
    }
}
