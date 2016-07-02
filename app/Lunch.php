<?php namespace HNG;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Lunch extends Eloquent 
{
    /**
     * @var array
     */
    protected $fillable = ['name', 'cost', 'buka_id'];

    /**
     * {@inheritdoc}
     */
    protected $table = "lunches";

    /**
     * @var array
     */
    protected $casts = ['cost' => 'float'];

    /**
     * Buka that lunch belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function buka()
    {
        return $this->belongsTo(Buka::class, 'buka_id');
    }
}