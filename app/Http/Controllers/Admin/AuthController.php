<?php
namespace HNG\Http\Controllers\Admin;

use Illuminate\Http\Request;
use HNG\Http\Controllers\Controller;

class AuthController extends Controller {

    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('verifyAdmin');
    }

    /**
     * Authenticate an admin account.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function authForm()
    {
        return redirect()->route('admin.dashboard');
        // return view('admin.login');
    }

    /**
     * Authenticate the admin account.
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function authProcess(Request $request)
    {
        $validAdmin = auth()->validate([
            'email'    => auth()->user()->email,
            'password' => $request->get('password'),
        ]);

        if ($validAdmin) {
            session(['administrator' => true]);
        }

        return $validAdmin ? redirect()->route('admin.dashboard') : back();
    }
}