<?php

namespace HNG\Providers;

use HNG\User;
use Validator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class CustomValidationServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        Validator::extend('permission', function($attribute, $value, $parameters) {
            return Gate::allows($parameters[0]);
        });

        Validator::extend('roleExists', function ($attribute, $value) {
            return array_key_exists($value, User::ROLES);
        });
    }
}
