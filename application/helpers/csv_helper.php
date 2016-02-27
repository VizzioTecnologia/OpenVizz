<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

// ------------------------------------------------------------------------

/**
 * CSV Helpers
 * Inspiration from PHP Cookbook by David Sklar and Adam Trachtenberg
 *
 * @author		Jérôme Jaglale
 * @modifier	Frank Souza
 * @link		http://maestric.com/en/doc/php/codeigniter_csv
 */

// ------------------------------------------------------------------------

/**
 * Array to CSV
 *
 * download == "" -> return CSV string
 * download == "toto.csv" -> download file toto.csv
 */
if ( ! function_exists('array_to_csv'))
{
	// let's add the $delimiter and $enclosure args ;)
	function array_to_csv( $array, $filename = NULL, $separator = NULL, $enclosure = NULL, $force_all_string = NULL, $download = NULL )
	{
		
		if ( isset( $enclosure ) ) {
			
			switch ( $enclosure ) {
			
				case 'tab':
					
					$enclosure = '	';
					break;
					
				case 'return':
					
					$enclosure = chr( 13 );
					break;
					
				case '':
					
					$enclosure = '';
					break;
					
			}
			
		}
		
		$separator = ! isset( $separator ) ? ';' : $separator;
		$enclosure = ! isset( $enclosure ) ? '"' : $enclosure;
		
		ob_start();
		
		$f = fopen('php://output', 'w') or show_error("Can't open php://output");
		
		if ( $download ) {
			
			header( 'Content-Description: File Transfer' );
			header( 'Content-Type: application/octet-stream' );
			
			header( 'Content-Encoding: iso-8859-1' );
			header( 'Content-type: application/vnd.ms-excel; charset=iso-8859-1' );
			header( 'Content-Disposition: attachement; filename="' . $filename . '"' );
			
			header( 'Content-Transfer-Encoding: binary' );
			header( 'Expires: 0' );
			header( 'Cache-Control: must-revalidate, post-check=0, pre-check=0' );
			header( 'Pragma: public' );
			
		}
		else {
			
			header( 'Content-Encoding: iso-8859-1' );
			header( 'Content-type: text/plain; charset=iso-8859-1' );
			header( 'Content-Disposition: inline; filename="' . $filename . '"' );
			
		}
		
		//fwrite( $f, "\xEF\xBB\xBF" ); // UTF-8 BOM
		
		$n = 0;
		foreach ( $array as $line ) {
			
			$n++;
			
			if ( $force_all_string ) {
				
				foreach ( $line as $k => & $v ) {
					
					$v = str_replace( $enclosure, $enclosure . $enclosure, $v );
					$v = $enclosure . $v . $enclosure;
					//$v = $enclosure . $v . $enclosure;
					
				}
				
				if ( ! fwrite( $f, implode( $separator, $line ) . "\r\n" ) ) {
					
					show_error( "Can't write line $n: $line" );
					
				}
				
			}
			else {
				
				foreach ( $line as $k => & $v ) {
					
					$v = str_replace( $enclosure, $enclosure . $enclosure, $v );
					
					if ( ! is_numeric( $v ) ) {
						
						$v = $enclosure . $v . $enclosure;
						
					}
					
					//$v = $enclosure . $v . $enclosure;
					
				}
				
				//if ( ! fputcsv( $f, $line, $separator, $enclosure ) ) {
				if ( ! fputs( $f, implode( $separator, $line )."\r\n" ) ) {
					
					show_error( "Can't write line $n: $line" );
					
				}
				
			}
			
		}
		
		fclose($f) or show_error("Can't close php://output");
		
		$str = ob_get_contents();
		$str = utf8_decode( $str );
		//$str = utf8_encode( chr(255) ) . utf8_encode( chr(254) ) . mb_convert_encoding( $str, 'UTF-16LE', 'UTF-8' );
		
		ob_end_clean();
		
		//$str = iconv( 'UTF-8', 'UTF-8//IGNORE', $str );
		//echo iconv( 'iso-8859-1', 'iso-8859-1', $str );
		echo $str;
		
		//echo $str;
		
	}
}

// ------------------------------------------------------------------------

function csv_encode_conv($var, $enc='Windows-1252') {
	
	$var = htmlentities($var, ENT_QUOTES, 'utf-8');
	$var = html_entity_decode($var, ENT_QUOTES , $enc);
	return $var;
	
}

// ------------------------------------------------------------------------

/**
 * Query to CSV
 *
 * download == "" -> return CSV string
 * download == "toto.csv" -> download file toto.csv
 */
if ( ! function_exists('query_to_csv'))
{
	function query_to_csv($query, $headers = TRUE, $download = "" )
	{
		if ( ! is_object($query) OR ! method_exists($query, 'list_fields'))
		{
			show_error('invalid query');
		}

		$array = array();

		if ($headers)
		{
			$line = array();
			foreach ($query->list_fields() as $name)
			{
				$line[] = $name;
			}
			$array[] = $line;
		}

		foreach ($query->result_array() as $row)
		{
			$line = array();
			foreach ($row as $item)
			{
				$line[] = $item;
			}
			$array[] = $line;
		}

		echo array_to_csv($array, $download);
	}
}

/* End of file csv_helper.php */
/* Location: ./system/helpers/csv_helper.php */