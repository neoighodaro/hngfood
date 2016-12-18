<?php

namespace HNG\Listeners;

use HNG\Events\FreelunchQuotaUpdated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class UpdateFreeLunchQuota
{

    /**
     * Handle the event.
     *
     * @param  FreelunchQuotaUpdated  $event
     * @return void
     */
    public function handle(FreelunchQuotaUpdated $event)
    {
        option('freelunch_quota', $event->newQuota);
    }
}
