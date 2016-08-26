@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('content')
    <div class="container margin">
        <div class="row profile">
            <div class="col-sm-12 col-md-3 col-lg-3">
                <div class="profile-sidebar">
                    {{--<div class="profile-userpic">--}}
                        {{--<img src="{{ auth()->user()->avatar }}" class="img-responsive" alt="">--}}
                    {{--</div>--}}
                    <div class="profile-usermenu">
                        <ul class="nav">
                            <li {!! Route::is('admin.dashboard.overview') ? 'class="active"' : '' !!}>
                                <a href="{{ route('admin.dashboard.overview') }}">
                                    <i class="glyphicon glyphicon-home"></i>
                                    Overview
                                </a>
                            </li>
                            @if (Gate::allows('users.manage'))
                            <li {!! Route::is('admin.users.manage') ? 'class="active"' : '' !!}>
                                <a href="{{ route('admin.users.manage') }}">
                                    <i class="glyphicon glyphicon-user"></i>
                                    User Management </a>
                            </li>
                            @endcan
                            {{--<li>--}}
                                {{--<a href="#" target="_blank">--}}
                                    {{--<i class="glyphicon glyphicon-ok"></i>--}}
                                    {{--Tasks </a>--}}
                            {{--</li>--}}
                            {{--<li>--}}
                                {{--<a href="#">--}}
                                    {{--<i class="glyphicon glyphicon-flag"></i>--}}
                                    {{--Help </a>--}}
                            {{--</li>--}}
                        </ul>
                    </div>
                </div>
            </div>
            <div class="col-sm-12 col-md-9 col-lg-9">
                @yield('admin-content')
            </div>
        </div>
    </div>
@endsection