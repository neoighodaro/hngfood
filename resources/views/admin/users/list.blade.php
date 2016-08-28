@extends('layouts.admin')

@section('admin-content')
    <div class="filter-box">
        <div class="row">
            <div class="col-sm-12">
                <form action="{{ URL::full() }}" method="GET">
                    <input type="text" placeholder="Quick Search..." value="{{ $searchQuery }}" class="sleek" name="q">
                </form>
            </div>
        </div>
    </div>
    @if ($searchQuery)
    <p class="result-msg">
        Showing results for &#8220;{{ $searchQuery }}&#8221; [<a href="{{ url()->current() }}">Clear Filters</a>]
    </p>
    @endif

    <div class="main-box no-header clearfix">
        <div class="main-box-body clearfix">
            <div class="table-responsive">
                <table class="table user-list">
                    <thead>
                    <tr>
                        <th><span><a href="#">Name</a></span></th>
                        <th class="text-center"><span><a href="#">Wallet</a></span></th>
                        <th class="text-center"><span>Free Lunch</span></th>
                        <th>&nbsp;</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $user)
                        <tr>
                            <td>
                                <img src="{{ $user->avatar }}" alt="">
                                <a class="user-link">{{ $user->name }}</a>
                                <span class="user-subhead">{{ $user->getNameFromRoleId($user->role) }}</span>
                            </td>
                            <td class="text-center">
                                <span class="label label-{{ $user->walletStatus }}">&#8358;{{ $user->wallet }}</span>
                            </td>
                            <td class="text-center">
                                <span>{{ $user->freelunches()->count() }}</span>
                            </td>
                            <td style="width: 20%;">
                                <a href="#" class="table-link" data-user="{{ collect($user)->only(['id', 'name', 'role', 'wallet'])->toJson() }}" data-freelunches="{{ $user->freelunches()->count() }}" data-roles="{{ json_encode($user::ROLES) }}" data-toggle="modal" data-target="#update-user">
                                    <span class="fa-stack">
                                        <i class="fa fa-square fa-stack-2x"></i>
                                        <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                                    </span>
                                </a>
                                {{--@if ( ! $user->hasRole(HNG\User::SUPERADMIN))--}}
                                {{--<a href="#" class="table-link danger">--}}
                                    {{--<span class="fa-stack">--}}
                                        {{--<i class="fa fa-square fa-stack-2x"></i>--}}
                                        {{--<i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>--}}
                                    {{--</span>--}}
                                {{--</a>--}}
                                {{--@endif--}}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @if ($users->nextPageUrl() || $users->previousPageUrl())
    {{  $users->links() }}
    @endif

    <!-- Update User Modal -->
    <div class="update-user modal fade" tabindex="-1" role="dialog" aria-labelledby="update-user" id="update-user">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title" data-user-id="0">Edit <span class="name"></span></h4>
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

                            @if (Gate::allows('free_lunch.manage'))
                            <div class="form-row">
                                <label for="freelunch">Free Lunch</label>
                                <p class="dim">Update the amount of free lunch this user has.</p>
                                <div class="input-group">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-danger btn-number" data-type="minus" data-field="freelunch">
                                        <span class="glyphicon glyphicon-minus"></span>
                                    </button>
                                </span>
                                    <input type="text" name="freelunch" class="form-control freelunch input-number" min="0" max="20">
                                <span class="input-group-btn">
                                    <button type="button" class="btn btn-success btn-number" data-type="plus" data-field="freelunch">
                                        <span class="glyphicon glyphicon-plus"></span>
                                    </button>
                                </span>
                                </div>
                            </div>
                            @endif

                            @if (Gate::allows('roles.manage'))
                            <div class="form-row">
                                <label for="user-role">Change User Role</label>
                                <p class="dim">Select the correct role for this user.</p>
                                <select class="form-control" id="user-role" value="1">
                                    @foreach(HNG\User::ROLES as $id => $role)
                                    <option value="{{ $id }}" class="role-select role-{{ $id }}">{{ $role }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif

                            @if (Gate::allows('wallet.manage'))
                            <div class="form-row">
                                <label for="user-wallet">Update Wallet</label>
                                <p class="dim">Update the users wallet with cash.</p>
                                <input type="text" class="form-control" name="wallet" id="user-wallet" value="0.00" />
                            </div>
                            @endif

                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button data-url="{{ route('admin.users.update') }}" class="btn btn-primary submit-user-change">Save Changes</button>
                </div>
            </div>
        </div>
    </div>
@endsection