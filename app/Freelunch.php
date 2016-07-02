<?php namespace HNG;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model as Eloquent;

class Freelunch extends Eloquent 
{
    /**
     * @var array
     */
    protected $fillable = [
        'reason',
        'giver_id',
        'redeemed',
        'expires_at',
        'receiver_id',
    ];

    /**
     * @var array
     */
    protected $timestamps = ['expires_at'];

    /**
     * Get active free lunches.
     *
     * @param  $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('redeemed', 0)
                     ->where('expires_at', '>', Carbon::now());
    }

    /**
     * Get free lunches expiring tomorrow.
     *
     * @param  $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeExpiring($query)
    {
        return $query->where('redeemed', 0)
                     ->where('expires_at', '<=', Carbon::tomorrow());
    }

    /**
     * Get expired free lunches.
     *
     * @param  $query
     * @return \Illuminate\Database\Query\Builder
     */
    public function scopeExpired($query)
    {
        return $query->where('redeemed', 0)
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
     * Redeem the free lunch.
     *
     * @return bool
     */
    public function redeem()
    {
        $this->attributes['redeemed'] = true;

        return $this->save();
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