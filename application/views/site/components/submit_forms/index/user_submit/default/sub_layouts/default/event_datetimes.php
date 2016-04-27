
<?php
	
	$prop_y = date( 'Y', strtotime( $user_submit[ 'data' ][ $_alias ] ) );
	$prop_m = date( 'm', strtotime( $user_submit[ 'data' ][ $_alias ] ) );
	$prop_d = date( 'd', strtotime( $user_submit[ 'data' ][ $_alias ] ) );
	
	$current_date[ 'y' ] = date( 'Y', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) );
	$current_date[ 'm' ] = date( 'm', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) );
	$current_date[ 'd' ] = date( 'd', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) );
	
	$format = '';
	
	$format .= ( $props[ $_alias ][ 'sf_date_field_use_year' ] AND ! ( $prop_y == $current_date[ 'y' ] AND check_var( $props[ $_alias ][ 'sf_date_field_hide_current_year' ] ) ) ) ? 'y' : '';
	$format .= ( $props[ $_alias ][ 'sf_date_field_use_month' ] AND ! ( $prop_m == $current_date[ 'm' ] AND check_var( $props[ $_alias ][ 'sf_date_field_hide_current_month' ] ) ) ) ? 'm' : '';
	$format .= ( $props[ $_alias ][ 'sf_date_field_use_day' ] AND ! ( $prop_d == $current_date[ 'd' ] AND check_var( $props[ $_alias ][ 'sf_date_field_hide_current_day' ] ) ) ) ? 'd' : '';
	
	$format = 'sf_us_dt_ft_pt_' . $format . '_' . $props[ $_alias ][ 'sf_date_field_presentation_format' ];
	
?>

<?php if ( $_field ) { 
	
	?>
	
	<div class="<?= check_var( $props[ $_alias ][ 'sf_date_field_relative_datetime' ] ) ? 'is-ud-date-prop-relative-datetime ' : ''; ?>ud-data-prop-date-format-<?= $format; ?> ud-data-prop ud-data-prop-event-datetime user-submit-event-datetime-wrapper user-submit-alias-<?= url_title( $_alias ); ?> user-submit-event-datetime-<?= url_title( $_field[ 'label' ] ); ?>">
		
		<div class="title"><?= $_field[ 'label' ]; ?></div>
		<div class="value"><?= $_field[ 'value' ]; ?></div>
		
	</div>
	
<?php } ?>
