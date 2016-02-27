<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

 /**
 * Error Logging Interface
 *
 * We use this as a simple mechanism to access the logging
 * class and send messages to be logged.
 *
 * @access			public
 * @param			string		$level
 * @param			string		$message
 * @param			boolean		$php_error
 * @param			mixed		$caller_info
 * @filesource		application/core/viacms_Common.php
 * @return			void
 */
if ( ! function_exists('log_message'))
{
	function log_message( $level = 'error', $message, $php_error = FALSE, $caller_info = TRUE )
	{
		static $_log;
		
		if (config_item( 'log_threshold' ) == 0)
		{
			return;
		}
		
		if ( is_string( $caller_info ) ){
			
			$message .= $caller_info;
			
		}
		else {
			
			$bt = debug_backtrace();
			$caller = array_shift( $bt );
			
			$message .= ' --- File: ' . str_replace( BASEPATH, '', $caller[ 'file' ] );
			$message .= ' --- Line: ' . str_replace( BASEPATH, '', $caller[ 'line' ] );
			
		}
		
		$_log =& load_class( 'Log' );
		$_log->write_log( $level, $message, $php_error );
		
	}
}


/* End of file viacms_Common.php */
/* Location: ./application/core/viacms_Common.php */