<?php  if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

function get_url( $original_url = NULL, $itemid = NULL ){
	
	if ( ! $original_url ) {
		
		return site_url();
		
	}
	
	if ( $original_url == '#' ) {
		
		return current_url() . '#';
		
	}
	
	// if the url is absolute, return this
	if ( url_is_absolute( $original_url ) ) {
		
		return $original_url;
		
	}
	
	$_tmp = explode( '/', $original_url );
	
	if (
		
		valid_domain( $original_url ) // if the url is a domain without the protocol
		OR ( count( $_tmp ) > 1 AND valid_domain( $_tmp[ 0 ] ) ) // if the url is a domain with segments without the protocol
		
	) {
		
		return 'http://' . $original_url;
		
	}
	
	$original_url = trim( $original_url, '/' );
	
	if ( parse_url( $original_url, PHP_URL_SCHEME ) != '' ) {
		
		return $original_url;
		
	}
	
	//echo '<small>' . print_r( $original_url, TRUE ) . '</small>';
	$CI =& get_instance();
	
	$reverse_urls = isset( $CI->mcm->system_params[ 'reverse_urls' ] ) ? $CI->mcm->system_params[ 'reverse_urls' ] : NULL;
	
	
	if ( ( isset( $reverse_urls ) AND is_array( $reverse_urls ) ) ) {
		
		if ( 0 === strpos( $original_url, 'submit_forms' ) ) {
			
			// -------------------------
			// Loading UniD model
			
			if ( ! $CI->load->is_model_loaded( 'unid' ) ) {
				
				$CI->load->model( 'unid_mdl', 'unid' );
				
			}
			
			// -------------------------
			// Loading UniD API model
			
			if ( ! $CI->load->is_model_loaded( 'ud_api' ) ) {
				
				$CI->load->model( 'unid_api_mdl', 'ud_api' );
				
			}
			
			// -------------------------
			// Getting Queries
			
			$query = array();
			
			$tmp = parse_url( $original_url );
			
			if ( isset( $tmp[ 'query' ] ) ) {
				
				parse_str( $tmp[ 'query' ], $query );
				
			}
			
			// -------------------------
			// Getting URI segments
			
			$segments = explode( '/', $original_url );
			
			if ( in_array( 'index', $segments ) ) {
				
				$final_url = $original_url;
				$friendly_url = check_var( $CI->mcm->filtered_system_params[ 'friendly_urls' ] );
				$sef_url = FALSE;
				$cp = FALSE;
				$filters = FALSE;
				
				$tmp = parse_url( $original_url );
				$query = array();
				
				if ( isset( $tmp[ 'query' ] ) ) {
					
					parse_str( $tmp[ 'query' ], $query );
					
				}
				
				$uri_segments = array(
					
					'submit_forms' => 'index',
					'miid' => 0,
					
					'a' => '',
					'sa' => '',
					'sfid' => '',
					'did' => '',
					's' => '',
					'sfsp' => '',
					'f' => '',
					'ipp' => '',
					'cp' => '',
					'ob' => '',
					'obd' => '',
					
				);
				
				foreach( $segments as $k => $v ) {
					
					if (
						
						isset( $uri_segments[ $v ] )
						
						AND isset( $segments[ $k + 1 ] )
						
					) {
						
						// -------------------------
						// Move these params to query string
						
						if ( $friendly_url AND in_array( $v, array( 'ipp', 'sfsp', 's', 'ob', 'obd' ) ) ) {
							
							$query[ $v ] = $segments[ $k + 1 ];
							
						}
						else {
							
							$uri_segments[ $v ] = $segments[ $k + 1 ];
							
						}
						
					}
					
				}
				
				// -------------------------
				// -------------------------
				// Data List
				
				if ( isset( $uri_segments[ 'a' ] ) AND $uri_segments[ 'a' ] == 'us' AND check_var( $uri_segments[ 'sfid' ] ) ) {
					
					// -------------------------
					// Cleaning up the URI segments
					
					foreach( $uri_segments as $k => & $uri_segment ) {
						
						if ( ! check_var( $uri_segment, TRUE ) OR ( in_array( $k, array( 'f', 'sfsp' ) ) AND $uri_segment == 'W10%3D' ) ) {
							
							$uri_segments[ $k ] = NULL;
							unset( $uri_segments[ $k ] );
							
						}
						else if ( in_array( $k, array( 'f' ) ) ) {
							
							// -------------------------
							// Decoding filters
							
							$uri_segments[ $k ] = $CI->unid->url_decode_ud_filters( $uri_segments[ $k ] );
							
							$filters = $uri_segments[ $k ];
							
							// -------------------------
							// Cleaning up the URL filters
							// Removing items that should be ignored when comparing with menu filters
							
							foreach( $uri_segments[ $k ] as $k2 => $url_filter ) {
								
								if ( isset( $url_filter[ 'ignore_menu' ] ) ) {
									
									$uri_segments[ $k ][ $k2 ] = NULL;
									unset( $uri_segments[ $k ][ $k2 ] );
									
								}
								
							}
							
							$uri_segments[ $k ] = $CI->unid->url_encode_ud_filters( $uri_segments[ $k ] );
							
						}
						else if ( in_array( $k, array( 'cp' ) ) ) {
							
							$cp = ( int ) $uri_segment;
							
						}
						
					}
					
					// -------------------------
					// Cleaning up the query strings
					
					foreach( $query as $k => & $q ) {
						
						if ( ! check_var( $q, TRUE ) OR $q == $CI->unid->url_encode_ud_filters( array() ) ) {
							
							$query[ $k ] = NULL;
							unset( $query[ $k ] );
							
						}
						
					}
					
					// -------------------------
					// -------------------------
					// Now that we have the all final URL params, we search it on DB
					
// 						echo '<pre>' . print_r( $db_search_url, TRUE ) . '</pre>';
						
					if ( $friendly_url ) {
						
						$db_search_url = $uri_segments;
						
						// -------------------------
						// If we have the menu, remove filters from search query
						
						if ( check_var( $uri_segments[ 'miid' ] ) ) {
							
							$db_search_url[ 'f' ] = NULL;
							unset( $db_search_url[ 'f' ] );
							
						}
						else {
							
							$db_search_url[ 'miid' ] = '([0-9]*)';
							
						}
						
						if ( $cp ) {
							
							$db_search_url[ 'cp' ] = '([$])1';
							
						}
						
						$db_search_url = $CI->uri->assoc_to_uri( $db_search_url );
						
						$db_search_query = 'SELECT * FROM tb_urls WHERE target REGEXP \'^' . $db_search_url . '$\' LIMIT 1';
						
// 						echo 'searching for: <pre>' . print_r( $db_search_query, TRUE ) . '</pre>';
						
						// retrieving url
						$db_query = $CI->db->query( $db_search_query );
// 						echo '<pre>' . print_r( $CI->db->_compile_select(), TRUE ) . '</pre>';
						
						$db_url = $db_query->row_array();
						
						if ( isset( $db_url[ 'sef_url' ] ) ) {
							
// 							echo 'we\'ve found: <pre>' . print_r( $db_search_query, TRUE ) . '</pre>';
							
							return ( $cp ? site_url( str_replace( '(:num)', $cp, $db_url[ 'sef_url' ] ) ) : site_url( $db_url[ 'sef_url' ] ) ) . assoc_array_to_qs( $query );
							
						}
						else {
						
// 							echo '<strong>NOT FOUND</strong>: <pre>' . print_r( $db_search_query, TRUE ) . '</pre>';
							
						}
						
// 						echo '<pre>' . print_r( $db_search_url, TRUE ) . '</pre>';
// 						echo '<pre>' . print_r( $uri_segments, TRUE ) . '</pre>';
						
					}
					
// 					echo '<pre>' . print_r( $original_url, TRUE ) . '</pre>';
					
					// -------------------------
					// -------------------------
					// If we have not found the URL on DB, we need to build and insert a new one
					
					// -------------------------
					// Loading UniD API model
					
					if ( ! $CI->load->is_model_loaded( 'menus' ) ) {
						
						$CI->load->model( 'menus_mdl', 'menus' );
						
					}
					
					$menu_item = FALSE;
					
					// -------------------------
					// We have menu ID? Let's get the own's menu
					// Otherwise, let's find it
					
					if ( ! ( check_var( $uri_segments[ 'miid' ] ) AND $menu_item = $CI->menus->get_menu_item( $uri_segments[ 'miid' ] ) ) ) {
						
						$CI->db->from( 'tb_menus' );
						$CI->db->where( 'component_item', 'users_submits' );
						$CI->db->like( 'params', '"submit_form_id":"' . $uri_segments[ 'sfid' ] . '"' );
						
						if ( check_var( $uri_segments[ 'f' ] ) ) {
							
							$uri_segments[ 'f' ] = $CI->unid->url_decode_ud_filters( $uri_segments[ 'f' ] );
							$f_query = '`params` NOT LIKE \'%' . $CI->db->escape_str( '"us_default_results_filters":""', TRUE ) . '%\'';
							
							$CI->db->where( $f_query, NULL, FALSE );
							
						}
						
						$menu_items = $CI->db->get()->result_array();
						
						// -------------------------
						// We've got the menus array? Let's get the "right" menu item
						
						if ( check_var( $menu_items ) ) {
							
							foreach( $menu_items as $_menu_item ) {
								
								if ( check_var( $uri_segments[ 'f' ] ) AND $uri_segments[ 'f' ] AND ( $_menu_item[ 'params' ] = get_params( $_menu_item[ 'params' ] ) ) AND check_var( $_menu_item[ 'params' ][ 'us_default_results_filters' ] ) ) {
									
									$default_filters = get_params( $_menu_item[ 'params' ][ 'us_default_results_filters' ] );
									
									foreach( $default_filters as $k => $default_filter ) {
										
										if ( isset( $default_filter[ 'ignore_menu' ] ) ) {
											
											$default_filters[ $k ] = NULL;
											unset( $default_filters[ $k ] );
											
										}
										
									}
									
									if ( $uri_segments[ 'f' ] === $default_filters ) {
										
										$uri_segments[ 'miid' ] = $_menu_item[ 'id' ];
										
										$filters = $default_filters;
										
										// -------------------------
										// The "right" menu item
										
										$menu_item = $_menu_item;
										
										break;
										
									}
									
								}
								
							}
							
						}
						
					}
					
					if ( $menu_item ) {
						
						$url_path = $sef_url = array();
						$_url_path = $CI->menus->get_path( $uri_segments[ 'miid' ] );
						
						foreach( $_url_path as $k => $seg ) {
							
							$sef_url[ $k ] = $seg[ 'alias' ];
							
						}
						
						$sef_url[] = url_title( $menu_item[ 'alias' ] );
						
						$_url_path = NULL;
						unset( $_url_path );
						
						$final_url = $CI->uri->assoc_to_uri( $uri_segments );
						
					}
					
					if ( $friendly_url AND $sef_url ) {
						
						// -------------------------
						// UniD Data List will have 4 url types:
						// 1: -cp -f
						// 2: -cp +f
						// 3: +cp -f
						// 4: +cp +f
						
						$final_url = $sef_url;
						
						$tb_urls_data[ 'sef_url' ] = join( '/', $sef_url );
						$tb_urls_data[ 'target' ] = $uri_segments;
						
						$tb_urls_data[ 'target' ][ 'cp' ] = NULL;
						unset( $tb_urls_data[ 'target' ][ 'cp' ] );
						
						$tb_urls_data[ 'target' ][ 'f' ] = NULL;
						unset( $tb_urls_data[ 'target' ][ 'f' ] );
						
						
						
						$tb_urls_data[ 'target' ] = $CI->uri->assoc_to_uri( $tb_urls_data[ 'target' ] );
						
						$db_url = $CI->db->query( 'SELECT * FROM tb_urls WHERE target REGEXP \'^' . $tb_urls_data[ 'target' ] . '$\' LIMIT 1' );
						$db_url = $db_url->row_array();
						
						if ( ! ( isset( $db_url[ 'target' ] ) AND $db_url[ 'target' ] == $tb_urls_data[ 'target' ] ) ) {
							
							if ( ! $CI->urls_common_model->insert( $tb_urls_data ) ){
								
								msg( ( 'error_trying_insert_submit_forms_url' ), 'title' );
								log_message( 'error', '[Urls] ' . lang( 'error_trying_insert_submit_forms_url' ) );
								
							}
							
// 							echo '<h1>1</h1><pre>' . print_r( $tb_urls_data, TRUE ) . '</pre>';
							
						}
						
						
						
						
						if ( $filters ) {
							
							$tb_urls_data[ 'target' ] = $uri_segments;
							
							$tb_urls_data[ 'target' ][ 'cp' ] = NULL;
							unset( $tb_urls_data[ 'target' ][ 'cp' ] );
							
							$tb_urls_data[ 'target' ][ 'f' ] = $CI->unid->url_encode_ud_filters( $filters );
							$tb_urls_data[ 'target' ] = $CI->uri->assoc_to_uri( $tb_urls_data[ 'target' ] );
							
							$db_url = $CI->db->query( 'SELECT * FROM tb_urls WHERE target REGEXP \'^' . $tb_urls_data[ 'target' ] . '$\' LIMIT 1' );
							$db_url = $db_url->row_array();
							
							if ( ! ( isset( $db_url[ 'target' ] ) AND $db_url[ 'target' ] == $tb_urls_data[ 'target' ] ) ) {
								
								if ( ! $CI->urls_common_model->insert( $tb_urls_data ) ){
									
									msg( ( 'error_trying_insert_submit_forms_url' ), 'title' );
									log_message( 'error', '[Urls] ' . lang( 'error_trying_insert_submit_forms_url' ) );
									
								}
								
// 								echo '<h1>2</h1><pre>' . print_r( $tb_urls_data, TRUE ) . '</pre>';
								
							}
							
						}
						
						if ( $cp ) {
							
							$tb_urls_data[ 'sef_url' ] = $sef_url;
							$tb_urls_data[ 'sef_url' ][] = '(:num)';
							$tb_urls_data[ 'sef_url' ] = join( '/', $tb_urls_data[ 'sef_url' ] );
							
							$tb_urls_data[ 'target' ] = $uri_segments;
							$tb_urls_data[ 'target' ][ 'cp' ] = '$1';
							
							$tb_urls_data[ 'target' ][ 'f' ] = NULL;
							unset( $tb_urls_data[ 'target' ][ 'f' ] );
							
							$db_search_url = $tb_urls_data[ 'target' ];
							
							$tb_urls_data[ 'target' ] = $CI->uri->assoc_to_uri( $tb_urls_data[ 'target' ] );
							
							$db_search_url[ 'cp' ] = '([$])1';
							
							$db_search_url = $CI->uri->assoc_to_uri( $db_search_url );
							
							$db_url = $CI->db->query( 'SELECT * FROM tb_urls WHERE target REGEXP \'^' . $db_search_url . '$\' LIMIT 1' );
							$db_url = $db_url->row_array();
							
							if ( ! ( isset( $db_url[ 'target' ] ) AND $db_url[ 'target' ] == $tb_urls_data[ 'target' ] ) ) {
								
								if ( ! $CI->urls_common_model->insert( $tb_urls_data ) ){
									
									msg( ( 'error_trying_insert_submit_forms_url' ), 'title' );
									log_message( 'error', '[Urls] ' . lang( 'error_trying_insert_submit_forms_url' ) );
									
								}
								
// 								echo '<h1>3</h1><pre>' . print_r( $tb_urls_data, TRUE ) . '</pre>';
								
							}
							
							$sef_url[] = $cp;
							
							$final_url = $sef_url;
							
							if ( $filters ) {
								
								$tb_urls_data[ 'target' ] = $uri_segments;
								$tb_urls_data[ 'target' ][ 'f' ] = $CI->unid->url_encode_ud_filters( $filters );
								$tb_urls_data[ 'target' ][ 'cp' ] = '$1';
								$tb_urls_data[ 'target' ] = $CI->uri->assoc_to_uri( $tb_urls_data[ 'target' ] );
								
								if ( ! $CI->urls_common_model->insert( $tb_urls_data ) ){
									
									msg( ( 'error_trying_insert_submit_forms_url' ), 'title' );
									log_message( 'error', '[Urls] ' . lang( 'error_trying_insert_submit_forms_url' ) );
									
								}
								
// 								echo '<h1>4</h1><pre>' . print_r( $tb_urls_data, TRUE ) . '</pre>';
								
							}
							
						}
						
						$final_url = join( '/', $final_url );
						
					}
					
				}
				
				return site_url( $final_url ) . assoc_array_to_qs( $query );
				
			}
			
		}
		else if ( $CI->mcm->filtered_system_params[ 'friendly_urls' ] AND 0 === strpos( $original_url, 'articles' ) AND environment() != ADMIN_ALIAS ) {

			if ( ! $CI->load->is_model_loaded( 'articles' ) ) {

				$CI->load->model( 'articles_mdl', 'articles' );

			}

			if ( ! $CI->load->is_model_loaded( 'articles_model' ) ) {

				$CI->load->model( 'admin/articles_model' );

			}
			
			$segments = explode( '/', $original_url );
			
			$component_item = $segments[ 2 ];
			/*
			if ( $component_item == 'article_detail' ){

				$article_id = $segments[ 4 ];

				$gap = array(

					'art_id' => $article_id,
					'limit' => 1,

				);

				$query = $CI->db->query( 'SELECT * FROM tb_urls WHERE target RLIKE \'^articles/index/' . $component_item . '/([0-9]*[0-9])/' . $article_id . '$\' LIMIT 1' );

				$url = $query->row_array();

				// if we not found the url, create new
				if ( empty( $url ) AND $article = $CI->articles->get( $article_id ) ){

					$category = $CI->articles->get_category( $article[ 'category_id' ] );

					if ( ! empty( $category ) ){

						$category_path = '';
						$category_path_array = $CI->articles_model->get_category_path( $article[ 'category_id' ] );

						foreach ( $category_path_array as $key => $value ) {

							$category_path .= $value[ 'alias' ] . '/';

						}

						$category = $category_path . $category[ 'alias' ] . '/';

					}
					else{

						$category = '';

					}

					$tb_urls_data = array(

						'sef_url' => $category . $article[ 'alias' ],
						'target' => $article[ 'url' ]

					);

					if ( $CI->urls_common_model->insert( $tb_urls_data ) === FALSE ){

						msg( ( 'error_trying_insert_' . $component_item . '_url' ), 'title' );
						log_message( 'error', '[Urls] ' . lang( 'error_trying_insert_' . $component_item . '_url' ) );

					}
					else {

						return site_url( $tb_urls_data[ 'sef_url' ] );

					}

				}
				else {

					return ( $url[ 'sef_url' ] == 'default_controller' ? site_url() : site_url( $tb_urls_data[ 'sef_url' ] ) );

				}

			}
			else if ( $component_item == 'articles_list' ){

				//echo '$component_item é: ' . $component_item . '<br />';

				$category = NULL;
				$category_id = $segments[ 4 ];
				$u = isset( $segments[ 5 ] ) ? $segments[ 5 ] : '0';
				$p = isset( $segments[ 6 ] ) ? $segments[ 6 ] : '0';
				$ipp = isset( $segments[ 7 ] ) ? $segments[ 7 ] : '0';

				//echo $original_url . '<br />';
				//print_r( $segments );

				if ( $category_id == '-1' OR $category_id == '0' OR $category = $CI->articles->get_category( $category_id ) ){

					$category_alias = '';

					if ( $category ){

						$category_path = '';
						$category_path_array = $CI->articles_model->get_category_path( $category_id );

						foreach ( $category_path_array as $key => $value ) {

							$category_path .= $value[ 'alias' ] . '/';

						}

						$category_alias = $category_path . $category[ 'alias' ];

					}
					else if ( $category_id == '-1' ){

						$category_alias = lang( 'url_all_articles' );

					}
					else if ( $category_id == '0' ){

						$category_alias = lang( 'url_articles_without_categories' );

					}

					//echo '$category_alias é: ' . $category_alias . '<br />';
					$query = $CI->db->query( 'SELECT * FROM tb_urls WHERE target RLIKE \'^articles/index/' . $component_item . '/([0-9]*[0-9])/' . ( $category_id == '0' ? '0' : $category_id ) . '/([0-9]*[0-9])' . ( isset ( $p ) ? '/' . $p : '' ) . ( isset ( $ipp ) ? '/' . $ipp : '' ) . '$\' LIMIT 1' );

					$url = $query->row_array();

					if ( empty( $url ) ){

						//echo 'chamando article list do url_helper <br />';
						//echo 'p = ' . $p . '<br />';

						$tb_urls_data = array(

							'sef_url' => $category_alias . ( $p ? '/' . $p : '' ),
							'target' => $category[ 'url' ]

						);

						//echo 'target = ' . $tb_urls_data[ 'target' ] . '<br />';

						if ( $CI->urls_common_model->insert( $tb_urls_data ) === FALSE ){

							msg( ( 'error_trying_insert_' . $component_item . '_url' ), 'title' );
							log_message( 'error', '[Urls] ' . lang( 'error_trying_insert_' . $component_item . '_url' ) );

						}
						else {

							return site_url( $tb_urls_data[ 'sef_url' ] );

						}

					}
					else{



						return ( $url[ 'sef_url' ] == 'default_controller' ? site_url() : site_url( $url[ 'sef_url' ] ) );

					}

				}
				else{



				}

			}*/

		}
		else if ( $CI->mcm->filtered_system_params[ 'friendly_urls' ] AND 0 === strpos( $original_url, 'contacts' ) AND environment() != ADMIN_ALIAS ) {

			$CI->load->model(

				array(

					'common/contacts_common_model',

				)

			);

			$segments = explode( '/', $original_url );

			$component_item = $segments[ 2 ];

			if ( $component_item == 'contact_details' ){

				$contact_id = $segments[ 4 ];

				// get contact params
				$gcp = array(

					'where_condition' => 't1.id = ' . $contact_id,
					'limit' => 1,

				 );

				if ( $contact = $CI->contacts_common_model->get_contacts( $gcp )->row_array() ){

					// retrieving url
					$query = $CI->db->query( 'SELECT * FROM tb_urls WHERE target RLIKE \'^contacts/index/' . $component_item . '/([0-9]*[0-9])/' . $contact[ 'id' ] . '$\' LIMIT 1' );
					$url = $query->row_array();

					// retrieving menu item id
					$menu_item_query = $CI->db->query( 'SELECT * FROM tb_menus WHERE type = \'component\' AND component_item = \'' . $component_item . '\' AND params LIKE \'%contact_id":"' . $contact[ 'id' ] . '%\' LIMIT 1' );
					$menu_item = $menu_item_query->row_array();

					if ( empty( $url ) ){

						$f_url = $contact[ 'name' ];

						if ( ! empty( $menu_item ) ){

							$f_url = $menu_item[ 'alias' ];

						}

						$f_url = url_title( $f_url );

						$tb_urls_data = array(

							'sef_url' => $f_url,
							'target' => $CI->contacts_common_model->get_link_contact_details( ( ! empty( $menu_item ) ? $menu_item[ 'id' ] : current_menu_id() ), $contact[ 'id' ] ),

						);

						if ( $CI->urls_common_model->insert( $tb_urls_data ) === FALSE ){

							msg( ( 'error_trying_insert_' . $component_item . '_url' ), 'title' );
							log_message( 'error', '[Urls] ' . lang( 'error_trying_insert_' . $component_item . '_url' ) );

						}
						else {

							return site_url( $tb_urls_data[ 'sef_url' ] );

						}

					}
					else{

						return ( $url[ 'sef_url' ] == 'default_controller' ? site_url() : site_url( $url[ 'sef_url' ] ) );

					}

				}

			}

		}
		if ( $CI->mcm->filtered_system_params[ 'friendly_urls' ] AND array_key_exists( $original_url, $reverse_urls ) ){
		
			//echo 'array_key_exists é: ' . $reverse_urls[ $original_url ] . ' : ' . $original_url . '<br />';
			return site_url( $reverse_urls[ $original_url ] );
			
		}
	}
	
	return site_url( $original_url );
	
}

