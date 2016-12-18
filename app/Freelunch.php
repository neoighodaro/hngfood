<?php namespace HNG;

use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Freelunch extends Eloquent {

    /**
     * Days free lunch is typically valid for.
     */
    const VALID_DAYS = 14;

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
        return $query->whereRedeemed(0)->where('expires_at', '>', Carbon::now());
    }

    /**
     * Get freelunch used this month.
     *
     * @param  $query
     * @return Object
     */
    public function scopeUsedThisMonth($query)
    {
        return $query->whereRedeemed(1)
            ->where('created_at', '>=', Carbon::now()->startOfMonth())
            ->where('expires_at', '<=', Carbon::now()->endOfMonth());
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
        // It's not worth a dime!
        if ($this->worth() == 0) {
            return $orderCost;
        }

        $freelunchRequired = $this->requiredToSettleOrder($orderCost);

        $this->redeemCount($freelunchRequired);

        $freelunchAsCash = $freelunchRequired * $this->cashValue();

        // If the free lunch cash value covers it all then nothing left to pay...
        return ($freelunchAsCash > $orderCost) ? 0 : $orderCost - $freelunchAsCash;
    }

    /**
     * Returns the amount of freelunches required to settle.
     *
     * @param  integer $orderCost
     * @return integer
     */
    public function requiredToSettleOrder($orderCost)
    {
        $freelunchValue = $this->worth();
        $activeCount    = $this->active()->count();

        $remainder = ($freelunchValue >= $orderCost)
            ? floor(($freelunchValue - $orderCost) / $this->cashValue())
            : 0;

        return (int) ($activeCount - $remainder);
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

    /**
     * Give Free Lunch
     *
     * @param $to
     * @param $from
     * @param $reason
     *
     * @return bool
     */
    public function give($from, $to, $reason)
    {
        return (bool) static::create([
            'to_id'      => $to,
            'reason'     => $reason,
            'from_id'    => $from,
            'expires_at' => Carbon::now()->addDays(static::VALID_DAYS),
        ]);
    }
}