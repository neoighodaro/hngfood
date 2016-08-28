<?php

namespace HNG\Http\Controllers\Admin;

use HNG\Freelunch;
use HNG\User;
use HNG\Http\Requests;
use HNG\Http\Controllers\Controller;

class UserController extends Controller {

    /**
     * Get the list of all the users.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function list()
    {
        return view('admin.users.list', [
            'inPageTitle' => 'User Management',
            'users' => User::orderBy('wallet', 'DESC')->paginate(50)
        ]);
    }

    public function update(Requests\AdminUserUpdateRequest $request)
    {
        $user = User::find($request->get('user_id'));
        $user->updateRole($request->get('role'));
        $user->updateFreelunch($request->get('freelunch'));

        // event(new UserWasUpdated($request));

        return ['status' => 'success', 'message' => 'user updated successfully.'];
    }
}
