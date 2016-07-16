<?php

namespace spec\HNG\Lunch;

use Doctrine\Instantiator\Exception\InvalidArgumentException;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class OrderSummariserSpec extends ObjectBehavior
{
    function it_is_instantiated_by_an_array()
    {
        $this->beConstructedWith($this->_orders());
        $this->orders()->shouldBe($this->_orders());
    }

    function it_should_throw_an_error_if_name_key_is_not_found()
    {
        $orders = $this->_orders() + ['id' => 1];
        $this->shouldThrow(InvalidArgumentException::class)->during('__construct', [$orders]);
    }

    function it_is_countable()
    {
        $this->beConstructedWith($this->_orders());
        $this->count()->shouldBe(5);
    }

    function it_should_return_one_dish_explicitly()
    {
        $this->beConstructedWith([
            ['name' => 'Jollof Rice']
        ]);

        $this->summary()->shouldBe('Jollof Rice');
    }

    function it_should_return_two_dishes_explicitly() {
        $this->beConstructedWith([
            ['name' => 'Jollof Rice'],
            ['name' => 'Beans'],
        ]);

        $this->summary()->shouldBe('Jollof Rice and Beans');
    }

    function it_should_return_aggregate_readable_for_three_or_more_dishes()
    {
        $this->beConstructedWith($this->_orders());
        $this->summary()->shouldReturn('Jollof Rice and 4 more dishes');
    }

    private function _orders()
    {
        return [
            ['name' => 'Jollof Rice'],
            ['name' => 'Beans'],
            ['name' => 'Beef'],
            ['name' => 'Amala'],
            ['name' => 'Fried Rice'],
        ];
    }
}
