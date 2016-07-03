@extends('layouts.app')

@section('title', 'Order History - '.env('APP_NAME'))

@section('content')
<div class="container">
    <div class="row">
        <div class="col-sm-12">

            @forelse($orders as $date => $lunchboxes)
            <table class="table">
                <thead>
                    <tr>
                        <th>{{$date}}</th>
                    </tr>
                </thead>
                @foreach($lunchboxes as $lunchbox)
                <tbody>
                    <tr>
                        <td>{{$lunchbox['orders']['name']}}</td>
                        <td>Tompson</td>
                        <td>the_mark7</td>
                        <td>
                            <a href="user.html"><i class="icon-pencil"></i></a>
                            <a href="#myModal" role="button" data-toggle="modal"><i class="icon-remove"></i></a>
                        </td>
                    </tr>
                </tbody>
                    {{--{{var_dump($lunchbox)}}--}}
                @endforeach
            </table>
            @empty
                <p>Move along now, nothing to see here.</p>
            @endforelse

        </div>

        {{$orders->links()}}

    </div>
</div>
@endsection