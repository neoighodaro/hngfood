<?php namespace HNG;

use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Freelunch extends Eloquent {

    /**
     * Days free lunch is typically valid for.
     */
    const VALID_DAYS = 7;

    /**
     * @var array
     */
    protected $fillable = [
        'reason',
        'from_id',
        'redeemed',
        'expires_at',
        'to_id',
    ];

    /**
     * @var array
     */
    public $timestamps = ['expires_at'];

    /**
     * Get active free lunches.
     *
     * @param      $query
     * @param null $user
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeActive($query, $user = null)
    {
        $user = ($user === null)
            ? (auth()->user() ? auth()->user()->id : 0)
            : $user;

        return $query->whereRedeemed(0)
                     ->whereToId($user)
                     ->where('expires_at', '>', Carbon::now());
    }

    /**
     * Get all active free lunches.
     *
     * @param $query
     * @return mixed
     */
    public function scopeActiveAll($query)
    {
        return $query->whereRedeemed(0)->where('expires_at', '>', carbon::now());
    }

    /**
     * Get free lunches expiring tomorrow.
     *
     * @param      $query
     * @param null $user
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeExpiring($query, $user = null)
    {
        $user = $user == null ? auth()->user()->id : $user;

        return $query->where('redeemed', 0)
                     ->where('to_id', $user)
                     ->where('expires_at', '<=', Carbon::tomorrow());
    }

    /**
     * Get expired free lunches.
     *
     * @param      $query
     * @param null $user
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeExpired($query, $user = null)
    {
        $user = $user == null ? auth()->user()->id : $user;

        return $query->where('redeemed', 0)
                     ->where('to_id', $user)
                     ->where('expires_at', '<', Carbon::now());
    }

    /**
     * Checks to see if the free lunch has expired.
     *
     * @return bool
     */
    public function hasExpired()
    {
        return $this->attributes['expires_at'] > Carbon::now();
    }

    /**
     * Redeem a free lunch.
     *
     * @return bool
     */
    public function redeem()
    {
        $this->attributes['redeemed'] = true;

        return $this->save();
    }

    /**
     * Redeem a number of free lunches.
     *
     * @param  integer $count
     * @return void
     */
    public function redeemCount($count = 0)
    {
        $count = (int) $count;

        $table = DB::table($this->getTable())
            ->where('redeemed', 0)
            ->where('to_id', auth()->user()->id);

        if ($count > 0) {
            $table = $table->take($count);
        }

        $table->update(['redeemed' => 1]);
    }

    /**
     * Get the current users freelunch cash worth.
     *
     * @return integer
     */
    public function worth()
    {
        return $this->active()->count() * $this->cashValue();
    }

    /**
     * Redeem free lunches required to roll.
     *
     * @param  integer $orderCost
     * @return void
     */
    public function deductRequiredToSettle($orderCost)
    {
        if (($value = $this->worth()) == 0) {
            return $orderCost;
        }

        if ($value >= $orderCost) {
            $shouldRemain = floor(($value - $orderCost) / $this->cashValue());

            $totalCount = $this->active()->count() - $shouldRemain;

            $remainder = 0;
        } else {
            $totalCount = $this->active()->count();

            $remainder = $orderCost - $value;
        }

        $this->redeemCount($totalCount);

        return $remainder;
    }

    /**
     * Give your free lunch to someone else.
     *
     * @param  $user_id
     * @param  $reason
     * @return bool
     */
    public function dash($user_id, $reason)
    {
        if ( ! $this->hasExpired() && $this->redeem()) {
            return (bool) static::create([
                'reason'     => $reason,
                'to_id'      => $user_id,
                'from_id'    => $this->attributes['to_id'],
                'expires_at' => $this->attributes['expires_at'],
            ]);
        }

        return false;
    }

    /**
     * Cash value for a free lunch.
     *
     * @return integer
     */
    public function cashValue()
    {
        return (int) config('food.freelunch_cost');
    }

    /**
     * Giver relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function giver()
    {
        return $this->belongsTo(User::class, 'from_id');
    }

    /**
     * Receiver relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function receiver()
    {
        return $this->belongsTo(User::class, 'to_id');
    }
}