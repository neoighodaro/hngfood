<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css" integrity="sha384-XdYbMnZ/QjLh6iI4ogqCTaIjrFk87ip+ekIjefZch0Y+PvJ8CDYtEs1ipDmPorQ+" crossorigin="anonymous">
    <link rel="stylesheet" href="//fonts.googleapis.com/css?family=Lato:100,300,400,700|Montserrat:700|Source+Sans+Pro:300,400">
    <link rel="shortcut icon" href="http://mediacdn.hotels.ng/hotels/v52/img/favicon.png">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" href="//cdn.jsdelivr.net/bootstrap.block-grid/latest/bootstrap3-block-grid.min.css">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>
<body id="app-layout">
    <nav class="navbar navbar-default navbar-static-top">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#app-navbar-collapse">
                    <span class="sr-only">Toggle Navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="{{ url('/') }}">
                    @include('partials.logo')
                </a>
            </div>

            <div class="collapse navbar-collapse" id="app-navbar-collapse">
                <ul class="nav navbar-nav navbar-right">
                    @if (auth()->guest())
                        <li><a href="{{route('auth.slack')}}">Sign in with Slack</a></li>
                    @else
                        <li>
                            <a href="#" title="{{ $freelunches->count() > 0 ? 'You have '.$freelunches->count().' free lunches' : '' }}">
                                &#8358;{{ auth()->user()->wallet }}
                                @if ($freelunches->count() > 0)
                                <span class="label label-danger freelunch">{{$freelunches->count()}}</span>
                                @endif
                            </a>
                        </li>
                        @if (Route::is('home') === false)
                        <li><a href="{{route('home')}}">Make An Order</a></li>
                        @endif
                        <li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-expanded="false">
                                {{ auth()->user()->name }} <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu" role="menu">
                                <li><a href="{{ route('order.history') }}"><i class="fa fa-btn fa-calendar"></i> Order History</a></li>
                                <li><a href="{{ route('logout') }}"><i class="fa fa-btn fa-sign-out"></i> Sign Out</a></li>
                            </ul>
                        </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>

    @if ( (isset($inPageTitle) && $inPageTitle) || (isset($inPageSubTitle) && $inPageSubTitle) )
    <div class="page-title">
        <div class="container">
            <div class="col-12">
                @if (isset($inPageTitle) && $inPageTitle) <h1>{{ $inPageTitle }}</h1> @endif
                @if (isset($inPageSubTitle) && $inPageSubTitle) <span>{{ $inPageSubTitle }}</span> @endif
            </div>
        </div>
    </div>
    @endif

    @yield('content')

    <footer>
        <div class="container">
            <p class="creators">Created by the HNG tech team.</p>
        </div>
    </footer>

    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.2.3/jquery.min.js" integrity="sha384-I6F5OKECLVtK/BL+8iSLDEHowSAfUo76ZL9+kGAgTRdiByINKJaqTPH/QVNS1VDb" crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/FitText.js/1.2.0/jquery.fittext.min.js"></script>
    <script src="{{ asset('js/app.js') }}"></script>
</body>
</html>
