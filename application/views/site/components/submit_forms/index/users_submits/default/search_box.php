<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
	
	$_show_terms_field = ( check_var( $params[ 'ud_data_availability_site_search' ] ) AND in_array( '__terms', $params[ 'ud_data_availability_site_search' ] ) ) ? TRUE : FALSE;
	
	
	
	$search_filters = url_segment_to_assoc_array( ltrim( $this->uri->ruri_string(), '/' ) );
	
	$search_filter_url = url_segment_to_assoc_array( $data_scheme[ 'data_list_site_link' ] );
	
	$search_filter_url[ 'sfsp' ] = check_var( $search_filter_url[ 'sfsp' ] ) ? $this->unid->url_decode_ud_filters( $search_filter_url[ 'sfsp' ] ) : array();
	
	$search_filters = check_var( $search_filters[ 'sfsp' ] ) ? $this->unid->url_decode_ud_filters( $search_filters[ 'sfsp' ] ) : array();
	
	$search_filters = check_var( $this->input->get( 'sfsp' ) ) ? array_merge_recursive_distinct( $search_filters, $this->unid->url_decode_ud_filters( $this->input->get( 'sfsp' ) ) ) : $search_filters;
	
	$search_filters = check_var( $post[ 'users_submits_search' ] ) ? array_merge_recursive_distinct( $search_filters, $post[ 'users_submits_search' ] ) : $search_filters;
	
//  						echo '<pre>' . print_r( $post[ 'users_submits_search' ], TRUE ) . '</pre>';
						
//  						echo '<pre>' . print_r( $search_filters, TRUE ) . '</pre>';
						
?><div id="users-submits-search-wrapper" class="ud-data-list-search-box-<?= $unique_hash; ?> ud-data-list-search-box ud-search-box users-submits-search-wrapper <?= check_var( $search_filters ) ? 'ud-search-mode-on' : 'ud-search-mode-off'; ?>">
	
	<?php
		
		if ( check_var( $params[ 'us_search_pre_text' ] ) AND $pre_text_pos == 'before_search_fields' ) {
			
			if ( check_var( $search_mode ) AND check_var( $params[ 'us_show_search_pre_text_on_search' ] ) ) {
				
				echo $params[ 'us_search_pre_text' ];
				
			}
			else if ( ! check_var( $search_mode ) AND check_var( $params[ 'us_show_search_pre_text_on_normal' ] ) ) {
				
				echo $params[ 'us_search_pre_text' ];
				
			}
			
		}
		
	?>
	<div >
  
  </form>
