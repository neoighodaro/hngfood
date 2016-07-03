<?php namespace HNG;

//use Illuminate\Pagination\Paginator;
use Illuminate\Pagination\LengthAwarePaginator as Paginator;
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

        foreach ($this->ordersGrouped as $order) {
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
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ordersGrouped()
    {
        return $this->hasMany(Order::class, 'lunchbox_id')
            ->selectRaw('*, count(*) as servings')
            ->groupBy('lunch_id');
    }

    /**
     * Get orders by history.
     *
     * @param        $query
     * @param Carbon $date
     * @return mixed
     */
    public function scopeHistory($query, Carbon $date = null)
    {
        return $query->where('created_at', '>=', $date)
            ->get()
            ->groupBy(function ($date) {
                return Carbon::parse($date->created_at)->format('M, Y');
            });
    }

    /**
     * Get the history of orders and group by month.
     *
     * @param     $query
     * @param int $perPage
     * @return Paginator
     */
    public function scopeHistoryPaginate($query, $perPage = 10)
    {
        $results = $query->history(Carbon::create()->startOfYear());

        // Get pagination information and slice the results.
        $start = (Paginator::resolveCurrentPage() - 1) * $perPage;
        $sliced = array_slice($results->toArray(), $start, $perPage);

        // Create a paginator instance.
        return new Paginator($sliced, $results->count(), $perPage);
    }
}