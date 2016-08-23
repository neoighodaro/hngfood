<?php

namespace HNG;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * @const array Roles
     */
    const ROLES = [
        1      => 'User',
        10     => 'Elevated User',
        100    => 'Manager',
        1000   => 'Admin',
        10000  => 'Super Admin',
    ];

    /**
     * @const array Permissions
     */
    const PERMISSIONS = [
        'lunch.manage'      => 'Admin',
        'buka.manage'       => 'Admin',
        'free_lunch.grant'  => 'Elevated User',
        'free_lunch.view'   => 'Admin',
        'free_lunch.manage' => 'Super Admin',
        '*'                 => 'Super Admin',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'slack_id', 'avatar', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'wallet' => 'float',
        'role'   => 'integer'
    ];

    /**
     * {@inheritdoc}
     */
    protected $with = ['freelunches'];

    /**
     * Get the user's first name.
     *
     * @param  string  $value
     * @return string
     */
    public function getWalletAttribute($value)
    {
        return number_format($value, 2);
    }

    /**
     * Lunchboxes relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lunchboxes()
    {
        return $this->hasMany(Lunchbox::class, 'user_id');
    }

    /**
     * Free lunches received.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function freelunches()
    {
        return $this->hasMany(Freelunch::class, 'to_id');
    }

    /**
     * Get role.
     *
     * @param $query
     * @param $role
     * @return mixed
     */
    public function scopeRole($query, $role)
    {
        return $query->whereId($this->id)->whereRole($this->getRoleIdFromNameOrId($role));
    }

    /**
     * Check if the user has a role.
     *
     * @param  string|int $name
     * @return bool
     */
    public function hasRole($name)
    {
        $expectedRoleId = $this->getRoleIdFromNameOrId($name);

        if ($this->exists && $expectedRoleId !== 0) {
            $userRoleId = $this->getRoleIdFromNameOrId($this->role);

            return $userRoleId >= $expectedRoleId;
        }

        return false;
    }

    /**
     * Get role ID from a specified role name.
     *
     * @param  $name
     * @return int
     */
    public function getRoleIdFromName($name)
    {
        $roleId = 0;

        foreach (static::ROLES as $id => $role) {
            if (strtolower($role) !== strtolower($name))
                continue;

            $roleId = $id;
        }

        return (int) $roleId;
    }

    /**
     * @param $name
     * @return int|string
     */
    private function getRoleIdFromNameOrId($name)
    {
        $roleId = is_numeric($name)
            ? (array_key_exists($name, static::ROLES) ? $name : 0)
            : $this->getRoleIdFromName($name);

        return $roleId;
    }
}
