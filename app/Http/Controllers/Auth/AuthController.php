<?php

namespace HNG\Http\Controllers\Auth;

use HNG\User;
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
        } catch (Exception $e) {
            return redirect(route('auth.slack'));
        }

        $authUser = $this->findOrCreateUser($user);

        auth()->login($authUser, true);

        return redirect(route('home'));
    }

    /**
     * Log user Out.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout()
    {
        auth()->logout();

        return redirect('/');
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
            return $authUser;
        }

        $createdUser = User::create([
            'name'     => $user->name,
            'email'    => $user->email,
            'slack_id' => $user->id,
            'avatar'   => $user->avatar,
        ]);
            
        event(new UserWasCreated($createdUser));

        return $createdUser;
    }
}
