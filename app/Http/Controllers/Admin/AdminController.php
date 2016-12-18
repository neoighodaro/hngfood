<?php
namespace HNG\Http\Controllers\Admin;

use Carbon\Carbon;
use HNG\Http\Controllers\Controller;
use HNG\Lunchbox;

class AdminController extends Controller {

    /**
     * AdminController constructor.
     */
    public function __construct()
    {
        parent::__construct();

        $this->middleware('verifyAdmin');
        //$this->middleware('verifyAdminSession');
    }

    /**
     * Authenticate an admin account.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $carbon = new Carbon;

        $ordersOverview = [
            'today'   => Lunchbox::ordersSince($carbon->startOfDay())->get(),
            'month'   => Lunchbox::ordersSince($carbon->startOfMonth())->get(),
            'year'    => Lunchbox::ordersSince($carbon->startOfYear())->get(),
            'century' => Lunchbox::ordersSince($carbon->startOfCentury())->get()
        ];

        return view('admin.overview', [
            'inPageTitle'    => 'Admin Dashboard',
            'ordersOverview' => $ordersOverview,
        ]);
    }
}