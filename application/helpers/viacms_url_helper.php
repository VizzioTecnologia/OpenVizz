<?php  if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

function get_url( $original_url = NULL, $itemid = NULL ){
	
	if ( ! $original_url ) {
		
		return base_url();
		
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
		
		if ( $CI->mcm->filtered_system_params[ 'friendly_urls' ] AND array_key_exists( $original_url, $reverse_urls ) ){
		
			//echo 'array_key_exists é: ' . $reverse_urls[ $original_url ] . ' : ' . $original_url . '<br />';
			return base_url() . $reverse_urls[ $original_url ];
			
		}
		else if ( 0 === strpos( $original_url, 'submit_forms' ) ) {
			
			$segments = explode( '/', $original_url );
			
			if ( in_array( 'index', $segments ) ) {
				
				$action = NULL;
				$submit_form_id = NULL;
				$filters_on = NULL;
				
				foreach( $segments as $k => $v ) {
					
					if (
						
						$v == 'a'
						AND isset( $segments[ $k + 1 ] )
						
					) {
						
						$action = $segments[ $k + 1 ];
						
					}
					
					if (
						
						$v == 'sfid'
						AND isset( $segments[ $k + 1 ] )
						
					) {
						
						$submit_form_id = $segments[ $k + 1 ];
						
					}
					
					if (
						
						(
							
							$v == 'sfsp'
							AND isset( $segments[ $k + 1 ] )
							
						)
						
						OR (
							
							$v == 'f'
							AND isset( $segments[ $k + 1 ] )
							
						)
						
						OR (
							
							$v == 's'
							AND isset( $segments[ $k + 1 ] )
							
						)
						
					) {
						
						$filters_on = TRUE;
						
					}
					
				}
				
				
				if ( $action == 'sf' AND $submit_form_id ) {
					
					$CI->db->from( 'tb_menus' );
					$CI->db->where( 'component_item', 'submit_form' );
					$CI->db->like( 'params', '"submit_form_id":"' . $submit_form_id . '"' );
					$CI->db->limit( 1 );
					
					$menu_item = $CI->db->get();
					
					if ( $menu_item->num_rows() > 0 ) {
						
						$menu_item = $menu_item->row_array();
						
						return base_url() . $menu_item[ 'link' ];
						
					}
					
				}
				else if ( $action == 'us' AND $submit_form_id AND ! $filters_on ) {
					
					$CI->db->from( 'tb_menus' );
					$CI->db->where( 'component_item', 'users_submits' );
					$CI->db->like( 'params', '"submit_form_id":"' . $submit_form_id . '"' );
					$CI->db->limit( 1 );
					
					$menu_item = $CI->db->get();
					
					if ( $menu_item->num_rows() > 0 ) {
						
						$menu_item = $menu_item->row_array();
						
						return base_url() . $menu_item[ 'link' ];
						
					}
					
				}
				
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

					if ( $CI->users->insert( $tb_urls_data ) === FALSE ){

						msg( ( 'error_trying_insert_' . $component_item . '_url' ), 'title' );
						log_message( 'error', '[Urls] ' . lang( 'error_trying_insert_' . $component_item . '_url' ) );

					}
					else {

						return $tb_urls_data[ 'sef_url' ];

					}

				}
				else {

					return ( $url[ 'sef_url' ] == 'default_controller' ? base_url() : $url[ 'sef_url' ] );

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

						if ( $CI->users->insert( $tb_urls_data ) === FALSE ){

							msg( ( 'error_trying_insert_' . $component_item . '_url' ), 'title' );
							log_message( 'error', '[Urls] ' . lang( 'error_trying_insert_' . $component_item . '_url' ) );

						}
						else {

							return base_url() . $tb_urls_data[ 'sef_url' ];

						}

					}
					else{



						return ( $url[ 'sef_url' ] == 'default_controller' ? base_url() : $url[ 'sef_url' ] );

					}

				}
				else{



				}

			}

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

						if ( $CI->users->insert( $tb_urls_data ) === FALSE ){

							msg( ( 'error_trying_insert_' . $component_item . '_url' ), 'title' );
							log_message( 'error', '[Urls] ' . lang( 'error_trying_insert_' . $component_item . '_url' ) );

						}
						else {

							return $tb_urls_data[ 'sef_url' ];

						}

					}
					else{

						return ( $url[ 'sef_url' ] == 'default_controller' ? base_url() : $url[ 'sef_url' ] );

					}

				}

			}

		}

	}
	
	return base_url() . $original_url;
	
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

	if ( ! $CI->input->get( 'ajax' ) ){

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
		
		$CI->session->set_envdata( 'last_url', $url . $url_qs );
		
	}
	
}

function get_last_url(){

	$CI =& get_instance();

	return $CI->session->envdata( 'last_url' );

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
	
}

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