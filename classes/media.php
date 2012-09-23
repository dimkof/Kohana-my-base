<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Class just to make easy call Base_Media functions
 *
 * <code>
 *  <?php
 *      Media::append('css', 'jquery.ui', 'screen');//etc
 *  ?>
 * </code>
 * @see Base_Media
 * @package Kohana-my-base
 * @copyright 2012 pussbb@gmail.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GENERAL PUBLIC LICENSE v3
 * @version 0.1.2
 * @link https://github.com/pussbb/Kohana-my-base
 * @subpackage template
 * @category template
 */

class Media {

    /**
     * @ignore
     */
    public static function __callStatic($name, $arguments)
    {
        $media = Base_Media::instance();
        if ($name == "instance")
            return $media;

        if (method_exists($media, $name))
            return call_user_func_array(array($media, $name), $arguments);
    }

}

