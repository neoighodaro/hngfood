@extends('layouts.app')

@section('title', 'Dashboard - '.env('APP_NAME'))
@section('content')

@if ($timekeeper->isWithinLunchOrderHours())
<div class="enjoy-it">
    <div class="container">
        <div class="col-12">
            <div class="garri-photo">
                <img src="/img/ijebu-garri.jpg" alt="Ijebu Garri" />
            </div>
            <h2>Sorry, lunch order has closed for the day. You should probably soak garri. &#8220;Enjoy it!&#8221;</h2>
        </div>
    </div>
</div>
@else
<div class="container big-btn">
    <div class="row">
        <div class="col-12">
            <a href="#" class="bigbtn" id="make-order-btn">Make An Order</a>
        </div>
    </div>
</div>

<div class="container foods">
    @foreach($bukas as $buka)
    <div class="row buka buka-{{$buka->id}} {{str_slug($buka->name)}}">
        <div class="col-lg-12">
            <h2 class="order-resturant" data-buka-base-cost="{{$buka->base_cost}}" data-buka-id="{{$buka->id}}">{{$buka->name}}</h2>
            <div class="block-grid-xs-2 block-grid-sm-2 block-grid-md-3 block-grid-lg-5">
                @foreach($buka->lunches as $lunch)
                <div class="food lunch-{{$lunch->id}}" data-lunch="{{ json_encode(array_only($lunch->toArray(), ['id', 'name', 'cost'])) }}" data-buka-base-cost="{{$buka->base_cost}}">
                    <div class="icon">
                        <span class="glyphicon glyphicon-ok" aria-hidden="true"></span>
                    </div>
                    <img src="{{$lunch->photo}}" class="img-rounded" alt="{{$lunch->name}}">
                    <span class="name">{{$lunch->name}}</span>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endforeach

    <!-- Food Order Modal -->
    <div class="modal fade food-modal" tabindex="-1" role="dialog" aria-labelledby="food-modal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="fixed-cost">
                                <p><strong class="modal-title"></strong> will cost <strong>&#8358;<span class="modal-cost"></span></strong> per serving.
                                How many servings/portions you would like to order.</p>
                                <select class="form-control" id="amount-servings" title="Servings Count">
                                    @foreach ([1,2,3,4,5] as $serving)
                                    <option value="{{$serving}}">{{ $serving }} {{ str_plural('Serving', $serving) }}</option>
                                    @endforeach
                                </select>
                                <span class="error">Please enter a valid amount.</span>
                            </div>
                            <div class="no-fixed-cost">
                                <p>How much <strong class="modal-title"></strong> would you like to order?</p>
                                <form class="form-inline" id="single-order">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-addon">&#8358;</div>
                                            <input autocomplete="off" type="text" class="form-control" id="amount-input" placeholder="Amount" />
                                            <div class="input-group-addon">.00</div>
                                        </div>
                                        <span class="error">Please enter a valid amount.</span>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="pull-left btn btn-default close-order" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger hide remove-order">Remove</button>
                    <button type="button" class="btn btn-primary add-to-order">Add to Order</button>
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
                    <h4 class="modal-title">Order Overview</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <div class="alert alert-danger">
                                <strong>Yikes!</strong> Sure you have cash?
                            </div>
                            <div class="alert alert-success">
                                <strong>Cool!</strong> Your order was placed.
                            </div>
                            <p>Heres a quick summary of your order. Your current balance is <strong>&#8358;{{ auth()->user()->wallet }}</strong></p>
                            <p>&nbsp;</p>
                            <table class="table order-overview-table"></table>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" data-redirect="{{route('order.completed', ['id' => ':id'])}}" data-url="{{route('order')}}" id="finalize-order" class="btn btn-success" data-loading-text="One Sec...">Place Order</button>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="complete-order">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="pull-left">
                    <h2>Total: &#8358;<span class="totalCost"></span></h2>
                    <span>Base Cost: &#8358;<span class="baseCost"></span></span>
                </div>
                <button class="btn btn-success pull-right complete-btn" data-toggle="modal" data-target="#order-overview">Complete Order</button>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
