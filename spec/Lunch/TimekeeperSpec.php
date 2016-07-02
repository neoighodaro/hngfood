<?php

namespace spec\HNG\Lunch;

use Carbon\Carbon;
use Prophecy\Argument;
use HNG\Lunch\Timekeeper;
use PhpSpec\ObjectBehavior;

class TimekeeperSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith(Carbon::now());
        $this->beAnInstanceOf(Timekeeper::class);
    }

    function it_returns_an_instance_of_carbon()
    {
        $this->carbon()->shouldBeAnInstanceOf(Carbon::class);
    }

    function it_should_return_false_for_is_weekend(Carbon $carbon)
    {
        $carbon->isWeekend()->willReturn(false);
        $this->beConstructedWith($carbon);
        $this->isWeekend()->shouldReturn(false);
        $carbon->isWeekend()->shouldHaveBeenCalled();
    }

    function it_should_return_false_for_is_weekday(Carbon $carbon)
    {
        $carbon->isWeekend()->willReturn(true);
        $this->beConstructedWith($carbon);
        $this->isWeekday()->shouldReturn(false);
    }

    function it_should_return_true_if_hour_is_between_two_specified_hours()
    {
        $eightAm = Carbon::now()->setTime(8, 0, 0);
        $this->beConstructedWith($eightAm);
        $this->isHoursBetween(7, 10)->shouldReturn(true);
    }
}
