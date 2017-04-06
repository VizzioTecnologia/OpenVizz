<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sf_us_loader_plugin extends Plugins_mdl{

	public function run( &$data, $params = NULL ){
		
		log_message( 'debug', '[Plugins] Users submits loader plugin initialized' );
		
		$regex = '/\{sf_us_loader\}\s*.*?\{\/sf_us_loader\}/i';
		
		$content = $this->voutput->get_content();
		
		preg_match_all( $regex, $content, $matches );
		
		if ( count( $matches[ 0 ] ) ){
			
			if ( ! $this->load->is_model_loaded( 'sfcm' ) ) {
				
				$this->load->model( 'common/submit_forms_common_model', 'sfcm' );
				
			}
			
			// -------------------------
			// Loading UniD API model
			
			if ( ! $this->load->is_model_loaded( 'ud_api' ) ) {
				
				$this->load->model( 'unid_api_mdl', 'ud_api' );
				
			}
			
			foreach ( $matches[ 0 ] as $key => $match ) {
				
				$json = str_replace( '{sf_us_loader}', '', $match );
				$json = str_replace( '{/sf_us_loader}', '', $json );
				
				$json = json_decode( trim( htmlspecialchars_decode( $json ) ), TRUE );
				
				/* Cada chave do array de nível primário deve ser
				o id do formulário ou um dos parâmetros abaixo.
				E cada formulário deve possuir suas próprias configurações*/
				
				//echo '<pre>' . print_r( $json, TRUE ) . '</pre>';
				
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
						
						if ( is_numeric( $_sf_id ) AND $submit_form = $this->sfcm->get_submit_forms( array( 'where_condition' => 't1.id = ' . $_sf_id, 'limit' => 1, ) )->row_array() ) {
							
							$this->ud_api->parse_ds( $submit_form );
							
							// Default params values
							
							$us_params = array(
								
								'st' => TRUE, // Show submit form title
								'csft' => FALSE, // Custom title: override the submit form title
								'us_id' => 0, // user(s) submit(s) id(s) can be array
								'pts' => NULL, // props to show
								't' => NULL, // search terms
								
								'if' => NULL, // Images fields: can be an asterisk (*), meaning that all fields will be used, or an array containing the fields
								'tf' => NULL, // Title fields: can be an asterisk (*), meaning that all fields will be used, or an array containing the fields
								'tal' => TRUE, // title_as_link_on_site_list
								'cf' => '*', // Content fields: can be an asterisk (*), meaning that all fields will be used, or an array containing the fields
								'oif' => NULL, // Other info fields: can be an asterisk (*), meaning that all fields will be used, or an array containing the fields
								'sf' => NULL, // Status fields: can be an asterisk (*), meaning that all fields will be used, or an array containing the fields
								
								'mc' => 2, // max columns, this param depends on css
								
								'f' => NULL, // filter
								'ni' => 0, // number of items, 0 = all users submits
								'ob' => NULL, // order by
								'obd' => NULL, // order by direction: (A or ASC), (D or DESC)
								'l' => 'default', // layout
								'wc' => NULL, // css wrapper class
								'srml' => TRUE, // show data readmore links
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
								else if ( in_array( $_config, array( 'if', 'tf', 'cf','oif','sf', ) ) ) {
									
									if ( ! empty( $_value ) ) {
										
										$___config = NULL;
										
										switch ( $_config ) {
											
											case 'if':
												
												$___config = 'ud_image_prop';
												
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
												
											
										}
										
										if ( $___config ) {
											
											if ( is_array( $_value ) ) {
												
												$submit_form[ 'ud_image_prop' ] = array();
												
												foreach ( $_value as $_v ) {
													
													$submit_form[ 'ud_image_prop' ][ $_v ] = 1;
													
												}
												
											}
											else {
												
												$submit_form[ 'ud_image_prop' ] = array(
													
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
								else {
									
									$us_params[ $_config ] = $_value;
									
								}
								
							}
							
							$us_params[ 'props_to_show_site_list' ] = $us_params[ 'pts' ];
							$us_params[ 'ud_data_list_d_titles_as_link' ] = $us_params[ 'tal' ];
							$us_params[ 'ud_data_list_d_readmore_link' ] = $us_params[ 'srml' ];
							$us_params[ 'ud_data_list_max_columns' ] = $us_params[ 'mc' ];
							$us_params[ 'show_default_results' ] = TRUE;
							
							$this->load->library( 'search' );
							
							$submit_form[ 'data_list_site_link' ] = ( check_var( $us_params[ 'crl' ] ) AND is_string( $us_params[ 'crl' ] ) ) ? get_url( $us_params[ 'crl' ] ) : $this->unid->get_link(
								
								array (
									
									'url_alias' => 'site_data_list',
									'ds' => $submit_form,
									'filters' => $us_params[ 'f' ],
									
								)
								
							);
							
							$view_data[ 'submit_forms' ][ $_sf_id ] = & $submit_form;
							$us_view_data[ '__index' ] = $__index;
							$us_view_data[ 'submit_form' ] = & $view_data[ 'submit_forms' ][ $_sf_id ];
							$submit_form[ 'plugin_params' ] = & $us_params;
							
							
							// -------------------------------------------------
							// Params filtering
							
							$component_params = $this->mcm->components[ 'submit_forms' ][ 'params' ];
							$us_view_data[ 'params' ] = filter_params( $component_params, $submit_form[ 'params' ] );
							$us_view_data[ 'params' ] = filter_params( $us_view_data[ 'params' ], $us_params );
							
							if ( isset( $us_params[ 'pts' ] ) ) {
								
								$us_params[ 'pts' ] = explode( ',', $us_params[ 'pts' ] );
								
								reset( $submit_form[ 'params' ][ 'props_to_show_site_list' ] );
								
								$tmp = array();
								
								while ( list( $k, $prop_to_show ) = each( $us_params[ 'pts' ] ) ) {
									
									if ( in_array( $prop_to_show, array( 'id', 'submit_datetime', 'mod_datetime' ) ) ) {
										
										$tmp[] = array(
											
											'alias' => $prop_to_show,
											'title' => ( check_var( $submit_form[ 'params' ][ 'ud_ds_default_data_' . $prop_to_show . '_pres_title' ] ) ? lang( $submit_form[ 'params' ][ 'ud_ds_default_data_' . $prop_to_show . '_pres_title' ] ) : lang( $prop_to_show ) ),
											'type' => 'built_in',
											
										);
										
									}
									else if ( isset( $submit_form[ 'fields' ][ $prop_to_show ] ) ){
									
										$prop = $submit_form[ 'fields' ][ $prop_to_show ];
										
										$tmp[] = array(
											
											'alias' => $prop_to_show,
											'title' => ( isset( $prop[ 'presentation_label' ] ) AND $prop[ 'presentation_label' ] ) ? $prop[ 'presentation_label' ] : $prop[ 'label' ],
											'type' => $prop[ 'field_type' ],
											
										);
										
									}
									
								}
								
								$us_view_data[ 'params' ][ 'props_to_show_site_list' ] = $tmp;
								
							}
							
							// Params filtering
							// -------------------------------------------------
							
							
							//------------------------------------------------------
							
							$_tmp = get_params( $us_params[ 'ob' ] );
							
							if ( is_array( $_tmp ) ) {
								
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
										'us_id' => $us_params[ 'us_id' ],
										'filters' => $us_params[ 'f' ],
										'order_by' => $us_params[ 'ob' ],
										'order_by_direction' => $us_params[ 'obd' ],
										
									),
									
								),
								
							);
							
							//echo '<pre>' . print_r( $search_config, TRUE ) . '</pre>';
							
							$this->search->reset_config();
							$this->search->config( $search_config );
							$submit_form[ 'users_submits' ] = $this->search->get_full_results( 'sf_us_search', TRUE );
							
							//------------------------------------------------------
							// view dos envios
							
							$us_theme_load_views_path = call_user_func( $this->mcm->environment . '_theme_plugins_views_path' ) . 'sf_us_loader' . DS . 'users_submits' . DS . $us_params[ 'l' ] . DS;
							$us_theme_views_path = THEMES_PATH . $us_theme_load_views_path;
							
							$us_default_load_views_path = PLUGINS_DIR_NAME . DS . 'sf_us_loader' . DS . 'users_submits' . DS . $us_params[ 'l' ] . DS;
							$us_default_views_path = VIEWS_PATH . $us_default_load_views_path;
							
							$us_view_filename = 'users_submits';
							
							// verificando se o tema atual possui a view
							if ( file_exists( $us_theme_views_path . $us_view_filename . '.php' ) ){
								
								$data[ 'module_data' ][ 'load_view_path' ] = $us_theme_views_path;
								$data[ 'module_data' ][ 'view_path' ] = $us_theme_views_path;
								
								$us_output_html[ $_sf_id ] = $this->load->view( $us_theme_load_views_path . $us_view_filename, $us_view_data, TRUE );
								
							}
							// verificando se a view existe no diretório de views padrão
							else if ( file_exists( $us_default_views_path . $us_view_filename . '.php' ) ){
								
								$data[ 'module_data' ][ 'load_view_path' ] = $us_default_load_views_path;
								$data[ 'module_data' ][ 'view_path' ] = $us_default_views_path;
								
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
					
					
					$content = str_replace( $match, minify_html( $output_html ), $content );
					
					$this->voutput->set_content( $content );
					
				}
				
				else {
					
					$content = str_replace( $match, lang( 'invalid_json' ), $content );
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
