<?php defined('SYSPATH') or die('No direct script access.');

/**
 * Helper functions to work with directories
 * @package Kohana-my-base
 * @copyright 2012 pussbb@gmail.com
 * @license http://www.gnu.org/copyleft/gpl.html GNU GENERAL PUBLIC LICENSE v3
 * @version 0.1.2 
 * @link https://github.com/pussbb/Kohana-my-base
 * @category extra
 * @subpackage extra
 */

class Dir {


	/**
	 * checks if dir exists and if not creates it(can create '/dir1/dir2')
	 * @param $directory string
	 * @access public
	 * @static
	 * @throws Exception
	 */
    public static function create_if_need($directory)
    {
        if ( ! file_exists($directory))
        {
            if ( ! mkdir($directory, 0755, TRUE))
                throw new Exception('Could not create directory'.$directory);
        }
    }

	/**
	 * removes dir and content
	 * @param $directory string
	 * @param $self bool
	 * @access public
	 * @static
	 */
    public static function rmdir($dir, $self = TRUE)
    {
		$files = scandir($dir);
	    array_shift($files);    // remove '.' from array
	    array_shift($files);    // remove '..' from array
	   
	    foreach ($files as $file) {
	        $file = $dir . '/' . $file;
	        if (is_dir($file)) {
	            Dir::rmdir($file);
	            rmdir($file);
	        } else {
	            unlink($file);
	        }
	    }
	    if ($self)
	    	rmdir($dir);
	}


	/**
	 * returns founded files in directory recursivly
	 * @param $condition string file extention or regexpr
	 */
	public static function files($dir, $condition = null, &$array = array())
	{
		$files = scandir($dir);
	    array_shift($files);    // remove '.' from array
	    array_shift($files);    // remove '..' from array

	    foreach ($files as $file) {
	        $file = Text::reduce_slashes($dir . '/' . $file);
	        if (is_dir($file)) {
	            Dir::files($file , $condition, $array);
	        } else {
	        	if ( ! $condition) {
	        		$array[] = $file;
	            }
	            elseif (Valid::regexpr($condition) && preg_match($condition, $file))
	            	$array[] = $file;
	            elseif (pathinfo($file, PATHINFO_EXTENSION) == $condition)
	            	$array[] = $file;
	        }
	    }
	    return $array;
	}
}