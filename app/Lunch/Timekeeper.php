<?php namespace HNG\Lunch;

use Carbon\Carbon;

class Timekeeper {

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
        $sob = option('WORK_RESUMES');
        $cob = option('WORK_CLOSES');

        return $this->isWeekday() && $this->isHoursBetween($sob, $cob);
    }

    /**
     * Is within the lunch order hours.
     *
     * @return bool
     */
    public function isWithinLunchOrderHours()
    {
        $soo = option('ORDER_RESUMES');
        $coo = option('ORDER_CLOSES');

        return $this->allowOrdersAtAnytime() OR ($this->isWeekday() && $this->isHoursBetween($soo, $coo));
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
        return (bool) get_option('ALLOW_ANYTIME_FOOD_ORDERS');
    }
}
