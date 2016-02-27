<?php

include( "vui_css.php" );
include( "vui_colors.php" );
include( "vui_color.php" );

class Vui{
	
	var $colors = array();
	
	protected $_library_path;
	protected $_svg_files_path = NULL;
	protected $_svg_colors_file = NULL;
	protected $_svg_full_url = NULL;
	
	function __construct( $svg_files_path = NULL, $svg_colors_file = NULL, $svg_full_url = NULL ){
		
		if ( ! defined ( 'DS' ) ) define( 'DS', DIRECTORY_SEPARATOR );
		
		$this->_library_path = pathinfo(__FILE__);
		$this->_library_path = $this->_library_path[ 'dirname' ] . DS;
		
		$this->css = new Vui_css();
		$this->colors = new Vui_colors();
		
		if ( ! $svg_files_path ){
			
			$this->set_svg_files_path( $this->_library_path . 'svg' . DS );
			
		}
		else {
			
			$this->set_svg_files_path( rtrim( $svg_files_path, DS ) . DS );
			
		}
		
		if ( ! $svg_colors_file ){
			
			$this->set_svg_colors_file( $this->_svg_files_path . 'colors.svg' );
			
		}
		else {
			
			$this->set_svg_colors_file( $svg_colors_file );
			
		}
		
		if ( $svg_full_url ){
			
			$this->set_svg_full_url( $svg_full_url );
			
		}
		
		if ( file_exists( $this->_svg_colors_file ) ){
			
			$this->get_colors_from_svg();
			
		}
		
	}
	
	public function __get( $name ) {
		
		if ( isset ( $this->$name ) ) {
			
			return $this->$name;
			
		}
		return NULL;
		
	}
	
	function set_svg_files_path( $path = NULL ){
		
		if ( $path AND is_dir( $path ) ){
			
			$this->_svg_files_path = $path;
			
		}
		
	}
	
	function set_svg_colors_file( $file = NULL ){
		
		if ( $file AND file_exists( $file ) ){
			
			$this->_svg_colors_file = $file;
			
		}
		
	}
	
	function set_svg_full_url( $url = NULL ){
		
		$this->_svg_full_url = $url;
		
		if ( file_exists( $this->_svg_files_path . '.cache' . DIRECTORY_SEPARATOR . $svg_cached ) ){
			
			return $this->_svg_full_url . '/.cache/' . $svg_cached;
			
		}
		else {
			
			// check if cache directory exists, if not, we create it
			if ( ! is_dir( $this->_svg_files_path . '.cache' ) ) {
				
				mkdir( $this->_svg_files_path . '.cache', 0775 );
				
			}
			
		}
		
	}
	
