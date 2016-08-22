<?php

namespace HNG\Lunch;

use InvalidArgumentException;

class OrderSummariser implements \Countable
{
    /**
     * @var array
     */
    protected $orders;

    /**
     * OrderSummariser constructor.
     *
     * @param $orders
     */
    public function __construct($orders)
    {
        foreach ($orders as $order) {
            if ( ! array_get($order, 'name')) {
                throw new InvalidArgumentException;
            }
        }

        $this->orders = $orders;
    }

    /**
     * Return the orders.
     *
     * @return array
     */
    public function orders()
    {
        return $this->orders;
    }

    /**
     * Returns a human readable summary of the orders.
     *
     * @return string
     */
    public function summary()
    {
        if ($this->count() >= 3) {
            $str = $this->createReadableStringForThreeOrMoreDishes();
        } else {
            $str = $this->createReadableStringForOneOrTwoDishes();
        }

        return $str;
    }

    /**
     * Object to string conversion.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->summary();
    }

    /**
     * Return a count of the orders.
     *
     * @return int
     */
    public function count()
    {
        return count($this->orders());
    }

    /**
     * Create readable string for one or two dishes.
     *
     * @return string
     */
    private function createReadableStringForOneOrTwoDishes()
    {
        $str = '';

        for ($i = 0; $i < $this->count(); $i ++) {
            $str .= $this->orders()[$i]['name'];

            if ($i < $this->count() - 1) {
                $str .= ($this->count() == 2) ? " and " : ", ";
            }
        }

        return $str;
    }

    /**
     * Create readable string for three or more dishes
     *
     * @return string
     */
    private function createReadableStringForThreeOrMoreDishes()
    {
        $firstDish = $this->orders()[0]['name'];

        $remaining = ($this->count() - 1);

        $str = $firstDish . " and " . $remaining . " more dishes";

        return $str;
    }
}
