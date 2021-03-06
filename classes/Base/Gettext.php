<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class adds extra functionality to Kohana_self to support tr from gettext
 *
 * @package Kohana-my-base
 * @copyright 2012 pussbb@gmail.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GENERAL PUBLIC LICENSE v3
 * @version 0.1.2
 * @link https://github.com/pussbb/Kohana-my-base
 * @category template
 * @subpackage template
 */
class Base_Gettext extends Kohana_I18n {

    /**
     * @var  string   target language: en-us, es-es, zh-cn, etc
     */
    public static $lang = 'en-EN';

    /**
     * @var  string   target language: en-us, es-es, zh-cn, etc
     */
    public static $domain = 'my_site';

    /**
     * @var  string   target language: en-us, es-es, zh-cn, etc
     */
    public static $encoding = 'UTF-8';

    /**
     * @var  string   target language: en-us, es-es, zh-cn, etc
     */
    public static $gettext_enabled = TRUE;

    public static $tr_func = NULL;

    public static function init($gettext_enabled = TRUE)
    {
        self::$gettext_enabled = $gettext_enabled;
        self::$tr_func = function($string, $values) {
            return vsprintf(gettext($string), $values);
        };
        if ( ! self::$gettext_enabled ) {
            self::$tr_func = function($string, $values) {
                preg_match_all(
                    '/%(?:\d+\$)?[+-]?(?:[ 0]|\'.{1})?-?\d*(?:\.\d+)?[bcdeEufFgGosxX]/',
                    $str,
                    $matches,
                    PREG_PATTERN_ORDER
                );
                return __($string, array_combine($matches, $values));
            };
        }
    }

    /**
     * set language
     * @static
     * @access public
     * @return string
     */
    public static function lang($lang = 'en-EN')
    {
        $_lang = parent::lang($lang);
        setlocale(LC_ALL, str_replace('-', '_', $lang).'.'.self::$encoding);
        bindtextdomain (self::$domain, self::base_dir());
        textdomain (self::$domain);
        bind_textdomain_codeset(self::$domain, self::$encoding);
        return $_lang;
    }

    /**
     * directory with files with translation for given lang
     * @static
     * @param $lang string lang code 'en-EN'
     * @return string
     */
    public static function tr_path($lang = 'en-EN')
    {
        $pieces = array(
            DOCROOT,
            'locale',
            Arr::get(explode('-', $lang), 0),
            'LC_MESSAGES'
        );
        return Text::reduce_slashes(implode(DIRECTORY_SEPARATOR, $pieces).DIRECTORY_SEPARATOR);
    }
    /**
     * main directory for the translations
     * @static
     * @retun string absolute path
     */
    public static function base_dir()
    {
        return DOCROOT.'locale'.DIRECTORY_SEPARATOR;
    }
    /**
     * get translation file
     * @static
     * @param $lang string
     * @param $ext string
     * @return string
     */
    public static function absolute_file_path($lang = 'en-EN', $ext = 'po')
    {
        return self::tr_path($lang).self::$domain.'.'.$ext;
    }
    /**
     * checks if gettext support enabled
     * @static
     * @retun bool
     */
    public static function gettext_enabled()
    {
        return Tools::can_call('gettext');
    }

}

if ( ! function_exists('tr') )
{
    /**
    * translate string via gettext
    * or Kohana's translation function
    *
    * @param $string string
    * @param $values array
    * @return string
    */
    function tr($string, array $values = array())
    {
        return call_user_func(Base_Gettext::$tr_func, $string, $values);
    }
}
