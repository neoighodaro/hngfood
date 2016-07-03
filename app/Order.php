<?php
namespace HNG;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Order extends Eloquent
{
    /**
     * @var array
     */
    protected $fillable = [
        'note',
        'name',
        'cost',
        'lunch_id',
        'lunchbox_id',
        'cost_variation',
    ];

    /**
     * @var array
     */
    protected $casts = ['cost' => 'float', 'cost_variation' => 'float'];

    /**
     * @var array
     */
    protected $with = ['lunch'];

    /**
     * Create a new order from Lunch.
     *
     * @param Lunch $lunch
     * @return static
     */
    public function createFromLunch(Lunch $lunch)
    {
        return new static([
            'lunch_id' => $lunch->id,
            'name'     => $lunch->name,
        ]);
    }

    /**
     * Get the cost of an order.
     *
     * @return mixed
     */
    public function getCostAttribute()
    {
        if ( ! $this->exists) {
            return false;
        }

        $fixedCost = $this->lunch->attributes['cost'];

        return $fixedCost > 0 ? $fixedCost : $this->attributes['cost'];
    }

    /**
     * Lunchbox relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function lunchbox()
    {
        return $this->belongsTo(Lunchbox::class, 'lunchbox_id');
    }

    /**
     * Lunch relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function lunch()
    {
        return $this->belongsTo(Lunch::class, 'lunch_id');
    }
}