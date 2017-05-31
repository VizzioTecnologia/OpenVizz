<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
	
	$_is_presentation = FALSE;
	
	$wrapper_class = array(
		
		'ud-data-list-wrapper',
		'results',
		
	);
	
	$property_presentation_types = array(
		
		'image',
		'title',
		'event_datetime',
		'content',
		'other_info',
		'status',
		
	);
	
	if ( check_var( $params[ 'ud_data_list_max_columns' ] ) ) {
		
		$wrapper_class[] = 'columns-' . $params[ 'ud_data_list_max_columns' ];
		
	}
	
	$__main_image = FALSE;
	$__page_main_image = FALSE;
	
	reset( $property_presentation_types );
	
	while ( list( $k, $v ) = each( $property_presentation_types ) ) {
		
		if ( isset( $params[ 'ud_' . $v . '_prop' ] ) ) {
			
			$_is_presentation = TRUE;
			
		}
		
		if ( check_var( $params[ 'ud_' . $v . '_prop' ] ) ) {
			
			reset( $params[ 'ud_' . $v . '_prop' ] );
			
			while ( list( $_alias, $_field ) = each( $params[ 'ud_' . $v . '_prop' ] ) ) {
				
				if ( check_var( $props_to_show[ $_alias ] ) ) {
					
					$wrapper_class[ $_alias ] = 'has-ud-' . str_replace( '_', '-', url_title( $v, '-', TRUE ) ) . '-prop';
					
				}
				
			}
			
		}
		
	}
	
	$wrapper_class = join( ' ', $wrapper_class );
	
