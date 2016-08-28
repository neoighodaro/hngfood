<?php

namespace HNG\Events;

class FreelunchQuotaUpdated {

    /**
     * @var Integer
     */
    public $oldQuota;

    /**
     * @var Integer
     */
    public $newQuota;

    /**
     * Create a new event instance.
     *
     * @param Integer $oldQuota
     * @param Integer $newQuota
     */
    public function __construct($oldQuota, $newQuota)
    {
        $this->oldQuota = $oldQuota;
        $this->newQuota = $newQuota;
    }
}
