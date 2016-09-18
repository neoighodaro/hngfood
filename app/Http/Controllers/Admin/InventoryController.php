<?php

namespace HNG\Http\Controllers\Admin;

use HNG\Buka;
use HNG\Http\Requests;
use HNG\Http\Controllers\Controller;

class InventoryController extends Controller {

    /**
     * Manage inventory.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('admin.inventory.index', [
            'inPageTitle' => 'Manage Inventory',
            'bukas'       => Buka::all(),
        ]);
    }
}
