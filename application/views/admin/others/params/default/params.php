<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );
	
	$output = '';
	
	if ( file_exists( THEMES_PATH . ADMIN_DIR_NAME . DS . $CI->config->item( 'admin_theme' ) . DS . 'views' . DS . OTHERS_VIEWS_DIR_NAME . DS . 'params' . DS . $layout . 'params_set.php' ) ){
		
		$lvp = 'admin' . DS . $CI->config->item( 'admin_theme' ) . DS . 'views' . DS . OTHERS_VIEWS_DIR_NAME . DS . 'params' . DS . $layout . DS . 'params_set';
		
	}
	else if ( file_exists( VIEWS_PATH . ADMIN_DIR_NAME . DS . OTHERS_VIEWS_DIR_NAME . DS . 'params' . DS . $layout . DS . 'params_set.php' ) ){
		
		$lvp = ADMIN_DIR_NAME . DS . OTHERS_VIEWS_DIR_NAME . DS . 'params' . DS . $layout . DS . 'params_set';
		
	}
	
	reset( $params[ 'params_spec' ] );
	
	while ( list( $section_key, $section ) = each( $params[ 'params_spec' ] ) ) {
		
		$data = array();
		
		$data['header'] = $section_key;
		$data['class'] = str_replace( '_', '-', $section_key );
		
		$data[ 'hidden_elements' ] = array();
		
		reset( $section );
		
		while ( list( $element_key, $element ) = each( $section ) ) {
			
			if ( $element[ 'type' ] == 'hidden' ) {
				
				$data[ 'hidden_elements' ][] = get_param_element( $element, $params_values, $params, $param_prefix, $layout );
				
			}
			else {
				
				$data[ 'elements' ][] = get_param_element( $element, $params_values, $params, $param_prefix, $layout );
				
			}
			
		}
		
		$output .= $CI->load->view( $lvp, $data, TRUE );
		
	}
	
	$data = NULL;
	unset( $data );
	
	/*
	foreach ( $params[ 'params_spec' ] as $section_key => $section ) {
		
		$data['header'] = $section_key;
		$data['class'] = str_replace( '_', '-', $section_key );
		
		$data[ 'hidden_elements' ] = array();
		
		foreach ( $section as $element_key => $element ) {
			
			if ( $element[ 'type' ] == 'hidden' ) {
				
				$data[ 'hidden_elements' ][] = get_param_element( $element, $params_values, $params, $param_prefix, $layout );
				
			}
			else {
				
				$data[ 'elements' ][] = get_param_element( $element, $params_values, $params, $param_prefix, $layout );
				
			}
			
		}
		
		if ( file_exists( THEMES_PATH . ADMIN_DIR_NAME . DS . $CI->config->item( 'admin_theme' ) . DS . 'views' . DS . OTHERS_VIEWS_DIR_NAME . DS . 'params' . DS . $layout . 'params_set.php' ) ){
			
			$output .= $CI->load->view( 'admin' . DS . $CI->config->item( 'admin_theme' ) . DS . 'views' . DS . OTHERS_VIEWS_DIR_NAME . DS . 'params' . DS . $layout . DS . 'params_set', $data, TRUE );
			
		}
		else if ( file_exists( VIEWS_PATH . ADMIN_DIR_NAME . DS . OTHERS_VIEWS_DIR_NAME . DS . 'params' . DS . $layout . DS . 'params_set.php' ) ){
			
			$output .= $CI->load->view( ADMIN_DIR_NAME . DS . OTHERS_VIEWS_DIR_NAME . DS . 'params' . DS . $layout . DS . 'params_set', $data, TRUE );
			
		}
		
		// reiniciando a variavel $data para uso na próxima section
		$data = array();
		
	}
	*/
?>


<div class="params-group params">
	
	<?= $output; ?>
	
</div>
