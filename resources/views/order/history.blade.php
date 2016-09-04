@extends('layouts.app')

@section('title', 'Order History - '.env('APP_NAME'))

@section('content')
<script type="text/javascript">var HNGOrderHistory = {};</script>
@if ($orders->count() > 0)
<div class="container smaller order-history">
    <div class="row">
        <div class="col-sm-12">
            <table class="table">
                <thead>
                    <th>Summary</th>
                    <th>Cost</th>
                </thead>
                <tbody>
                @foreach($orders as $lunchbox)
                    <script type="text/javascript">HNGOrderHistory["{{$lunchbox->id}}"] = {!! json_encode($lunchbox->ordersGrouped()) !!}</script>
                    <tr>
                        <td>
                            <p>
                                <a href="#" class="open-overview"
                                   data-lunchbox-id="{{$lunchbox->id}}"
                                   data-total-cost="{{$lunchbox->totalCost()}}"
                                   data-base-cost="{{$lunchbox->buka->base_cost}}"
                                   title="View Order Summary">
                                    {{ summarise_order($lunchbox->ordersGrouped()->toArray()) }}
                                </a>
                                <span class="days-ago">{{$lunchbox->created_at->diffForHumans()}}</span>
                            </p>
                        </td>
                        <td>@cash($lunchbox->totalCost())</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
        {{$orders->links()}}
    </div>
</div>

<!-- Order Overview Modal -->
<div id="order-overview" class="modal fade" role="dialog">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Order Summary</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-sm-12">
                        <p>Here's a quick overview of the order you made.</p>
                        <table class="table order-overview-table">
                            <tbody class="order-details">
                            <tr class="baseCost">
                                <th scope="row">Base Cost</th>
                                <td class="right"></td>
                            </tr>
                            <tr>
                                <th scope="row">Total:</th>
                                <td class="right totalCost"></td>
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
@else
<div class="enjoy-it">
    <div class="container">
        <div class="col-12">
            <div class="garri-photo">
                <img src="{{asset('/img/barbrady.gif')}}" alt="Nothing to see" />
            </div>
            <h2>Move along, there's nothing to see here.</h2>
        </div>
    </div>
</div>
@endif
@endsection