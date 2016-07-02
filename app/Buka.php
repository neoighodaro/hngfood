<?php namespace HNG;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Buka extends Eloquent 
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'base_cost'];

    /**
     * @var array
     */
    protected $casts = ['base_cost' => 'float'];

    /**
     * @var array
     */
    protected $with = ['lunches'];

    /**
     * Get lunches assigned to the buka.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lunches()
    {
        return $this->hasMany(Lunch::class, 'buka_id');
    }

    /**
     * Get lunchboxes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function lunchboxes()
    {
        return $this->hasMany(Lunchbox::class, 'buka_id');
    }
}