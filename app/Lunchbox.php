<?php namespace HNG;

use HNG\Http\Requests\Request;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Lunchbox extends Eloquent 
{
    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'buka_id',
        'free_lunch',
        'plate_number'
    ];

    /**
     * @var array
     */
    protected $casts = [
        'free_lunch'   => 'boolean'
    ];

    /**
     * @var array
     */
    protected $with = ['orders'];

    /**
     * Create a new lunchbox with orders.
     *
     * @param  Request $request
     * @return static
     */
    public function createWithOrders(Request $request)
    {
        $lunchbox = static::create([
            'user_id' => $request->user()->id,
            'buka_id' => $request->get('buka_id'),
        ]);

        $orders = [];

        foreach ($request->get('orders') as $order)
        {
            $lunch = Lunch::find($order['id']);

            $orders[] = (new Order)->createFromLunch($lunch);
        }

        $lunchbox->orders()->saveMany($orders);

        return $lunchbox->with('orders')->first();
    }

    /**
     * Get the total cost of the entire order.
     *
     * @return bool|float
     */
    public function totalCost()
    {
        if ($this->exists)
        {
            $totalCosts = 0.00;

            foreach ($this->orders as $order) {
                $totalCosts += (float) $order->expected_cost;
            }

            dd($this->buka->base_cost);

            if ($this->buka->base_cost > 0) {
                $totalCosts += (float) $this->buka->base_cost;
            }

            return $totalCosts;
        }

        return false;
    }

    /**
     * Return the related buka.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function buka()
    {
        return $this->belongsTo(Buka::class, 'buka_id');
    }

    /**
     * User relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Orders relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class, 'lunchbox_id');
    }
}