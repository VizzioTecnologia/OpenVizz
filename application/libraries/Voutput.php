<?php

class Voutput{

	protected $_buffer = array();
	protected $_head_title = NULL;
	protected $environment;

	// --------------------------------------------------------------------

	public function __construct( $config = array() ) {

		$this->CI = & get_instance();

		$this->environment = $this->CI->mcm->environment;

		$this->_buffer[ 'head' ][ 'title' ] = '';
		$this->_buffer[ 'head' ][ 'meta' ] = array();
		$this->_buffer[ 'head' ][ 'favicons' ] = array();
		$this->_buffer[ 'head' ][ 'stylesheets' ] = array();
		$this->_buffer[ 'head' ][ 'stylesheets_declarations' ] = array();
		$this->_buffer[ 'head' ][ 'scripts' ] = array();
		$this->_buffer[ 'head' ][ 'scripts_declarations' ] = array();
		$this->_buffer[ 'body_start' ][ 'scripts_declarations' ] = array();
		$this->_buffer[ 'content' ] = array();
		$this->_buffer[ 'body_end' ][ 'scripts_declarations' ] = array();
		$this->_buffer[ 'body_end' ][ 'scripts' ] = array();

	}
	
	// --------------------------------------------------------------------
	
	public function get_buffer(){
		
		return $this->_buffer;
		
	}
	
	// --------------------------------------------------------------------

	public function set_head_title( $value = NULL ){

		if ( $value AND is_string( $value ) ){
			
			$this->_head_title = $value;
			
			$this->_buffer[ 'head' ][ 'title' ] = '<title>' . $this->_head_title . '</title>';
			
			return TRUE;
			
		}

		return FALSE;

	}

	// --------------------------------------------------------------------

	public function append_favicon( $file = NULL, $rel = NULL, $sizes = NULL, $prefix = '<link ', $sufix = " >\n" ){

		if ( $file ){

			$file_name = $file;

			$file = call_user_func( $this->environment . '_theme_path' ) . $file;
			$file = file_exists( $file ) ? $file : FALSE;

			$rel = ( $rel AND is_string( $rel ) ) ? $rel : 'shortcut icon';

			if ( $file ){

				$file_url = call_user_func( $this->environment . '_theme_url' ) . '/' . $file_name;

				if ( isset( $this->_buffer[ 'head' ][ 'favicons' ][ $rel . $sizes ] ) ){

					$this->_buffer['head']['favicons'][ $rel . $sizes ] .=	 '<link ' . ( $rel ? 'rel="' . $rel . '" ' : '' ) . ( $sizes ? 'sizes="' . $sizes . '" ' : '' ) . 'href="' . $file_url . '" >' . "\n";

				}
				else {

					$this->_buffer['head']['favicons'][ $rel . $sizes ] =	 '<link ' . ( $rel ? 'rel="' . $rel . '" ' : '' ) . ( $sizes ? 'sizes="' . $sizes . '" ' : '' ) . 'href="' . $file_url . '" >' . "\n";

				}

				return TRUE;

			}

		}

		return FALSE;

	}

	// --------------------------------------------------------------------

	public function append_head_meta( $name = NULL, $value = NULL, $prefix = '<meta ', $sufix = " >\n" ){

		if ( $name AND $value AND is_string( $value ) ){

			if ( isset( $this->_buffer[ 'head' ][ 'meta' ][ $name ] ) ){

				$this->_buffer[ 'head' ][ 'meta' ][ $name ] .= $prefix . $value . $sufix;

			}
			else {

				$this->_buffer[ 'head' ][ 'meta' ][ $name ] = $prefix . $value . $sufix;

			}

			return TRUE;

		}

		return FALSE;

	}

	// --------------------------------------------------------------------

	public function append_head_stylesheet( $name = NULL, $url = NULL, $prefix = '<link href="', $sufix = "\" rel=\"stylesheet\" type=\"text/css\" >\n" ){

		if ( $name AND $url AND is_string( $url ) ){

			if ( isset( $this->_buffer[ 'head' ][ 'stylesheets' ][ $name ] ) ){

				$this->_buffer[ 'head' ][ 'stylesheets' ][ $name ] .= $prefix . $url . $sufix;

			}
			else {

				$this->_buffer[ 'head' ][ 'stylesheets' ][ $name ] = $prefix . $url . $sufix;

			}

			return TRUE;

		}

		return FALSE;

	}
	
	// --------------------------------------------------------------------
	
