<?php

if ( ! function_exists('number_unformat')) {
    /**
     * Unformat formatted number.
     *
     * @param  string $number
     * @return float
     */
    function number_unformat($number)
    {
        return filter_var($number, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }
}

if ( ! function_exists('summarise_order'))
{
    /**
     * Summarise orders.
     *
     * @param  array $orders
     * @return \HNG\Lunch\OrderSummariser
     */
    function summarise_order(array $orders)
    {
        return (new HNG\Lunch\OrderSummariser($orders))->summary();
    }
}

if ( ! function_exists('lunchbox_cost'))
{
    /**
     * Get the lunchbox total cost.
     *
     * @param  int|HNG\Lunchbox $lunchbox
     * @return mixed
     */
    function lunchbox_cost($lunchbox)
    {
        $lunchbox = $lunchbox instanceof HNG\Lunchbox
            ? $lunchbox
            : HNG\Lunchbox::find($lunchbox);

        return $lunchbox->totalCost();
    }
}