function url_is_absolute( $str ){
	
	if ( $str ) {
		
		if ( ( substr( $str, 0, 7 ) == 'http://' ) || ( substr( $str, 0, 8 ) == 'https://' ) ) {
			
			return TRUE;
			
		}
		
	}
	
}

function valid_domain( $str ){
	
	if ( $str ) {
		
		if ( ( preg_match("/^(?!\-)(?:[a-zA-Z\d\-]{0,62}[a-zA-Z\d]\.){1,126}(?!\d+)[a-zA-Z\d]{1,63}$/i", $str) //valid chars check
		//if ( ( preg_match("/^([a-z\d](-*[a-z\d])*)(\.([a-z\d](-*[a-z\d])*))*$/i", $str) //valid chars check
			&& preg_match("/^.{1,253}$/", $str) //overall length check
			&& preg_match("/^[^\.]{1,63}(\.[^\.]{1,63})*$/", $str ) ) ) //length of each label )
		{
			
			return TRUE;
			
		}
		
		return FALSE;
		
	}
	
}

function urlencode_RFC_3986( $str ){
	
	if ( $str ) {
		
		$entities = array('%21', '%2A', '%27', '%28', '%29', '%3B', '%3A', '%40', '%26', '%3D', '%2B', '%24', '%2C', '%2F', '%3F', '%25', '%23', '%5B', '%5D');
		$replacements = array('!', '*', "'", "(", ")", ";", ":", "@", "&", "=", "+", "$", ",", "/", "?", "%", "#", "[", "]");
		return str_replace( $entities, $replacements, urlencode( $str) );
		
	}
	
}