	public function get_head_stylesheet( $name = NULL ){
		
		if ( $name AND isset( $this->_buffer[ 'head' ][ 'stylesheets' ][ $name ] ) ){
			
			return $this->_buffer[ 'head' ][ 'stylesheets' ][ $name ];
			
		}
		
		return FALSE;
		
	}
	
	// --------------------------------------------------------------------
	
	public function unset_head_stylesheet( $name = NULL ){
		
		if ( $name AND isset( $this->_buffer[ 'head' ][ 'stylesheets' ][ $name ] ) ){
			
			unset( $this->_buffer[ 'head' ][ 'stylesheets' ][ $name ] );
			
			if ( ! isset( $this->_buffer[ 'head' ][ 'stylesheets' ][ $name ] ) ) {
				
				return TRUE;
				
			}
			
		}
		
		return FALSE;
		
	}
	
	// --------------------------------------------------------------------

	public function append_head_stylesheet_declaration( $name = NULL, $value = NULL, $prefix = '<style type="text/css">', $sufix = "</style>\n" ){

		if ( $name AND $value AND is_string( $value ) ){

			if ( isset( $this->_buffer[ 'head' ][ 'stylesheets_declarations' ][ $name ] ) ){

				$this->_buffer[ 'head' ][ 'stylesheets_declarations' ][ $name ] .= $prefix . $value . $sufix;

			}
			else {

				$this->_buffer[ 'head' ][ 'stylesheets_declarations' ][ $name ] = $prefix . $value . $sufix;

			}

			return TRUE;

		}

		return FALSE;

	}

	// --------------------------------------------------------------------

	public function append_head_script( $name = NULL, $url = NULL, $prefix = '<script src="', $sufix = "\" type=\"text/javascript\" ></script>\n" ){

		if ( $name AND $url AND is_string( $url ) ){

			if ( isset( $this->_buffer[ 'head' ][ 'scripts' ][ $name ] ) ){

				$this->_buffer[ 'head' ][ 'scripts' ][ $name ] .= $prefix . $url . $sufix;

			}
			else {

				$this->_buffer[ 'head' ][ 'scripts' ][ $name ] = $prefix . $url . $sufix;

			}

			return TRUE;

		}

		return FALSE;

	}

	// --------------------------------------------------------------------

	public function append_head_script_declaration( $name = NULL, $value = NULL, $prefix = '<script type="text/javascript" >', $sufix = "</script>\n" ){

		if ( $name AND $value AND is_string( $value ) ){

			if ( isset( $this->_buffer[ 'head' ][ 'scripts_declarations' ][ $name ] ) ){

				$this->_buffer[ 'head' ][ 'scripts_declarations' ][ $name ] .= $prefix . $value . $sufix;

			}
			else {

				$this->_buffer[ 'head' ][ 'scripts_declarations' ][ $name ] = $prefix . $value . $sufix;

			}

			return TRUE;

		}

		return FALSE;

	}

	// --------------------------------------------------------------------

	public function append_body_start_script_declaration( $name = NULL, $value = NULL, $prefix = '<script type="text/javascript" >', $sufix = "</script>\n" ){

		if ( $name AND $value AND is_string( $value ) ){

			if ( isset( $this->_buffer[ 'body_start' ][ 'scripts_declarations' ][ $name ] ) ){

				$this->_buffer[ 'body_start' ][ 'scripts_declarations' ][ $name ] .= $prefix . $value . $sufix;

			}
			else {

				$this->_buffer[ 'body_start' ][ 'scripts_declarations' ][ $name ] = $prefix . $value . $sufix;

			}

			return TRUE;

		}

		return FALSE;

	}
	
	// --------------------------------------------------------------------

	public function append_body_end_script( $name = NULL, $url = NULL, $prefix = '<script src="', $sufix = "\" type=\"text/javascript\" ></script>\n" ){

		if ( $name AND $url AND is_string( $url ) ){

			if ( isset( $this->_buffer[ 'body_end' ][ 'scripts' ][ $name ] ) ){

				$this->_buffer[ 'body_end' ][ 'scripts' ][ $name ] .= $prefix . $url . $sufix;

			}
			else {

				$this->_buffer[ 'body_end' ][ 'scripts' ][ $name ] = $prefix . $url . $sufix;

			}

			return TRUE;

		}

		return FALSE;

	}

	// --------------------------------------------------------------------

