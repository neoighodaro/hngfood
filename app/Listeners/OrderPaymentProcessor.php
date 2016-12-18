<?php

namespace HNG\Listeners;

use HNG\Freelunch;
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

        $this->freelunch = new Freelunch;
    }

    /**
     * Handle the event.
     *
     * @param LunchWasOrdered $event
     */
    public function handle(LunchWasOrdered $event)
    {
        $orderCost     = $event->order->totalCost();
        $availableCash = number_unformat($this->user->wallet);

        if ($event->request->wantsToRedeemFreelunch()) {
            $orderCost = $this->freelunch->deductRequiredToSettle($orderCost);
        }

        $this->user->wallet = $availableCash - $orderCost;
        $this->user->save();
    }
}
