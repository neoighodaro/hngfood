<?php

namespace HNG\Events;

use HNG\Lunchbox;
use HNG\Http\Requests\OrderRequest;
use Illuminate\Queue\SerializesModels;

class LunchWasOrdered extends Event
{
    use SerializesModels;

    /**
     * @var Lunchbox
     */
    public $order;

    /**
     * @var OrderRequest
     */
    public $request;

    /**
     * Create a new event instance.
     *
     * @param Lunchbox $order
     */
    public function __construct(Lunchbox $order, OrderRequest $request)
    {
        $this->order = $order;

        $this->request = $request;
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
