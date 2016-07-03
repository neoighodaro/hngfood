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
        $this->middleware('verifyOrderId')->only('orderCompleted');
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
            'inPageTitle' => 'Dashboard',
            'timekeeper'  => $timekeeper,
            'bukas'       => $buka->all(),
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

        return Lunchbox::without('buka,lunches')->findOrFail($order->id);
    }

    /**
     * Completed an order successfully.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function orderCompleted($id)
    {
        $order = Lunchbox::without('orders')
            ->with('ordersGrouped')
            ->find($id);

        $dancers = config('app.dancers');
        $dancer  = $dancers[array_rand($dancers)];

        return view('order.completed', compact('order', 'dancer'));
    }

    public function orderHistory()
    {
        $orders = Lunchbox::get()->groupBy(function ($date) {
            return \Carbon\Carbon::parse($date->created_at)->format('M, Y');
        });

        return view('order.history', [
            'orders'      => $orders,
            'inPageTitle' => 'Order History',
        ]);
    }
}
