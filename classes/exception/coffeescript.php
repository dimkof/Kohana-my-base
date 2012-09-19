<?php defined('DOCROOT') or die('No direct script access.');
/**
 * sets human readable message for json_last_error
 *
 * @package Kohana-my-base
 * @copyright 2012 pussbb@gmail.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GENERAL PUBLIC LICENSE v3
 * @version 0.1.2 
 * @link https://github.com/pussbb/Kohana-my-base
 * @category extra
 * @subpackage extra
 */
class Exception_CoffeeScript extends Exception {
    /**
     * sets human readable message for json_last_error
     * @internal
     * @constructor
     * @access public
     * @return void
     */
    public function __construct($message , $code = 0)
    {
        ob_clean();
    	Kohana_Kohana_Exception::$error_view = "errors/coffeescript";
    	parent::__construct($message);
    }
}