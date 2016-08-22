@extends('layouts.app')

@section('title', 'Admin Login')

@section('content')
    <div class="container login">
        <div class="row">
            <div class="col-sm-12 col-md-4"></div>
            <div class="col-sm-12 col-md-4">
                <p>Enter your account password and ye' may enter.</p>
                <div class="row">
                    <div class="col-sm-12">
                        <form action="{{ route('admin.login') }}" method="post">
                            {!! csrf_field() !!}
                            <div class="form-group">
                                <input type="password" name="password" style="height:50px" class="form-control" id="password" placeholder="Password">
                            </div>
                            <button type="submit" class="btn btn-lg btn-block btn-primary">Log In</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection