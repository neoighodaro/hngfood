@extends('layouts.admin')

@section('title', 'Manage Inventory')

@section('admin-content')
    <div class="stats-section clearfix">
        <h3 class="pull-left">Available Bukas</h3>
        <a class="btn btn-success pull-right add-food-btn">Add Food Item to Buka</a>
        <a class="btn btn-primary pull-right add-buka-btn" data-toggle="modal" data-target=".create-buka-modal">Add Buka</a>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <ul class="bukas-list">
            @foreach($bukas as $buka)
                <li>
                    <div class="buka-wrapper" style="background-image: url('{{ $buka->avatar }}');">
                        <h4>{{ $buka->name }}</h4>
                        <span>{{ $buka->lunches->count() }} Food Type(s)</span>
                        <div class="overlay"></div>
                    </div>
                </li>
            @endforeach
            </ul>
        </div>
    </div>

    <!-- Create Buka Modal -->
    <div class="modal fade create-buka-modal" tabindex="-1" role="dialog" aria-labelledby="create-buka-modal">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Add a new Buka</h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="no-fixed-cost">
                                <form>
                                    <div class="alert alert-danger hidden">
                                        <strong>Yikes!</strong> Something is wrong with your input.
                                    </div>
                                    <div class="alert alert-success hidden">
                                        <strong>Cool!</strong> Buka created successfully.
                                    </div>
                                    <div class="form-group">
                                        <label for="buka-name">Buka Name</label>
                                        <p>Enter the name of the Buka below</p>
                                        <input autocomplete="off" type="text" class="form-control" id="buka-name" placeholder="e.g Vicky's Place" required />
                                    </div>
                                    <div class="form-group">
                                        <label for="buka-base-cost">Base Cost</label>
                                        <p>Additional costs associated with a single order e.g cost of the take-away pack.</p>
                                        <div class="input-group">
                                            <div class="input-group-addon">@currency()</div>
                                            <input type="number" class="form-control" id="buka-base-cost" value="0" placeholder="e.g 1000" />
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="pull-left btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary create-buka" data-url="{{ route('admin.inventory.manage.add') }}" data-alt-text="Please wait...">Create Buka</button>
                </div>
            </div>
        </div>
    </div>
@stop