function redirect( $uri = '', $method = 'location', $http_response_code = 302, $msg = NULL ){
	
	$CI =& get_instance();
	
	if ( ! $CI->input->get( 'ajax' ) AND ! $CI->input->post( 'ajax', TRUE ) ){
		
		if ( ! preg_match( '#^https?://#i', $uri ) ){
			
			$uri = site_url( $uri );
			
		}
		
		switch( $method ){
			
			case 'refresh' :
				
				header( "Refresh:0;url=" . $uri );
				
				break;
				
			default :
				
				if ( $msg AND gettype( $msg ) === 'array'){
					
					msg( ( $msg[ 'title' ] ), 'title' );
					msg( ( $msg[ 'msg' ] ), $msg[ 'type' ] );
					
				}
				
				header( "Location: " . $uri, TRUE, $http_response_code );
				
				break;
				
		}
		
		exit;
		
	}
	else {
		
		echo 'Redirect: ' . $uri;
		
	}
	
}

function redirect_last_url( $msg = NULL ){

	if ( $msg AND gettype( $msg ) === 'array'){

		redirect( get_last_url(), $method = 'location', 302, $msg );

	}
	else redirect( get_last_url(), $method = 'location', 302 );

}

function set_last_url( $url ){

	$CI =& get_instance();

	if ( ! $CI->input->get( 'ajax' ) ){
		
		$prefix = BASE_URL;
		
		if ( substr( $url, 0, strlen( $prefix ) ) == $prefix ) {
			
			$url = substr( $url, strlen( $prefix ) );
			
		}
		
		$url = ltrim( $url, '/' );
		
		$url = parse_url( $url );
		$url_qs = isset( $url[ 'query' ] ) ? $url[ 'query' ] : NULL;
		$url = isset( $url[ 'path' ] ) ? $url[ 'path' ] : '';
		
		parse_str( $url_qs, $url_qs );
		
		
		if ( $CI->input->get() ) {
			
			$url_qs = array_merge( $url_qs, $CI->input->get() );
			
		}
		else {
			
			$url_qs = $url_qs;
			
		}
		
		$url_qs = assoc_array_to_qs( $url_qs );
		$url = $url . $url_qs;
		
		$env_data = $CI->session->envdata( 'last_url' );
		
		if ( ! is_array( $env_data ) ) {
			
			$env_data = array();
			
		}
		if ( check_var( $CI->current_component[ 'unique_name' ] ) ) {
			
			$env_data[ $CI->current_component[ 'unique_name' ] ] = $url;
			
		}
		
		$CI->session->set_envdata( 'last_url', $env_data );
		
	}
	
}

