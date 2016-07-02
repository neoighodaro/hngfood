<?php

namespace HNG\Listeners;

use HNG\Events\LunchWasOrdered;

class OrderPaymentProcessor
{
    /**
     * @var User
     */
    private $user;

    /**
     * Create the event listener.
     *
     */
    public function __construct()
    {
        $this->user = auth()->user();
    }

    /**
     * Handle the event.
     *
     * @param LunchWasOrdered $event
     */
    public function handle(LunchWasOrdered $event)
    {
        $orderCost = $event->order->totalCost();

        $this->user->wallet = ($this->user->wallet - $orderCost);
        $this->user->save();
    }
}
