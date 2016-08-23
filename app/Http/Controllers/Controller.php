<?php

namespace HNG\Http\Controllers;

use HNG\Freelunch;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesResources;

class Controller extends BaseController {

    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * Class constructor.
     */
    public function __construct()
    {
        app('view')->share([
            'freelunches' => (new Freelunch)->active()
        ]);
    }
}
