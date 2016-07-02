@extends('layouts.app')

@section('title', 'Lunch Breaks Rock!')

@section('content')
<div class="kitchen-bg">
    <div class="overlay"></div>
    <div class="container">
        <div class="col-1 col-lg-offset-1">
            <div class="jumbo">
                <h1>Lunch Order Management For Teams!</h1>
                <div class="slack-btn">
                    <a href="{{route('auth.slack')}}">
                        <img alt="Sign in with Slack" height="40" width="172" src="https://platform.slack-edge.com/img/sign_in_with_slack.png" srcset="https://platform.slack-edge.com/img/sign_in_with_slack.png 1x, https://platform.slack-edge.com/img/sign_in_with_slack@2x.png 2x" />
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
