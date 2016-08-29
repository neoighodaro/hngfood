<?php

namespace HNG\Http\Controllers\Auth;

use HNG\User;
use Exception;
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

            if (array_get($user->user, 'team.domain') !== config('services.slack.domain')) {
                throw new Exception("Invalid slack team.");
            }
        } catch (Exception $e) {
            return redirect()->route('auth.slack');
        }

        $authUser = $this->findOrCreateUser($user);

        auth()->login($authUser, true);

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
            //$authUser->username = $user->username;
            $authUser->avatar   = $user->avatar;
            $authUser->save();

            return $authUser;
        }

        $createdUser = User::create([
            'slack_id' => $user->id,
            //'username' => $user->username,
            'name'     => $user->name,
            'email'    => $user->email,
            'avatar'   => $user->avatar,
        ]);

        event(new UserWasCreated($createdUser));

        return $createdUser;
    }
}
