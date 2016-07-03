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
    function summarise_order(array $orders)
    {
        $summary = "";

        $count = count($orders);

        $maxExplicitlyWritten = 3;

        for ($i = 0; $i < $count; $i++) {
            $suffix = "";

            if ($i < $maxExplicitlyWritten) {
                // Add the suffix
            } else {
                if ($i === $count) {
                    // add the suffix
                    $suffix .= " and ";
                } else {
                    $suffix .= "";
                }
            }

            $summary .= $suffix;
        }

        return $summary;
    }
}