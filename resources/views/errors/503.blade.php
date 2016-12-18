@extends('layouts.app')

@section('title', 'Maintenance, Be Right Back - '.option('APP_NAME'))

@section('content')
<div class="enjoy-it huge-margins">
    <div class="container">
        <div class="col-12">
            <div class="garri-photo">
                <img src="{{asset('/img/sad-food.png')}}" alt="404">
            </div>
            <h2>Be Right Back!</h2>
            <p>The site is having its wires and engines oiled. We will be back anytime now. Try refreshing this screen in a few minutes.</p>
        </div>
    </div>
</div>
@endsection