	function svg_file( $file, $color = NULL, $class = NULL ){
		
		if ( $file ){
			
			$file = rtrim( $file, DIRECTORY_SEPARATOR );
			$file = ltrim( $file, DIRECTORY_SEPARATOR );
			
			$file_parts = pathinfo( $file );
			
			$ext = '.svg';
			
			if ( isset( $file_parts[ 'extension' ] ) ){
				
				$file = rtrim( $file, '.' . $file_parts[ 'extension' ] );
				
			}
			
			if ( isset( $file_parts[ 'extension' ] ) AND $file_parts[ 'extension' ] === 'svgz' ){
				
				$ext = '.' . $file_parts[ 'extension' ];
				
			}
			
			$svg_file = $this->_svg_files_path . $file . $ext;
			
			if ( $this->_svg_full_url AND is_dir( $this->_svg_files_path . '.cache' ) AND is_readable( $svg_file ) ) {
				
				if ( $color ){
					
					$svg_cache_file_name = $file . '_' . md5( $color ) . $ext;
					
				}
				else {
					
					$svg_cache_file_name = $file . $ext;
					
				}
				
				if ( is_readable( $this->_svg_files_path . '.cache' . DIRECTORY_SEPARATOR . $svg_cache_file_name ) ){
					
					return $this->_svg_full_url . '/.cache/' . $svg_cache_file_name;
					
				}
				else {
					
					$svg_cache_file = $this->_svg_files_path . '.cache' . DIRECTORY_SEPARATOR . $svg_cache_file_name;
					
					$svg = file_get_contents( $svg_file );
					
					if ( $svg ) {
						
						if ( $color ){
							
							$svg = simplexml_load_string( $svg );
							$this->_change_color( $svg, $color, $class );
							
							$svg = $svg->asXML();
							//$svg = gzencode( $svg, 9 );
							
						}
						
					}
					else {
						
						// blank svg
						$svg = '<?xml version="1.0" encoding="UTF-8" standalone="no"?><svg id="svg2" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns="http://www.w3.org/2000/svg" height="48.0001" width="48" version="1.1" xmlns:cc="http://creativecommons.org/ns#" xmlns:dc="http://purl.org/dc/elements/1.1/"><metadata id="metadata7"><rdf:RDF><cc:Work rdf:about=""><dc:format>image/svg+xml</dc:format><dc:type rdf:resource="http://purl.org/dc/dcmitype/StillImage"/><dc:title/></cc:Work></rdf:RDF></metadata></svg>';
						
					}
					
					$handle = fopen( $svg_cache_file, "w" );
					fwrite( $handle, $svg );
					fclose( $handle );
					
					return $this->_svg_full_url . '/.cache/' . $svg_cache_file_name;
					
				}
				
			}
			else {
				
				$file = $this->_svg_files_path . $file . $ext;
				
				$handle = fopen( $file, "r" );
				$svg = fread( $handle, filesize( $file ) );
				fclose( $handle );
				
				if ( $svg ) {
					
					if ( $color ){
						
						$svg = simplexml_load_string( $svg );
						$this->_change_color( $svg, $color, $class );
						
						$svg = $svg->asXML();
						
					}
					
				}
				else {
					
					$svg = 'PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iVVRGLTgiIHN0YW5kYWxvbmU9Im5vIj8+CjwhLS0gQ3JlYXRlZCB3aXRoIElua3NjYXBlIChodHRwOi8vd3d3Lmlua3NjYXBlLm9yZy8pIC0tPgo8c3ZnIGlkPSJzdmcyIiB4bWxuczpyZGY9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkvMDIvMjItcmRmLXN5bnRheC1ucyMiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgaGVpZ2h0PSIxMDMuODk0NyIgd2lkdGg9IjEwMy44OTQ3IiB2ZXJzaW9uPSIxLjEiIHhtbG5zOmNjPSJodHRwOi8vY3JlYXRpdmVjb21tb25zLm9yZy9ucyMiIHhtbG5zOmRjPSJodHRwOi8vcHVybC5vcmcvZGMvZWxlbWVudHMvMS4xLyI+Cgk8bWV0YWRhdGEgaWQ9Im1ldGFkYXRhNyI+CgkJPHJkZjpSREY+CgkJCTxjYzpXb3JrIHJkZjphYm91dD0iIj4KCQkJCTxkYzpmb3JtYXQ+aW1hZ2Uvc3ZnK3htbDwvZGM6Zm9ybWF0PgoJCQkJPGRjOnR5cGUgcmRmOnJlc291cmNlPSJodHRwOi8vcHVybC5vcmcvZGMvZGNtaXR5cGUvU3RpbGxJbWFnZSIvPgoJCQkJPGRjOnRpdGxlLz4KCQkJPC9jYzpXb3JrPgoJCTwvcmRmOlJERj4KCTwvbWV0YWRhdGE+Cgk8cmVjdCBpZD0icmVjdDI5ODciIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDphY2N1bXVsYXRlO2NvbG9yOiMwMDAwMDA7IiBoZWlnaHQ9IjEwMy44OTQ3IiB3aWR0aD0iMTAzLjg5NDciIHk9IjAiIHg9IjAiIGZpbGw9IiNjODM3MzciLz4KCTxwYXRoIGlkPSJwYXRoMjk4MyIgc3R5bGU9ImVuYWJsZS1iYWNrZ3JvdW5kOmFjY3VtdWxhdGU7Y29sb3I6IzAwMDAwMDsiIGQ9Ik03OC41MDU4MSwyNS4zODg5MSwyNS4zODg5MSw3OC41MDU4MW0wLTUzLjExNjksNTMuMTE2OSw1My4xMTY5IiBzdHJva2U9IiNGRkYiIHN0cm9rZS13aWR0aD0iMSIgZmlsbD0ibm9uZSIvPgo8L3N2Zz4=';
					
				}
				
				return 'data:image/svg+xml;base64,' . base64_encode( $svg );
				
			}
			
		}
		
	}
	
