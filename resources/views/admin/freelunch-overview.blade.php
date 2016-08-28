@extends('layouts.admin')

@section('admin-content')
    <div class="stats-section">
        <h3>Free Lunch Overview</h3>
        <div class="row actual-stats">
            <div class="col-sm-12 col-md-3">
                <h4>Unused</h4>
                <span>{{ $freelunchOverview['unused'] }}</span>
            </div>
            <div class="col-sm-12 col-md-3">
                <h4>Available Quota [<a href="#" data-toggle="modal" data-target="#update-freelunch">Manage</a>]</h4>
                <span>{{ $freelunchOverview['remaining'] }}</span>
            </div>
        </div>
    </div>

    <!-- Update User Modal -->
    <div class="update-freelunch modal fade" tabindex="-1" role="dialog" aria-labelledby="update-freelunch" id="update-freelunch">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title">Free Lunch Quota</h4>
                </div>
                <div class="modal-body">
                    <div class="saving-changes">
                        <div class="center">
                            <div class="showbox">
                                <div class="loader">
                                    <svg class="circular" viewBox="25 25 50 50">
                                        <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"/>
                                    </svg>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="form-row">
                                <p class="dim">Set the quota remaining. This number would be the cap of free lunches available to be given away.</p>
                                <div class="input-group">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-danger btn-number" data-type="minus" data-field="freelunch">
                                            <span class="glyphicon glyphicon-minus"></span>
                                        </button>
                                    </span>
                                    <input type="text" name="freelunch" class="form-control freelunch input-number" value="{{ $freelunchOverview['remaining'] }}" min="0" max="500">
                                    <span class="input-group-btn">
                                        <button type="button" class="btn btn-success btn-number" data-type="plus" data-field="freelunch">
                                            <span class="glyphicon glyphicon-plus"></span>
                                        </button>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button data-url="{{ route('admin.freelunch.update') }}" class="btn btn-primary submit-change">Save Changes</button>
                </div>
            </div>
        </div>
    </div>

@endsection