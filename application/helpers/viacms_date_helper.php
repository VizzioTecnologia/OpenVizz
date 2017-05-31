<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function ov_strftime( $format, $timestamp ){
	
	// Check for Windows to find and replace the %e 
	// modifier correctly
	if (strtoupper(substr(PHP_OS, 0, 3)) == 'WIN') {
		
		$format = preg_replace('#(?<!%)((?:%%)*)%e#', '\1%#d', $format );
		
	}
	
	$format = str_replace(
		
		array(
			
			'%T',
			
		),
		
		array(
			
			'%H:%M:%S',
			
		),
		
		$format
		
	);
	
	return strftime( $format, $timestamp );
	
}

function created_date( $date_time, $format ){
	
	$time = human_to_unix( $date_time );
	$day = mdate("%d", $time);
	$month = lang(mdate("%F", $time));
	$year = mdate("%Y", $time);
	$hour = mdate("%H", $time);
	$minute = lang(mdate("%i", $time));
	$second = mdate("%s", $time);
	
	return sprintf( $format, $day, $month, $year, $hour, $minute, $second);
	
}

function s_datediff( $str_interval, $dt_menor, $dt_maior, $relative=false ) {
	
	if( is_string( $dt_menor)) $dt_menor = date_create( $dt_menor);
	if( is_string( $dt_maior)) $dt_maior = date_create( $dt_maior);
	
	$diff = date_diff( $dt_menor, $dt_maior, ! $relative);
	
	switch( $str_interval ){
		case "y": 
			$total = $diff->y + $diff->m / 12 + $diff->d / 365.25; break;
		case "m":
			$total= $diff->y * 12 + $diff->m + $diff->d/30 + $diff->h / 24;
			break;
		case "d":
			$total = $diff->y * 365.25 + $diff->m * 30 + $diff->d + $diff->h/24 + $diff->i / 60;
			break;
		case "h": 
			$total = ($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h + $diff->i/60;
			break;
		case "i": 
			$total = (($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i + $diff->s/60;
			break;
		case "s": 
			$total = ((($diff->y * 365.25 + $diff->m * 30 + $diff->d) * 24 + $diff->h) * 60 + $diff->i)*60 + $diff->s;
			break;
		}
	if( $diff->invert)
			return -1 * $total;
	else    return $total;
	
}

function date_is_valid( $date, $strict = true ) {
	$dateTime = DateTime::createFromFormat('Y-m-d', $date);
	if ($strict) {
		$errors = DateTime::getLastErrors();
		if (!empty($errors['warning_count'])) {
			return false;
		}
	}
	return $dateTime !== false;
}

/* End of file VECMS_date_helper.php */
/* Location: ./application/helpers/VECMS_date_helper.php */
