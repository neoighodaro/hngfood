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
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function list()
    {
        $users = (new User)->filteredList();

        return view('admin.users.list', [
            'searchQuery' => request()->get('q'),
            'inPageTitle' => 'User Management',
            'users' => $users
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

        $user->updateRoleWalletAndFreelunches($request->only([
            'role',
            'wallet',
            'freelunch'
        ]));

        event(new UserWasUpdated([
            'oldUser'       => $oldUser,
            'updateRequest' => $request,
            'updatedUser'   => User::find($user->id),
        ]));

        return ['status' => 'success'];
    }
}
