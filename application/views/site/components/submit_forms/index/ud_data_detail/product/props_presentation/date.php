<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
	
	$prop_y = date( 'Y', strtotime( $prop_value ) );
	$prop_m = date( 'm', strtotime( $prop_value ) );
	$prop_d = date( 'd', strtotime( $prop_value ) );
	
	$current_date[ 'y' ] = date( 'Y', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) );
	$current_date[ 'm' ] = date( 'm', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) );
	$current_date[ 'd' ] = date( 'd', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) );
	
	/*
	echo '<br/>---------------------';
	echo '<br/>$prop_value- ' . $prop_value;
	echo '<br/>y-' . ( $prop_y - $current_date[ 'y' ] );
	echo '<br/>m-' . ( $prop_m - $current_date[ 'm' ] );
	echo '<br/>d-' . ( $prop_d - $current_date[ 'd' ] );
	echo '<br/>strtotime- ' . strtotime( $prop_value );
	echo '<br/>gmt_to_local- ' . gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
	
	$date = new DateTime( $prop_value );
	$now = new DateTime( date( 'Y-m-d', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) ) );
	
	$difference = $date->diff( $now, FALSE );
	
	echo '<br/>' . $date->diff( $now )->format("%y years, %b months, %d days, %h hours and %i minuts");;
	echo '<br/>' . ( strtotime( $prop_value ) - strtotime( date( 'Y-m-d', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) ) ) );
	
	echo '<br/>' . $difference->y;
	echo '<br/>' . $difference->m;
	echo '<br/>' . $difference->d;
	
	echo '<br/>' . s_datediff( 'y', date( 'Y-m-d', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) ), $prop_value, FALSE );
	echo '<br/>' . s_datediff( 'm', date( 'Y-m-d', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) ), $prop_value, FALSE );
	echo '<br/>' . s_datediff( 'd', date( 'Y-m-d', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) ), $prop_value, FALSE );
	*/
	
	$format = '';
	
	$format .= ( check_var( $_prop[ 'sf_date_field_use_year' ] ) AND ! ( $prop_y == $current_date[ 'y' ] AND check_var( $_prop[ 'sf_date_field_hide_current_year' ] ) ) ) ? 'y' : '';
	$format .= ( check_var( $_prop[ 'sf_date_field_use_month' ] ) AND ! ( $prop_m == $current_date[ 'm' ] AND $prop_y == $current_date[ 'y' ] AND check_var( $_prop[ 'sf_date_field_hide_current_month' ] ) ) ) ? 'm' : '';
	$format .= ( check_var( $_prop[ 'sf_date_field_use_day' ] ) AND ! ( $prop_d == $current_date[ 'd' ] AND $prop_m == $current_date[ 'm' ] AND $prop_y == $current_date[ 'y' ] AND check_var( $_prop[ 'sf_date_field_hide_current_day' ] ) ) ) ? 'd' : '';
	
	$format = 'sf_us_dt_ft_pt_' . $format . '_' . ( check_var( $_prop[ 'sf_date_field_presentation_format' ] ) ? $_prop[ 'sf_date_field_presentation_format' ] : 'ymd' );
	
	$relative_datetime = '';
	
	$is_today = FALSE;
	$is_yesterday = FALSE;
	$is_tomorrow = FALSE;
	
	if ( check_var( $_prop[ 'sf_date_field_relative_datetime' ] ) AND date_is_valid( $prop_value ) ) {
		
		$date = new DateTime( $prop_value );
		$now = new DateTime( date( 'Y-m-d', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) ) );
		$yesterday = new DateTime( date( 'Y-m-d', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) ) );
		$yesterday->sub( date_interval_create_from_date_string( '1 day' ) );
		$tomorrow = new DateTime( date( 'Y-m-d', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) ) );
		$tomorrow->add( date_interval_create_from_date_string( '1 day' ) );
		
		$difference = $date->diff( $now );
		
		$_y = $difference->y;
		$_m = $difference->m;
		$_d = $difference->d;
		$_h = $difference->h;
		$_i = $difference->i;
		$_s = $difference->s;
		
		$relative_datetime_str_prefix_fw = 'sf_us_dt_relative_fw_';
		$relative_datetime_str_prefix_bw = 'sf_us_dt_relative_bw_';
		
		if ( $date == $now ) {
			
			$is_today = TRUE;
			
			$relative_datetime = lang( 'sf_us_dt_relative_today' );
			
		}
		else if ( $date == $yesterday ) {
			
			$is_yesterday = TRUE;
			
			$relative_datetime = lang( 'sf_us_dt_relative_yesterday' );
			
		}
		else if ( $date == $tomorrow ) {
			
			$is_tomorrow = TRUE;
			
			$relative_datetime = lang( 'sf_us_dt_relative_tomorrow' );
			
		}
		else if ( $_y != 0 ) {
			
			if ( $_y == 1 ) {
				
				$relative_datetime = sprintf( lang( $relative_datetime_str_prefix_fw . 'y' ), $_m );
				
			}
			else if ( $_y == -1 ) {
				
				$relative_datetime = sprintf( lang( $relative_datetime_str_prefix_bw . 'y' ), $_m );
				
			}
			if ( $date < $now ) {
				
				$relative_datetime = sprintf( lang( $relative_datetime_str_prefix_bw . 'plural_' . 'y' ), $_y );
				
			}
			else if ( $date > $now ) {
				
				$relative_datetime = sprintf( lang( $relative_datetime_str_prefix_fw . 'plural_' . 'y' ), $_y );
				
			}
			
		}
		else if ( $_m != 0 ) {
			
			if ( $_m == 1 ) {
				
				$relative_datetime = sprintf( lang( $relative_datetime_str_prefix_fw . 'm' ), $_m );
				
			}
			else if ( $_m == -1 ) {
				
				$relative_datetime = sprintf( lang( $relative_datetime_str_prefix_bw . 'm' ), $_m );
				
			}
			else if ( $date < $now ) {
				
				$relative_datetime = sprintf( lang( $relative_datetime_str_prefix_bw . 'plural_' . 'm' ), $_m );
				
			}
			else if ( $date > $now ) {
				
				$relative_datetime = sprintf( lang( $relative_datetime_str_prefix_fw . 'plural_' . 'm' ), $_m );
				
			}
			
		}
		else if ( $_d != 0 ) {
			
			if ( $date < $now ) {
				
				$relative_datetime = sprintf( lang( $relative_datetime_str_prefix_bw . 'plural_' . 'd' ), $_d );
				
			}
			else if ( $date > $now ) {
				
				$relative_datetime = sprintf( lang( $relative_datetime_str_prefix_fw . 'plural_' . 'd' ), $_d );
				
			}
			
		}
		
	}
	
	$prop_value =
		
		( $relative_datetime ? '<span class="relative-datetime' . ( ( $is_today ) ? ' is-today' : ( ( $is_yesterday ) ? ' is-yesterday' : ( ( $is_tomorrow ) ? ' is-tomorrow' : '' ) ) ) . '">'
		. $relative_datetime
		. '</span> ' : '' )
		
		. '<span class="normal-datetime">'
		. ov_strftime( lang( $format ), strtotime( $prop_value ) )
		. '</span>';
	
?>
