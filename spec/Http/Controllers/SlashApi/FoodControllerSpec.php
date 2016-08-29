<?php

namespace spec\HNG\Http\Controllers\SlashApi;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FoodControllerSpec extends ObjectBehavior {

    function it_should_return_the_bukas_list_on_index()
    {
        $response = $this->index();

        $response->shouldBeArray();
        $response->shouldContain();
    }
}
