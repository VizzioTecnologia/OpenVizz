<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2011, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * CodeIgniter Language Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		ExpressionEngine Dev Team, modified by Frank Souza
 * @link		http://codeigniter.com/user_guide/helpers/language_helper.html
 */

// ------------------------------------------------------------------------


/**
 * Lang
 *
 * Fetches a language variable and optionally outputs a form label
 *
 * @access	public
 * @param	string	the language line
 * @param	string	the id of the form element or a boolean value for activate, or not sprintf function with next arguments
 * @return	string
 */
function lang( $line, $id = NULL, $sp1 = '', $sp2 = '', $sp3 = '', $sp4 = '' ){
	
	$CI =& get_instance();
	$line = $CI->lang->line($line) ? $CI->lang->line($line) : $line;

	if ( $id ){
		
		$line = '<label for="'.$id.'">'.$line."</label>";
	}
	
	if ( $sp1 OR $sp2 OR $sp3 OR $sp4 ){
		
		$line = sprintf( $line, ( $sp1 ? $sp1 : NULL ), ( $sp2 ? $sp2 : NULL ), ( $sp3 ? $sp3 : NULL ), ( $sp4 ? $sp4 : NULL ) );
		
	}
	
	return $line;
	
}

// ------------------------------------------------------------------------
/* End of file language_helper.php */
/* Location: ./application/helpers/VECMS_language_helper.php */