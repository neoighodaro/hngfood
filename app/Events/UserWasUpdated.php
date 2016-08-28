<?php

namespace HNG\Events;

class UserWasUpdated
{
    /**
     * @var \HNG\User
     */
    public $oldUser;

    /**
     * @var \HNG\User
     */
    public $updatedUser;

    /**
     * @var \HNG\Http\Requests\AdminUserUpdateRequest
     */
    public $updateRequest;

    /**
     * Create a new event instance.
     *
     * @param array $details
     */
    public function __construct(array $details)
    {
        $this->oldUser       = $details['oldUser'];
        $this->updatedUser   = $details['updatedUser'];
        $this->updateRequest = $details['updateRequest'];
    }
}