?><div id="ud-d-search-results-wrapper" class="<?= $wrapper_class; ?>">
	
	<?php if ( check_var( $ud_data_array ) ) {
		
		$show_results_count = FALSE;
		$show_results_count_str = '';
		
		if ( check_var( $params[ 'show_results_count' ] ) AND ! check_var( $search_mode ) ) {
			
			$show_results_count = TRUE;
			
		}
		else if ( check_var( $params[ 'show_search_results_count' ] ) AND check_var( $search_mode ) ) {
			
			$show_results_count = TRUE;
			
		}
		
		if ( $show_results_count ) {
		
			if ( $ud_data_list_total_results > 1 ) {
				
				if ( check_var( $params[ 'ud_d_list_results_string' ] ) AND ! check_var( $search_mode ) ) {
					
					$show_results_count_str = lang( $params[ 'ud_d_list_results_string' ] , NULL, '<span class="ud-d-list-results-count">' . $ud_data_list_total_results . '</span>' );
					
				}
				else if ( check_var( $params[ 'ud_d_list_search_results_string' ] ) AND check_var( $search_mode ) ) {
					
					$show_results_count_str = lang( $params[ 'ud_d_list_search_results_string' ] , NULL, '<span class="ud-d-list-results-count">' . $ud_data_list_total_results . '</span>' );
					
				}
				else {
					
					$show_results_count_str = lang( 'ud_d_list_search_results_string' , NULL, '<span class="ud-d-list-results-count">' . $ud_data_list_total_results . '</span>' );
					
				}
				
			}
			else {
				
				if ( check_var( $params[ 'ud_d_list_single_result_string' ] ) AND ! check_var( $search_mode ) ) {
					
					$show_results_count_str = sprintf( $params[ 'ud_d_list_single_result_string' ], '<span class="ud-d-list-results-count">' . $ud_data_list_total_results . '</span>' );
					
				}
				else if ( check_var( $params[ 'ud_d_list_search_single_result_string' ] ) AND check_var( $search_mode ) ) {
					
					$show_results_count_str = sprintf( $params[ 'ud_d_list_search_single_result_string' ], '<span class="ud-d-list-results-count">' . $ud_data_list_total_results . '</span>' );
					
				}
				else {
					
					$show_results_count_str = sprintf( lang( 'ud_d_list_search_single_result_string' ), '<span class="ud-d-list-results-count">' . $ud_data_list_total_results . '</span>' );
					
				}
				
			}
			
		?>
		
		<div class="ud-d-list-results-title-wrapper ud-d-list-results-count-wrapper">
			
			<span class="s1">
				
				<span class="ud-d-list-results-title">
					
					<?= $show_results_count_str; ?>
					
				</span>
				
			</span>
			
		</div>
		
		<?php }
		
		if ( check_var( $pagination ) ) {
			
			echo $pagination;
			
		}
		
		echo '<div class="items ud-data-items ud-d-list">';
		
		foreach ( $ud_data_array as $key => $ud_data ) {
			
			$this->ud_api->parse_ud_data( $ud_data, $props_to_show, TRUE );
			
			$_ud_data_wrapper_class = array();
			
			reset( $property_presentation_types );
			
			while ( list( $k, $v ) = each( $property_presentation_types ) ) {
				
				${ '_ud_' . $v . '_props' } = array();
				
				if ( check_var( $params[ 'ud_' . $v . '_prop' ] ) ) {
					
					$__class = FALSE;
					
					reset( $params[ 'ud_' . $v . '_prop' ] );
					
					while ( list( $_alias, $_field ) = each( $params[ 'ud_' . $v . '_prop' ] ) ) {
						
						if ( check_var( $props_to_show[ $_alias ] ) AND check_var( $ud_data[ 'parsed_data' ][ 'full' ][ $_alias ][ 'value' ], TRUE ) ) {
							
							if ( ! $__class ) $__class = 'has-ud-' . $v;
							
							${ '_ud_' . $v . '_props' }[ $_alias ][ 'label' ] = $ud_data[ 'parsed_data' ][ 'full' ][ $_alias ][ 'label' ];
							${ '_ud_' . $v . '_props' }[ $_alias ][ 'value' ] = $ud_data[ 'parsed_data' ][ 'full' ][ $_alias ][ 'value' ];
							
						}
						
					}
					
// 					echo '<pre>' . '${ _ud_' . $v . '_props }' . ': ' . print_r( ${ '_ud_' . $v . '_props' }, TRUE ) . '</pre><br/>'; 
					
					if ( $__class ) $_ud_data_wrapper_class[ $__class ] = $__class;
					
				}
				
			}
			
			reset( $ud_data[ 'parsed_data' ][ 'full' ] );
			
			while ( list( $_pk, $parsed_array ) = each( $ud_data[ 'parsed_data' ][ 'full' ] ) ) {
				
				$ud_data_unique_hash = md5( uniqid( rand(), true ) );
				
				$prop_value = & $ud_data[ 'parsed_data' ][ 'full' ][ $_pk ][ 'value' ];
				
				$_prop = NULL;
				
				if ( isset( $props[ $_pk ] ) ) {
					
					$_prop = $props[ $_pk ];
					
				}
				
				if ( check_var( $ud_data[ 'data' ][ $_prop[ 'alias' ] ] ) AND in_array( $_prop[ 'field_type' ], array( 'combo_box', 'radiobox' ) ) AND check_var( $params[ 'ud_data_availability_site_search' ][ $_prop[ 'alias' ] ] ) ) {
					
					$search_filter_url = url_segment_to_assoc_array( $data_scheme[ 'data_list_site_link' ] );
					
					$search_filter_url[ 'sfsp' ] = check_var( $search_filter_url[ 'sfsp' ] ) ? $this->unid->url_decode_ud_filters( $search_filter_url[ 'sfsp' ] ) : array();
					
					$update = TRUE;
					
					if (
						
						check_var( $search_filter_url[ 'sfsp' ][ 'dinamic_filter_fields' ][ $_prop[ 'alias' ] ] ) AND $search_filter_url[ 'sfsp' ][ 'dinamic_filter_fields' ][ $_prop[ 'alias' ] ] == $ud_data[ 'data' ][ $_prop[ 'alias' ] ]
						
					) {
						
						$update = FALSE;
						
					}
					
					if ( $update ) {
						
						$search_filter_url[ 'sfsp' ][ 'dinamic_filter_fields' ][ $_prop[ 'alias' ] ] = $ud_data[ 'data' ][ $_prop[ 'alias' ] ];
						
					}
					
					$search_filter_url[ 'sfsp' ] = $this->unid->url_encode_ud_filters( $search_filter_url[ 'sfsp' ] );
					
					$ud_data[ 'parsed_data' ][ 'full' ][ $_pk ][ 'search_filter_url' ] = $this->uri->assoc_to_uri( $search_filter_url );
					
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
			
			// ----------------------------
			
			if ( $_is_presentation ) {
				
				$ud_status_prop_class = array();
				$_ud_status = array();
				$_ud_status_classes = '';
				$_ud_has_status = FALSE;
				
				if ( check_var( $params[ 'ud_status_prop' ] ) ) {
					
					foreach( $params[ 'ud_status_prop' ] as $alias => $v ) {
						
						$status_field = $props[ $alias ];
						
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
								
								if ( check_var( $status_field[ 'advanced_options' ][ $_item ] ) AND check_var( $ud_data[ 'data' ][ $alias ], TRUE ) AND $ud_data[ 'data' ][ $alias ] == $status_field[ 'advanced_options' ][ $_item ] ) {
									
									$ud_status_prop_class[ $alias ][ $_item ] = $_item . ' status-' . str_replace( 'prop_is_ud_status_', '', $_item );
									
									$_ud_status[ $alias ] = $_item . ' status-' . str_replace( 'prop_is_ud_status_', '', $_item );
									
									$_ud_has_status = TRUE;
									
								}
								
							};
							
						}
						
					}
					
				}
				
				if ( $_ud_has_status ) {
					
					$_ud_status_classes = join( $_ud_status, ' ' );
					
				}
				
				$_ud_data_wrapper_class = join( ' ', $_ud_data_wrapper_class );
				
				$__main_image = FALSE;
				
				if ( check_var( $params[ 'ud_image_prop' ] ) ) {
					
					reset( $params[ 'ud_image_prop' ] );
					
					$ud_main_image_prop_alias = key( $params[ 'ud_image_prop' ] );
					
					if ( ! $__main_image AND $ud_main_image_prop_alias AND check_var( $ud_data[ 'data' ][ $ud_main_image_prop_alias ] ) ) {
						
						$__main_image = get_url( $ud_data[ 'data' ][ $ud_main_image_prop_alias ], NULL, FALSE );
						
						if ( check_var( $params[ 'ud_d_list_main_image_on_title' ] ) ) {
							
							$tmp = str_replace( trim( BASE_URL, '/' ) . '/', '', $__main_image );
							
							if ( ! url_is_absolute( $tmp ) ) {
								
								$bg_image = THUMBS_DIR_URL . '/' . ltrim( $tmp, '/' );
								
							}
							else $bg_image = & $__main_image;
							
							$this->voutput->append_head_stylesheet_declaration( 'ud_data_' . $ud_data[ 'id' ], '
								
								#ud-d-list-data-' . $ud_data[ 'id' ] . ',
								#ud-d-list-data-' . $ud_data[ 'id' ] . ':before,
								#ud-d-list-data-' . $ud_data[ 'id' ] . ':after,
								#ud-d-list-data-' . $ud_data[ 'id' ] . ' .ud-titles-wrapper,
								#ud-d-list-data-' . $ud_data[ 'id' ] . ' .ud-titles-wrapper .ud-data-prop {
									
									background-image: url(\'' . $bg_image . '\');
									
								}
								
							' );
							
						}
						
					}
					
				}
				
				echo '<div id="ud-d-list-data-' . $ud_data[ 'id' ] . '" data-fancybox="ud-d-list-' . $unique_hash . '" data-src="' . $ud_data[ 'site_link' ] . '" data-selector=".ud-data" data-type="ajax" class="' . $ud_data_unique_hash . ( ( $__main_image AND check_var( $params[ 'ud_d_list_main_image_on_title' ] ) ) ? ' ud-image-on-title ' : ' ' ) . 'item ' . ( check_var( $params[ 'ud_data_list_d_use_modal' ] ) ? 'modal' : '' ) . ' ud-data ' . $_ud_data_wrapper_class . ' ' . $_ud_status_classes . '">';
				
				reset( $property_presentation_types );
				
				$__item_count = 1;
				
				while ( list( $k, $v ) = each( $property_presentation_types ) ) {
					
					if ( ! empty( ${ '_ud_' . $v . '_props' } ) ) {
						
						if ( $v != 'title' OR ( $v == 'title' AND ! check_var( $params[ 's' ] ) ) ) {
							
							if ( $__main_image ) {
								
								if ( check_var( $params[ 'ud_d_list_main_image_on_title' ] ) ) {
									
								}
								
								if ( ! $__page_main_image AND check_var( $params[ 'ud_d_list_page_content_title_image' ] ) ) {
									
									$this->voutput->append_head_stylesheet_declaration( 'ud_page_title', '
										
										#submit-form-users-submits-' . $unique_hash . ' > .page-title {
											
											background-image: url(\'' . $__main_image . '\');
											
										}
										
									' );
									
									$__page_main_image = TRUE;
									
								}
								
							}
							
						}
						
						echo '<div class="item items-' . count( ${ '_ud_' . $v . '_props' } ) . ' ud-' . str_replace( '_', '-', rtrim( url_title( $v, '-', TRUE ), 's' ) ) . 's-wrapper">';
						
						$__item_count = 1;
						
						foreach ( ${ '_ud_' . $v . '_props' } as $_alias => $_field ) {
							
							if ( $_field AND ( isset( $props[ $_alias ] ) ) OR in_array( $_alias, array( 'id', 'submit_datetime', 'mod_datetime' ) ) ) {
								
								require( rtrim( $v, 's' ) . 's.php' );
								
							}
							
							$__item_count++;
							
						}
						
						echo '</div>';
						
					}
					
				}
				
				// ---------------------------
				
				if ( file_exists( $_path . 'readmore.php' ) ) {
					
					require( $_path . 'readmore.php' );
					
				}
				
				echo '</div>';
				
				// ---------------------------
				
			}
			
		}
		
		echo '</div>';
		
		if ( check_var( $pagination ) ) {
			
			echo $pagination;
			
		}
		
	} else {
		
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
			
		</div><?php
		
	}
	
?>


<?php if ( check_var( $params[ 'ud_data_list_d_use_modal' ] ) AND $this->plugins->load( 'fancybox' ) ){ ?>

	<?php
		
		$script = "
			
			$( document ).on( 'ready', function( e ){
				
				$( '#submit-form-users-submits-" . $unique_hash . " .modal' ).fancybox({
					
					type: 'ajax',
					onInit: function(){
						
						$( \"html, body\" ).addClass( \"fancybox-opened\" );
						$('.fancybox-container').addClass( 'ud-d-detail' );
						
					},
					afterClose: function(){
						
						$( \"html, body\" ).removeClass( \"fancybox-opened\" );
						
					},
					onComplete : function(){
						
					},
					
					smallBtn : 'auto',
					image : {
						
						protect : true
						
					},
					
					spinnerTpl : '<div class=\"fancybox-loading\"><div></div></div>'
					
				});
				
			});
			
		";
		
		$this->voutput->append_head_script_declaration( 'fancybox_ud_d_detail_' . $unique_hash, $script );
		
	?>
	
<script type="text/javascript" >
	
	$( document ).on( 'ready', function( e ){
		/*
		var fbContent;
		
		$( "#submit-form-users-submits-<?= $unique_hash; ?> .modal" ).attr( 'data-fancybox', 'ud-modal-group-<?= $unique_hash; ?>' ).fancybox({
			
			//live: true,
			fitToView: true,
			closeBtn: true,
			helpers: {
				
				overlay: {
					
					showEarly  : true
					
				}
				
			},
			content: fbContent,
			beforeShow: function(){
				
				fbContent = $( this ).html();
				$(".fancybox-overlay").addClass( "ud-d-detail" );
				
			},
			afterClose: function(){
				fbContent = '';
			},
			'onComplete' : function(){
				
				jQuery.fancybox.showActivity();
				jQuery('#fancybox-frame').load(function(){
					jQuery.fancybox.hideActivity();
				});
				
				$("html, body").addClass( "fancybox-opened" );
				
			}
			<?php
				
				if ( $this->ua->is_mobile() ) {
					
					echo ",
						
						'openEffect': 'none',
						'closeEffect': 'none',
						'nextEffect': 'none',
						'prevEffect': 'none'
						
					";
					
				}
				
			?>
			
		});
		*/
	});
	
</script>

<?php } ?>


</div><?php