	public function append_body_end_script_declaration( $name = NULL, $value = NULL, $prefix = '<script type="text/javascript" >', $sufix = "</script>\n" ){

		if ( $name AND $value AND is_string( $value ) ){

			if ( isset( $this->_buffer[ 'body_end' ][ 'scripts_declarations' ][ $name ] ) ){

				$this->_buffer[ 'body_end' ][ 'scripts_declarations' ][ $name ] .= $prefix . $value . $sufix;

			}
			else {

				$this->_buffer[ 'body_end' ][ 'scripts_declarations' ][ $name ] = $prefix . $value . $sufix;

			}

			return TRUE;

		}

		return FALSE;

	}
	
	// --------------------------------------------------------------------

	public function append_content( $name = NULL, $value = NULL ){

		if ( isset( $name ) AND is_string( $name ) AND isset( $value ) AND is_string( $value ) ){

			if ( isset( $this->_buffer[ 'content' ][ $name ] ) ){

				$this->_buffer[ 'content' ][ $name ] .= $value;

			}
			else {

				$this->_buffer[ 'content' ][ $name ] = $value;

			}

			return TRUE;

		}
		else if ( ( isset( $name ) AND is_string( $name ) AND ! isset( $value ) ) OR ( ! isset( $name ) AND isset( $value ) AND is_string( $value ) ) ){

			if ( isset( $value ) AND is_string( $value ) ) {

				$this->_buffer[ 'content' ][] = $value;

			}
			else {

				$this->_buffer[ 'content' ][] = $name;

			}

			return TRUE;

		}

		return FALSE;

	}

	// --------------------------------------------------------------------

	public function set_content( $value = NULL ){

		if ( isset( $value ) AND is_string( $value ) ){

			$this->_buffer[ 'content' ] = array();

			$this->_buffer[ 'content' ][] = ( string ) $value;

			return TRUE;

		}

		return FALSE;

	}

	// --------------------------------------------------------------------

	public function get_content( $name = NULL ){

		if ( isset( $name ) AND is_string( $name ) AND isset( $this->_buffer[ 'content' ][ $name ] ) ){

			$return = ( string ) $this->_buffer[ 'content' ][ $name ];

		}
		else if ( isset( $name ) AND ( ! is_string( $name ) OR ! isset( $this->_buffer[ 'content' ][ $name ] ) ) ){

			$return = ( string ) $this->_buffer[ 'content' ][ $name ];

		}
		else {

			$return = $this->_get_string_from_array( $this->_buffer[ 'content' ] );

		}

		return $return;

	}

	// --------------------------------------------------------------------

	public function get_head_title(){

		return strip_tags( $this->_head_title );

	}

	// --------------------------------------------------------------------

	public function get_body_start(){

		return $this->_get_string_from_array( $this->_buffer[ 'body_start' ] );

	}

	// --------------------------------------------------------------------

	public function get_body_start_array(){

		return $this->_buffer[ 'body_start' ];

	}

	// --------------------------------------------------------------------

	public function get_body_end(){
		
		return $this->_get_string_from_array( $this->_buffer[ 'body_end' ] );

	}

	// --------------------------------------------------------------------

	public function get_head(){
		
		return $this->_get_string_from_array( $this->_buffer[ 'head' ] );

	}

	// --------------------------------------------------------------------

	public function get_head_array(){

		return $this->_buffer[ 'head' ];

	}

	// --------------------------------------------------------------------

	protected function _get_string_from_array( $array = NULL ){
		
		if( is_array( $array ) AND ! empty( $array ) ){
			
			$output = '';
			
			// keeping the stuff in order
			
			$_array = array(
				
				'meta' => NULL,
				'title' => NULL,
				'favicons' => NULL,
				'stylesheets' => NULL,
				'stylesheets_declarations' => NULL,
				'scripts' => NULL,
				'scripts_declarations' => NULL,
				
			);
			
			if ( isset( $array[ 'scripts' ] ) ) {
				
				$_array[ 'scripts' ] = $array[ 'scripts' ];
				unset( $array[ 'scripts' ] );
				
			}
			
			$array = array_merge( $_array, $array );
			
// 			echo '<pre>' . htmlspecialchars( print_r( $array, TRUE ) ) . '</pre>'; exit;
			
			foreach ( $array as $key => $item ) {
				
				if ( is_string( $item ) ){
					
					$output .= $item;
					
				}
				else if ( is_array( $item ) ){
					
					$output .= $this->_get_string_from_array( $item );
					
				}
				
			}
			
			return $output;
			
		}
		else return '';
		
	}
	
}
