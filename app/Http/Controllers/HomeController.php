<?php

namespace HNG\Http\Controllers;

use HNG\Buka;
use HNG\Events\LunchWasOrdered;
use HNG\Http\Requests;
use HNG\Lunch\Timekeeper;
use HNG\Http\Requests\OrderRequest;
use HNG\Lunchbox;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @param  Timekeeper $timekeeper
     * @param  Buka       $buka
     * @return \Illuminate\Http\Response
     */
    public function index(Timekeeper $timekeeper, Buka $buka)
    {
        return view('home', [
            'pageTitle'  => 'Dashboard',
            'timekeeper' => $timekeeper,
            'bukas'      => $buka->all(),
        ]);
    }

    /**
     * Create an order.
     *
     * @param  OrderRequest $request
     * @param  Lunchbox     $lunchbox
     * @return array
     */
    public function order(OrderRequest $request, Lunchbox $lunchbox)
    {
        $order = $lunchbox->createWithOrders($request);

        event(new LunchWasOrdered($order));

        return $order;
    }
}
