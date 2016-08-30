<?php

namespace spec\HNG\Http\Controllers\SlackCommands;

use HNG\Http\Requests\SlackCommandRequest as Request;
use HNG\User;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class WalletControllerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('HNG\Http\Controllers\SlackCommands\WalletController');
    }
}
