<?php defined('SYSPATH') or die('No direct script access.');
/**
 * Class to manipulate with user Lang
 *
 * @package Kohana-my-base
 * @copyright 2012 pussbb@gmail.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GENERAL PUBLIC LICENSE v3
 * @version 0.1.2
 * @link https://github.com/pussbb/Kohana-my-base
 * @category extra
 * @subpackage extra
 */
class Base_Language {


    /**
     * @var array
     */
    private static $lang_codes = array();
    private static $available_cache = array();

    /**
     * set current lang
     * @param $language (model object)
     * @static
     */
    public static function set($language)
    {
        Session::instance()->set('language', $language);
    }
    /**
     * get current lang
     * @param mixed $lang
     * @return language (model object)
     * @static
     */
    public static function get($lang = NULL)
    {
        $language = Session::instance()->get('language');
        if ( ! is_object($language) && ! $lang) {
            return self::get_default();
        }
        elseif ( ! is_object($language) ||
          (is_object($language) && ($lang && $language->code != $lang))) {
            try {
                return self::get_lang($lang);
            } catch(Exception $e) {
            }
        }
        return $language;
    }
    /**
     * get default lang
     * @return default language (model object)
     * @static
     */
    public static function get_default()
    {
        $code = Kohana::$config->load('site.default_language');
        return self::get_lang($code);
    }

    /**
     * get lang
     * @param string $code language code
     * @return language (model object)
     * @static
     */
    private static function get_lang($code = NULL)
    {
        $filter = $code ? array('code' => $code) : array('locale' => I18n::lang());
        $language = Arr::get(self::$available_cache, $code, Model_Language::find($filter, TRUE));
        self::set($language);
        return $language;
    }

    /**
     * get all available languages code
     * @return array
     * @static
     */
    public static function all_codes()
    {
        if ( ! self::$lang_codes ) {
            self::$lang_codes = array_keys(self::$available_cache?:self::available());
        }
        return self::$lang_codes;
    }

    /**
     * get all available languages
     * @return array
     * @static
     */
    public static function available()
    {
        if ( ! self::$available_cache ) {
            foreach(Model_Language::find_all() as $lang) {
                self::$available_cache[$lang->code] = $lang;
            }
        }
        return self::$available_cache;
    }
    /**
     * php reg expr to match lang codes
     * @return array
     * @static
     */
    public static function uri_check_codes()
    {
        return '('.implode('|', self::all_codes()).')';
    }
}
