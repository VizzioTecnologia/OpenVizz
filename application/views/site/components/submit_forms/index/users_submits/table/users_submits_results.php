<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

$ud_data_list = & $users_submits;
// echo '<pre>' . print_r( $props_to_show, TRUE ) . '</pre>';

?>


<div class="users-submits-wrapper results">
	
	<?php if ( check_var( $ud_data_list ) ) { ?>
		
		<?php if ( check_var( $params[ 'show_results_count' ] ) ) { ?>
		
		<div class="users-submits-search-results-title-wrapper ud-data-list-count">
			
			<h3 class="users-submits-search-results-title">
				
				<?php
					
					if ( $ud_data_list_total_results > 1 ) {
						
						if ( check_var( $params[ 'users_submits_search_results_string' ] ) ) {
							
							echo sprintf( $params[ 'users_submits_search_results_string' ], '<span class="users-submits-search-count">' . $ud_data_list_total_results . '</span>' );
							
						}
						else {
							
							echo sprintf( lang( 'users_submits_search_results_string' ), '<span class="users-submits-search-count">' . $ud_data_list_total_results . '</span>' );
							
						}
						
					}
					else {
						
						if ( check_var( $params[ 'users_submits_search_single_result_string' ] ) ) {
							
							echo sprintf( $params[ 'users_submits_search_single_result_string' ], '<span class="users-submits-search-count">' . $ud_data_list_total_results . '</span>' );
							
						}
						else {
							
							echo sprintf( lang( 'users_submits_search_single_result_string' ), '<span class="users-submits-search-count">' . $ud_data_list_total_results . '</span>' );
							
						}
						
					}
					
				?>
				
			</h3>
			
		</div>
		
		<?php } ?>
		
		<div class="s1 table-wrapper">
			
			<table class="ud-d-list responsive multi-selection-table">
				
				<tr>
					
					<?php foreach ( $props_to_show as $key => $prop ) { ?>
						
						<?php $current_column = $prop[ 'alias' ]; ?>
						
						<th class="col-<?= $current_column; ?><?= check_var( $props[ $current_column ][ 'ud_data_list_css_class' ] ) ? ' ' . $props[ $current_column ][ 'ud_data_list_css_class' ] : ''; ?>">
						
							<?= lang( $prop[ 'title' ] ); ?>
							
						</th>
						
					<?php } ?>
					
				</tr>
				
				<?php foreach( $ud_data_list as & $ud_data ) {
					
					$this->ud_api->parse_ud_data( $ud_data, $props_to_show );
					
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
				
				<tr class="ud-data-wrapper <?= $_us_has_status ? $_us_status_classes : ''; ?>">
					
					<?php foreach ( $props_to_show as $key => $prop_to_show ) {
						
						$_prop_is_ud_status = FALSE;
						
						$advanced_options = check_var( $props[ $prop_to_show[ 'alias' ] ][ 'advanced_options' ] ) ? $props[ $prop_to_show[ 'alias' ] ][ 'advanced_options' ] : FALSE;
						
						$pd = NULL;
						$alias = $prop_to_show[ 'alias' ];
						
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
							
							if ( check_var( $data_scheme[ 'ud_title_prop' ][ $alias ] ) AND check_var( $params[ 'ud_data_list_d_readmore_link' ] ) ) {
								
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
			
			<?php if ( check_var( $ud_data_list ) ) { ?>
			
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
		
		<h4 class="title">
		
			<div class="users-submits-description-no-search-results">
				
				<?= $_result_string; ?>
				
			</div>
			
		</h4>
		
	<?php } ?>
	
</div>
