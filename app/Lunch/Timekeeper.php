<?php namespace HNG\Lunch;

use Carbon\Carbon;
use HNG\Option;

class Timekeeper
{
    /**
     * @var Carbon
     */
    protected $carbon;

    /**
     * Timekeeper constructor.
     *
     * @param Carbon|null $carbon
     */
    public function __construct(Carbon $carbon = null)
    {
        $this->carbon = $carbon ? $carbon : Carbon::now();
    }

    /**
     * Check if we are in a weekend.
     *
     * @return bool
     */
    public function isWeekend()
    {
        return $this->carbon()->isWeekend();
    }

    /**
     * Check if we are in a weekday
     *
     * @return bool
     */
    public function isWeekday()
    {
        return ! $this->isWeekend();
    }

    /**
     * Check if the current hour is between two hours.
     *
     * @param  $firstHour
     * @param  $secondHour
     * @return bool
     */
    public function isHoursBetween($firstHour, $secondHour)
    {
        $currentHour = $this->carbon()->hour;

        return $currentHour >= $firstHour && $currentHour < $secondHour;
    }

    /**
     * Are we during normal working hours?
     *
     * @return bool
     */
    public function duringWorkingHours()
    {
        return $this->isWeekday() && $this->isHoursBetween(8, 6);
    }

    /**
     * Is within the lunch order hours.
     *
     * @return bool
     */
    public function isWithinLunchOrderHours()
    {
        return $this->allowOrdersAtAnytime() OR ($this->isWeekday() && $this->isHoursBetween(8, 9));
    }

    /**
     * Carbon instance.
     *
     * @return Carbon
     */
    public function carbon()
    {
        return $this->carbon;
    }

    /**
     * Checks if the environment is local.
     *
     * @return boolean
     */
    private function allowOrdersAtAnytime()
    {
        $fallback = env('ALLOW_ANYTIME_FOOD_ORDERS');

        return (bool) option('ALLOW_ANYTIME_FOOD_ORDERS', Option::READONLY, $fallback);
    }
}
