<?php

namespace HNG;

use Illuminate\Database\Eloquent\Model;

class Option extends Model {

    /**
     * @var
     */
    const READONLY = '__NOTHINGNESS__';

    /**
     * @var array
     */
    protected $fillable = ['value', 'option'];

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get an option value.
     *
     * @param         $name
     * @param  string $value
     * @param bool    $default
     * @return bool|mixed
     */
    public function name($name, $value = self::READONLY, $default = false)
    {
        $option = $this->whereOption($name)->first();

        if ($value === self::READONLY) {
            return $option ? $option->value : $default;
        }

        if ($option = $this->whereOption($name)->first()) {
            return (bool) $option->update(['value' => $value]);
        }

        return (bool) $this->create(['option' => $name, 'value' => $value]);
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
}
