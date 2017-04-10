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
 
function &get_config( $replace = array() ) {
	
	static $_config;

	if (isset($_config))
	{
		return $_config[0];
	}

	// Is the config file in the environment folder?
	if ( ! defined('ENVIRONMENT') OR ! file_exists($file_path = APPPATH.'config/'.ENVIRONMENT.'/config.php'))
	{
		$file_path = APPPATH.'config/config.php';
	}

	// Fetch the config file
	if ( ! file_exists($file_path))
	{
		exit('The configuration file does not exist.');
	}

	require($file_path);

	// Does the $config array exist in the file?
	if ( ! isset($config) OR ! is_array($config))
	{
		exit('Your config file does not appear to be formatted correctly.');
	}

	// Are any values being dynamically replaced?
	if (count($replace) > 0)
	{
		foreach ($replace as $key => $val)
		{
			if (isset($config[$key]))
			{
				$config[$key] = $val;
			}
		}
	}

	$_config[0] =& $config;
	return $_config[0];
	
}
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

function set_status_header($code = 200, $text = '')
{
	$stati = array(
						200	=> 'OK',
						201	=> 'Created',
						202	=> 'Accepted',
						203	=> 'Non-Authoritative Information',
						204	=> 'No Content',
						205	=> 'Reset Content',
						206	=> 'Partial Content',

						300	=> 'Multiple Choices',
						301	=> 'Moved Permanently',
						302	=> 'Found',
						304	=> 'Not Modified',
						305	=> 'Use Proxy',
						307	=> 'Temporary Redirect',

						400	=> 'Bad Request',
						401	=> 'Unauthorized',
						403	=> 'Forbidden',
						404	=> 'Not Found',
						405	=> 'Method Not Allowed',
						406	=> 'Not Acceptable',
						407	=> 'Proxy Authentication Required',
						408	=> 'Request Timeout',
						409	=> 'Conflict',
						410	=> 'Gone',
						411	=> 'Length Required',
						412	=> 'Precondition Failed',
						413	=> 'Request Entity Too Large',
						414	=> 'Request-URI Too Long',
						415	=> 'Unsupported Media Type',
						416	=> 'Requested Range Not Satisfiable',
						417	=> 'Expectation Failed',
						450	=> 'Not logged in',

						500	=> 'Internal Server Error',
						501	=> 'Not Implemented',
						502	=> 'Bad Gateway',
						503	=> 'Service Unavailable',
						504	=> 'Gateway Timeout',
						505	=> 'HTTP Version Not Supported'
					);

	if ($code == '' OR ! is_numeric($code))
	{
		show_error('Status codes must be numeric', 500);
	}

	if (isset($stati[$code]) AND $text == '')
	{
		$text = $stati[$code];
	}

	if ($text == '')
	{
		show_error('No status text available.  Please check your status code number or supply your own message text.', 500);
	}

	$server_protocol = (isset($_SERVER['SERVER_PROTOCOL'])) ? $_SERVER['SERVER_PROTOCOL'] : FALSE;

	if (substr(php_sapi_name(), 0, 3) == 'cgi')
	{
		header("Status: {$code} {$text}", TRUE);
	}
	elseif ($server_protocol == 'HTTP/1.1' OR $server_protocol == 'HTTP/1.0')
	{
		header($server_protocol." {$code} {$text}", TRUE, $code);
	}
	else
	{
		header("HTTP/1.1 {$code} {$text}", TRUE, $code);
	}
}

/* End of file viacms_Common.php */
/* Location: ./application/core/viacms_Common.php */
