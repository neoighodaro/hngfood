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
            : (new HNG\Lunchbox)->find($lunchbox);

        return $lunchbox->totalCost();
    }
}

if ( ! function_exists('get_option'))
{
    /**
     * Read an option from the database.
     *
     * @param      $name
     * @param bool $default
     * @return bool|mixed
     */
    function get_option($name, $default = false)
    {
        if (strpos($name, '.') !== false) {
            $key = explode('.', $name)[0];

            $option = (new HNG\Option)->name($key, HNG\Option::READONLY, $default);

            $value = array_get($option, str_replace($key.'.', '', $name));
        } else {
            $value = (new HNG\Option)->name($name, HNG\Option::READONLY, $default);
        }

        return $value;
    }
}

if ( ! function_exists('add_option'))
{
    /**
     * Write new option to the database.
     *
     * @param $name
     * @param $value
     * @return bool|mixed
     */
    function add_option($name, $value)
    {
        return (new HNG\Option)->name($name, $value);
    }
}

if ( ! function_exists('option'))
{
    /**
     * Get or set an option.
     *
     * @param        $name
     * @param string $value
     * @param bool   $default
     * @return bool|mixed
     */
    function option($name, $value = HNG\Option::READONLY, $default = false)
    {
        if ($value === HNG\Option::READONLY) {
            return get_option($name, $default);
        }

        return add_option($name, $value);
    }
}