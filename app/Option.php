<?php

namespace HNG;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Option extends Model {

    /**
     * @var
     */
    const READONLY = '__NOTHINGNESS__';

    /**
     * Cache key and expiry in minutes.
     */
    const CACHE_KEY    = 'HNG_FOOD_OPTIONS';
    const CACHE_EXPIRY = 5;
    const USE_CACHE    = false;

    /**
     * @var array
     */
    protected $fillable = ['value', 'option'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get or Set an option value.
     *
     * @param         $name
     * @param  string $value
     * @param bool    $default
     * @return bool|mixed
     */
    public function name($name, $value = self::READONLY, $default = false)
    {
        if ($value === static::READONLY) {
            return (static::USE_CACHE AND Cache::has(static::CACHE_KEY))
                ? $this->readOptionFromFileCache($name, $default)
                : $this->readOptionFromDatabase($name, $default);
        }

        $option = $this->whereOption($name)->first();

        $updatedOrCreatedAnOption = (bool) $option
            ? $option->update(['value', $value])
            : static::create(['option' => $name, 'value' => $value]);

        if ($updatedOrCreatedAnOption === true) {
            $this->recacheOptions();
        }

        return $updatedOrCreatedAnOption;
    }

    /**
     * Read this option from database.
     *
     * @param  $name
     * @param  $default
     * @return mixed
     */
    protected function readOptionFromDatabase($name, $default)
    {
        $this->recacheOptions();

        $option = static::whereOption($name)->first();

        return $option ? $option->value : $default;
    }

    /**
     * Read the option from the file cache.
     *
     * @param  $option
     * @param  $default
     * @return mixed
     */
    protected function readOptionFromFileCache($option, $default)
    {
        if ($options = Cache::get(static::CACHE_KEY)) {
            return array_get($options, $option, $default);
        }

        return $default;
    }

    /**
     * Get a value attribute.
     *
     * @param $value
     * @return mixed
     */
    public function getValueAttribute($value)
    {
        return $this->isJsonString($value) ? json_decode($value, true) : $value;
    }

    /**
     * Set the value attribute.
     *
     * @param $value
     */
    public function setValueAttribute($value)
    {
        if (is_array($value) OR is_object($value)) {
            $value = json_encode($value);
        }

        $this->attributes['value'] = $value;
    }

    /**
     * Checks to see if valid JSON string.
     *
     * @param $string
     * @return bool
     */
    protected function isJsonString($string)
    {
        $string = json_decode($string);
        return json_last_error() === JSON_ERROR_NONE;
    }

    /**
     * Cache the options table.
     */
    protected function recacheOptions()
    {
        if (static::USE_CACHE === true) {
            Cache::has(static::CACHE_KEY) AND Cache::forget(static::CACHE_KEY);
            Cache::put(static::CACHE_KEY, static::all()->toArray(), static::CACHE_EXPIRY);
        }
    }
}
