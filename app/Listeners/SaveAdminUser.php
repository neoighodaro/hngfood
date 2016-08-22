<?php

namespace HNG\Listeners;

use HNG\Events\UserWasCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class SaveAdminUser {

    /**
     * Handle the event.
     *
     * @param  UserWasCreated  $event
     * @return void
     */
    public function handle(UserWasCreated $event)
    {
        // If this is the first user registering for the app then set them up as a super
        // administrator...and set the default password as your slack client secret,
        // change this value.

        if ($event->user->id === 1) {
            $event->user->password = bcrypt(env('SLACK_CLIENT_SECRET'));
            $event->user->role     = $event->user->getRoleIdFromName('Super Admin');
            $event->user->save();
        }
    }
}
