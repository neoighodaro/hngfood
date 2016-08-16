<?php

namespace HNG;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
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
        'wallet' => 'float'
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
}
