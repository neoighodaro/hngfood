<?php
namespace HNG\Http\Controllers\Admin;

use HNG\Http\Controllers\Controller;

class AdminController extends Controller {

    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('verifyAdmin');
        $this->middleware('verifyAdminSession');
    }

    /**
     * Authenticate an admin account.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.index', [
            'inPageTitle' => 'Admin Dashboard',
        ]);
    }
}