	private function _change_color( &$xml_obj, $color, $class = NULL ){
		
		if ( $class ){
			
			if( gettype( $class ) !== 'string' ){
				
				$class = 'vui_change_color';
				
			}
			
			$elements = $xml_obj->xpath('//*[@class="' . $class . '"]');
			
		}
		else{
			
			$elements = $xml_obj->xpath('//*');
			
		}
		
		foreach ( $elements as $child ){
			
			// linearGradients
			if ( $child->getName() === 'linearGradient' AND $child->count() ){
				
				foreach ( $child as $key => $child_2 ) {
					
					if ( $child_2->getName() === 'stop' ){
						
						if ( isset( $child_2[ 'style' ] ) ){
							
							$properties = explode( ';' , $child_2[ 'style' ] );
							
							foreach ( $properties as $key => $property ) {
								
								$property = explode( ':', $property );
								
								if ( $property[ 0 ] === 'stop-color' ){
									
									$properties[ $key ] = 'stop-color:' . $color;
									
								}
								
							}
							
							$child_2[ 'style' ] = join( ';', $properties );
							
						}
						
					}
					
				}
				
			}
			
			if ( isset( $child[ 'style' ] ) ){
				
				$properties = explode( ';' , $child[ 'style' ] );
				
				foreach ( $properties as $key => $property ) {
					
					$property = explode( ':', $property );
					
					if ( $property[ 0 ] === 'fill' AND $property[ 1 ] !== 'none' ){
						
						$properties[ $key ] = 'fill:' . $color;
						
					}
					if ( $property[ 0 ] === 'stroke' AND $property[ 1 ] !== 'none' ){
						
						$properties[ $key ] = 'stroke:' . $color;
						
					}
					
				}
				
				$child[ 'style' ] = join( ';', $properties );
				
			}
			
		}
		
	}
		
	function multidimensional_array_search($array, $key, $value){
		
		$results = array();
		$this->multidimensional_array_search_recursive($array, $key, $value, $results);
		return $results;
		
	}
	
	function multidimensional_array_search_recursive($array, $key, $value, &$results){
		
		if (!is_array($array)) {
			return;
		}
		
		if (isset($array[$key]) && $array[$key] == $value) {
			$results[] = $array;
		}
		
		foreach ($array as $subarray) {
			$this->multidimensional_array_search_recursive($subarray, $key, $value, $results);
		}
		
	}
	
