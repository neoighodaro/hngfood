<?php

namespace HNG\Providers;

use HNG;
use Illuminate\Contracts\Auth\Access\Gate as GateContract;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        'HNG\Model' => 'HNG\Policies\ModelPolicy',
    ];

    /**
     * @const array Permissions
     */
    const PERMISSIONS = [
        'free_lunch.grant'  => HNG\User::SUPERUSER,
        'inventory.manage'  => HNG\User::ADMIN,
        'free_lunch.manage' => HNG\User::ADMIN,
        'wallet.manage'     => HNG\User::SUPERADMIN,
        'users.manage'      => HNG\User::ADMIN,
        'roles.manage'      => HNG\User::SUPERADMIN,
        '*'                 => HNG\User::SUPERADMIN,
    ];

    /**
     * Register any application authentication / authorization services.
     *
     * @param  \Illuminate\Contracts\Auth\Access\Gate  $gate
     * @return void
     */
    public function boot(GateContract $gate)
    {
        $this->registerPolicies($gate);

        foreach (static::PERMISSIONS as $permission => $role) {
            Gate::define($permission, function (HNG\User $user) use ($role) {
                return $user->hasRole($role);
            });
        }
    }
}
