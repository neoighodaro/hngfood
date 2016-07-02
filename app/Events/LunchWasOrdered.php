<?php

namespace HNG\Events;

use HNG\Lunchbox;
use Illuminate\Queue\SerializesModels;

class LunchWasOrdered extends Event
{
    use SerializesModels;

    /**
     * @var Lunchbox
     */
    public $order;

    /**
     * Create a new event instance.
     *
     * @param Lunchbox $order
     */
    public function __construct(Lunchbox $order)
    {
        $this->order = $order;
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return [];
    }
}
