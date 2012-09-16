<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class to clean values in query
 * and convert them to type of the database table column type
 *
 * @package Kohana-my-base
 * @copyright 2012 pussbb@gmail.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GENERAL PUBLIC LICENSE v3
 * @version 0.1.2
 * @link https://github.com/pussbb/Kohana-my-base
 * @category database
 * @subpackage database
 */
class Base_Db_Sanitize {

    /**
     * convert value to int
     * @static
     * @param $value
     * @return array|int
     */
    public static function int($value)
    {
        if (!Arr::is_array($value))
            return intval($value);
        return array_map('intval', $value);
    }

    /**
     * convert to string and clean from xss injections
     * @static
     * @param $value
     * @return mixed|string
     */
    public static function string($value)
    {
        if (!Arr::is_array($value))
            return (string)Text::xss_clean($value);
        return aray_map('Text::xss_clean', $value);
    }
    
    public static function date($value)
    {
        return Date::format($value, 'YYYY-MM-DD');
    }

    public static function datetime($value)
    {
        return Date::format($value, 'YYYY-MM-DD HH:MM:SS');
    }

    public static function time($value)
    {
        return Date::format($value, 'HH:MM:SS');
    }
    /**
     * checks if class has function for field type
     * @static
     * @param $type
     * @param $value
     * @return mixed
     */
    public static function value($type, $value)
    {
        if ( ! method_exists('Base_Db_Value', $type))
            return $value;
        return Base_Db_Sanitize::$type($key, $value);
    }

}