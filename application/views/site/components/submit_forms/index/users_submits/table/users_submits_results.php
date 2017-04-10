<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

?>


<div class="users-submits-wrapper results">
	
	<?php if ( check_var( $ud_data_array ) ) { ?>
		
		<?php if ( check_var( $params[ 'show_results_count' ] ) ) { ?>
		
		<div class="ud-d-list-results-title-wrapper ud-d-list-results-count-wrapper">
			
			<span class="s1">
				
				<span class="ud-d-list-results-title">
					
					<?php
						
						if ( $ud_data_list_total_results > 1 ) {
							
							if ( check_var( $params[ 'ud_d_list_search_results_string' ] ) ) {
								
								echo sprintf( $params[ 'ud_d_list_search_results_string' ], '<span class="ud-d-list-results-count">' . $ud_data_list_total_results . '</span>' );
								
							}
							else {
								
								echo sprintf( lang( 'ud_d_list_search_results_string' ), '<span class="ud-d-list-results-count">' . $ud_data_list_total_results . '</span>' );
								
							}
							
						}
						else {
							
							if ( check_var( $params[ 'users_submits_search_single_result_string' ] ) ) {
								
								echo sprintf( $params[ 'users_submits_search_single_result_string' ], '<span class="ud-d-list-results-count">' . $ud_data_list_total_results . '</span>' );
								
							}
							else {
								
								echo sprintf( lang( 'users_submits_search_single_result_string' ), '<span class="ud-d-list-results-count">' . $ud_data_list_total_results . '</span>' );
								
							}
							
						}
						
					?>
					
				</span>
				
			</span>
			
		</div>
		
		<?php } ?>
		
		<div class="s1 table-wrapper">
			
			<table class="ud-d-list responsive multi-selection-table">
				
				<?php
				
				$___index = TRUE;
				
				foreach( $ud_data_array as & $ud_data ) {
					
					$this->ud_api->parse_ud_data( $ud_data, $props_to_show );
					
					$_ud_data_wrapper_class = array();
					
					reset( $ud_data[ 'parsed_data' ][ 'full' ] );
					
					while ( list( $_pk, $parsed_array ) = each( $ud_data[ 'parsed_data' ][ 'full' ] ) ) {
						
						$prop_value = & $ud_data[ 'parsed_data' ][ 'full' ][ $_pk ][ 'value' ];
						
						$_prop = NULL;
						
						if ( isset( $props[ $_pk ] ) ) {
							
							$_prop = $props[ $_pk ];
							
						}
						
						if ( check_var( $_prop[ 'field_type' ] ) ){
							
							// date
							if ( $_prop[ 'field_type' ] == 'date' ){
								
								if ( check_var( $_prop[ 'sf_date_field_relative_datetime' ] ) AND date_is_valid( $prop_value ) ) {
									
									$date = new DateTime( $prop_value );
									$now = new DateTime( date( 'Y-m-d', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) ) );
									$yesterday = new DateTime( date( 'Y-m-d', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) ) );
									$yesterday->sub( date_interval_create_from_date_string( '1 day' ) );
									$tomorrow = new DateTime( date( 'Y-m-d', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) ) );
									$tomorrow->add( date_interval_create_from_date_string( '1 day' ) );
									
									if ( $date == $now ) {
										
										$_ud_data_wrapper_class[] = 'has-ud-event-datetime-is-today';
										
										$_ud_data_wrapper_class[] = 'is-today';
										
									}
									else if ( $date == $yesterday ) {
										
										$_ud_data_wrapper_class[] = 'has-ud-event-datetime-is-yesterday';
										
										$_ud_data_wrapper_class[] = 'is-yesterday';
										
									}
									else if ( $date == $tomorrow ) {
										
										$_ud_data_wrapper_class[] = 'has-ud-event-datetime-is-tomorrow';
										
										$_ud_data_wrapper_class[] = 'is-tomorrow';
										
									}
									
								}
								
							}
							
							if ( check_var( $prop_value ) AND check_var( $_prop[ 'advanced_options' ][ 'prop_is_ud_content' ] ) ) {
								
								$content_word_limit = check_var( $params[ 'users_submit_content_word_limit' ] ) ? $params[ 'users_submit_content_word_limit' ] : 120;
								$word_limit_str = '...';
								
								if ( $content_word_limit > 0 AND strlen( $prop_value ) > $content_word_limit ) {
									
									$prop_value = word_limiter( htmlspecialchars_decode( $prop_value ), $content_word_limit, $word_limit_str );
									
								}
								else {
									
									$prop_value = word_limiter( htmlspecialchars_decode( $prop_value ) );
									
								}
								
							}
							
						}
						
						$_ud_data_wrapper_class[] = 'ud-data-item-' . $_pk . '-value-' . word_limiter( str_replace( '_', '-', url_title( $prop_value, '-', TRUE ) ), 20, '' );
						
					}
					
					$_ud_data_wrapper_class = join( ' ', $_ud_data_wrapper_class );
					
					if ( isset( $___index ) ) {
						
						echo '<tr>';
						
						foreach ( $props_to_show as $alias => $v ) {
							
							echo '<th class="col-' . $alias . ( check_var( $props[ $alias ][ 'ud_data_list_css_class' ] ) ? ' ' . $props[ $alias ][ 'ud_data_list_css_class' ] : '' ) . '">';
							
							echo lang( $ud_data[ 'parsed_data' ][ 'full' ][ $alias ][ 'label' ] );
							
							echo '</th>';
							
						}
						
						echo '</tr>';
						
						$___index = NULL;
						unset( $___index );
						
					}
					
					$ud_status_prop_class = array();
					$_us_status = array();
					$_us_status_classes = '';
					$_us_has_status = FALSE;
					
					if ( check_var( $data_scheme[ 'ud_status_prop' ] ) ) {
						
	// 				echo '<pre>' . print_r( $props, TRUE ) . '</pre>';exit;
					
						foreach( $props as $alias => $status_field ) {
							
							if ( ! isset( $data_scheme[ 'ud_status_prop' ][ $alias ] ) ) {
								
								continue;
								
							}
							else {
								
								if ( check_var( $status_field[ 'options_from_users_submits' ] )
								AND ( check_var( $status_field[ 'options_title_field' ] )
								OR check_var( $status_field[ 'options_title_field_custom' ] ) ) ) {
									
									$_current_field_array = array(
										
										'prop_is_ud_status_active',
										'prop_is_ud_status_inactive',
										'prop_is_ud_status_enabled',
										'prop_is_ud_status_disabled',
										'prop_is_ud_status_canceled',
										'prop_is_ud_status_postponed',
										'prop_is_ud_status_archived',
										'prop_is_ud_status_published',
										'prop_is_ud_status_unpublished',
										'prop_is_ud_status_scheduled',
										
									);
									
									foreach( $_current_field_array as $_item ) {
										
										// Se o valor do dado unid for igual
										
										if ( check_var( $status_field[ 'advanced_options' ][ $_item ] ) AND check_var( $ud_data[ 'data' ][ $alias ] ) AND $ud_data[ 'data' ][ $alias ] == $status_field[ 'advanced_options' ][ $_item ] ) {
											
											$ud_status_prop_class[ $alias ][ $_item ] = $_item . ' status-' . str_replace( 'prop_is_ud_status_', '', $_item );
											
											$_us_status[ $alias ] = $_item . ' status-' . str_replace( 'prop_is_ud_status_', '', $_item );
											
											$_us_has_status = TRUE;
											
										}
										
									}
									
								}
								
							}
							
						}
						
					}
					
					if ( $_us_has_status ) {
						
						$_us_status_classes = join( $_us_status, ' ' );
						
					}
					
				?>
				
				<tr class="ud-data-wrapper <?= $_us_has_status ? $_us_status_classes : ''; ?> modal ud-data <?= $_ud_data_wrapper_class; ?>">
					
					<?php foreach ( $props_to_show as $alias => $prop_to_show ) {
						
						$_prop_is_ud_status = FALSE;
						
						$advanced_options = check_var( $props[ $alias ][ 'advanced_options' ] ) ? $props[ $alias ][ 'advanced_options' ] : FALSE;
						
						$pd = NULL;
						
						reset( $ud_data[ 'parsed_data' ][ 'full' ] );
						
						while( list( $_alias, $_pd ) = each( $ud_data[ 'parsed_data' ][ 'full' ] ) ) {
							
							if ( $alias == $_alias ) {
								
								$alias = $_alias;
								$pd = $_pd;
								
								break;
								
							}
							
						}
						
						if ( $pd ) {
							
							if ( check_var( $props[ $alias ][ 'field_type' ] ) AND $props[ $alias ][ 'field_type' ] == 'textarea' AND ( ! isset( $ud_data[ 'data' ][ $alias ] ) OR ! is_array( $ud_data[ 'data' ][ $alias ] ) ) ) {
								
								$pd[ 'value' ] = word_limiter( htmlspecialchars_decode( $pd[ 'value' ] ) );
								
							}
							else {
								
								$pd[ 'value' ] = word_limiter( $pd[ 'value' ] );
								
							}
							
						}
						
						?>
						
						<td
							
							class="<?php
								
								echo ' ud-data-prop-wrapper';
								echo ' col-' . $alias;
								echo check_var( $props[ $alias ][ 'ud_data_list_css_class' ] ) ? ' ' . $props[ $alias ][ 'ud_data_list_css_class' ] : '';
								echo check_var( $advanced_options[ 'prop_is_ud_image' ] ) ? ' field-is-image' : '';
								echo check_var( $advanced_options[ 'prop_is_ud_title' ] ) ? ' field-is-presentation-title' : '';
								echo check_var( $advanced_options[ 'prop_is_ud_content' ] ) ? ' field-is-presentation-content' : '';
								echo check_var( $advanced_options[ 'prop_is_ud_other_info' ] ) ? ' field-is-presentation-other-info' : '';
								echo check_var( $advanced_options[ 'prop_is_ud_email' ] ) ? ' field-is-email' : '';
								echo check_var( $advanced_options[ 'prop_is_ud_url' ] ) ? ' field-is-url' : '';
								echo check_var( $advanced_options[ 'prop_is_ud_status' ] ) ? ' field-is-status' : '';
								echo $pd[ 'value' ] ? ' ud-data-prop-value-' . $alias . '-' . url_title( base64_encode( $pd[ 'value' ] ), '-', TRUE ) : '';
								
								if ( ! check_var( $props[ $alias ][ 'options_from_users_submits' ] ) AND ! check_var( $props[ $alias ][ 'options' ] ) ) {
									
									echo ' ud-data-value-bit';
									
								}
								
								if ( $_us_has_status AND isset( $_us_status[ $alias ] ) ) {
									
									echo ' ' . $_us_status[ $alias ];
									
								}
								
								echo ' sf-field-type-' . ( check_var( $props[ $alias ][ 'field_type' ] ) ? $props[ $alias ][ 'field_type' ] : 'default' ) . '-wrapper';
							
							?>"
							
						>
							
						<?php
						
							echo '<span class="ud-data-value-wrapper">';
							
							if ( check_var( $data_scheme[ 'ud_title_prop' ][ $alias ] ) AND check_var( $params[ 'ud_data_list_d_titles_as_link' ] ) ) {
								
								echo '<a href="' . $ud_data[ 'site_link' ] . '">';
								
								echo $pd[ 'value' ];
								
								echo '</a>';
								
							}
							else {
								
								echo $pd[ 'value' ];
								
							}
							
							echo '</span>';
							
						?>
						
						</td>
						
					<?php } ?>
					
				</tr>
				
				<?php
				
					$ud_data = NULL;
					unset( $ud_data );
					
				} ?>
				
			</table>
			
			<?php if ( check_var( $ud_data_array ) ) { ?>
			
				<div class="clear"></div>
				
				<?php
					
					/* ---------------------------------------------------------------------------
					* ---------------------------------------------------------------------------
					* Read more
					* ---------------------------------------------------------------------------
					*/
					
					if ( check_var( $params[ 'ud_data_list_ds_readmore_link' ] ) AND file_exists( $_path . 'readmore.php' ) ) {
						
						require( $_path . 'readmore.php' );
						
					}
					
				?>
			
			<?php } ?>
			
		</div>
		
		<div class="clear"></div>
		
	<?php } else {
		
		$_result_string = '';
		
		if ( $this->input->post( 'users_submits_search', TRUE ) ) {
			
			if ( check_var( $params[ 'ud_data_no_search_result_str' ] ) ) {
				
				$_result_string = lang( $params[ 'ud_data_no_search_result_str' ] );
				
			} else {
				
				$_result_string = lang( 'ud_data_no_search_result_str_value' );
				
			}
			
		} else if ( check_var( $params[ 'show_default_results' ] ) ) {
			
			if ( check_var( $params[ 'ud_data_no_result_str' ] ) ) {
				
				$_result_string = lang( $params[ 'ud_data_no_result_str' ] );
				
			} else {
				
				$_result_string = lang( 'ud_data_no_result_str_value' );
				
			}
			
		} else if ( check_var( $params[ 'use_search' ] ) ) {
			
			$_result_string = lang( 'ud_data_list_init_search_str' );
			
		} ?>
		
		<div class="ud-d-list-no-search-results-desc-wrapper no-results">
		
			<span class="ud-d-list-no-search-results-desc">
				
				<?= $_result_string; ?>
				
			</span>
			
		</div>
		
	<?php } ?>
	
</div>
