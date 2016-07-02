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
        'actual_cost',
        'lunchbox_id',
        'expected_cost',
    ];

    /**
     * @var array
     */
    protected $casts = [
        'expected_cost' => 'float',
        'actual_cost'   => 'float',
    ];

    /**
     * Create a new order from Lunch.
     *
     * @param Lunch $lunch
     * @return static
     */
    public function createFromLunch(Lunch $lunch)
    {
        return new static([
            'name'          => $lunch->getAttribute('name'),
            'expected_cost' => $lunch->getAttribute('cost'),
        ]);
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
}