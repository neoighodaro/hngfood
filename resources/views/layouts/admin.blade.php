@extends('layouts.app')

@section('title', 'Admin Dashboard')
@section('content')
    <div class="container margin">
        <div class="row profile">
            <div class="col-sm-12 col-md-3 col-lg-3">
                <div class="profile-sidebar">
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
                                    User Management
                                </a>
                            </li>
                            @endif

                            @if (Gate::allows('free_lunch.view'))
                            <li {!! Route::is('admin.freelunch.overview') ? 'class="active"' : '' !!}>
                                <a href="{{ route('admin.freelunch.overview') }}">
                                    <i class="glyphicon glyphicon-apple"></i>
                                    Free Lunch </a>
                            </li>
                            @endif

                            <li>
                                <a href="{{ route('admin.inventory.manage') }}">
                                    <i class="glyphicon glyphicon-cutlery"></i>
                                    Manage Inventory </a>
                            </li>
                            {{--<li>--}}
                                {{--<a href="#">--}}
                                    {{--<i class="glyphicon glyphicon-cog"></i>--}}
                                    {{--Site Settings </a>--}}
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