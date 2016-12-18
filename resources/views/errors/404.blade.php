@extends('layouts.app')

@section('title', 'Oops! Page not found - '.option('APP_NAME'))

@section('content')
<div class="enjoy-it huge-margins">
    <div class="container">
        <div class="col-12">
            <div class="garri-photo">
                <img src="{{asset('/img/sad-food.png')}}" alt="404">
            </div>
            <h2>Well, this is embarassing!</h2>
            <p>There's a probability that we messed up something from our end, then again, maybe you just can't type. Who knows? Mean while, you can <a href="{{ route('home') }}">click here</a> to go back to the home page.</p>
        </div>
    </div>
</div>
@endsection