</div>
	<?= form_open_multipart( /*get_url( $this->uri->ruri_string() )*/ current_url()  . '#content-block', array( 'id' => 'ud-d-search-form-' . $unique_hash ) );
		
		$combo_box_fields_to_search = array();
		
		?>
		
		<script type="application/ld+json">
		{
			"@context": "http://schema.org",
			"@type": "WebSite",
			"url": "<?= BASE_URL; ?>",
			"potentialAction": {
				"@type": "SearchAction",
				"target": "<?= current_url() . '?q={users_submits_search_terms}'; ?>",
				"query-input": "required name=users_submits_search_terms"
			}
		}
		</script>
		
		<?php if ( $_show_terms_field ) { ?>
			
			<div class="ud-d-form-field-wrapper ud-d-form-field-wrapper-terms ud-d-form-field-wrapper-terms submit-form-field-wrapper submit-form-field-wrapper-terms submit-form-field-wrapper-terms">
				
				<?php
					
					echo form_label( check_var( $params[ 'search_terms_string' ] ) ? lang( $params[ 'search_terms_string' ] ) : lang( 'search_terms' ) );
					
				?>
				
				<div class="submit-form-field-control"><?php
					
					echo vui_el_input_text(
						
						array(
							
							'id' => 'submit-form-terms',
							'name' => 'users_submits_search_terms',
							'value' => $terms,
							'text' => check_var( $params[ 'search_terms_string' ] ) ? lang( $params[ 'search_terms_string' ] ) : lang( 'search_terms' ),
// 							'title' => lang( 'search_terms' ),
							'icon' => 'search',
							'form' => 'ud-d-search-form-' . $unique_hash,
							
						)
						
					);
					
				?></div>
				
			</div><?php
			
		}
		
		$props_options = array(
			
			'id' => lang( 'id' ),
			'submit_datetime' => lang( 'submit_datetime' ),
			'mod_datetime' => lang( 'mod_datetime' ),
			
		);
		
		foreach ( $props as $key_2 => $field ) {
			
			$field_name = url_title( $field[ 'alias' ], '-', TRUE );
			$formatted_field_name = 'form[' . $field_name . ']';
			$field_value = ( isset( $search_filters[ 'dinamic_filter_fields' ][ $field_name ] ) ) ? $search_filters[ 'dinamic_filter_fields' ][ $field_name ] : '';
			
			if ( ! in_array( $field[ 'field_type' ], array( 'button', 'html' ) ) ) {
				
				$props_options[ $field[ 'alias' ] ] = $field[ 'label' ];
				
			}
			
			//print_r( $params[ 'ud_data_availability_site_search' ] ); exit;
			
// 			echo '<pre>' . print_r( $params[ 'ud_data_availability_site_search' ], TRUE ) . '</pre>';
			
			if ( $field[ 'field_type' ] == 'combo_box' AND check_var( $params[ 'ud_data_availability_site_search' ][ $field[ 'alias' ] ] ) ) {
				
				$options = array();
				
				$combo_box_fields_to_search[ $field[ 'alias' ] ] = $field;
				
				if ( check_var( $field[ 'options_from_users_submits' ] ) AND ( check_var( $field[ 'options_title_field' ] ) OR check_var( $field[ 'options_title_field_custom' ] ) ) ) {
					
					$filters = NULL;
					
					if ( check_var( $field[ 'options_filter_site' ] ) ) {
						
						$filters = $field[ 'options_filter_site' ];
						
					}
					else if ( check_var( $field[ 'options_filter' ] ) ) {
						
						$filters = $field[ 'options_filter' ];
						
					}
					
					$search_config = array(
						
						'plugins' => 'sf_us_search',
						'allow_empty_terms' => TRUE,
						'ignore_terms' => TRUE,
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
					
					$this->load->library( 'search' );
					$this->search->reset_config();
					$this->search->config( $search_config );
					
					$_users_submits = $this->search->get_full_results( 'sf_us_search', TRUE );
					
// 					echo '<pre>' . print_r( $_users_submits, TRUE ) . '</pre><br/>'; 
					
					foreach( $_users_submits as & $_user_submit ) {
						
						$_user_submit[ 'data' ] = get_params( $_user_submit[ 'data' ] );
						
						if ( $field[ 'options_title_field' ] )
						
						foreach( $_user_submit[ 'data' ] as $_dk => $_data ) {
							
							if ( $_dk == $field[ 'options_title_field' ] )
							
							$options[ $_user_submit[ 'id' ] ] = $_data;
							
						};
						
					};
					
				}
				else {
					
					$options_temp = explode( "\n" , $field[ 'options' ] );
					
					foreach( $options_temp as $option ) {
						
						$options[ $option ] = $option;
						
					};
					
				}
				
				$default_options = array( '' => lang( 'combobox_select' ) );
				
//  						echo '<pre>' . print_r( array_merge_recursive_distinct( $default_options, $options ), TRUE ) . '</pre>';
						
			?><div class="adv-search ud-d-form-field-wrapper ud-d-form-field-wrapper ud-d-form-field-wrapper-<?= $field[ 'alias' ]; ?> ud-d-form-field-wrapper-<?= $field[ 'field_type' ]; ?> submit-form-field-wrapper submit-form-field-wrapper-<?= $field[ 'alias' ]; ?> submit-form-field-wrapper-<?= $field[ 'field_type' ]; ?> <?= check_var( $search_filters[ 'dinamic_filter_fields' ][ $field[ 'alias' ] ] ) ? 'adv-search-visible' : ''; ?>">
				
				<?= form_label( lang( isset( $field[ 'presentation_label' ] ) ? $field[ 'presentation_label' ] : $field[ 'label' ] ), NULL, 
					
					array(
						
						'title' => lang( $field[ 'presentation_label' ] ),
						
					)
					
				); ?>
				
				<?php
					
					if ( check_var( $params[ 'ud_d_list_search_options_list_style' ][ $field[ 'alias' ] ] ) AND $params[ 'ud_d_list_search_options_list_style' ][ $field[ 'alias' ] ] == 'list' ) {
						
						echo '<ul class="menu">';
						
						foreach( $options as $k => $option ) {
							
							echo '<li class="menu-item ';
							
							if (
								
								check_var( $search_filters[ 'dinamic_filter_fields' ][ $field[ 'alias' ] ] ) AND $search_filters[ 'dinamic_filter_fields' ][ $field[ 'alias' ] ] == $k
								
							) {
								
								echo 'current';
								
							}
							
							echo '">';
							
							$update = TRUE;
							
							$link_href = $search_filter_url;
							
							$link_href[ 'sfsp' ] = $search_filters;
							
//  						echo '<pre>' . print_r( $search_filter_url, TRUE ) . '</pre>';
						
							if (
								
								check_var( $search_filter_url[ 'sfsp' ][ 'dinamic_filter_fields' ][ $field[ 'alias' ] ] ) AND $search_filter_url[ 'sfsp' ][ 'dinamic_filter_fields' ][ $field[ 'alias' ] ] == $k
								
							) {
								
								$update = FALSE;
								
							}
							
							if ( $update ) {
								
								$link_href[ 'sfsp' ][ 'dinamic_filter_fields' ][ $field[ 'alias' ] ] = $k;
								
							}
							
							$link_href[ 'sfsp' ] = $this->unid->url_encode_ud_filters( $link_href[ 'sfsp' ] );
							
							echo '<a class="menu-item-link" href="' . get_url( $this->uri->assoc_to_uri( $link_href ) ) . '">';
							echo '<span class="menu-item-content">';
							echo $option;
							echo '</span>';
							echo '</a>';
							
							echo '</li>';
							
						};
						
						echo '</ul>';
						
					}
					else {
					
				?>
				
				<div class="submit-form-field-control">
					
					<?= vui_el_dropdown(
						
						array(
							
							'id' => 'submit-form-' . $field[ 'alias' ],
							'title' => isset( $field[ 'description' ] ) ? lang( $field[ 'description' ] ) : NULL,
							'value' => $field_value,
							'name' => $filter_fields_input_name . '[' . $field[ 'alias' ] . ']',
							'options' => array_merge_recursive_distinct( $default_options, $options ),
							'class' => 'form-element submit-form submit-form-' . $field[ 'alias' ],
							'form' => 'ud-d-search-form-' . $unique_hash,
							
						)
						
					); ?>
					
				</div>
				
				<?php } ?>
				
			</div><?php
			
			}
			
		}
		
		if ( check_var( $params[ 'ud_d_list_show_order_by_controls' ] ) ) {
		
		?><div class="adv-search ud-d-form-field-wrapper ud-d-form-field-wrapper ud-d-form-field-wrapper-order-by submit-form-field-wrapper submit-form-field-wrapper-order-by <?= check_var( $search_filters[ 'order_by' ] ) ? 'adv-search-visible' : ''; ?>">
			
			<?= form_label( lang( 'order_by' ) ); ?>
			
			<div class="submit-form-field-control">
				
				<?php
					
					$ud_d_list_order_by_options = array(
						
						'' => lang( 'combobox_select' ),
						'random' => lang( 'random' ),
						
					);
					
					$ud_d_list_order_by_options = array_merge( $ud_d_list_order_by_options, $props_options );
					
					if ( check_var( $params[ 'ud_d_list_order_by_visible_props' ] ) ) {
						
						reset( $ud_d_list_order_by_options );
						
						while ( list( $k, $v ) = each( $ud_d_list_order_by_options ) ) {
							
							if ( ! check_var( $params[ 'ud_d_list_order_by_visible_props' ][ $k ] ) ) {
								
								$ud_d_list_order_by_options[ $k ] = NULL;
								unset( $ud_d_list_order_by_options[ $k ] );
								
							}
							
						}
						
					}
					
					$ud_d_list_order_by_options = array_merge( array( '' => lang( 'combobox_select' ) ), $ud_d_list_order_by_options );
					
// 					echo '<pre>' . print_r( $ud_d_list_order_by_options, TRUE ) . '</pre>';
					
				?>
				
				<?= vui_el_dropdown(
					
					array(
						
						'id' => 'submit-form-' . $field[ 'alias' ],
						'title' => isset( $field[ 'description' ] ) ? lang( $field[ 'description' ] ) : NULL,
						'value' => $order_by,
						'name' => 'users_submits_search[order_by]',
						'options' => $ud_d_list_order_by_options,
						'class' => 'form-element submit-form submit-form-' . $field[ 'alias' ],
						'form' => 'ud-d-search-form-' . $unique_hash,
						
					)
					
				); ?>
				
			</div>
			
		</div><?php
		
		?><div class="adv-search ud-d-form-field-wrapper ud-d-form-field-wrapper ud-d-form-field-wrapper-order-by-direction submit-form-field-wrapper submit-form-field-wrapper-order-by-direction <?= check_var( $search_filters[ 'order_by_direction' ] ) ? 'adv-search-visible' : ''; ?>">
			
			<?= form_label( lang( 'order_by_direction' ) ); ?>
			
			<div class="submit-form-field-control">
				
				<?= vui_el_dropdown(
					
					array(
						
						'id' => 'submit-form-' . $field[ 'alias' ],
						'title' => isset( $field[ 'description' ] ) ? lang( $field[ 'description' ] ) : NULL,
						'value' => $order_by_direction,
						'name' => 'users_submits_search[order_by_direction]',
						'options' => array(
							
							'' => lang( 'combobox_select' ),
							'asc' => lang( 'ordering_asc' ),
							'desc' => lang( 'ordering_desc' ),
							'random' => lang( 'ordering_random' ),
							
						),
						'class' => 'form-element submit-form submit-form-' . $field[ 'alias' ],
						'form' => 'ud-d-search-form-' . $unique_hash,
						
					)
					
				); ?>
				
			</div>
			
		</div><?php
		
		}
		
		?><div class="ud-d-form-field-wrapper ud-d-form-field-wrapper-submit_button ud-d-form-field-wrapper-button submit-form-field-wrapper ud-search-box-search-button-wrapper submit-form-field-wrapper-submit_search submit-form-field-wrapper-button">
			
			<div class="submit-form-field-control">
				
				<?= vui_el_button(
					
					array(
						
						'id' => 'submit-form-submit_search',
						'button_type' => 'button',
						'text' => lang( 'submit_search' ),
						'title' => lang( 'submit_search' ),
						'icon' => 'search',
						'only_icon' => FALSE,
						'name' => 'users_submits_search[submit_search]',
						'class' => 'form-element submit-form submit-form-submit_search',
						'wrapper_class' => 'action ud-search-box-search-button',
						'form' => 'ud-d-search-form-' . $unique_hash,
						
					)
					
				); ?>
				
			</div>
			
		</div><?php
		
		echo form_close();
		
		if ( check_var( $params[ 'us_search_pre_text' ] ) AND $pre_text_pos == 'after_search_fields' ) {
			
			if ( check_var( $search_mode ) AND check_var( $params[ 'us_show_search_pre_text_on_search' ] ) ) {
				
				echo $params[ 'us_search_pre_text' ];
				
			}
			else if ( ! check_var( $search_mode ) AND check_var( $params[ 'us_show_search_pre_text_on_normal' ] ) ) {
				
				echo $params[ 'us_search_pre_text' ];
				
			}
			
		}
		
		$combo_box_fields_to_search = array();
		
	?>
	
<script type="text/javascript" >
	
	$( document ).on( 'ready', function() {
		
// 		if ( Cookies.get( "ud_d_search_<?= $data_scheme[ 'id' ]; ?>" ) == 1 ) {
// 			
// 			$( '.ud-data-list-search-box-<?= $unique_hash; ?>' ).addClass( 'search-box-expanded' );
// 			
// 		}
		
		$( '#ud-d-search-form-<?= $unique_hash; ?>' ).append( '<?= str_replace( array( "'", "\n", "\t", ), array( "\'", "", "", ),
			
			'<div class="ud-d-form-field-wrapper ud-d-form-field-wrapper-submit_reset ud-d-form-field-wrapper-button submit-form-field-wrapper submit-form-field-wrapper-submit_reset submit-form-field-wrapper-button">
				
				<div class="submit-form-field-control">
					
					' . vui_el_button(
						
						array(
							
							'id' => 'ud-d-search-reset-' . $unique_hash,
							'button_type' => 'button',
							'text' => lang( 'submit_reset_filter' ),
							'title' => lang( 'submit_reset_filter' ),
							'icon' => 'reset',
							'only_icon' => FALSE,
							'name' => 'users_submits_search[submit_reset]',
							'class' => 'form-element submit-form submit-form-submit_reset',
							'wrapper_class' => 'action',
							'form' => 'ud-d-search-form-' . $unique_hash,
							
						)
						
					) . '
					
				</div>
				
			</div>' .
			
			'<div class="ud-d-form-field-wrapper ud-d-form-field-wrapper-advs-expander ud-d-form-field-wrapper-button submit-form-field-wrapper">
				
				<div class="submit-form-field-control">
					
					' . vui_el_button(
						
						array(
							
							'id' => 'ud-d-search-advs-expander-' . $unique_hash,
							'button_type' => 'anchor',
							'text' => lang( 'ud_d_search_advs_expand' ),
							'title' => lang( 'ud_d_search_advs_expand' ),
							'icon' => 'more',
							'only_icon' => TRUE,
							'class' => '',
							'wrapper_class' => 'action',
							
						)
						
					) . '
					
				</div>
				
			</div>'
			
		); ?>' );
		
		$( '[name^="users_submits_search"]' ).on( 'change', function(e) {
			
			var jthis = $( this );
			var form = null;
			
			if ( jthis.attr( 'form' ) ) {
				
				formIdString = '#' + jthis.attr( 'form' );
				form = $( formIdString );
				
			}
			else if ( jthis.closest('form').length > 0 ) {
				
				form = jthis.closest('form');
				formIdString = '#' + form.attr( 'id' );
				
			}
			
			form.submit();
			
			e.preventDefault();
			
		});
		
		$( '#ud-d-search-form-<?= $unique_hash; ?>' ).on( 'click', function(e) {
			
			$( '.ud-data-list-search-box' ).addClass( 'focused' );
			
		});
		
		$( document ).click( function( event ) {
			
			if ( ! $(event.target ).closest( '#ud-d-search-form-<?= $unique_hash; ?>' ).length ) {
				
				$( '.ud-data-list-search-box' ).removeClass( 'focused' );
				
			}     
			
		});
		
		$( '#ud-d-search-advs-expander-<?= $unique_hash; ?>' ).on( 'keypress keydown keyup click', function(e) {
			
			$( '.ud-data-list-search-box-<?= $unique_hash; ?>' ).toggleClass( 'search-box-expanded' );
			$( 'body' ).toggleClass( 'search-box-expanded' );
			
// 			if ( $( '.desktop .ud-data-list-search-box-<?= $unique_hash; ?>' ).hasClass( 'search-box-expanded' ) ) {
// 				
// 				Cookies.set( "ud_d_search_<?= $data_scheme[ 'id' ]; ?>", 1, { expires: 1 } );
// 				
// 			}
// 			else {
// 				
// 				Cookies.remove( "ud_d_search_<?= $data_scheme[ 'id' ]; ?>" );
// 				
// 			}
			
			e.preventDefault();
			
		});
		
		$( '#ud-d-search-reset-<?= $unique_hash; ?>' ).on( 'keypress keydown keyup click', function(e) {
			
			var jthis = $( this );
			var form = null;
			
			if ( jthis.attr( 'form' ) ) {
				
				formIdString = '#' + jthis.attr( 'form' );
				form = $( formIdString );
				
			}
			else if ( jthis.closest('form').length > 0 ) {
				
				form = jthis.closest('form');
				formIdString = '#' + form.attr( 'id' );
				
			}
			
			form.attr( 'action', '<?= get_url( $this->uri->ruri_string() ); ?>' );
			
			form.find( '[name^="users_submits_search"]' ).each( function( index ) {
				
				$( this ).find( 'option:selected' ).removeAttr( 'selected' );
				$( this ).find( 'option:first' ).attr( 'selected', 'selected' );
				$( this ).val( '' );
	// 			$( this ).trigger( 'change' );
				
			});
			
// 			Cookies.remove( "ud_d_search_<?= $data_scheme[ 'id' ]; ?>" );
			
			form.submit();
			
			e.preventDefault();
			
		});
		
	});
	
</script>
	
</div><?php