	function get_content( $URL ){
			
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_URL, $URL);
		$data = curl_exec($ch);
		curl_close($ch);
		return $data;
		
	}
	
	function xmlToArray($xml, $options = array()) {
		
		$defaults = array(
			'namespaceSeparator' => ':',//you may want this to be something other than a colon
			'attributePrefix' => '@',	//to distinguish between attributes and nodes with the same name
			'alwaysArray' => array(),	//array of xml tag names which should always become arrays
			'autoArray' => true,		//only create arrays for tags which appear more than once
			'textContent' => '$',		//key used for the text content of elements
			'autoText' => true,		 //skip textContent key if node has no attributes or child nodes
			'keySearch' => false,		//optional search and replace on tag and attribute names
			'keyReplace' => false		//replace values for above search values (as passed to str_replace())
		);
		$options = array_merge($defaults, $options);
		$namespaces = $xml->getDocNamespaces();
		$namespaces[''] = null; //add base (empty) namespace
	 
		//get attributes from all namespaces
		$attributesArray = array();
		foreach ($namespaces as $prefix => $namespace) {
			foreach ($xml->attributes($namespace) as $attributeName => $attribute) {
				//replace characters in attribute name
				if ($options['keySearch']) $attributeName =
						str_replace($options['keySearch'], $options['keyReplace'], $attributeName);
				$attributeKey = $options['attributePrefix']
						. ($prefix ? $prefix . $options['namespaceSeparator'] : '')
						. $attributeName;
				$attributesArray[$attributeKey] = (string)$attribute;
			}
		}
	 
		//get child nodes from all namespaces
		$tagsArray = array();
		foreach ($namespaces as $prefix => $namespace) {
			foreach ($xml->children($namespace) as $childXml) {
				//recurse into child nodes
				$childArray = $this->xmlToArray($childXml, $options);
				list($childTagName, $childProperties) = each($childArray);
	 
				//replace characters in tag name
				if ($options['keySearch']) $childTagName =
						str_replace($options['keySearch'], $options['keyReplace'], $childTagName);
				//add namespace prefix, if any
				if ($prefix) $childTagName = $prefix . $options['namespaceSeparator'] . $childTagName;
	 
				if (!isset($tagsArray[$childTagName])) {
					//only entry with this key
					//test if tags of this type should always be arrays, no matter the element count
					$tagsArray[$childTagName] =
							in_array($childTagName, $options['alwaysArray']) || !$options['autoArray']
							? array($childProperties) : $childProperties;
				} elseif (
					is_array($tagsArray[$childTagName]) && array_keys($tagsArray[$childTagName])
					=== range(0, count($tagsArray[$childTagName]) - 1)
				) {
					//key already exists and is integer indexed array
					$tagsArray[$childTagName][] = $childProperties;
				} else {
					//key exists so convert to integer indexed array with previous value in position 0
					$tagsArray[$childTagName] = array($tagsArray[$childTagName], $childProperties);
				}
			}
		}
	 
		//get text content of node
		$textContentArray = array();
		$plainText = trim((string)$xml);
		if ($plainText !== '') $textContentArray[$options['textContent']] = $plainText;
	 
		//stick it all together
		$propertiesArray = !$options['autoText'] || $attributesArray || $tagsArray || ($plainText === '')
				? array_merge($attributesArray, $tagsArray, $textContentArray) : $plainText;
	 
		//return node as array
		return array(
			$xml->getName() => $propertiesArray
		);
		
	}
	
	
	function hex2rgb( $hex, $rgba = FALSE ) {
		
		$hex = str_replace("#", "", $hex);
		
		$a = 'ff';
		
		if( strlen( $hex ) == 3 ) {
			
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
			
		}
		else if( strlen( $hex ) > 6 ) {
			
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
			$a = hexdec( substr( $hex, 6, 2 ) ) / 255;
			
			$a = number_format( ( float )$a, 4, '.', '' );
			
		}
		else {
			
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
			
		}
		
		if ( $rgba ){
			
			$return = array( $r, $g, $b, $a );
			
		}
		else{
			
			$return = array( $r, $g, $b );
			
		}
		
		return implode( ", ", $return );
		
	}
	
	function get_colors_from_svg(){
		
		if ( file_exists( $this->_svg_colors_file ) ){
			
			$svg = file_get_contents( $this->_svg_colors_file );
			
			$svg = simplexml_load_string( $svg );
			
			$svg = $this->xmlToArray( $svg );
			
			//print_r( $svg );
			
			$vui_colors = array();
			
			$vui_colors_tmp = $this->multidimensional_array_search( $svg, '@inkscape:label', 'vui_color' );
			
			foreach ( $vui_colors_tmp as $key => $vui_color ) {
				
				$default_opacity_index = 255;
				
				$styles = explode( ';', $vui_color[ '@style' ] );
				
				foreach ( $styles as $key => $style ) {
					
					$style = explode( ':', $style );
					
					if ( $style[ 0 ] === 'fill-opacity' ){
						
						$default_opacity_index = $style[ 1 ] * 255;
						
					}
					
				}
				
				foreach ( $styles as $key => $style ) {
					
					$style = explode( ':', $style );
					
					if ( $style[ 0 ] === 'fill' ){
						
						// color name
						$cn = $vui_color[ '@id' ];
						
						$this->colors->{ $cn } = new Vui_color( $style[ 1 ], $default_opacity_index );
						
					}
					
				}
				
			}
			
		}
		
	}
	
}

/*
$vui = new Vui( 'http://localhost:8080/projetos/viacms/themes/admin/default/assets/images/icons/' );

print_r($vui);
*/
