@extends('layouts.app')

@section('title', 'Dashboard - '.option('APP_NAME'))
@section('content')

@if ( ! $timekeeper->isWithinLunchOrderHours())
<div class="enjoy-it">
    <div class="container">
        <div class="col-12">
            <div class="garri-photo">
                <img src="{{asset('/img/ijebu-garri.jpg')}}" alt="Ijebu Garri" />
            </div>
            <h2>Sorry, lunch order has closed for the day. You should probably soak garri. &#8220;Enjoy it!&#8221;</h2>
        </div>
    </div>
</div>
@else
<!-- Make Order Button -->
<div class="container big-btn">
    <div class="row">
        <div class="col-12">
            <a href="#" class="bigbtn" id="make-order-btn">Make An Order</a>
        </div>
    </div>
</div>

<!-- Bukas and Menu -->
<div class="container foods" data-active-buka>
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
</div>

<!-- Buka Select Modal -->
<div class="buka-select modal fade" tabindex="-1" role="dialog" aria-labelledby="buka-select-modal">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Whollup! Select Buka</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-lg-12">
                        <p>Select the buka you would like to order from. Costs may vary depending on the buka you select.</p>
                        <ul class="bukas">
                            @foreach ($bukas as $buka)
                            <li>
                                <a data-buka-id="{{ $buka->id }}" data-display-buka="{{ str_slug($buka->name) }}" href="#" style="background-image:url({{$buka->avatar}})">
                                    <div class="overlay"></div>
                                    <span>{{$buka->name}}</span>
                                </a>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

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
                            <p><strong class="modal-title"></strong> will cost <strong>@cash('<span class="modal-cost"></span>')</strong> per serving.
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
                                        <div class="input-group-addon">@currency()</div>
                                        <input autocomplete="off" type="text" class="form-control" id="amount-input" placeholder="Amount" />
                                        <div class="input-group-addon">.00</div>
                                    </div>
                                    <span class="error">Please enter a valid amount.</span>
                                </div>
                            </form>
                        </div>
                        <div class="additional-note">
                            <div class="form-group">
                                <label for="comment">Additional Note</label>
                                <textarea class="form-control" rows="5" id="add-note" placeholder="e.g Soup type or special considerations"></textarea>
                            </div>
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
                            <strong>Yikes!</strong> Your wallet is not able to handle the pressure sorry.
                        </div>
                        <div class="alert alert-success">
                            <strong>Cool!</strong> Your order was placed.
                        </div>
                        <p>Heres a quick summary of your order. Your current balance is <strong>@wallet()</strong></p>
                        <p>&nbsp;</p>
                        <table class="table order order-overview-table"></table>
                        @if ($freelunch->count() > 0)
                        <p class="free-lunch-alert">
                            <span class="totally">Oshey! Your free lunch will offset the food bill totally.</span>
                            <span class="partially">Your entire free lunch applied will shave <strong></strong> off your bill.</span>
                        </p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                @if ($freelunch->count() > 0)
                <div class="pull-left">
                    <button type="button" id="apply-freelunch" data-free-lunch-count="{{$freelunch->count()}}" data-free-lunch-value="{{$freelunch->first()->cashValue()}}" class="btn btn-default" data-alt-text="Use Only Wallet">Use Free Lunch</button>
                </div>
                @endif
                <button type="button" data-redirect="{{route('order.completed', ['id' => ':id'])}}" data-url="{{route('order')}}" id="finalize-order" class="btn btn-success" data-loading-text="One Sec...">Place Order</button>
            </div>
        </div>
    </div>
</div>

<!-- Complete Order Slab -->
<div id="complete-order">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="pull-left">
                    <h2>Total: @cash('<span class="totalCost"></span>')</h2>
                    <span>Base Cost: @cash('<span class="baseCost"></span>')</span>
                </div>
                <button class="btn btn-success pull-right complete-btn" data-toggle="modal" data-target="#order-overview">Complete Order</button>
            </div>
        </div>
    </div>
</div>
@endif

@endsection
