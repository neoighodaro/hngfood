<?php

namespace spec\HNG\Http\Controllers\SlashApi;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class SlashWalletControllerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('HNG\Http\Controllers\SlashApi\SlashWalletController');
    }
}
