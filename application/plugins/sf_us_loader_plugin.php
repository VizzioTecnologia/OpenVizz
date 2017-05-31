<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sf_us_loader_plugin extends Plugins_mdl{

	public function run( &$data, $params = NULL ){
		
		log_message( 'debug', '[Plugins] Users submits loader plugin initialized' );
		
		$hide_fail_errors = FALSE;
		
		$regex = '/\{sf_us_loader(\s*.*?)\}(\s*.*?)\{\/sf_us_loader\}/i';
		
		$content = $this->voutput->get_content();
		
		preg_match_all( $regex, $content, $matches );
		
// 				echo '<pre>' . print_r( $matches, TRUE ) . '</pre>';
				
		if ( check_var( $matches[ 2 ][ 0 ] ) ){
			
			if ( ! $this->load->is_model_loaded( 'sfcm' ) ) {
				
				$this->load->model( 'common/submit_forms_common_model', 'sfcm' );
				
			}
			
			// -------------------------
			// Loading UniD API model
			
			if ( ! $this->load->is_model_loaded( 'ud_api' ) ) {
				
				$this->load->model( 'unid_api_mdl', 'ud_api' );
				
			}
			
			$match = $matches[ 2 ][ 0 ];
			
			if ( check_var( $matches[ 1 ][ 0 ] ) ){
				
				$matches[ 1 ][ 0 ] = explode( ' ', $matches[ 1 ][ 0 ] );
				
				reset( $matches[ 1 ][ 0 ] );
				
				while ( list( $_key, $_match ) = each( $matches[ 1 ][ 0 ] ) ) {
					
					if ( strpos( $_match, 'hide_fail_errors' ) !== FALSE ) {
						
						$hide_fail_errors = TRUE;
						
					}
					
				}
				
			}
// 				$json = str_replace( '{sf_us_loader}', '', $match );
// 				$json = str_replace( '{/sf_us_loader}', '', $json );
			
			$json = json_decode( trim( htmlspecialchars_decode( $match ) ), TRUE );
			
			/* Cada chave do array de nível primário deve ser
			o id do formulário ou um dos parâmetros abaixo.
			E cada formulário deve possuir suas próprias configurações*/
			
			if ( is_array( $json ) ) {
				
				$view_data = NULL;
				$us_output_html = NULL;
				
				$plugin_params = array(
					
					'l' => 'default', // layout
					'wc' => NULL, // css wrapper class
				);
				
				foreach ( $json as $_config => $_value ) {
					
					// ignorando os índices numéricos, pois os mesmos serão interpretados como os ids dos formulários
					if ( ! is_numeric( $_config ) ) {
						
						$plugin_params[ $_config ] = $_value;
						
						if ( is_numeric( $_value ) AND $_config == 'l' ) {
							
							if ( $_value > 0 ) {
								
								$plugin_params[ $_config ] = ( int ) $_value;
								
							}
							
						}
						else {
							
							$plugin_params[ $_config ] = $_value;
							
						}
						
					}
					
				}
				
				//------------------------------------------------------
				// defining the wrapper params
				
				$view_data[ 'wrapper_params' ] = & $plugin_params;
				
				//------------------------------------------------------
				// parsing the params
				
				$__index = 1;
				
				foreach ( $json as $_sf_id => $_us_params ) {
					
					if ( is_numeric( $_sf_id ) AND $data_scheme = $this->sfcm->get_submit_forms( array( 'where_condition' => 't1.id = ' . $_sf_id, 'limit' => 1, ) )->row_array() ) {
						
						// Default params values
						
						$us_params = array(
							
							'st' => TRUE, // Show submit form title
							'dstal' => TRUE, // Data Scheme title as link
							'csft' => FALSE, // Custom title: override the submit form title
							'ud_id' => 0, // user(s) submit(s) id(s) can be array
							'pts' => NULL, // props to show
							't' => NULL, // search terms
							'src' => '0', // show_results_count
							'rs' => NULL, // ud_d_list_results_string
							'srs' => NULL, // ud_d_list_single_result_string
							'nrs' => NULL, // no result string
							
							'tal' => TRUE, // ud data title_as_link_on_site_list
							
							'ip' => NULL, // Image props
							'edp' => NULL, // Event datetime props
							'tp' => NULL, // Title props
							'cp' => NULL, // Content props
							'oip' => NULL, // Other info props
							'sp' => NULL, // Status props
							
							'mc' => 2, // max columns, this param depends on css
							
							'f' => NULL, // filter
							'ni' => 0, // number of items, 0 = all users submits
							'ob' => NULL, // order by
							'miid' => 0, // menu item id
							'obd' => NULL, // order by direction: (A or ASC), (D or DESC)
							'l' => 'default', // layout
							'wc' => NULL, // css wrapper class
							'srml' => TRUE, // show data readmore links
							'dscrms' => NULL, // Data Scheme custom read more string
							'sdsrml' => TRUE, // show data schemes readmore links
							'sfl' => NULL, // Submit form link: array:
								
								/*
								
								title: link title
								text: link text
								target: link target: can be:
									_blank: Opens the linked page in a new window or tab
									_self: Opens the linked page in the same frame as it was clicked (this is default)
									_parent: Opens the linked document in the parent frame
									_top: Opens the linked document in the full body of the window
									[framename]: Opens the linked document in a named frame
									
									more info: http://www.w3schools.com/tags/att_a_target.asp
									
								*/
								
							'usl' => NULL, // Users submits link: array:
								
								/*
								
								title: link title
								text: link text
								target: link target: can be:
									_blank: Opens the linked page in a new window or tab
									_self: Opens the linked page in the same frame as it was clicked (this is default)
									_parent: Opens the linked document in the parent frame
									_top: Opens the linked document in the full body of the window
									[framename]: Opens the linked document in a named frame
									
									more info: http://www.w3schools.com/tags/att_a_target.asp
									
								*/
								
							'crl' => NULL, // Custom readmore links: multidimensional array:
								
								/*
								
								title: link title
								text: link text
								url: link url
								target: link target: can be:
									_blank: Opens the linked page in a new window or tab
									_self: Opens the linked page in the same frame as it was clicked (this is default)
									_parent: Opens the linked document in the parent frame
									_top: Opens the linked document in the full body of the window
									[framename]: Opens the linked document in a named frame
									
									more info: http://www.w3schools.com/tags/att_a_target.asp
									
								*/
								
								
						);
						
						//------------------------------------------------------
						// parsing the params
						
						foreach ( $_us_params as $_config => $_value ) {
							
							$us_params[ $_config ] = $_value;
							
							if ( is_numeric( $_value ) AND $_config == 'ni' ) {
								
								if ( $_value > 0 ) {
									
									$us_params[ $_config ] = ( int ) $_value;
									
								}
								
							}
							else if ( in_array( $_config, array( 'ip', 'edp', 'tp', 'cp','oip','sp', ) ) ) {
								
								if ( ! empty( $_value ) ) {
									
									$___config = NULL;
									
									switch ( $_config ) {
										
										case 'if':
											
											$___config = 'ud_image_prop';
											
											break;
											
										case 'edp':
											
											$___config = 'ud_event_datetime_prop';
											
											break;
											
										case 'tf':
											
											$___config = 'ud_title_prop';
											
											break;
											
										case 'cf':
											
											$___config = 'ud_content_prop';
											
											break;
											
										case 'oif':
											
											$___config = 'ud_other_info_prop';
											
											break;
											
										case 'sf':
											
											$___config = 'ud_status_prop';
											
											break;
											
										$_tmp = get_params( $us_params[ $_config ] );
										
										if ( is_array( $_tmp ) ) {
											
											$_value = $_tmp;
											
										}
										
// 											echo '<pre>' . print_r( $_value, TRUE ) . '</pre>';
										
										$_tmp = NULL;
										unset( $_tmp );
										
									}
									
									if ( $___config ) {
										
										if ( is_array( $_value ) ) {
											
											$data_scheme[ $___config ] = array();
											
											foreach ( $_value as $_v ) {
												
												$data_scheme[ $___config ][ $_v ] = 1;
												
											}
											
										}
										else {
											
											$data_scheme[ $___config ] = array(
												
												$_value => 1,
												
											);
											
										}
										
									}
									
								}
								
							}
							else if ( in_array( $_config, array( 'sfl', 'usl', 'crl', ) ) ) {
								
								if ( is_array( $_value ) AND ! empty( $_value ) ) {
									
									$us_params[ $_config ] = $_value;
									
								}
								
							}
							
						}
						
						$us_params[ 'props_to_show' ] = check_var( $us_params[ 'props_to_show' ], TRUE ) ? $us_params[ 'props_to_show' ] : NULL;
						$us_params[ 'props_to_show' ] = check_var( $us_params[ 'pts' ], TRUE ) ? $us_params[ 'pts' ] : $us_params[ 'props_to_show' ];
						
						$us_params[ 'props_to_show_site_list' ] = check_var( $us_params[ 'props_to_show_site_list' ], TRUE ) ? $us_params[ 'props_to_show_site_list' ] : NULL;
						$us_params[ 'props_to_show_site_list' ] = check_var( $us_params[ 'props_to_show' ], TRUE ) ? $us_params[ 'props_to_show' ] : $us_params[ 'props_to_show_site_list' ];
						
						$us_params[ 'ud_data_list_d_titles_as_link' ] = check_var( $us_params[ 'ud_data_list_d_titles_as_link' ], TRUE ) ? $us_params[ 'ud_data_list_d_titles_as_link' ] : NULL;
						$us_params[ 'ud_data_list_d_titles_as_link' ] = check_var( $us_params[ 'tal' ], TRUE ) ? $us_params[ 'tal' ] : $us_params[ 'ud_data_list_d_titles_as_link' ];
						$us_params[ 'ud_data_list_d_titles_as_link' ] = check_var( $_us_params[ 'ud_data_list_d_titles_as_link' ], TRUE ) ? $_us_params[ 'ud_data_list_d_titles_as_link' ] : $us_params[ 'ud_data_list_d_titles_as_link' ];
						$us_params[ 'ud_data_list_d_titles_as_link' ] = check_var( $_us_params[ 'tal' ], TRUE ) ? $_us_params[ 'tal' ] : $us_params[ 'ud_data_list_d_titles_as_link' ];
						$us_params[ 'tal' ] = & $us_params[ 'ud_data_list_d_titles_as_link' ];
						
						$us_params[ 'ud_ds_title_as_link_on_list' ] = check_var( $us_params[ 'ud_ds_title_as_link_on_list' ], TRUE ) ? $us_params[ 'ud_ds_title_as_link_on_list' ] : NULL;
						$us_params[ 'ud_ds_title_as_link_on_list' ] = check_var( $us_params[ 'dstal' ], TRUE ) ? $us_params[ 'dstal' ] : $us_params[ 'ud_ds_title_as_link_on_list' ];
						$us_params[ 'ud_ds_title_as_link_on_list' ] = check_var( $_us_params[ 'ud_ds_title_as_link_on_list' ], TRUE ) ? $_us_params[ 'ud_ds_title_as_link_on_list' ] : $us_params[ 'ud_ds_title_as_link_on_list' ];
						$us_params[ 'ud_ds_title_as_link_on_list' ] = check_var( $_us_params[ 'dstal' ], TRUE ) ? $_us_params[ 'dstal' ] : $us_params[ 'ud_ds_title_as_link_on_list' ];
						$us_params[ 'dstal' ] = & $us_params[ 'ud_ds_title_as_link_on_list' ];
						
						$us_params[ 'ud_data_list_d_readmore_link' ] = check_var( $us_params[ 'ud_data_list_d_readmore_link' ], TRUE ) ? $us_params[ 'ud_data_list_d_readmore_link' ] : NULL;
						$us_params[ 'ud_data_list_d_readmore_link' ] = check_var( $us_params[ 'srml' ], TRUE ) ? $us_params[ 'srml' ] : $us_params[ 'ud_data_list_d_readmore_link' ];
						$us_params[ 'ud_data_list_d_readmore_link' ] = check_var( $_us_params[ 'ud_data_list_d_readmore_link' ], TRUE ) ? $_us_params[ 'ud_data_list_d_readmore_link' ] : $us_params[ 'ud_data_list_d_readmore_link' ];
						$us_params[ 'ud_data_list_d_readmore_link' ] = check_var( $_us_params[ 'srml' ], TRUE ) ? $_us_params[ 'srml' ] : $us_params[ 'ud_data_list_d_readmore_link' ];
						$us_params[ 'srml' ] = & $us_params[ 'ud_data_list_d_readmore_link' ];
						
						$us_params[ 'ud_data_list_ds_readmore_custom_str' ] = check_var( $us_params[ 'ud_data_list_ds_readmore_custom_str' ], TRUE ) ? $us_params[ 'ud_data_list_ds_readmore_custom_str' ] : NULL;
						$us_params[ 'ud_data_list_ds_readmore_custom_str' ] = check_var( $us_params[ 'dscrms' ], TRUE ) ? $us_params[ 'dscrms' ] : $us_params[ 'ud_data_list_ds_readmore_custom_str' ];
						$us_params[ 'ud_data_list_ds_readmore_custom_str' ] = check_var( $_us_params[ 'ud_data_list_ds_readmore_custom_str' ], TRUE ) ? $_us_params[ 'ud_data_list_ds_readmore_custom_str' ] : $us_params[ 'ud_data_list_ds_readmore_custom_str' ];
						$us_params[ 'ud_data_list_ds_readmore_custom_str' ] = check_var( $_us_params[ 'dscrms' ], TRUE ) ? $_us_params[ 'dscrms' ] : $us_params[ 'ud_data_list_ds_readmore_custom_str' ];
						$us_params[ 'dscrms' ] = & $us_params[ 'ud_data_list_ds_readmore_custom_str' ];
						
						$us_params[ 'ud_data_list_ds_readmore_link' ] = check_var( $us_params[ 'ud_data_list_ds_readmore_link' ], TRUE ) ? $us_params[ 'ud_data_list_ds_readmore_link' ] : NULL;
						$us_params[ 'ud_data_list_ds_readmore_link' ] = check_var( $us_params[ 'sdsrml' ], TRUE ) ? $us_params[ 'sdsrml' ] : $us_params[ 'ud_data_list_ds_readmore_link' ];
						$us_params[ 'ud_data_list_ds_readmore_link' ] = check_var( $_us_params[ 'ud_data_list_ds_readmore_link' ], TRUE ) ? $_us_params[ 'ud_data_list_ds_readmore_link' ] : $us_params[ 'ud_data_list_ds_readmore_link' ];
						$us_params[ 'ud_data_list_ds_readmore_link' ] = check_var( $_us_params[ 'sdsrml' ], TRUE ) ? $_us_params[ 'sdsrml' ] : $us_params[ 'ud_data_list_ds_readmore_link' ];
						$us_params[ 'sdsrml' ] = & $us_params[ 'ud_data_list_ds_readmore_link' ];
						
						$us_params[ 'ud_data_list_max_columns' ] = check_var( $us_params[ 'ud_data_list_max_columns' ], TRUE ) ? $us_params[ 'ud_data_list_max_columns' ] : NULL;
						$us_params[ 'ud_data_list_max_columns' ] = check_var( $us_params[ 'mc' ], TRUE ) ? $us_params[ 'mc' ] : $us_params[ 'ud_data_list_max_columns' ];
						$us_params[ 'ud_data_list_max_columns' ] = check_var( $_us_params[ 'ud_data_list_max_columns' ], TRUE ) ? $_us_params[ 'ud_data_list_max_columns' ] : $us_params[ 'ud_data_list_max_columns' ];
						$us_params[ 'ud_data_list_max_columns' ] = check_var( $_us_params[ 'mc' ], TRUE ) ? $_us_params[ 'mc' ] : $us_params[ 'ud_data_list_max_columns' ];
						$us_params[ 'mc' ] = & $us_params[ 'ud_data_list_max_columns' ];
						
						$us_params[ 'ud_d_list_layout_site' ] = & $us_params[ 'l' ];
						$us_params[ 'show_default_results' ] = TRUE;
						$us_params[ 'ud_data_no_result_str' ] =  & $us_params[ 'nrs' ];
						$us_params[ 'show_results_count' ] =  & $us_params[ 'src' ];
						$us_params[ 'ud_d_list_search_results_string' ] =  & $us_params[ 'rs' ];
						$us_params[ 'ud_d_list_search_single_result_string' ] =  & $us_params[ 'srs' ];
						$us_params[ 'us_default_results_filters' ] =  & $us_params[ 'f' ];
						
// 				echo '<h3>Loader:</h3><pre>' . print_r( $_us_params, TRUE ) . '</pre>';
				
// 						echo '<h3>Loader:</h3><pre>' . print_r( $us_params, TRUE ) . '</pre>';
						
						$this->load->library( 'search' );
						
						if ( isset( $us_params[ 'pts' ] ) ) {
							
							$us_params[ 'pts' ] = explode( ',', $us_params[ 'pts' ] );
							
							reset( $us_params[ 'pts' ] );
							
							$tmp = array();
							
							while ( list( $k, $prop_to_show ) = each( $us_params[ 'pts' ] ) ) {
								
								if ( isset( $data_scheme[ 'fields' ][ $prop_to_show ] ) ){
								
									$prop = $data_scheme[ 'fields' ][ $prop_to_show ];
									
								}
								
								$tmp[ $prop_to_show ] = $prop_to_show;
								
							}
							
							$us_params[ 'props_to_show' ] = $tmp;
							
						}
						else {
						
							$us_params[ 'props_to_show' ] = array();
							
						}
						
						$view_data[ 'data_schemes' ][ $_sf_id ] = & $data_scheme;
						$us_view_data[ '__index' ] = $__index;
						$us_view_data[ 'data_scheme' ] = & $view_data[ 'data_schemes' ][ $_sf_id ];
						$us_view_data[ 'props' ] = & $us_view_data[ 'data_scheme' ][ 'fields' ];
						
						// -------------------------------------------------
						
						$menu_item_params = array();
						
						if ( check_var( $us_params[ 'miid' ] ) AND is_numeric( $us_params[ 'miid' ] ) AND ( $menu_item = $this->menus->get_menu_item( ( int ) $us_params[ 'miid' ] ) ) ) {
							
							$menu_item_params = get_params( $menu_item[ 'params' ] );
							$menu_item_params[ 'us_default_results_filters' ] = get_params( $menu_item_params[ 'us_default_results_filters' ] );
							
						}
						
						// -------------------------------------------------
						
						if ( ! check_var( $us_params[ 'props_to_show' ] ) ) {
							
							$us_params[ 'props_to_show' ] = ( ( check_var( $menu_item_params[ 'ud_d_list_site_override_visible_props' ] ) AND isset( $menu_item_params[ 'ud_d_list_site_props_to_show' ] ) ) ? $menu_item_params[ 'ud_d_list_site_props_to_show' ] : NULL );
							
						}
						
						$menu_item_params[ 'ud_d_list_site_props_to_show' ] = NULL;
						unset( $menu_item_params[ 'ud_d_list_site_props_to_show' ] );
						
						$this->ud_api->parse_ds( $data_scheme, FALSE, $us_params[ 'props_to_show' ] );
						
						$data_scheme[ 'params' ][ 'props_to_show' ] = & $data_scheme[ 'params' ][ 'props_to_show_site_list' ];
						$us_view_data[ 'params' ][ 'props_to_show' ] = & $data_scheme[ 'params' ][ 'props_to_show_site_list' ];
						$us_view_data[ 'props_to_show' ] = & $data_scheme[ 'params' ][ 'props_to_show_site_list' ];
						$us_params[ 'props_to_show' ] = & $data_scheme[ 'params' ][ 'props_to_show_site_list' ];
						
						$data_scheme[ 'plugin_params' ] = & $us_params;
						
						// -------------------------------------------------
						// Params filtering
						
						$component_params = $this->mcm->components[ 'submit_forms' ][ 'params' ];
						$us_view_data[ 'params' ] = filter_params( $component_params, $data_scheme[ 'params' ] );
						$us_view_data[ 'params' ] = filter_params( $us_view_data[ 'params' ], $menu_item_params );
						$us_view_data[ 'params' ] = filter_params( $us_view_data[ 'params' ], $us_params );
						
						// Params filtering
						// -------------------------------------------------
						
						$data_scheme[ 'data_list_site_link' ] = ( check_var( $us_params[ 'crl' ] ) AND is_string( $us_params[ 'crl' ] ) ) ? get_url( $us_params[ 'crl' ] ) : $this->unid->get_link(
							
							array (
								
								'url_alias' => 'site_data_list',
								'ds' => $data_scheme,
								'filters' => $us_view_data[ 'params' ][ 'us_default_results_filters' ],
								
							)
							
						);
						
						//------------------------------------------------------
						
// 							echo '<h3>Loader:</h3><pre>' . print_r( $us_params[ 'ob' ], TRUE ) . '</pre>';
						
						$_tmp = get_params( $us_params[ 'ob' ] );
						
						if ( is_array( $_tmp ) AND check_var( $_tmp ) ) {
							
							$us_params[ 'ob' ] = $_tmp;
							
						}
						
						$_tmp = NULL;
						unset( $_tmp );
						
						$search_config = array(
							
							'terms' => $us_params[ 't' ],
							'plugins' => 'sf_us_search',
							'ipp' => $us_params[ 'ni' ],
							'cp' => 1,
							'allow_empty_terms' => TRUE,
							'random' => ( is_string( $us_params[ 'ob' ] ) AND strtolower( $us_params[ 'ob' ] ) == 'random' ) ? TRUE : FALSE,
							'plugins_params' => array(
								
								'sf_us_search' => array(
									
									'sf_id' => $_sf_id,
									'us_id' => $us_params[ 'ud_id' ],
									'filters' => $us_view_data[ 'params' ][ 'us_default_results_filters' ],
									'order_by' => $us_params[ 'ob' ],
									'order_by_direction' => $us_params[ 'obd' ],
									
								),
								
							),
							
						);
						
						$this->search->reset_config();
						$this->search->config( $search_config );
						$us_view_data[ 'ud_data_array' ] = $this->search->get_full_results( 'sf_us_search', TRUE );
						$us_view_data[ 'ud_data_list_total_results' ] = $this->search->count_all_results( 'sf_us_search' );
						
						//------------------------------------------------------
						// view dos envios
						
						$us_theme_load_views_path = call_user_func( $this->mcm->environment . '_theme_plugins_views_path' ) . 'sf_us_loader' . DS . 'ud_data_list' . DS . $us_params[ 'l' ] . DS;
						$us_theme_views_path = THEMES_PATH . $us_theme_load_views_path;
						
						$us_default_load_views_path = PLUGINS_DIR_NAME . DS . 'sf_us_loader' . DS . 'ud_data_list' . DS . $us_params[ 'l' ] . DS;
						$us_default_views_path = VIEWS_PATH . $us_default_load_views_path;
						
						$us_view_filename = 'ud_data_list';
						
						// verificando se o tema atual possui a view
						if ( file_exists( $us_theme_views_path . $us_view_filename . '.php' ) ){
							
							$us_output_html[ $_sf_id ] = $this->load->view( $us_theme_load_views_path . $us_view_filename, $us_view_data, TRUE );
							
						}
						// verificando se a view existe no diretório de views padrão
						else if ( file_exists( $us_default_views_path . $us_view_filename . '.php' ) ){
							
							$us_output_html[ $_sf_id ] = $this->load->view( $us_default_load_views_path . $us_view_filename, $us_view_data, TRUE );
							
						}
						
						$__index++;
						
					}
					
				}
				
				$view_data[ 'us_output_html' ] = & $us_output_html;
				
				//echo '<pre>' . print_r( $view_data, TRUE ) . '</pre>'; exit;
				
				//------------------------------------------------------
				// verificando se o layout principal existe
				
				$wrapper_theme_load_views_path = call_user_func( $this->mcm->environment . '_theme_plugins_views_path' ) . 'sf_us_loader' . DS . 'wrapper' . DS . $plugin_params[ 'l' ] . DS;
				$wrapper_theme_views_path = THEMES_PATH . $wrapper_theme_load_views_path;
				
				$wrapper_default_load_views_path = PLUGINS_DIR_NAME . DS . 'sf_us_loader' . DS . 'wrapper' . DS . $plugin_params[ 'l' ] . DS;
				$wrapper_default_views_path = VIEWS_PATH . $wrapper_default_load_views_path;
				
				$wrapper_view_filename = 'wrapper';
				
				// verificando se o tema atual possui a view
				if ( file_exists( $wrapper_theme_views_path . $wrapper_view_filename . '.php' ) ){
					
					$output_html = $this->load->view( $wrapper_theme_load_views_path . $wrapper_view_filename, $view_data, TRUE );
					
				}
				// verificando se a view existe no diretório de views padrão
				else if ( file_exists( $wrapper_default_views_path . $wrapper_view_filename . '.php' ) ){
					
					$output_html = $this->load->view( $wrapper_default_load_views_path . $wrapper_view_filename, $view_data, TRUE );
					
				}
				
				
				$content = str_replace( $matches[ 0 ][ 0 ], minify_html( $output_html ), $content );
				
				$this->voutput->set_content( $content );
				
			}
			
			
			else {
				
				if ( $hide_fail_errors ) {
				
					$content = str_replace( $matches[ 0 ][ 0 ], '', $content );
					$this->voutput->set_content( $content );
					
// 						echo '<pre>' . print_r( $matches, TRUE ) . '</pre>'; exit;
					
				}
				else {
					
					$content = str_replace( $matches[ 0 ][ 0 ], lang( 'invalid_json' ) . $json, $content );
					$this->voutput->set_content( $content );
					
				}
				
			}
			
			
			
			/*
			 * -------------------------------------------------------------------------------------------------
			 * Executa novamente os plugins de conteúdo
			 */
			
			// carrega os plugins de conteúdo
			parent::load( NULL, 'content' );
			
			//parent::_set_performed( 'article_loader' );
			
			/*
			 * -------------------------------------------------------------------------------------------------
			 */

		}

		return TRUE;

	}

}