function get_last_url(){
	
	$CI =& get_instance();
	
	$env_data = $CI->session->envdata( 'last_url' );
	
	if ( is_array( $env_data ) AND check_var( $CI->current_component[ 'unique_name' ] ) AND check_var( $env_data[ $CI->current_component[ 'unique_name' ] ] ) ) {
		
		$env_data = $env_data[ $CI->current_component[ 'unique_name' ] ];
		
	}
	
	return $env_data;

}

function current_url() {

	$CI =& get_instance();

	$url = $CI->config->site_url( $CI->uri->uri_string() );
	return $url . assoc_array_to_qs();

}

function url_title($str, $separator = '-', $lowercase = TRUE){

	if ( $separator == 'dash' OR $separator == '-' )
	{
	    $separator = '-';
	}
	else if ( $separator == 'underscore' OR $separator == '_' )
	{
	    $separator = '_';
	}
	else $separator = '';

	$q_separator = preg_quote($separator);

	$trans = array(
		'á|ã|à|ä|â|å'                     => 'a',
		'é|ẽ|è|ë|ê'                     => 'e',
		'í|ĩ|ì|ï|î'                     => 'i',
		'ó|õ|ò|ö|ô'                     => 'o',
		'ú|ũ|ù|ü|û'                     => 'u',
		'%'                     => 'pc',
		'ç'                     => 'c',
		'&.+?;'                 => '',
		'[^a-z0-9 _-]'          => '',
		'\s+'                   => $separator,
		'('.$q_separator.')+'   => $separator
	);

	$str = strip_tags( $str );

	foreach ($trans as $key => $val)
	{
		$str = preg_replace("#".$key."#i", $val, $str);
	}

	if ( $lowercase === TRUE )
	{
		$str = strtolower($str);
	}

	return trim($str, $separator);

}


// --------------------------------------------------------------------

/**
 * Return a associative array from a given absolute url string
 *
 * @access public
 * @param string
 * @return array
 * @author Frank Souza
 */
/*
function url_string_to_assoc_array( $url_string = NULL ) {
	
	if ( ! isset( $url_string ) ) {
		
		$CI =& get_instance();
		
		// if the url is absolute, return this
		if ( valid_domain( $url_string ) AND url_is_absolute( $url_string ) ) {
			
			return $original_url;
			
		}
		
		$_tmp = explode( '/', $original_url );
		
	}
	
	return array();
	
}*/

// --------------------------------------------------------------------

/**
 * Return a query string from a associative array
 *
 * @access public
 * @param array
 * @return string
 * @author Frank Souza
 */

function assoc_array_to_qs( $array = NULL, $get_query_string = TRUE ) {

	$out = NULL;

	if ( ! isset( $array ) AND $get_query_string ) {

		$CI =& get_instance();

		$array = $CI->input->get();

	}

	if ( is_array( $array ) ) {

		$out = http_build_query( $array, '', '&amp;' );

	}

	return $out ? '?' . $out : '';

}

/* End of file VECMS_url_helper.php */
/* Location: ./application/helpers/VECMS_url_helper.php */
