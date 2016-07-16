<?php namespace HNG;

use Carbon\Carbon;
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

        foreach ($request->get('orders') as $order) {
            for ($i = 0; $i < $order['servings']; $i++) {
                $lunch = Lunch::find($order['id']);

                // Create a new order
                $newOrder = (new Order)->createFromLunch($lunch);

                // If the lunch does not have a fixed price, then enter the variable
                // price which would be used to calculate the final cost.
                if ($lunch->cost <= 0) {
                    $newOrder->cost = $order['cost'];
                }

                $orders[] = $newOrder;
            }
        }

        $lunchbox->orders()->saveMany($orders);

        return $lunchbox;
    }

    /**
     * Get the total cost of the entire order.
     *
     * @return bool|float
     */
    public function totalCost()
    {
        if ( ! $this->exists) {
            return false;
        }

        $totalCost = 0.00;

        $ordersGrouped = $this->ordersGrouped();

        foreach ($ordersGrouped as $order) {
            $totalCost += (float) ($order->cost * $order->servings);
        }

        if ($this->buka->base_cost > 0) {
            $totalCost += (float) $this->buka->base_cost;
        }

        return $totalCost;
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

    /**
     * Orders relationship.
     *
     * @return \Illuminate\Support\Collection
     */
    public function ordersGrouped()
    {
        return $this->orders()
            ->selectRaw('*,count(*) as servings')
            ->groupBy('lunch_id')
            ->get();
    }

    /**
     * Get orders between a certain time period.
     *
     * @param  $query
     * @param  string|Int|Carbon $startDate
     * @param  string|Int|Carbon $endDate
     * @return mixed
     */
    public function scopeOrdersBetween($query, $startDate, $endDate)
    {
        $endDate   = $this->carbonInstanceFromDate($endDate, Carbon::now());
        $startDate = $this->carbonInstanceFromDate($startDate, Carbon::now()->startOfMonth());

        return $query->where('created_at', '>=', $startDate)->where('created_at', '<=', $endDate);
    }

    /**
     * Get carbon instance from date.
     *
     * @param        $date
     * @param Carbon $default
     * @return array
     */
    protected function carbonInstanceFromDate($date, Carbon $default)
    {
        if ( ! $date instanceof Carbon) {
            $timestamp = strtotime($date);

            if ( ! $timestamp OR ! $date = Carbon::createFromTimestamp($timestamp)) {
                $date = $default;
            }
        }

        return $date;
    }
}