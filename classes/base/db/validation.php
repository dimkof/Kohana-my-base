<?php defined('SYSPATH') or die('No direct script access.');

class Base_Db_Validation {

    private static $new_record = FALSE;

    public static function int($key, $value, $rules)
    {
        $is_nullable = (bool) Arr::get($rules, 'is_nullable');
        if ($is_nullable)
            return NULL;

        $extra = Arr::get($rules, 'extra');
        if ($extra) {
            if (preg_match('/auto_increment/i', $extra)
                    && Base_Db_Validation::$new_record)
                return NULL;
        }

        if (!$is_nullable && !Valid::not_empty($value))
            return __('must_not_be_empty');

        if (!Valid::numeric($value))
            return __('must_be_valid_integer');

        $min = Arr::get($rules, 'min');
        $max = Arr::get($rules, 'max');
        if (($min && $max) && !Valid::range($value, $min, $max))
            return __('must_be_between_%1_and_%2', array('%1' => $min, '%2' => $max));

        if ($min && ($min > $value))
            return __('must_be_greater_than_' . $min);

        if ($max && ($max < $value))
            return __('must_be_less_than_' . $max);

        return NULL;
    }

    public static function string($key, $value, $rules)
    {
        $is_nullable = (bool) Arr::get($rules, 'is_nullable');
        if ($is_nullable)
            return NULL;

        if (!Valid::not_empty($value))
            return __('must_not_be_empty');

        $max = Arr::get($rules, 'max');
        if ($max && !Valid::max_length($value, $max))
            return __('must_be_less_than_' . $max);

        return NULL;
    }

    public static function check(&$model)
    {
        Base_Db_Validation::$new_record = $model->new_record();
        $result = TRUE;
        foreach ($model->get_table_columns() as $key => $rules) {
            $type = Arr::get($rules, 'type');
            if (method_exists('Base_Db_Validation', $type)) {
                $value = isset($model->$key) ? $model->$key : NULL;
                $_result = Base_Db_Validation::$type($key, $value, $rules);
                if ($_result) {
                    $model->add_error($key, $_result);
                    $result = FALSE;
                }
            }
        }
        return $result;
    }

}