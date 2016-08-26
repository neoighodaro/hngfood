<?php

namespace HNG\Http\Controllers\Admin;

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
        return view('admin.users.list', ['users' => User::paginate(50)]);
    }
}
