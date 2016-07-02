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