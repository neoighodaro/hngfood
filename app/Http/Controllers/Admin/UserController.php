<?php

namespace HNG\Http\Controllers\Admin;

use HNG\User;
use HNG\Http\Requests;
use HNG\Events\UserWasUpdated;
use HNG\Http\Controllers\Controller;

class UserController extends Controller {

    /**
     * Get the list of all the users.
     *
     * @param User $user
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userlist(User $user)
    {
        return view('admin.users.list', [
            'inPageTitle' => 'User Management',
            'searchQuery' => request()->get('q'),
            'users'       => $user->filteredList(),
        ]);
    }

    /**
     * Update the user.
     *
     * @param  Requests\AdminUserUpdateRequest $request
     * @return array
     */
    public function update(Requests\AdminUserUpdateRequest $request)
    {
        $user = User::find($request->get('user_id'));

        $oldUser = clone $user;

        $user->updateRoleWalletAndFreelunches(
            $request->only(['role', 'wallet', 'freelunch'])
        );

        event(new UserWasUpdated([
            'oldUser'       => $oldUser,
            'updateRequest' => $request,
            'updatedUser'   => User::find($user->id),
        ]));

        return ['status' => 'success'];
    }
}
