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
function lang(){
	
	$num_args = func_num_args();
	$arg_list = func_get_args();
	
	if ( $num_args > 0 ) {
		
		$line = $arg_list[ 0 ];
		$id = isset( $arg_list[ 1 ] ) ? $arg_list[ 1 ] : NULL;
		$vsprintf_args = $arg_list;
		
		$CI =& get_instance();
		$line = $CI->lang->line( $line ) ? $CI->lang->line( $line ) : $line;
		
		if ( $id ){
			
			$line = '<label for="' . $id . '">' . $line . "</label>";
			
		}
		
		if ( $num_args > 2 ) {
			
			unset( $vsprintf_args[ 1 ] );
			unset( $vsprintf_args[ 0 ] );
			
			$line = vsprintf( $line, $vsprintf_args );
			
		}
		
		return $line;
		
	}
	
}

// ------------------------------------------------------------------------
/* End of file language_helper.php */
/* Location: ./application/helpers/VECMS_language_helper.php */