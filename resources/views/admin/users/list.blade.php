@extends('layouts.admin')

@section('admin-content')
<div class="main-box no-header clearfix">
    <div class="main-box-body clearfix">
        <div class="table-responsive">
            <table class="table user-list">
                <thead>
                <tr>
                    <th><span>Name</span></th>
                    <th class="text-center"><span>Wallet</span></th>
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
                        <td style="width: 20%;">
                            <a href="#" class="table-link">
                            <span class="fa-stack">
                                <i class="fa fa-square fa-stack-2x"></i>
                                <i class="fa fa-search-plus fa-stack-1x fa-inverse"></i>
                            </span>
                            </a>
                            <a href="#" class="table-link">
                            <span class="fa-stack">
                                <i class="fa fa-square fa-stack-2x"></i>
                                <i class="fa fa-pencil fa-stack-1x fa-inverse"></i>
                            </span>
                            </a>
                            <a href="#" class="table-link danger">
                            <span class="fa-stack">
                                <i class="fa fa-square fa-stack-2x"></i>
                                <i class="fa fa-trash-o fa-stack-1x fa-inverse"></i>
                            </span>
                            </a>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection