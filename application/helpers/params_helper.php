<?php  if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );


function get_params( $attributes = NULL, $ignore_empty_values = FALSE ){
	
	if ( is_array( $attributes ) ) {
		
		if ( $ignore_empty_values ) {
			
			array_clean_empty_values( $attributes );
			
		}
		
		return $attributes;
		
	}
	else if ( $attributes = get_json_array( $attributes ) ){
		
		if ( $ignore_empty_values ) {
			
			array_clean_empty_values( $attributes );
			
		}
		
		return array_merge( array(), $attributes );
		
	}
	else if ( is_string( $attributes ) AND strpos( $attributes, '=' ) === FALSE ){
		
		$attributes = explode( "\n", $attributes );
		$array = array();
		$array2 = array();
		
		reset( $attributes );
		
		while ( list( $key, $value ) = each( $attributes ) ) {
			
			$array2 = explode( "=", $value );
			$array2[ 1 ] = trim( isset( $array2[ 1 ] ) ? ( string ) $array2[ 1 ] : '' );
			
			if ( $value != '' AND ! $ignore_empty_values ) {
				
				$array[ $array2[ 0 ] ] = trim( $array2[ 1 ] );
				
			}
			
		}
		
		/*
		foreach ( $attributes as $value ) {
			
			$array2 = explode( "=", $value );
			$array2[ 1 ] = trim( isset( $array2[ 1 ] ) ? ( string ) $array2[ 1 ] : '' );
			
			if ( $value != '' AND ! $ignore_empty_values ) {
				
				$array[ $array2[ 0 ] ] = trim( $array2[ 1 ] );
				
			}
			
		}
		*/
		return array_merge( array(), $array );
		
	}
	else {
		
		return array();
		
	}
	
}
function get_json_params( $attributes = NULL ){
	if ( $attributes ){
		$array = json_decode( $attributes, TRUE );
		return $array;
	}
}


// $base_params são os parâmetros que serão usados caso algum parâmetro em $params seja global
function filter_params( $base_params, $params, $keep_blank = FALSE ){
	
	$_final_params = $base_params;
	
	//echo '<strong>$base_params</strong> = <pre>' . print_r( $base_params, TRUE ) . '</pre><br/>';
	
	//echo '<strong>$params</strong> = <pre>' . print_r( $params, TRUE ) . '</pre><br/>';
	
	if ( is_array( $base_params ) AND is_array( $params ) ){
		
		while ( list( $key, $param ) = each( $params ) ) {
			
			$_final_params[ $key ] = $param;
			
			if ( ! isset( $param ) OR $param == '' AND ! $keep_blank AND isset( $base_params[ $key ] ) ) {
				
				$_final_params[ $key ] = $param;
				
			}
			
			if ( is_array( $param ) AND isset( $base_params[ $key ] ) AND is_array( $base_params[ $key ] ) ) {
				
				$_final_params[ $key ] = filter_params( $base_params[ $key ], $param, $keep_blank );
				
			}
			else if ( ( ! isset( $param ) OR $param == 'global' OR ( $param == '' AND ! $keep_blank ) ) AND isset( $base_params[ $key ] ) ) {
				
				$_final_params[ $key ] = $base_params[ $key ];
				
			}
			else if ( ! isset( $base_params[ $key ] ) ) {
				
				$_final_params[ $key ] = $param;
				
			}
			
		}
		
		/*
		foreach ( $params as $key => $param ) {
			
			$_final_params[ $key ] = $param;
			
			if ( ! isset( $param ) OR $param == '' AND ! $keep_blank AND isset( $base_params[ $key ] ) ) {
				
				$_final_params[ $key ] = $param;
				
			}
			
			if ( is_array( $param ) AND isset( $base_params[ $key ] ) AND is_array( $base_params[ $key ] ) ) {
				
				$_final_params[ $key ] = filter_params( $base_params[ $key ], $param, $keep_blank );
				
			}
			else if ( ( ! isset( $param ) OR $param == 'global' OR ( $param == '' AND ! $keep_blank ) ) AND isset( $base_params[ $key ] ) ) {
				
				$_final_params[ $key ] = $base_params[ $key ];
				
			}
			else if ( ! isset( $base_params[ $key ] ) ) {
				
				$_final_params[ $key ] = $param;
				
			}
			
		}
		*/
		
	}
	
	//echo '<strong>$_final_params</strong> = <pre>' . print_r( $_final_params, TRUE ) . '</pre><br/>';
	
	return $_final_params;
	
}


// Obtém um array de especificações de elementos a partir de um XML
function get_params_spec_from_xml( $xml_file = NULL ){
	
	if ( $xml_file ){
		$params = array();
		
		$xml = simplexml_load_file( $xml_file );
		
		if ( $xml->getName() == 'params' ){
			$params['params_spec'] = $params['params_spec_values'] = array();
		}
		
		foreach( $xml->children() as $children ){
			
			//echo 'nome da sessao: '.( string )$children['name'].'<br/>';
			
			$children_name = ( string )$children['name'];
			$children_icon = ( string )$children['icon'];
			$params['params_spec'][$children_name] = array();
			$i = 0;
			
			foreach( $children->children() as $param_1 ){
				
				//echo 'elemento sendo analisado: '.( string )$param_1['name'].'<br/>';
				
				// obtendo os atributos para facilitar a leitura do desenvolvedor e reduzir a codificação
				$type = ( string )$param_1['type'];
				$name = ( string )$param_1['name'];
				$_id = $name ? $name : $i;
				$value = ( string )$param_1['value'];
				$default = ( string )$param_1['default'];
				$label = ( string )$param_1['label'];
				$level = ( string )$param_1['level'];
				$tip = ( string )$param_1['tip'];
				$inline = ( string )$param_1['inline'];
				$translate = ( bool )$param_1['translate'];
				$ext_tip = ( string )$param_1['ext_tip'];
				$class = ( string )$param_1['class'];
				$editor = ( string )$param_1['editor'];
				$maxlength = ( string )$param_1['maxlength'];
				$content = ( string )$param_1['content'];
				
				$disabled = ( string )$param_1['disabled'];
				$readonly = ( string )$param_1['readonly'];
				$size = ( string )$param_1['size'];
				
				$min = isset( $param_1['min'] ) ? ( int )$param_1['min'] : FALSE;
				$max = isset( $param_1['max'] ) ? ( int )$param_1['max'] : FALSE;
				$pattern = ( string )$param_1['pattern'];
				$step = isset( $param_1['step'] ) ? ( int )$param_1['step'] : FALSE;
				
				//echo $default;
				
				$_new = array(
					
					'type' => $type,
					'name' => $name,
					'value' => $value,
					'default' => $default,
					'label' => $label,
					'level' => $level,
					'tip' => $tip,
					'inline' => $inline,
					'class' => $class,
					'editor' => $editor,
					'maxlength' => $maxlength,
					'content' => $content,
					
					'disabled' => $disabled,
					'readonly' => $readonly,
					'size' => $size,
					
					'min' => $min,
					'max' => $max,
					'pattern' => $pattern,
					'step' => $step,
					
				);
				
				if ( $type == 'select' ){
					
					foreach( $param_1->children() as $param_2 ){
						
						if ( $param_2->getName() == 'option' ){
							
							$param_2_name = 'options';
							
							$_new[$param_2_name][( string )$param_2['value']] = ( string )$param_2;
						}
						else if ( $param_2->getName() == 'validation' ){
							
							$param_2_name = 'validation';
							
							$_new[$param_2_name]['rules'] = ( string )$param_2['rules'];
							foreach( $param_2->children() as $message ){
								
								if ( $message->getName() == 'message' ){
									
									$_new[$param_2_name]['messages'] = array( 
										'rule' => ( string )$message['rule'],
										'message' => ( string )$message,
									 );
								}
								
							}
						}
						
					}
					
				}
				else if ( $type == 'php' ){
					
					if ( ! isset( $CI ) ){
						
						$CI =& get_instance();
						
					}
					
					eval( ( string ) $param_1 );
					
				}
				else{
					
					foreach( $param_1->children() as $param_2 ){
						
						if ( $param_2->getName() == 'validation' ){
							
							$param_2_name = 'validation';
							
							$_new[$param_2_name]['rules'] = ( string )$param_2['rules'];
							
						}
						
					}
					
				}
				
				// ----------------------------------------------------------
				// criando o array final com os valores padrões
				
				$key = $_new['name'];
				
				$params['params_spec'][$children_name][] = $_new;
				
				// definindo o valor padrão, que pode variar conforme o tipo de elemento
				// por exemplo, campos de textos utilizam o atributo default como valor, radio e checkbox utilizam o value juntamente com o default, o qual indica se o campo estará marcado ou não
				
				// iniciando o valor final
				$final_value = $translate ? lang( $default ) : $default;
				
				// se o elemento for do tipo checkbox ou radio
				if ( $type == 'radio' OR $type == 'checkbox' ){
					
					// checkbox e radio inputs utilizam o value como valor, se somente estiverem marcados
					// portanto atribuimos seus valores se $value possuir conteudo
					$final_value = $default ? $value : FALSE;
					
				}
				
				// se já existir uma chave no array com o mesmo name, quer dizer que o campo é um array
				// ATENÇÃO! até o momento, campos em array são suportados apenas para o tipos checkbox, veja que está incluso checkbox no final da condição abaixo
				if ( key_exists( $key, $params['params_spec_values'] ) AND $final_value AND ( $type == 'checkbox' ) ){
					
					// se a chave possuir conteúdo e não for do tipo array, convertemos este em um
					// a primeira vez que esta chave possui valor, ela é do tipo string
					// em uma segunda análise pela chave, vemos que ela já existe, logo a convertemos em array
					if ( $params['params_spec_values'][$key] AND ! is_array( $params['params_spec_values'][$key] ) ){
						$params['params_spec_values'][$key] = ( array ) $params['params_spec_values'][$key];
					}
					
					// caso contrário, se for um checkbox, adicionamos ao array seus valores
					else{
						
						$params['params_spec_values'][$key][$final_value] = $final_value;
						
					}
					
				}
				// caso contrário, se não existir a chave, atribui esta
				// para todos os outros tipos de campos
				else if ( ! key_exists( $key, $params['params_spec_values'] ) ){
					$params['params_spec_values'][$key] = $final_value;
				}
				
				$i++;
				
			}
			
		}
		
		//echo '<pre>' . print_r( $params, TRUE ) . '</pre>';
		
		//echo '<br/><br/>';
		//print_r( $params );
		
		return $params;
	}
}

function params_to_string( $data = NULL ){
	
	if ( $data ){
		$output = '';
		
		while ( list( $key, $value ) = each( $data ) ) {
			
			if ( strpos( $key,PARAM_PREFIX ) !== FALSE ) {
				
				if ( is_array( $value ) ) {
					
					$output .= str_replace( PARAM_PREFIX, '', $key ).'[]='.( implode( '|', $value ) )."\n";
				}
				else {
					
					$output .= str_replace( PARAM_PREFIX, '', $key ).'='.$value."\n";
				}
			}
			
		}
		
		/*
		foreach ( $data as $key => $value ) {
			
			if ( strpos( $key,PARAM_PREFIX ) !== FALSE ){
				if ( is_array( $value ) ){
					$output .= str_replace( PARAM_PREFIX, '', $key ).'[]='.( implode( '|', $value ) )."\n";
				}
				else{
					$output .= str_replace( PARAM_PREFIX, '', $key ).'='.$value."\n";
				}
			}
			
		}
		*/
		return $output;
	}
}

// define as regras de validação dos campos
function set_params_validations( $params_spec = NULL, $param_prefix = PARAM_PREFIX ){
	
	if ( $params_spec ){
		
		$CI =& get_instance();
		$CI->load->library( array( 'form_validation' ) );
		
		if ( is_array( $params_spec ) ) {
			
			reset( $params_spec );
			
			while ( list( $section_key, $section ) = each( $params_spec ) ) {
				
				if ( is_array( $section ) ) {
					
					reset( $section );
					
					while ( list( $element_key, $element ) = each( $section ) ) {
						
						if ( isset( $element[ 'name' ] ) ) {
							
							$name = & $element[ 'name' ];
							
							$name_sbrackets = '';
							preg_match_all( '/\[[^\]]*\]/', $name, $sbrackets );
							
							if ( count( $sbrackets[ 0 ] ) ) {
								
								$name_sbrackets = join( '', $sbrackets[ 0 ] );
								
							}
							
							$name_no_brackets = str_replace( $name_sbrackets, '', $name );
							
							$CI->form_validation->set_rules( $param_prefix . '[' . $name_no_brackets . ']' . $name_sbrackets, lang( ( in_array( $element[ 'type' ], array( 'checkbox', 'radio', ) ) ? $element[ 'name' ] : $element[ 'label' ] ) ), isset( $element[ 'validation' ][ 'rules' ] ) ? $element[ 'validation' ][ 'rules' ] : '' );
							
						}
						
					}
					
				}
				
			}
			
		}
		
		/*
		foreach ( $params_spec as $section_key => $section ) {
			
			foreach ( $section as $element_key => $element ) {
				
				if ( isset( $element[ 'name' ] ) ) {
					
					$name = & $element[ 'name' ];
					
					$name_sbrackets = '';
					preg_match_all( '/\[[^\]]*\]/', $name, $sbrackets );
					
					if ( count( $sbrackets[ 0 ] ) ) {
						
						$name_sbrackets = join( '', $sbrackets[ 0 ] );
						
					}
					
					$name_no_brackets = str_replace( $name_sbrackets, '', $name );
					
					$CI->form_validation->set_rules( $param_prefix . '[' . $name_no_brackets . ']' . $name_sbrackets, lang( ( in_array( $element[ 'type' ], array( 'checkbox', 'radio', ) ) ? $element[ 'name' ] : $element[ 'label' ] ) ), isset( $element[ 'validation' ][ 'rules' ] ) ? $element[ 'validation' ][ 'rules' ] : '' );
					
				}
				
			}
			
		}
		*/
	}
	
}

function params_to_html( $params = NULL, $params_values = NULL, $param_prefix = PARAM_PREFIX, $layout = 'default' ){
	//print_r( $params_values );
	if ( $params ){
		
		$CI =& get_instance();
		
		$data[ 'params' ] = $params;
		$data[ 'params_values' ] = $params_values;
		$data[ 'param_prefix' ] = $param_prefix;
		$data[ 'layout' ] = $layout;
		$data[ 'CI' ] = $CI;
		
		if ( check_var( $params[ 'params_spec' ] ) ) {
			
			if ( file_exists( THEMES_PATH . ADMIN_DIR_NAME . DS . $CI->config->item( 'admin_theme' ) . DS . 'views' . DS . OTHERS_VIEWS_DIR_NAME . DS . 'params' . DS . $layout . 'params.php' ) ){
				
				return $CI->load->view( 'admin' . DS . $CI->config->item( 'admin_theme' ) . DS . 'views' . DS . OTHERS_VIEWS_DIR_NAME . DS . 'params' . DS . $layout . DS . 'params', $data, TRUE );
				
			}
			else if ( file_exists( VIEWS_PATH . ADMIN_DIR_NAME . DS . OTHERS_VIEWS_DIR_NAME . DS . 'params' . DS . $layout . DS . 'params.php' ) ){
				
				return $CI->load->view( ADMIN_DIR_NAME . DS . OTHERS_VIEWS_DIR_NAME . DS . 'params' . DS . $layout . DS . 'params', $data, TRUE );
				
			}
			else {
				
				return '';
				
			}
			
		}
		else {
			
			return '';
			
		}
		
	}
	
}

function _resolve_array_param_value( $prefix, $value ) {
	
	$new_values = array();
	
	while ( list( $k, $v ) = each( $value ) ) {
		
		$new_k = array();
		
		if ( is_array( $v ) ) {
			
			$new_k = _resolve_array_param_value( $prefix . '[' . $k . ']', $v );
			
		}
		else {
			
			$new_values[ $prefix . '[' . $k . ']' ] = $v;
			
		}
		
		$new_values = $new_values + $new_k;
		
	}
	
	/*
	foreach( $value as $k => $v ) {
		
		$new_k = array();
		
		if ( is_array( $v ) ) {
			
			$new_k = _resolve_array_param_value( $prefix . '[' . $k . ']', $v );
			
		}
		else {
			
			$new_values[ $prefix . '[' . $k . ']' ] = $v;
			
		}
		
		$new_values = $new_values + $new_k;
		
	}
	*/
	return $new_values;
	
}

// converte uma estrutura de array em um elemento de formulário html
function get_param_element( $element = NULL, $params_values = NULL, $params_spec = NULL, $param_prefix = PARAM_PREFIX, $layout = 'default' ){
	
	if ( $element ){
		
		$CI =& get_instance();
		$CI->load->helper( array( 'form' ) );
		
		if ( $CI->input->post( $param_prefix ) ){
			
			$post = $CI->input->post( $param_prefix );
			
		}
		
		// -------------------------------------------
		// Este bloco de código converte a variável multidimensional $post para unidimensional
		
		if ( isset( $post ) ){
			
			$new_values = array();
			
			reset( $post );
			
			while ( list( $pk, $post_item ) = each( $post ) ) {
				
				if ( is_array( $post_item ) ) {
					
					unset( $post[ $pk ] );
					
					$post = $post + _resolve_array_param_value( $pk, $post_item );
					
				}
				else {
					
					$post_item = htmlspecialchars_decode( $post_item );
					
				}
				
			}
			
			// substitui os valores padrões pelos obtidos via post
			$params_values = array_merge_recursive_distinct( $params_values, $post );
			
		}
		
		
		
		//print_r( $params_values );
		
		$data = array();
		
		$type = ( ( isset( $element['type'] ) AND $element['type'] ) ? $element['type'] : '' );
		$data[ 'type' ] = & $type;
		$name = ( ( isset( $element['name'] ) AND $element['name'] ) ? $element['name'] : '' );
		$data[ 'name' ] = & $name;
		
		
		$name_sbrackets = '';
		preg_match_all( '/\[[^\]]*\]/', $name, $sbrackets );
		
		if ( count( $sbrackets[ 0 ] ) ) {
			
			$name_sbrackets = join( '', $sbrackets[ 0 ] );
			
		}
		
		$name_no_brackets = str_replace( $name_sbrackets, '', $name );
		
		//echo '<pre>' . print_r( $out, TRUE ) . '</prev>';
		
		$formatted_name = ( isset( $element['name'] ) AND $element['name'] ) ? $param_prefix.'['.$name_no_brackets.']' . $name_sbrackets : '';
		$data[ 'formatted_name' ] = & $formatted_name;
		$label = ( isset( $element['label'] ) AND $element['label'] ) ? lang( $element['label'] ) : '';
		$data[ 'label' ] = & $label;
		$level = ( isset( $element['level'] ) AND $element['level'] ) ? lang( $element['level'] ) : '';
		$data[ 'level' ] = & $level;
		$content = ( isset( $element['content'] ) AND $element['content'] ) ? lang( $element['content'] ) : '';
		$data[ 'content' ] = & $content;
		$class = ( isset( $element['class'] ) AND $element['class'] ) ? $element['class'] : '';
		$data[ 'class' ] = & $class;
		$value = ( isset( $element['value'] ) AND $element['value'] ) ? $element['value'] : '';
		$data[ 'value' ] = & $value;
		$inline = ( isset( $element['inline'] ) AND $element['inline'] ) ? $element['inline'] : FALSE;
		$data[ 'inline' ] = & $inline;
		$editor = ( isset( $element['editor'] ) AND $element['editor'] ) ? $element['editor'] : FALSE;
		$data[ 'editor' ] = & $editor;
		$maxlength = ( isset( $element['maxlength'] ) AND is_numeric( $element['maxlength'] ) ) ? $element['maxlength'] : FALSE;
		$data[ 'maxlength' ] = & $maxlength;
		$checked = ( isset( $element['checked'] ) AND ( bool ) $element['checked'] ) ? TRUE : FALSE;
		$data[ 'checked' ] = & $checked;
		$tip = ( isset( $element['tip'] ) AND $element['tip'] ) ? lang( $element['tip'] ) : '';
		$data[ 'tip' ] = & $tip;
		$icon = ( isset( $element['icon'] ) AND $element['icon'] ) ? $element['icon'] : '';
		$data[ 'icon' ] = & $icon;
		$ext_tip = rawurlencode( $tip );
		$data[ 'ext_tip' ] = & $ext_tip;
		
		$disabled = ( check_var( $element['disabled'] ) ) ? TRUE : FALSE;
		$data[ 'disabled' ] = & $disabled;
		$readonly = ( check_var( $element['readonly'] ) ) ? TRUE : FALSE;
		$data[ 'readonly' ] = & $readonly;
		$size = ( isset( $element['size'] ) ) ? ( int ) $element['size'] : NULL;
		$data[ 'size' ] = & $size;
		
		$min = ( isset( $element['min'] ) AND is_int( $element['min'] ) ) ? ( int ) $element['min'] : NULL;
		$data[ 'min' ] = & $min;
		$max = ( isset( $element['max'] ) AND is_int( $element['max'] ) ) ? ( int ) $element['max'] : NULL;
		$data[ 'max' ] = & $max;
		$pattern = ( isset( $element['pattern'] ) AND $element['pattern'] ) ? $element['pattern'] : NULL;
		$data[ 'pattern' ] = & $pattern;
		$step = ( isset( $element['step'] ) AND is_int( $element['step'] ) ) ? ( int ) $element['step'] : NULL;
		$data[ 'step' ] = & $step;
		
		$breaks = array( '<br/>', '<br />', '<br>', );
		$tip = str_ireplace( $breaks, "\r\n", $tip );
		$tip = str_replace( '"', "'", $tip );
		$tip = strip_tags( $tip );
		
		if ( $tip ){
			
			$class .= ' info-tip ';
			
		}
		
		$element_value = isset( $params_values[$name] ) ? $params_values[$name] : '';
		$data[ 'element_value' ] = & $element_value;
		$element_spec_value = isset( $params_spec['params_spec_values'][$name] ) ? $params_spec['params_spec_values'][$name] : '';
		$data[ 'element_spec_value' ] = & $element_spec_value;
		
		// obtem os options das especificações
		$options = ( isset( $element['options'] ) AND $element['options'] ) ? ( array ) $element['options'] : '';
		$data[ 'options' ] = & $options;
		
		$params_values[$name] = ( isset( $params_values[$name] ) ) ? $params_values[$name] : '';
		$data[ 'params_values' ] = & $params_values;
		
		// o código abaixo não mantém o valor 0 ( zero ) nos campos de texto
		//$params_values[$name] = ( isset( $params_values[$name] ) AND $params_values[$name] ) ? $params_values[$name] : '';
		
		$html = sprintf( lang( 'error_trying_to_load_field_param_view' ), $type );
		
		$f_types = array(
			
			'html',
			'group_open',
			'group_close',
			'hidden',
			'number',
			'password',
			'select',
			'radio',
			'checkbox',
			'text',
			'textarea',
			'spacer',
			'php',
			
		);
		
		if ( in_array( $type, $f_types ) ) {
			
			if ( $type != 'php' ) {
				
				if ( file_exists( THEMES_PATH . ADMIN_DIR_NAME . DS . $CI->config->item( 'admin_theme' ) . DS . 'views' . DS . OTHERS_VIEWS_DIR_NAME . DS . 'params' . DS . $layout . 'ft_' . $type . '.php' ) ){
					
					$html = $CI->load->view( 'admin' . DS . $CI->config->item( 'admin_theme' ) . DS . 'views' . DS . OTHERS_VIEWS_DIR_NAME . DS . 'params' . DS . $layout . DS . 'ft_' . $type, $data, TRUE );
					
				}
				else if ( file_exists( VIEWS_PATH . ADMIN_DIR_NAME . DS . OTHERS_VIEWS_DIR_NAME . DS . 'params' . DS . $layout . DS . 'ft_' . $type . '.php' ) ){
					
					$html = $CI->load->view( ADMIN_DIR_NAME . DS . OTHERS_VIEWS_DIR_NAME . DS . 'params' . DS . $layout . DS . 'ft_' . $type, $data, TRUE );
					
				}
				
				return $html;
				
			}
			
		}
		else {
			
			return sprintf( lang( 'unknow_element' ), $type );
			
		}
		
	}
	
}


/* End of file general_helper.php */
/* Location: ./application/helpers/params_helper.php */
