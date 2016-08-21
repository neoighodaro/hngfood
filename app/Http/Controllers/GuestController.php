<?php
namespace HNG\Http\Controllers;


class GuestController extends Controller {

    /**
     * GuestController constructor.
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Display the welcome page.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('welcome');
    }
}