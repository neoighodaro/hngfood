@extends('layouts.admin')

@section('admin-content')
    <div class="stats-section">
        <h3>Food Orders Overview</h3>
        <div class="row actual-stats">
            <div class="col-sm-12 col-md-3">
                <h4><a href="#" title="View orders">Today</a></h4>
                <span>{{ $ordersOverview['today']->count() }}</span>
            </div>
            <div class="col-sm-12 col-md-3">
                <h4><a href="#" title="View orders">This Month</a></h4>
                <span>{{ $ordersOverview['month']->count() }}</span>
            </div>
            <div class="col-sm-12 col-md-3">
                <h4>This Year</h4>
                <span>{{ $ordersOverview['year']->count() }}</span>
            </div>
            <div class="col-sm-12 col-md-3">
                <h4>This Century</h4>
                <span>{{ $ordersOverview['century']->count() }}</span>
            </div>
        </div>
    </div>
@endsection