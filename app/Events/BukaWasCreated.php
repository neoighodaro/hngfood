<?php

namespace HNG\Events;

use HNG\Buka;

class BukaWasCreated
{
    /**
     * @var Buka
     */
    public $buka;

    /**
     * Create a new event instance.
     *
     * @param Buka $buka
     */
    public function __construct(Buka $buka)
    {
        $this->buka = $buka;
    }
}
