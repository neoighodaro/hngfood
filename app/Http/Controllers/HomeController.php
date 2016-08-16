<?php

namespace HNG\Http\Controllers;

use HNG\Buka;
use HNG\Lunchbox;
use HNG\Freelunch;
use Carbon\Carbon;
use HNG\Http\Requests;
use HNG\Lunch\Timekeeper;
use Illuminate\Http\Request;
use HNG\Events\LunchWasOrdered;
use HNG\Http\Requests\OrderRequest;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('timekeeper')->only('order');
        $this->middleware('verifyOrderId')->only('orderCompleted');
    }

    /**
     * Show the application dashboard.
     *
     * @param  Timekeeper $timekeeper
     * @param  Buka       $buka
     * @param  Freelunch  $freelunch
     * @return \Illuminate\Http\Response
     */
    public function index(Timekeeper $timekeeper, Buka $buka, Freelunch $freelunch)
    {
        return view('home', [
            'inPageTitle' => 'Dashboard',
            'timekeeper'  => $timekeeper,
            'bukas'       => $buka->all(),
            'freelunch'   => $freelunch->active(),
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

        event(new LunchWasOrdered($order, $request));

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
        $order = Lunchbox::find($id);

        $dancers = config('app.dancers');
        $dancer  = $dancers[array_rand($dancers)];

        return view('order.completed', compact('order', 'dancer'));
    }


    /**
     * Get order history.
     *
     * @param  Request $request
     * @return Response
     * @todo   Create a filter system for the UI
     */
    public function orderHistory(Request $request)
    {
        $end   = $request->get('end');
        $start = $request->get('start', Carbon::now()->startOfMonth());

        $orders = Lunchbox::ordersBetween($start, $end)
            ->without('orders')
            ->with('buka')
            ->orderBy('created_at', 'DESC')
            ->paginate(10);

        return view('order.history', [
            'orders'      => $orders,
            'inPageTitle' => 'Orders History',
        ]);
    }

}
