@extends('layouts.app')

@section('title', 'Order Completed - '.env('APP_NAME'))

@section('content')
<div class="enjoy-it">
    <div class="container">
        <div class="col-12">
            <div class="trigger"></div>
            <svg version="1.1" id="tick" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                 viewBox="0 0 37 37" style="enable-background:new 0 0 37 37;" xml:space="preserve">
                <path class="circ path"
                      style="fill:none;stroke:#10c110;stroke-width:3;stroke-linejoin:round;stroke-miterlimit:10;"
                      d="M30.5,6.5L30.5,6.5c6.6,6.6,6.6,17.4,0,24l0,0c-6.6,6.6-17.4,6.6-24,0l0,0c-6.6-6.6-6.6-17.4,0-24l0,0C13.1-0.2,23.9-0.2,30.5,6.5z"/>
                <polyline class="tick path"
                          style="fill:none;stroke:#10c110;stroke-width:3;stroke-linejoin:round;stroke-miterlimit:10;"
                          points="11.6,20 15.9,24.2 26.4,13.8 "/>
            </svg>
            <h2>Order Completed! <a href="#" data-toggle="modal" data-target="#order-overview">View Order</a>.</h2>
            <div class="garri-photo">
                <img src="{{ asset($dancer) }}" alt="Order Completed">
            </div>
        </div>
    </div>
</div>

<!-- Order Overview Modal -->
<div id="order-overview" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Order Invoice</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <p>
                            Here's a quick overview of this order which was placed roughly
                            <strong class="dim">{{$order->created_at->diffForHumans()}}</strong>.
                        </p>
                        <table class="table order-overview-table">
                            <tbody>
                                @foreach ($order->ordersGrouped() as $food)
                                <tr>
                                    <th scope="row">{{$food->name}}</th>
                                    <td class="right">&#8358;{{$food->cost * $food->servings}}</td>
                                </tr>
                                @endforeach
                                @if ($order->buka->base_cost > 0)
                                <tr>
                                    <th scope="row">Base Cost</th>
                                    <td class="right">&#8358;{{$order->buka->base_cost}}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th scope="row">Total:</th>
                                    <td class="right">&#8358;{{$order->totalCost()}}</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
@endsection