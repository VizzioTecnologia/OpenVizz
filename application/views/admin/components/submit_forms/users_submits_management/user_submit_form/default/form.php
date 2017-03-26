<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
	
	$props_path = ADMIN_COMPONENTS_VIEWS_PATH . 'submit_forms' . DS . 'users_submits_management' . DS . 'user_submit_form' . DS . 'default' . DS . 'props' . DS;
	
	$unique_hash = md5( uniqid( rand(), true ) );
	
?>

<?= form_open_multipart( get_url( 'admin' . $this->uri->ruri_string() ), array( 'id' => 'submit-form-form', ) ); ?>
	
	<?php foreach ( $fields as $key => $field ) {
		
		if ( check_var( $field[ 'availability' ][ 'admin' ] ) ) {
			
			$field_name = $field[ 'alias' ];
			$formatted_field_name = 'form[' . $field_name . ']';
			$error = form_error( $formatted_field_name, '<span class="msg-inline-error error">', '</span>' );
			$field_value = ( check_var( $field[ 'default_value' ] ) ) ? $field[ 'default_value' ] : NULL;
			
			if ( $this->mcm->environment == ADMIN_ALIAS ) {
				
				$_is_ud_image = FALSE;
				$_is_ud_file = FALSE;
				$_ud_upload_dir = MEDIA_DIR_NAME;
				$_ud_upload_dir_start_dir = '';
				
				$_ud_image_js_function_name = url_title( 'update_image_' . $field_name, '_' );
				$_ud_file_js_function_name = url_title( 'update_file_' . $field_name, '_' );
				
			}
			if ( $this->mcm->environment == SITE_ALIAS ) {
				
				if ( ! $field_value ) {
					
					
					
				}
				
			}
			
			$field_value = ( isset( $post[ 'form' ][ $field_name ] ) ) ? $post[ 'form' ][ $field_name ] : ( ( isset( $user_submit[ 'data' ][ $field_name ] ) ) ? $user_submit[ 'data' ][ $field_name ] : '' );
			
			if ( check_var( $field[ 'advanced_options' ][ 'prop_is_ud_image' ] ) ) {
				
				$_is_ud_image = TRUE;
				
			}
			
			if ( check_var( $field[ 'advanced_options' ][ 'prop_is_ud_file' ] ) ) {
				
				$_is_ud_file = TRUE;
				
			}
			
			if ( $field[ 'field_type' ] == 'html' AND isset( $field[ 'html' ] ) ) {
				
				require( $props_path . 'html.php' );
				
			} else if ( $field[ 'field_type' ] == 'input_text' ) {
				
				?><div id="<?= $unique_hash; ?>-ud-prop-field-<?= $field_name; ?>" class="<?= $_is_ud_image ? 'ud-image' : ''; ?> ud-prop-field-wrapper vui-field-wrapper vui-field-wrapper-inline <?= ( check_var( $field[ 'form_css_class' ] ) ) ? $field[ 'form_css_class' ] : ''; ?> submit-form-field-wrapper submit-form-field-wrapper-<?= $field_name; ?>-wrapper submit-form-field-wrapper-<?= $field[ 'field_type' ]; ?> <?= ( $error ) ? 'form-error error' : ''; ?>"><?php
					
					echo form_label( lang( $field[ 'label' ] ) . ( check_var( $field[ 'field_is_required' ] ) ? ' <span class="submit-form-required">*</span> ' . $error : ' ' . $error ) );
					
					?><div class="submit-form-field-control ud-field-control">
						
						<?php
							
							if ( $_is_ud_file ) {
								
								if ( check_var( $field[ 'advanced_options' ][ 'prop_is_ud_file_upload_dir' ] ) ) {
									
									$_ud_upload_dir = trim( $field[ 'advanced_options' ][ 'prop_is_ud_file_upload_dir' ], '/' );
									
								}
								
								if ( $_is_ud_image ) {
									
									if ( check_var( $field_value ) ) {
										
										$thumb_params = array(
											
											'wrapper_class' => 'us-image-wrapper',
											'src' => url_is_absolute( $field_value ) ? $field_value : get_url( 'thumbs/' . $field_value ),
											'href' => get_url( $field_value ),
											'rel' => 'us-thumb',
											'title' => $field_value,
											'id' => 'thumb-submit-form-' . $field_name,
											'modal' => TRUE,
											'prevent_cache' => TRUE,
											
										);
										
										echo vui_el_thumb( $thumb_params );
										
									}
									
								}
								
							}
							
							echo vui_el_input_text(
								
								array(
									
									'text' => lang( $field[ 'presentation_label' ] ),
									'id' => 'submit-form-' . $field_name,
									'value' => $field_value,
									'name' => $formatted_field_name,
									'class' => 'form-element submit-form submit-form-' . $field_name,
									'icon' => $_is_ud_image ? 'image' : NULL,
									
								)
								
							);
							
							if ( ! url_is_absolute( $field_value ) ) {
								
								$_path = pathinfo( $field_value );
								
								if ( check_var( $_path[ 'dirname' ] ) ) {
									
									$_ud_upload_dir_start_dir = trim( str_replace( $_ud_upload_dir, '', $_path[ 'dirname' ] ), DS );
									
								}
								
							}
							
						?>
						
						<?php if ( $_is_ud_file ) { ?>
							
							<script type="text/javascript">
								
								$( document ).bind( 'ready', function(){
									
									$( '#<?= 'submit-form-' . $field_name; ?>' ).after( '<?php
										
										echo str_replace(
											
											"'", "\'", vui_el_button(
												
												array(
													
													'url' => '#',
													'text' => lang( 'select_image' ),
													'get_url' => FALSE,
													'id' => 'image-picker',
													'icon' => 'more',
													'only_icon' => TRUE,
													'wrapper-class' => 'vui-picker',
													'class' => 'modal-file-picker',
													'attr' => array(
														
														'data-rffieldid' => 'submit-form-' . $field_name,
														'data-rftype' => $_is_ud_image ? 'image' : ( $_is_ud_file ? 'all' : '' ),
														'data-rfdir' => $_ud_upload_dir,
														'data-rf-start-dir' => $_ud_upload_dir_start_dir,
														'data-rf-callback-function-on-choose' => $_is_ud_image ? $_ud_image_js_function_name : ( $_is_ud_file ? $_ud_file_js_function_name : '' ),
														
													)
														
												)
												
											)
											
										);
										
									?>' );
									
								});
								
								<?php if ( $_is_ud_image ) { ?>
									
									window.<?= $_ud_image_js_function_name; ?> = function() {
										
										console.log( 'file choosed' );
										
										var url = '<?= BASE_URL; ?>/' + $( '#<?= 'submit-form-' . $field_name; ?>' ).val();
										
										if ( url != '' ){
											
											$.imageCrop.open({
												
												imgSrc: url,
												callback: update_image_<?= $_ud_image_js_function_name; ?>
												
											});
											
										}
										
									}
									window.update_image_<?= $_ud_image_js_function_name; ?> = function(){
										
										var url = $( '#<?= 'submit-form-' . $field_name; ?>' ).val();
										var $thumb_image = $( '#<?= 'thumb-submit-form-' . $field_name; ?>' );
										
										var image_src = url + '?' + Math.floor( ( Math.random() * 100 ) + 1 ); // prevent cache
										
										console.log( '<?= lang( 'Image src: ' ); ?>' + image_src );
										
										var thumb_image_src = 'thumbs/' + image_src;
										
										console.log( '<?= lang( 'Thumb image src: ' ); ?>' + thumb_image_src );
										
										if ( url != '' ){
											
											$thumb_image.attr( 'src', thumb_image_src );
											
											console.log( $thumb_image.attr( 'src' ) )
											
											console.log( '<?= lang( 'The picture should have changed now' ); ?>' )
											
										}
										
										$.fancybox.close();
										
									}
									
								<?php } else if ( $_is_ud_file ) { ?>
									
									window.<?= $_ud_image_js_function_name; ?> = function() {
										
										console.log( 'file choosed' );
										
									}
									
								<?php } ?>
								
							</script>
							
						<?php } ?>
						
						<?php if ( check_var( $field[ 'description' ] ) ) { ?>
						<div class="ud-prop-field-description submit-form-field-description">
							
							<?= $field[ 'description' ]; ?>
							
						</div>
						<?php } ?>
						
					</div>
					
				</div><?php
				
			}
			
			else if ( in_array( $field[ 'field_type' ], array( 'combo_box', 'radiobox', 'checkbox' ) ) ) {
				
				?><div id="<?= $unique_hash; ?>-ud-prop-field-<?= $field_name; ?>" class="<?= $_is_ud_image ? 'ud-image' : ''; ?> ud-prop-field-wrapper ud-prop-type-<?= $field[ 'field_type' ]; ?> vui-field-wrapper vui-field-wrapper-inline <?= ( check_var( $field[ 'form_css_class' ] ) ) ? $field[ 'form_css_class' ] : ''; ?> submit-form-field-wrapper submit-form-field-wrapper-<?= $field_name; ?> submit-form-field-wrapper-<?= $field[ 'field_type' ]; ?> <?= ( $error ) ? 'form-error error' : ''; ?>">
					
					<?php
						
						if ( ( in_array( $field[ 'field_type' ], array( 'radiobox', 'checkbox' ) ) AND ( check_var( $field[ 'options' ] ) OR check_var( $field[ 'options_from_users_submits' ] ) ) ) OR ! ( in_array( $field[ 'field_type' ], array( 'radiobox', 'checkbox' ) ) AND ! ( check_var( $field[ 'options' ] ) OR check_var( $field[ 'options_from_users_submits' ] ) ) ) OR ! in_array( $field[ 'field_type' ], array( 'radiobox', 'checkbox' ) ) ) {
							
							echo form_label( lang( $field[ 'label' ] ) . ( check_var( $field[ 'field_is_required' ] ) ? ' <span class="submit-form-required">*</span> ' . $error : ' ' . $error ) );
							
						}
						
						$options = array();
						
						if ( $field[ 'field_type' ] == 'combo_box' ) {
							
							$options[ '' ] = lang( 'combobox_select' );
							
						}
						
						if ( check_var( $field[ 'options_from_users_submits' ] ) AND ( check_var( $field[ 'options_title_field' ] ) OR check_var( $field[ 'options_title_field_custom' ] ) ) ) {
							
							$filters = NULL;
							
							if ( check_var( $field[ 'options_filter_admin' ] ) ) {
								
								$filters = $field[ 'options_filter_admin' ];
								
							}
							else if ( check_var( $field[ 'options_filter' ] ) ) {
								
								$filters = $field[ 'options_filter' ];
								
							}
							
							$search_config = array(
								
								'plugins' => 'sf_us_search',
								'allow_empty_terms' => TRUE,
								'ipp' => 0,
								'cp' => NULL,
								'plugins_params' => array(
									
									'sf_us_search' => array(
										
										'sf_id' => $field[ 'options_from_users_submits' ],
										'filters' => json_decode( $filters, TRUE ),
										'order_by' => ( isset( $field[ 'options_filter_order_by' ] ) ? $field[ 'options_filter_order_by' ] : $field[ 'options_title_field' ] ),
										'order_by_direction' => ( isset( $field[ 'options_filter_order_by_direction' ] ) ? $field[ 'options_filter_order_by_direction' ] : 'ASC' ),
										
									),
									
								),
								
							);
							
							$CI =& get_instance();
							$CI->load->library( 'search' );
							$CI->search->config( $search_config );
							
							$users_submits = $CI->search->get_full_results( 'sf_us_search', TRUE );
							
							foreach( $users_submits as & $_user_submit ) {
								
								$_user_submit[ 'data' ] = get_params( $_user_submit[ 'data' ] );
								
								if ( $field[ 'options_title_field' ] ) {
									
									foreach( $_user_submit[ 'data' ] as $_dk => $_data ) {
										
										if ( $_dk == $field[ 'options_title_field' ] )
										
										$options[ $_user_submit[ 'id' ] ] = $_data;
										
									};
									
								}
								
							};
							
						}
						else {
							
							if ( check_var( $field[ 'options' ] ) ) {
								
								$options_temp = explode( "\n" , $field[ 'options' ] );
								
								foreach( $options_temp as $option ) {
									
									$options[ $option ] = $option;
									
								};
								
							}
							else {
								
								$options[ 1 ] = $field[ 'label' ];
								
							}
							
						}
						
					?>
					
					<div class="submit-form-field-control">
						
						<?php
							
							if ( $field[ 'field_type' ] == 'radiobox' ) {
								
								foreach( $options as $k => $option ) {
									
									$attr_options = array(
										
										'wrapper-class' => 'radiobox-sub-item',
										'class' => 'form-element submit-form submit-form-' . $field_name,
										'name' => $formatted_field_name,
										'id' => 'submit-form-' . $field_name,
										'value' => $k,
										'checked' => FALSE,
										'text' => $option,
										
									);
									
									if ( $field_value AND is_array( $field_value ) AND in_array( $k, $field_value ) ) {
										
										$attr_options[ 'checked' ] = TRUE;
										
									}
									else if ( $field_value AND is_string( $field_value ) AND $k == $field_value ) {
										
										$attr_options[ 'checked' ] = TRUE;
										
									}
									
									echo vui_el_radiobox( $attr_options );
									
								};
								
							}
							else if ( $field[ 'field_type' ] == 'combo_box' ) {
								
								echo vui_el_dropdown(
									
									array(
										
										'id' => 'submit-form-' . $field_name,
										'title' => isset( $field[ 'description' ] ) ? lang( $field[ 'description' ] ) : NULL,
										'value' => $field_value,
										'name' => $formatted_field_name,
										'options' => $options,
										'class' => 'form-element submit-form submit-form-' . $field_name,
										
									)
									
								);
								
							}
							else if ( $field[ 'field_type' ] == 'checkbox' ) {
								
								$formatted_field_name .= '[]';
								
								//print_r( $field_value );
								
								//print_r( $options );
								
								foreach( $options as $k => $option ) {
									
									$_checked = FALSE;
									
									if ( ( is_array( $field_value ) AND in_array( $k, $field_value ) ) OR $field_value == $k ) {
										
										$_checked = TRUE;
										
									}
									
									$attr_options = array(
										
										'wrapper-class' => 'checkbox-sub-item',
										'class' => 'form-element submit-form submit-form-' . $field_name,
										'name' => $formatted_field_name,
										'id' => 'submit-form-' . $field_name,
										'value' => $k,
										'checked' => $_checked,
										'text' => $option,
										
									);
									
									if ( $field_value AND is_array( $field_value ) AND in_array( $k, $field_value ) ) {
										
										$attr_options[ 'checked' ] = TRUE;
										
									}
									else if ( $field_value AND is_string( $field_value ) AND $k == $field_value ) {
										
										$attr_options[ 'checked' ] = TRUE;
										
									}
									
									echo vui_el_checkbox( $attr_options );
									
								};
								
							}
							
						?>
						
						<?php if ( check_var( $field[ 'description' ] ) ) { ?>
						<div class="ud-prop-field-description submit-form-field-description">
							
							<?= $field[ 'description' ]; ?>
							
						</div>
						<?php } ?>
						
					</div>
					
				</div><?php
				
			}
			
			else if ( $field[ 'field_type' ] == 'date' ) {
				
				$ud_prop_date_unique_hash = md5( uniqid( rand(), true ) );
				
				$this->plugins->load( array( 'types' => array( 'js_time_picker', ) ) );
				
				$error = form_error( $formatted_field_name . '[d]', '<span class="msg-inline-error error">', '</span>' );
				$error .= form_error( $formatted_field_name . '[m]', '<span class="msg-inline-error error">', '</span>' );
				$error .= form_error( $formatted_field_name . '[y]', '<span class="msg-inline-error error">', '</span>' );
				
				$options_d = array(
					
					'' => lang( 'combobox_select_day' ) . ( $field[ 'sf_date_field_day_is_req' ] ? ' *' : '' ),
					
				);
				
				for ( $i = $field[ 'sf_date_field_day_min_value' ]; $i <= $field[ 'sf_date_field_day_max_value' ]; $i++ ){
					
					$options_d[ str_pad( $i, 2, "0", STR_PAD_LEFT ) ] = str_pad( $i, 2, "0", STR_PAD_LEFT );
					
				}
				
				$options_m = array(
					
					'' => lang( 'combobox_select_month' ) . ( $field[ 'sf_date_field_month_is_req' ] ? ' *' : '' ),
					
				);
				
				for ( $i = $field[ 'sf_date_field_month_min_value' ]; $i <= $field[ 'sf_date_field_month_max_value' ]; $i++ ){
					
					$options_m[ str_pad( $i, 2, "0", STR_PAD_LEFT ) ] = str_pad( $i, 2, "0", STR_PAD_LEFT );
					
				}
				
				$options_y = array(
					
					'' => lang( 'combobox_select_year' ) . ( $field[ 'sf_date_field_year_is_req' ] ? ' *' : '' ),
					
				);
				
				for ( $i = $field[ 'sf_date_field_year_min_value' ]; $i <= $field[ 'sf_date_field_year_max_value' ]; $i++ ){
					
					$options_y[ str_pad( $i, 2, "0", STR_PAD_LEFT ) ] = str_pad( $i, 2, "0", STR_PAD_LEFT );
					
				}
				
				$field_name = isset( $field[ 'alias' ] ) ? $field[ 'alias' ] : $this->sfcm->make_field_name( $field[ 'label' ] );
				$formatted_field_name = 'form[' . $field_name . ']';
				
				$use_day = check_var( $field[ 'sf_date_field_use_day' ] );
				$use_month = check_var( $field[ 'sf_date_field_use_month' ] );
				$use_year = check_var( $field[ 'sf_date_field_use_year' ] );
				
				if ( isset( $post[ 'form' ][ $field_name ] ) ) {
					
					if ( is_array( $post[ 'form' ][ $field_name ] ) ) {
						
						$field_value_y = ( isset( $post[ 'form' ][ $field_name ][ 'y' ] ) ) ? $post[ 'form' ][ $field_name ][ 'y' ] : '';
						$field_value_m = ( isset( $post[ 'form' ][ $field_name ][ 'm' ] ) ) ? $post[ 'form' ][ $field_name ][ 'm' ] : '';
						$field_value_d = ( isset( $post[ 'form' ][ $field_name ][ 'd' ] ) ) ? $post[ 'form' ][ $field_name ][ 'd' ] : '';
						
					}
					else {
						
						$___date = explode( '-', $post[ 'form' ][ $field_name ] );
						
						$field_value_y = ( isset( $___date[ 0 ] ) AND ( int ) $___date[ 0 ] > 0 ) ? 'y' : '';
						$field_value_m = ( isset( $___date[ 1 ] ) AND ( int ) $___date[ 1 ] > 0 ) ? 'm' : '';
						$field_value_d = ( isset( $___date[ 2 ] ) AND ( int ) $___date[ 2 ] > 0 ) ? 'd' : '';
						
						$___date = NULL;
						unset( $___date );
						
					}
					
				}
				else if ( $field_value AND $use_day AND $use_month AND $use_year AND $date = DateTime::createFromFormat( 'Y-m-d', $field_value ) AND $date->format( 'Y-m-d' ) == $field_value ) {
					
					$field_value_y = $date->format( 'Y' );
					$field_value_m = $date->format( 'm' );
					$field_value_d = $date->format( 'd' );
					
					$date = NULL;
					unset( $date );
					
				}
				else if ( $field_value AND ( ! $use_day OR ! $use_month OR ! $use_year ) ) {
					
					$date = explode( '-', $field_value );
					
					$field_value_y = $date[ 0 ];
					$field_value_m = $date[ 1 ];
					$field_value_d = $date[ 2 ];
					
					$date = NULL;
					unset( $date );
					
				}
				else {
					
					$field_value_d = ( isset( $field[ 'sf_date_field_day_def_value' ] ) ? $field[ 'sf_date_field_day_def_value' ] : '' );
					$field_value_m = ( isset( $field[ 'sf_date_field_month_def_value' ] ) ? $field[ 'sf_date_field_month_def_value' ] : '' );
					$field_value_y = ( isset( $field[ 'sf_date_field_year_def_value' ] ) ? $field[ 'sf_date_field_year_def_value' ] : '' );
					
				}
				
				?><div id="<?= $unique_hash; ?>-ud-prop-field-<?= $field_name; ?>" class="<?= $ud_prop_date_unique_hash; ?> <?= $_is_ud_image ? 'ud-image' : ''; ?> ud-prop-field-wrapper ud-prop-type-<?= $field[ 'field_type' ]; ?> ud-prop-type-<?= $field[ 'field_type' ]; ?>-<?php if ( $use_day ) echo 'd'; ?><?php if ( $use_month ) echo 'm'; ?><?php if ( $use_year ) echo 'y'; ?> vui-field-wrapper vui-field-wrapper-inline <?= ( check_var( $field[ 'form_css_class' ] ) ) ? $field[ 'form_css_class' ] : ''; ?> submit-form-field-wrapper submit-form-field-wrapper-<?= $field_name; ?> submit-form-field-wrapper-<?= $field[ 'field_type' ]; ?> <?= ( $error ) ? 'form-error error' : ''; ?>">
					
					<?= form_label( lang( $field[ 'label' ] ) . $error ); ?>
					
					<?php if ( check_var( $field[ 'description' ] ) ) { ?>
					<div class="ud-prop-field-description submit-form-field-description">
						
						<?= $field[ 'description' ]; ?>
						
					</div>
					<?php }
					
					?><div class="submit-form-field-control submit-form-field-control-date">
						
						<?php
							
							if ( $use_day ) {
								
								echo vui_el_dropdown(
									
									array(
										
										'id' => 'submit-form-' . $field_name . '-d',
										'value' => $field_value_d,
										'name' => $formatted_field_name . '[d]',
										'options' => $options_d,
										'class' => 'form-element submit-form submit-form-' . $field_name . '-d',
										
									)
									
								);
								
							}
							
							if ( $use_month ) {
								
								echo vui_el_dropdown(
									
									array(
										
										'id' => 'submit-form-' . $field_name . '-m',
										'value' => $field_value_m,
										'name' => $formatted_field_name . '[m]',
										'options' => $options_m,
										'class' => 'form-element submit-form submit-form-' . $field_name . '-m',
										
									)
									
								);
								
							}
							
							if ( $use_year ) {
								
								echo vui_el_dropdown(
									
									array(
										
										'id' => 'submit-form-' . $field_name . '-y',
										'value' => $field_value_y,
										'name' => $formatted_field_name . '[y]',
										'options' => $options_y,
										'class' => 'form-element submit-form submit-form-' . $field_name . '-y',
										
									)
									
								);
								
							}
							
							if ( $use_day AND $use_month AND $use_year AND $this->plugins->performed( 'jquery_ui' ) ) { ?>
							
							<script type="text/javascript">
								
								window.update_jqui_date_field_<?= $ud_prop_date_unique_hash; ?> = function() {
									
									var dateText = $( '[name="<?= $formatted_field_name . '[y]'; ?>"]' ).val() + '-' + $( '[name="<?= $formatted_field_name . '[m]'; ?>"]' ).val() + '-' + $( '[name="<?= $formatted_field_name . '[d]'; ?>"]' ).val();
									
									$( '#<?= 'ud-prop-date-' . $ud_prop_date_unique_hash; ?>' ).attr( 'data-jqui-dp-initial-date', dateText );
									$( '#<?= 'ud-prop-date-' . $ud_prop_date_unique_hash; ?>' ).data( 'jqui-dp-initial-date', dateText );
									
								}
								
								window.update_date_fields_<?= $ud_prop_date_unique_hash; ?> = function( dateText, dp, el ) {
									
									console.log( new Date( dateText ) );
									console.log( '<?= lang( 'console_logs_ud_prop_date_selected' ); ?>: ' + dateText );
									
									var date_arr = dateText.split( "-" );
									
									$( '#<?= 'ud-prop-date-' . $ud_prop_date_unique_hash; ?>' ).attr( 'data-jqui-dp-initial-date', dateText );
									$( '#<?= 'ud-prop-date-' . $ud_prop_date_unique_hash; ?>' ).data( 'jqui-dp-initial-date', dateText );
									
									var day  = date_arr[ 2 ] ? date_arr[ 2 ] : '',  
										month = date_arr[ 1 ] ? date_arr[ 1 ] : '',             
										year =  date_arr[ 0 ] ? date_arr[ 0 ] : '';
										
									
									console.log( day );
									
									$( '[name="<?= $formatted_field_name . '[d]'; ?>"] option:selected' ).removeAttr( 'selected' );
									$( '[name="<?= $formatted_field_name . '[d]'; ?>"] [value=' + day + ']' ).attr( 'selected', true );
									
									$( '[name="<?= $formatted_field_name . '[m]'; ?>"] option:selected' ).removeAttr( 'selected' );
									$( '[name="<?= $formatted_field_name . '[m]'; ?>"] [value=' + month + ']' ).attr( 'selected', true );
									
									$( '[name="<?= $formatted_field_name . '[y]'; ?>"] option:selected' ).removeAttr( 'selected' );
									$( '[name="<?= $formatted_field_name . '[y]'; ?>"] [value=' + year + ']' ).attr( 'selected', true );
									
									$( '[name="<?= $formatted_field_name . '[d]'; ?>"]' ).val( day ).change();
									$( '[name="<?= $formatted_field_name . '[m]'; ?>"]' ).val( month ).change();
									$( '[name="<?= $formatted_field_name . '[y]'; ?>"]' ).val( year ).change();
									
								}
								
								$( document ).bind( 'ready', function(){
									
									$( '.<?= $ud_prop_date_unique_hash; ?>' ).append( '<?php
										
										echo str_replace(
											
											"'", "\'", vui_el_button(
												
												array(
													
													'button_type' => 'anchor',
													'text' => lang( 'select_date' ),
													'icon' => 'calendar',
													'only_icon' => TRUE,
													'wrapper_class' => 'vui-picker vui-date-selector-wrapper',
													'class' => 'date',
													'id' => 'ud-prop-date-' . $ud_prop_date_unique_hash,
													'attr' => array(
														
														'data-jqui-dp-on-select-callback-function' => 'update_date_fields_' . $ud_prop_date_unique_hash,
														'data-jqui-dp-dialog' => '1',
														'data-jqui-dp-initial-date' => $field_value,
														'data-max-date' => str_pad( $field[ 'sf_date_field_year_max_value' ], 2, "0", STR_PAD_LEFT ) . '-' . str_pad( $field[ 'sf_date_field_month_max_value' ], 2, "0", STR_PAD_LEFT ) . '-' . str_pad( $field[ 'sf_date_field_day_max_value' ], 2, "0", STR_PAD_LEFT ),
														'data-min-date' => str_pad( $field[ 'sf_date_field_year_min_value' ], 2, "0", STR_PAD_LEFT ) . '-' . str_pad( $field[ 'sf_date_field_month_min_value' ], 2, "0", STR_PAD_LEFT ) . '-' . str_pad( $field[ 'sf_date_field_day_min_value' ], 2, "0", STR_PAD_LEFT ),
														
													)
														
												)
												
											)
											
										);
										
									?>' );
									
									$( document ).on( 'change', '.<?= $ud_prop_date_unique_hash; ?>', function( e ){
										
										update_jqui_date_field_<?= $ud_prop_date_unique_hash; ?>();
										
									});
									
								});
								
							</script>
							
						<?php } ?>
						
					</div><?php
					
				?></div><?php
				
			}
			
			else if ( $field[ 'field_type' ] == 'articles' ) {
				
				?><div id="<?= $unique_hash; ?>-ud-prop-field-<?= $field_name; ?>" class="<?= $_is_ud_image ? 'ud-image' : ''; ?> ud-prop-field-wrapper ud-prop-type-<?= $field[ 'field_type' ]; ?> vui-field-wrapper vui-field-wrapper-inline <?= ( check_var( $field[ 'form_css_class' ] ) ) ? $field[ 'form_css_class' ] : ''; ?> submit-form-field-wrapper submit-form-field-wrapper-<?= $field_name; ?> submit-form-field-wrapper-<?= $field[ 'field_type' ]; ?> <?= ( $error ) ? 'form-error error' : ''; ?>">
					
					<?= form_label( lang( $field[ 'label' ] ) . ( check_var( $field[ 'field_is_required' ] ) ? ' <span class="submit-form-required">*</span> ' . $error : '' ) ); ?>
					
					<?php
						
						$options = array(
							
							'' => lang( 'combobox_select' ),
							
						);
						
						foreach ( $field[ 'articles' ] as $article_key => $article ) {
							
							$options[ $article[ 'category_title' ] ][ $article[ 'title' ] ] = $article[ 'title' ];
							
						}
						
					?>
					
					<div class="submit-form-field-control">
						
						<?php
							
							echo vui_el_dropdown(
								
								array(
									
									'id' => 'submit-form-' . $field_name,
									'value' => $field_value,
									'name' => $formatted_field_name,
									'options' => $options,
									'class' => 'form-element submit-form submit-form-' . $field_name,
									
								)
								
							);
							
						?>
						
						<?php if ( check_var( $field[ 'description' ] ) ) { ?>
						<div class="ud-prop-field-description submit-form-field-description">
							
							<?= $field[ 'description' ]; ?>
							
						</div>
						<?php } ?>
						
					</div>
					
				</div><?php
				
			}
			
			else if ( $field[ 'field_type' ] == 'textarea' ) {
				
				?><div id="<?= $unique_hash; ?>-ud-prop-field-<?= $field_name; ?>" class="<?= $_is_ud_image ? 'ud-image' : ''; ?> ud-prop-field-wrapper ud-prop-type-<?= $field[ 'field_type' ]; ?> vui-field-wrapper <?= ( check_var( $field[ 'form_css_class' ] ) ) ? $field[ 'form_css_class' ] : ''; ?> submit-form-field-wrapper submit-form-field-wrapper-<?= $field_name; ?> submit-form-field-wrapper-<?= $field[ 'field_type' ]; ?> <?= ( $error ) ? 'form-error error' : ''; ?>">
					
					<?= form_label( lang( $field[ 'label' ] ) . ( check_var( $field[ 'field_is_required' ] ) ? ' <span class="submit-form-required">*</span> ' . $error : '' ) ); ?>
					
					<div class="submit-form-field-control">
						
						<?php
							
							$editor = FALSE;
							
							if ( ( check_var( $submit_form[ 'params' ][ 'submit_forms_allow_html_tags' ] ) AND check_var( $field[ 'sf_field_type_textarea_us_use_editor' ][ 'admin' ] ) ) OR ( check_var( $field[ 'sf_field_type_textarea_allow_html' ] ) AND check_var( $field[ 'sf_field_type_textarea_us_use_editor' ][ 'admin' ] ) ) ){
								
								$editor = TRUE;
								
								$this->plugins->load( NULL, 'js_text_editor' );
								
							}
							
							echo vui_el_textarea(
								
								array(
									
									'text' => lang( $field[ 'presentation_label' ] ),
									'id' => 'submit-form-' . $field_name,
									'value' => $field_value,
									'name' => $formatted_field_name,
									'class' => 'form-element submit-form submit-form-' . $field_name . ( $editor ? ' js-editor' : '' ),
									
								)
								
							);
							
						?>
						
						<?php if ( check_var( $field[ 'description' ] ) ) { ?>
						<div class="ud-prop-field-description submit-form-field-description">
							
							<?= $field[ 'description' ]; ?>
							
						</div>
						<?php } ?>
						
					</div>
					
				</div><?php
				
			}
			
			else if ( $field[ 'field_type' ] == 'button' ) {
				
				?><div id="<?= $unique_hash; ?>-ud-prop-field-<?= $field_name; ?>" class="<?= $_is_ud_image ? 'ud-image' : ''; ?> ud-prop-field-wrapper ud-prop-type-<?= $field[ 'field_type' ]; ?> vui-field-wrapper vui-field-wrapper-inline submit-form-field-wrapper submit-form-field-wrapper-<?= $field_name; ?> submit-form-field-wrapper-<?= $field[ 'field_type' ]; ?>">
				
				<div class="submit-form-field-control">
					
					<?php
						
						echo vui_el_button(
							
							array(
								
								'text' => lang( $field[ 'label' ] ),
								'id' => 'submit-form-' . $field_name,
								'value' => lang( $field[ 'label' ] ),
								'name' => $formatted_field_name,
								'class' => 'form-element submit-form submit-form-' . $field_name,
								
							)
							
						);
						
					?>
					
					<?php if ( check_var( $field[ 'description' ] ) ) { ?>
						
						<div class="ud-prop-field-description submit-form-field-description">
							
							<?= $field[ 'description' ]; ?>
							
						</div>
						
					<?php } ?>
					
				</div>
				
			</div><?php
			
			}
			
			if ( check_var( $field[ 'validation_rule' ] ) AND in_array( 'mask', $field[ 'validation_rule' ] ) ) {
				
				$this->plugins->load( 'jquery_maskedinput' );
				
				if ( check_var( $field[ 'ud_validation_rule_parameter_mask_type' ] ) ) {
					
					echo '<script type="text/javascript">';
					
					if ( $field[ 'ud_validation_rule_parameter_mask_type' ] == 'custom_mask' AND check_var( $field[ 'ud_validation_rule_parameter_mask_custom_mask' ] ) ) {
						
						?>
							
							$( document ).bind( 'ready', function(){
								
								$( '#submit-form-<?= $field_name; ?>' ).mask( '<?= $field[ 'ud_validation_rule_parameter_mask_custom_mask' ]; ?>' );
								
							});
							
						<?php
						
					}
					else if ( $field[ 'ud_validation_rule_parameter_mask_type' ] == 'zip_brazil' ) {
						
						?>
							
							$( document ).bind( 'ready', function(){
								
								$( '#submit-form-<?= $field_name; ?>' ).mask( '00000-000' );
								
							});
							
						<?php
						
					}
					else if ( $field[ 'ud_validation_rule_parameter_mask_type' ] == 'phone_brazil' ) {
						
						?>
							
							$( document ).bind( 'ready', function(){
								
								var $pb_mask_behavior = function (val) {
									
									return val.replace(/\D/g, '').length === 11 ? '(00) 00000-0000' : '(00) 0000-00009';
									
								},
								$pb_options = {
									
									onKeyPress: function( val, e, field, options ) {
										
										field.mask( $pb_mask_behavior.apply( {}, arguments ), options );
										
									}
									
								};
								
								$( '#submit-form-<?= $field_name; ?>' ).mask( $pb_mask_behavior, $pb_options );
								
							});
							
						<?php
						
					}
					else if ( $field[ 'ud_validation_rule_parameter_mask_type' ] == 'money' ) {
						
						?>
							
							$( document ).bind( 'ready', function(){
								
								$( '#submit-form-<?= $field_name; ?>' ).mask( '#.##0,00', { reverse: true } );
								
							});
							
						<?php
						
					}
					
					echo '</script>';
					
				}
				
			}
			
			if ( check_var( $field[ 'conditional_field' ] ) ) {
				
				$target_field_name = $this->sfcm->make_field_name( $field[ 'conditional_target_field' ] );
				$target_formatted_field_name = 'form[' . $target_field_name . ']';
				$target_field_id = $unique_hash . '-ud-prop-field-' . $field_name;
				$show_function_name = 'show_prop_field_' . $unique_hash . '_' . $field[ 'key' ];
				
				$field_name_js = '$targetFieldName_' . $field[ 'key' ];
				
				if ( check_var( $field[ 'conditional_field_function' ] ) AND $field[ 'conditional_field_function' ] === 'show' ) {
					
					?><script type="text/javascript">
						
						function <?= $show_function_name; ?>(){
							
							<?php if ( $field[ 'conditional_field_cond' ] === 'equal' ) { ?>
								
								<?php if ( in_array( $fields[ $field[ 'conditional_target_field' ] ][ 'field_type' ], array( 'checkbox', ) ) ) { ?>
									
									if ( $( '[name="<?= $target_formatted_field_name; ?>[]"][value="<?= $field[ 'conditional_field_values' ]; ?>"]:checked').length ) {
									
								<?php } else if ( in_array( $fields[ $field[ 'conditional_target_field' ] ][ 'field_type' ], array( 'radiobox', ) ) ) { ?>
									
									if ( $( '[name="<?= $target_formatted_field_name; ?>"][value="<?= $field[ 'conditional_field_values' ]; ?>"]:checked').length ) {
									
								<?php } else { ?>
									
									if ( $( '[name="<?= $target_formatted_field_name; ?>"]' ).val() === '<?= $field[ 'conditional_field_values' ]; ?>' ) {
									
								<?php } ?>
								
							<?php } else if ( $field[ 'conditional_field_cond' ] === 'different' AND ( ( isset( $field[ 'conditional_field_values' ] ) AND $field[ 'conditional_field_values' ] === '' ) OR ! check_var( $field[ 'conditional_field_values' ] ) ) ) { ?>
								
								<?php if ( in_array( $fields[ $field[ 'conditional_target_field' ] ][ 'field_type' ], array( 'checkbox', ) ) ) { ?>
									
									if ( $( '[name="<?= $target_formatted_field_name; ?>[]"]:checked').length ) {
									
								<?php } else if ( in_array( $fields[ $field[ 'conditional_target_field' ] ][ 'field_type' ], array( 'radiobox', ) ) ) { ?>
									
									if ( $( '[name="<?= $target_formatted_field_name; ?>"]:checked').length ) {
									
								<?php } else { ?>
									
									if ( $.trim( $( '[name="<?= $target_formatted_field_name; ?>"]' ).val() ).length > 0 ) {
									
								<?php } ?>
								
							<?php } else if ( $field[ 'conditional_field_cond' ] === 'different' ) { ?>
								
								<?php if ( in_array( $fields[ $field[ 'conditional_target_field' ] ][ 'field_type' ], array( 'checkbox', ) ) ) { ?>
									
									if ( $( '[name="<?= $target_formatted_field_name; ?>[]"][value="<?= $field[ 'conditional_field_values' ]; ?>"]:checked').length == 0 ) {
									
								<?php } else if ( in_array( $fields[ $field[ 'conditional_target_field' ] ][ 'field_type' ], array( 'radiobox', ) ) ) { ?>
									
									if ( $( '[name="<?= $target_formatted_field_name; ?>"][value="<?= $field[ 'conditional_field_values' ]; ?>"]:checked').length == 0 ) {
									
								<?php } else { ?>
									
									if ( $( '[name="<?= $target_formatted_field_name; ?>"]' ).val() !== '<?= $field[ 'conditional_field_values' ]; ?>' ) {
									
								<?php } ?>
								
							<?php } ?>
								
								$( '#<?= $target_field_id; ?>' ).show();
								$( '#<?= $target_field_id; ?>' ).find( '[name="no_validation_fields[<?= $field_name; ?>]"]' ).remove();
								
							}
							else {
								
								$( '#<?= $target_field_id; ?>' ).hide();
								
								<?php if ( in_array( $fields[ $field[ 'conditional_target_field' ] ][ 'field_type' ], array( 'checkbox', 'radiobox', ) ) ) { ?>
									
									$( '#<?= $target_field_id; ?> .form-element' ).attr( 'checked', '' );
									
								<?php } else if ( $fields[ $field[ 'conditional_target_field' ] ][ 'field_type' ] != 'button' ) { ?>
									
									$( '#<?= $target_field_id; ?> .form-element' ).val( '' );
									
								<?php } ?>
								
								$( '#<?= $target_field_id; ?> .form-element' ).change();
								
								if ( ! $( '[name="fields_ignore_validation[<?= $field_name; ?>]"]' ).length ) {
									
									$( '#<?= $target_field_id; ?>' ).append( '<input type="hidden" name="no_validation_fields[<?= $field_name; ?>]" value="<?= $field_name; ?>" />' );
									
								}
								
							}
							
						};
						
						$( document ).bind( 'ready', function(){
							
							<?php if ( in_array( $fields[ $field[ 'conditional_target_field' ] ][ 'field_type' ], array( 'checkbox', ) ) ) { ?>
								
								$( '[name="<?= $target_formatted_field_name; ?>[]"]' ).bind( 'change keyup', function(){
									
									<?= $show_function_name; ?>();
									
								});
								
							<?php } else { ?>
								
								$( '[name="<?= $target_formatted_field_name; ?>"]' ).bind( 'change keyup', function(){
									
									<?= $show_function_name; ?>();
									
								});
								
							<?php } ?>
							
							<?= $show_function_name; ?>();
							
						});
						
					</script><?php
					
				}
				
			}
			
		}
		
	} ?>
	
	<?= form_hidden( 'submit_form_id', @$submit_form[ 'id' ] ); ?>
	
<?= form_close(); ?>
