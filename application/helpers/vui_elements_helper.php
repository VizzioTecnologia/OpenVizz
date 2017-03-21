<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Via UI Elements Helpers
 *
 * @category	Helpers
 * @author		Frank Souza
 */

// ------------------------------------------------------------------------

// ------------------------------------------------------------------------

/**
 * Function vui_el_icon
 *
 * Generates a vui icon ( should be styled by css ).
 *
 * @access	public
 * @param	array	The array params
 * @return	string
 */

function vui_el_icon( $f_params = NULL ){
	
	$CI =& get_instance();
	
	// -------------------------------------------------
	// Parsing vars ------------------------------------
	
	$f_params[ 'icon' ] =												isset( $f_params[ 'icon' ] ) ? $f_params[ 'icon' ] : '';
	$f_params[ 'title' ] =												isset( $f_params[ 'title' ] ) ? $f_params[ 'title' ] : '';
	$f_params[ 'class' ] =												isset( $f_params[ 'class' ] ) ? $f_params[ 'class' ] : '';
	$f_params[ 'id' ] =													isset( $f_params[ 'id' ] ) ? $f_params[ 'id' ] : '';
	$f_params[ 'name' ] =												isset( $f_params[ 'name' ] ) ? $f_params[ 'name' ] : '';
	$f_params[ 'minify' ] =												isset( $f_params[ 'minify' ] ) ? $f_params[ 'minify' ] : TRUE;
	$f_params[ 'layout' ] =												isset( $f_params[ 'layout' ] ) ? $f_params[ 'layout' ] : 'default';
	
	// Parsing vars ------------------------------------
	// -------------------------------------------------
	
	// verificando se o tema atual possui a view
	if ( file_exists( THEMES_PATH . theme_helpers_views_path() . 'vui_el' . DS . $f_params[ 'layout' ] . DS . 'icon.php' ) ){
		
		$view = $CI->load->view( theme_helpers_views_path() . 'vui_el' . DS . $f_params[ 'layout' ] . DS . 'icon', $f_params, TRUE );
		
	}
	// verificando se a view existe no diretório de views padrão
	else if ( file_exists( VIEWS_PATH . HELPERS_DIR_NAME . DS . 'vui_el' . DS . $f_params[ 'layout' ] . DS . 'icon.php' ) ){
		
		$view = $CI->load->view( HELPERS_DIR_NAME . DS . 'vui_el' . DS . $f_params[ 'layout' ] . DS . 'icon', $f_params, TRUE);
		
	}
	
	return $f_params['minify'] ? minify_html( $view ) : $view;
	
}
// ------------------------------------------------------------------------

/**
 * Function vui_el_button
 *
 * Generates an HTML button ( should be styled by css ).
 * This function make a button sctructure allowing icons and others cool things
 *
 * @access	public
 * @param	array	The array params
 * @return	string
 */

function vui_el_button( $f_params = NULL ){
	
	// -------------------------------------------------
	// Parsing vars ------------------------------------
	
	$f_params[ 'button_type' ] =										isset( $f_params[ 'button_type' ] ) ? $f_params[ 'button_type' ] : 'anchor';
	$f_params[ 'icon' ] =												isset( $f_params[ 'icon' ] ) ? $f_params[ 'icon' ] : '';
	$f_params[ 'only_icon' ] =											isset( $f_params[ 'only_icon' ] ) ? $f_params[ 'only_icon' ] : FALSE;
	$f_params[ 'url' ] =												isset( $f_params[ 'url' ] ) ? $f_params[ 'url' ] : '';
	$f_params[ 'check_current_url' ] =									isset( $f_params[ 'check_current_url' ] ) ? $f_params[ 'check_current_url' ] : TRUE;
	$f_params[ 'get_url' ] =											isset( $f_params[ 'get_url' ] ) ? $f_params[ 'get_url' ] : TRUE;
	$f_params[ 'text' ] =												isset( $f_params[ 'text' ] ) ? $f_params[ 'text' ] : '';
	$f_params[ 'title' ] =												isset( $f_params[ 'title' ] ) ? $f_params[ 'title' ] : '';
	$f_params[ 'wrapper_class' ] =										isset( $f_params[ 'wrapper_class' ] ) ? $f_params[ 'wrapper_class' ] : '';
	$f_params[ 'attr' ] =												isset( $f_params[ 'attr' ] ) ? $f_params[ 'attr' ] : NULL;
	$f_params[ 'wrapper_attr' ] =										isset( $f_params[ 'wrapper_attr' ] ) ? $f_params[ 'wrapper_attr' ] : NULL;
	$f_params[ 'class' ] =												isset( $f_params[ 'class' ] ) ? $f_params[ 'class' ] : '';
	$f_params[ 'id' ] =													isset( $f_params[ 'id' ] ) ? $f_params[ 'id' ] : '';
	$f_params[ 'wrapper_id' ] =											isset( $f_params[ 'wrapper_id' ] ) ? $f_params[ 'wrapper_id' ] : '';
	$f_params[ 'name' ] =												isset( $f_params[ 'name' ] ) ? $f_params[ 'name' ] : '';
	$f_params[ 'value' ] =												isset( $f_params[ 'value' ] ) ? $f_params[ 'value' ] : '';
	$f_params[ 'target' ] =												isset( $f_params[ 'target' ] ) ? $f_params[ 'target' ] : '';
	$f_params[ 'form' ] =												isset( $f_params[ 'form' ] ) ? $f_params[ 'form' ] : '';
	$f_params[ 'minify' ] =												isset( $f_params[ 'minify' ] ) ? $f_params[ 'minify' ] : TRUE;
	$f_params[ 'autofocus' ] =											isset( $f_params[ 'autofocus' ] ) ? $f_params[ 'autofocus' ] : FALSE;
	$f_params[ 'layout' ] =												isset( $f_params[ 'layout' ] ) ? $f_params[ 'layout' ] : 'default';
	
	// Parsing vars ------------------------------------
	// -------------------------------------------------
	
	$CI =& get_instance();
	
	// verificando se o tema atual possui a view
	if ( file_exists( THEMES_PATH . theme_helpers_views_path() . 'vui_el' . DS . $f_params[ 'layout' ] . DS . 'button.php' ) ){
		
		$view = $CI->load->view( theme_helpers_views_path() . 'vui_el' . DS . $f_params[ 'layout' ] . DS . 'button', $f_params, TRUE );
		
	}
	// verificando se a view existe no diretório de views padrão
	else if ( file_exists( VIEWS_PATH . HELPERS_DIR_NAME . DS . 'vui_el' . DS . $f_params[ 'layout' ] . DS . 'button.php' ) ){
		
		$view = $CI->load->view( HELPERS_DIR_NAME . DS . 'vui_el' . DS . $f_params[ 'layout' ] . DS . 'button', $f_params, TRUE);
		
	}
	
	return $f_params[ 'minify' ] ? minify_html( $view ) : $view;
	
}

// ------------------------------------------------------------------------

/**
 * Function vui_el_input_password
 *
 * Generates an vui html input text ( should be styled by css ).
 * This function make a input text sctructure allowing icons and others cool things
 *
 * @access	public
 * @param	array	The array params
 * @return	string
 */

function vui_el_input_password( $f_params = NULL ){
	
	// -------------------------------------------------
	// Parsing vars ------------------------------------
	
	$text = $f_params['text'] =													isset( $f_params['text'] ) ? $f_params['text'] : '';
	$icon = $f_params['icon'] =													isset( $f_params['icon'] ) ? $f_params['icon'] : '';
	$title = $f_params['title'] =												isset( $f_params['title'] ) ? $f_params['title'] : '';
	$wrapper_class = $f_params['wrapper_class'] =								isset( $f_params['wrapper_class'] ) ? $f_params['wrapper_class'] : '';
	$class = $f_params['class'] =												isset( $f_params['class'] ) ? $f_params['class'] : '';
	$id = $f_params['id'] =														isset( $f_params['id'] ) ? $f_params['id'] : '';
	$value = $f_params['value'] =												isset( $f_params['value'] ) ? $f_params['value'] : '';
	$name = $f_params['name'] =													isset( $f_params['name'] ) ? $f_params['name'] : '';
	$form = $f_params['form'] =													isset( $f_params['form'] ) ? $f_params['form'] : '';
	$attr = $f_params['attr'] =													isset( $f_params['attr'] ) ? $f_params['attr'] : '';
	$minify = $f_params['minify'] =												isset( $f_params[ 'minify' ] ) ? $f_params[ 'minify' ] : TRUE;
	$autofocus = $f_params['autofocus'] =										isset( $f_params[ 'autofocus' ] ) ? $f_params[ 'autofocus' ] : FALSE;
	$layout = $f_params[ 'layout' ] =											isset( $f_params[ 'layout' ] ) ? $f_params[ 'layout' ] : 'default';

	// Parsing vars ------------------------------------
	// -------------------------------------------------
	
	$CI =& get_instance();
	
	// verificando se o tema atual possui a view
	if ( file_exists( THEMES_PATH . theme_helpers_views_path() . 'vui_el' . DS . $layout . DS . 'input_password.php' ) ){
		
		$view = $CI->load->view( theme_helpers_views_path() . 'vui_el' . DS . $layout . DS . 'input_password', $f_params, TRUE );
		
	}
	// verificando se a view existe no diretório de views padrão
	else if ( file_exists( VIEWS_PATH . HELPERS_DIR_NAME . DS . 'vui_el' . DS . $layout . DS . 'input_password.php' ) ){
		
		$view = $CI->load->view( HELPERS_DIR_NAME . DS . 'vui_el' . DS . $layout . DS . 'input_password', $f_params, TRUE);
		
	}
	
	return $minify ? minify_html( $view ) : $view;
	
}

// ------------------------------------------------------------------------

/**
 * Function vui_el_input_text
 *
 * Generates an vui html input text ( should be styled by css ).
 * This function make a input text sctructure allowing icons and others cool things
 *
 * @access	public
 * @param	array	The array params
 * @return	string
 */

function vui_el_input_text( $f_params = NULL ){
	
	// -------------------------------------------------
	// Parsing vars ------------------------------------
	
	$text = $f_params['text'] =													isset( $f_params['text'] ) ? $f_params['text'] : '';
	$icon = $f_params['icon'] =													isset( $f_params['icon'] ) ? $f_params['icon'] : '';
	$title = $f_params['title'] =												isset( $f_params['title'] ) ? $f_params['title'] : '';
	$wrapper_class = $f_params['wrapper_class'] =								isset( $f_params['wrapper_class'] ) ? $f_params['wrapper_class'] : '';
	$class = $f_params['class'] =												isset( $f_params['class'] ) ? $f_params['class'] : '';
	$id = $f_params['id'] =														isset( $f_params['id'] ) ? $f_params['id'] : '';
	$value = $f_params['value'] =												isset( $f_params['value'] ) ? $f_params['value'] : '';
	$name = $f_params['name'] =													isset( $f_params['name'] ) ? $f_params['name'] : '';
	$form = $f_params['form'] =													isset( $f_params['form'] ) ? $f_params['form'] : '';
	$attr = $f_params['attr'] =													isset( $f_params['attr'] ) ? $f_params['attr'] : '';
	$minify = $f_params['minify'] =												isset( $f_params[ 'minify' ] ) ? $f_params[ 'minify' ] : TRUE;
	$autofocus = $f_params['autofocus'] =										isset( $f_params[ 'autofocus' ] ) ? $f_params[ 'autofocus' ] : FALSE;
	$layout = $f_params[ 'layout' ] =											isset( $f_params[ 'layout' ] ) ? $f_params[ 'layout' ] : 'default';

	// Parsing vars ------------------------------------
	// -------------------------------------------------
	
	$CI =& get_instance();
	
	// verificando se o tema atual possui a view
	if ( file_exists( THEMES_PATH . theme_helpers_views_path() . 'vui_el' . DS . $layout . DS . 'input_text.php' ) ){
		
		$view = $CI->load->view( theme_helpers_views_path() . 'vui_el' . DS . $layout . DS . 'input_text', $f_params, TRUE );
		
	}
	// verificando se a view existe no diretório de views padrão
	else if ( file_exists( VIEWS_PATH . HELPERS_DIR_NAME . DS . 'vui_el' . DS . $layout . DS . 'input_text.php' ) ){
		
		$view = $CI->load->view( HELPERS_DIR_NAME . DS . 'vui_el' . DS . $layout . DS . 'input_text', $f_params, TRUE);
		
	}
	
	return $minify ? minify_html( $view ) : $view;
	
}

// ------------------------------------------------------------------------

/**
 * Function vui_el_input_file
 *
 * Generates an vui html input file ( should be styled by css ).
 * This function make a input file sctructure allowing icons and others cool things
 *
 * @access	public
 * @param	array	The array params
 * @return	string
 */

function vui_el_input_file( $f_params = NULL ){
	
	// -------------------------------------------------
	// Parsing vars ------------------------------------
	
	$text = $f_params['text'] =													isset( $f_params['text'] ) ? $f_params['text'] : '';
	$icon = $f_params['icon'] =													isset( $f_params['icon'] ) ? $f_params['icon'] : '';
	$title = $f_params['title'] =												isset( $f_params['title'] ) ? $f_params['title'] : '';
	$wrapper_class = $f_params['wrapper_class'] =								isset( $f_params['wrapper_class'] ) ? $f_params['wrapper_class'] : '';
	$class = $f_params['class'] =												isset( $f_params['class'] ) ? $f_params['class'] : '';
	$id = $f_params['id'] =														isset( $f_params['id'] ) ? $f_params['id'] : '';
	$value = $f_params['value'] =												isset( $f_params['value'] ) ? $f_params['value'] : '';
	$name = $f_params['name'] =													isset( $f_params['name'] ) ? $f_params['name'] : '';
	$form = $f_params['form'] =													isset( $f_params['form'] ) ? $f_params['form'] : '';
	$attr = $f_params['attr'] =													isset( $f_params['attr'] ) ? $f_params['attr'] : '';
	$minify = $f_params['minify'] =												isset( $f_params[ 'minify' ] ) ? $f_params[ 'minify' ] : TRUE;
	$autofocus = $f_params['autofocus'] =										isset( $f_params[ 'autofocus' ] ) ? $f_params[ 'autofocus' ] : FALSE;
	$layout = $f_params[ 'layout' ] =											isset( $f_params[ 'layout' ] ) ? $f_params[ 'layout' ] : 'default';
	$multiple = $f_params['multiple'] =											isset( $f_params[ 'multiple' ] ) ? $f_params[ 'multiple' ] : FALSE;

	// Parsing vars ------------------------------------
	// -------------------------------------------------
	
	$CI =& get_instance();
	
	// verificando se o tema atual possui a view
	if ( file_exists( THEMES_PATH . theme_helpers_views_path() . 'vui_el' . DS . $layout . DS . 'input_file.php' ) ){
		
		$view = $CI->load->view( theme_helpers_views_path() . 'vui_el' . DS . $layout . DS . 'input_file', $f_params, TRUE );
		
	}
	// verificando se a view existe no diretório de views padrão
	else if ( file_exists( VIEWS_PATH . HELPERS_DIR_NAME . DS . 'vui_el' . DS . $layout . DS . 'input_file.php' ) ){
		
		$view = $CI->load->view( HELPERS_DIR_NAME . DS . 'vui_el' . DS . $layout . DS . 'input_file', $f_params, TRUE);
		
	}
	
	return $minify ? minify_html( $view ) : $view;
	
}

// ------------------------------------------------------------------------

/**
 * Function vui_el_textarea
 *
 * Generates an vui html textarea ( should be styled by css ).
 * This function make a textarea sctructure allowing icons and others cool things
 *
 * @access	public
 * @param	array	The array params
 * @return	string
 */

function vui_el_textarea( $f_params = NULL ){
	
	// -------------------------------------------------
	// Parsing vars ------------------------------------
	
	$f_params['text'] =													isset( $f_params['text'] ) ? $f_params['text'] : '';
	$f_params['icon'] =													isset( $f_params['icon'] ) ? $f_params['icon'] : '';
	$f_params['title'] =												isset( $f_params['title'] ) ? $f_params['title'] : '';
	$f_params['wrapper_class'] =										isset( $f_params['wrapper_class'] ) ? $f_params['wrapper_class'] : '';
	$f_params['class'] =												isset( $f_params['class'] ) ? $f_params['class'] : '';
	$f_params['id'] =													isset( $f_params['id'] ) ? $f_params['id'] : '';
	$f_params['value'] =												isset( $f_params['value'] ) ? $f_params['value'] : '';
	$f_params['name'] =													isset( $f_params['name'] ) ? $f_params['name'] : '';
	$f_params['form'] =													isset( $f_params['form'] ) ? $f_params['form'] : '';
	$f_params['attr'] =													isset( $f_params['attr'] ) ? $f_params['attr'] : '';
	$f_params['minify'] =												isset( $f_params[ 'minify' ] ) ? $f_params[ 'minify' ] : TRUE;
	$f_params['autofocus'] =											isset( $f_params[ 'autofocus' ] ) ? $f_params[ 'autofocus' ] : FALSE;
	$f_params[ 'layout' ] =												isset( $f_params[ 'layout' ] ) ? $f_params[ 'layout' ] : 'default';

	// Parsing vars ------------------------------------
	// -------------------------------------------------
	
	$CI =& get_instance();
	
	// verificando se o tema atual possui a view
	if ( file_exists( THEMES_PATH . theme_helpers_views_path() . 'vui_el' . DS . $f_params[ 'layout' ] . DS . 'textarea.php' ) ){
		
		$view = $CI->load->view( theme_helpers_views_path() . 'vui_el' . DS . $f_params[ 'layout' ] . DS . 'textarea', $f_params, TRUE );
		
	}
	// verificando se a view existe no diretório de views padrão
	else if ( file_exists( VIEWS_PATH . HELPERS_DIR_NAME . DS . 'vui_el' . DS . $f_params[ 'layout' ] . DS . 'textarea.php' ) ){
		
		$view = $CI->load->view( HELPERS_DIR_NAME . DS . 'vui_el' . DS . $f_params[ 'layout' ] . DS . 'textarea', $f_params, TRUE);
		
	}
	
	return $f_params['minify'] ? minify_html( $view ) : $view;
	
}

// ------------------------------------------------------------------------

/**
 * Function vui_el_input_number
 *
 * Generates an vui html input number ( should be styled by css ).
 * This function make a input number sctructure allowing icons and others cool things
 *
 * @access	public
 * @param	array	The array params
 * @return	string
 */

function vui_el_input_number( $f_params = NULL ){
	
	// -------------------------------------------------
	// Parsing vars ------------------------------------
	
	$text = $f_params['text'] =													isset( $f_params['text'] ) ? $f_params['text'] : '';
	$icon = $f_params['icon'] =													isset( $f_params['icon'] ) ? $f_params['icon'] : '';
	$title = $f_params['title'] =												isset( $f_params['title'] ) ? $f_params['title'] : '';
	$wrapper_class = $f_params['wrapper_class'] =								isset( $f_params['wrapper_class'] ) ? $f_params['wrapper_class'] : '';
	$class = $f_params['class'] =												isset( $f_params['class'] ) ? $f_params['class'] : '';
	$id = $f_params['id'] =														isset( $f_params['id'] ) ? $f_params['id'] : '';
	$value = $f_params['value'] =												isset( $f_params['value'] ) ? $f_params['value'] : '';
	$name = $f_params['name'] =													isset( $f_params['name'] ) ? $f_params['name'] : '';
	$form = $f_params['form'] =													isset( $f_params['form'] ) ? $f_params['form'] : '';
	$attr = $f_params['attr'] =													isset( $f_params['attr'] ) ? $f_params['attr'] : '';
	$minify = $f_params['minify'] =												isset( $f_params[ 'minify' ] ) ? $f_params[ 'minify' ] : TRUE;
	$autofocus = $f_params['autofocus'] =										isset( $f_params[ 'autofocus' ] ) ? $f_params[ 'autofocus' ] : FALSE;
	$layout = $f_params[ 'layout' ] =											isset( $f_params[ 'layout' ] ) ? $f_params[ 'layout' ] : 'default';

	$max = $f_params['max'] =													isset( $f_params['max'] ) ? $f_params['max'] : NULL;
	$min = $f_params['min'] =													isset( $f_params['min'] ) ? $f_params['min'] : NULL;
	$pattern = $f_params['pattern'] =											isset( $f_params['pattern'] ) ? $f_params['pattern'] : NULL;
	$step = $f_params['step'] =													isset( $f_params['step'] ) ? $f_params['step'] : NULL;

	// Parsing vars ------------------------------------
	// -------------------------------------------------
	
	$CI =& get_instance();
	
	// verificando se o tema atual possui a view
	if ( file_exists( THEMES_PATH . theme_helpers_views_path() . 'vui_el' . DS . $layout . DS . 'input_number.php' ) ){
		
		$view = $CI->load->view( theme_helpers_views_path() . 'vui_el' . DS . $layout . DS . 'input_number', $f_params, TRUE );
		
	}
	// verificando se a view existe no diretório de views padrão
	else if ( file_exists( VIEWS_PATH . HELPERS_DIR_NAME . DS . 'vui_el' . DS . $layout . DS . 'input_number.php' ) ){
		
		$view = $CI->load->view( HELPERS_DIR_NAME . DS . 'vui_el' . DS . $layout . DS . 'input_number', $f_params, TRUE);
		
	}
	
	return $minify ? minify_html( $view ) : $view;
	
}

// ------------------------------------------------------------------------

/**
 * Function vui_el_dropdown
 *
 * Generates an vui html dropdown ( should be styled by css ).
 * This function make a dropdown sctructure allowing icons and others cool things
 *
 * @access	public
 * @param	array	The array params
 * @return	string
 */

function vui_el_dropdown( $f_params = NULL ){
	
	// -------------------------------------------------
	// Parsing vars ------------------------------------
	
	$text = $f_params['text'] =									isset( $f_params['text'] ) ? $f_params['text'] : '';
	$icon = $f_params['icon'] =									isset( $f_params['icon'] ) ? $f_params['icon'] : '';
	$title = $f_params['title'] =								isset( $f_params['title'] ) ? $f_params['title'] : '';
	$wrapper_class = $f_params['wrapper_class'] =				isset( $f_params['wrapper_class'] ) ? $f_params['wrapper_class'] : '';
	$class = $f_params['class'] =								isset( $f_params['class'] ) ? $f_params['class'] : '';
	$id = $f_params['id'] =										isset( $f_params['id'] ) ? $f_params['id'] : '';
	$value = $f_params['value'] =								isset( $f_params['value'] ) ? $f_params['value'] : '';
	$name = $f_params['name'] =									isset( $f_params['name'] ) ? $f_params['name'] : '';
	$form = $f_params['form'] =									isset( $f_params['form'] ) ? $f_params['form'] : '';
	$attr = $f_params['attr'] =									isset( $f_params['attr'] ) ? $f_params['attr'] : '';
	$size = $f_params['size'] =									isset( $f_params['size'] ) ? $f_params['size'] : NULL;
	$options = $f_params['options'] =							isset( $f_params['options'] ) ? $f_params['options'] : NULL;
	$minify = $f_params['minify'] =								isset( $f_params[ 'minify' ] ) ? $f_params[ 'minify' ] : TRUE;
	$multiselect = $f_params['multiselect'] =					isset( $f_params[ 'multiselect' ] ) ? $f_params[ 'multiselect' ] : FALSE;
	$layout = $f_params[ 'layout' ] =							isset( $f_params[ 'layout' ] ) ? $f_params[ 'layout' ] : 'default';

	// Parsing vars ------------------------------------
	// -------------------------------------------------
	
	$CI =& get_instance();
	
	// verificando se o tema atual possui a view
	if ( file_exists( THEMES_PATH . theme_helpers_views_path() . 'vui_el' . DS . $layout . DS . 'dropdown.php' ) ){
		
		$view = $CI->load->view( theme_helpers_views_path() . 'vui_el' . DS . $layout . DS . 'dropdown', $f_params, TRUE );
		
	}
	// verificando se a view existe no diretório de views padrão
	else if ( file_exists( VIEWS_PATH . HELPERS_DIR_NAME . DS . 'vui_el' . DS . $layout . DS . 'dropdown.php' ) ){
		
		$view = $CI->load->view( HELPERS_DIR_NAME . DS . 'vui_el' . DS . $layout . DS . 'dropdown', $f_params, TRUE);
		
	}
	
	return $minify ? minify_html( $view ) : $view;
	
}

// ------------------------------------------------------------------------

/**
 * Function vui_el_checkbox
 *
 * Generates an HTML checkbox wrapped with a label ( should be styled by css ).
 * This function make a checkbox sctructure allowing icons and others cool things
 *
 * @access	public
 * @param	array	The array params
 * @return	string
 */

function vui_el_checkbox( $f_params = NULL ){
	
	// -------------------------------------------------
	// Setting variables default values ----------------
	
	$f_params[ 'checked' ] =											isset( $f_params[ 'checked' ] ) ? $f_params[ 'checked' ] : FALSE;
	$f_params[ 'url' ] =												isset( $f_params[ 'url' ] ) ? $f_params[ 'url' ] : '';
	$f_params[ 'text' ] =												isset( $f_params[ 'text' ] ) ? $f_params[ 'text' ] : '';
	$f_params[ 'icon' ] =												isset( $f_params[ 'icon' ] ) ? $f_params[ 'icon' ] : '';
	$f_params[ 'only_icon' ] =											isset( $f_params[ 'only_icon' ] ) ? $f_params[ 'only_icon' ] : FALSE;
	$f_params[ 'icon_as_check' ] =										isset( $f_params[ 'icon_as_check' ] ) ? $f_params[ 'icon_as_check' ] : FALSE;
	$f_params[ 'has_check' ] =											isset( $f_params[ 'has_check' ] ) ? $f_params[ 'has_check' ] : TRUE;
	$f_params[ 'title' ] =												isset( $f_params[ 'title' ] ) ? $f_params[ 'title' ] : '';
	$f_params[ 'ext_tip' ] =											isset( $f_params[ 'title' ] ) ? rawurlencode( $f_params[ 'title' ] ) : '';
	$f_params[ 'wrapper_class' ] =										isset( $f_params[ 'wrapper_class' ] ) ? $f_params[ 'wrapper_class' ] : '';
	$f_params[ 'class' ] =												isset( $f_params[ 'class' ] ) ? $f_params[ 'class' ] : '';
	$f_params[ 'id' ] =													isset( $f_params[ 'id' ] ) ? $f_params[ 'id' ] : '';
	$f_params[ 'name' ] =												isset( $f_params[ 'name' ] ) ? $f_params[ 'name' ] : '';
	$f_params[ 'form' ] =												isset( $f_params[ 'form' ] ) ? $f_params[ 'form' ] : '';
	$f_params[ 'attr' ] =												isset( $f_params[ 'attr' ] ) ? $f_params[ 'attr' ] : ''; // extra attributes for the search input element
	$f_params[ 'minify' ] =												isset( $f_params[ 'minify' ] ) ? $f_params[ 'minify' ] : TRUE; // if true, the html output will be minified
	$f_params[ 'layout' ] =												isset( $f_params[ 'layout' ] ) ? $f_params[ 'layout' ] : 'default';
	$f_params[ 'value' ] =												isset( $f_params[ 'value' ] ) ? $f_params[ 'value' ] : '1';
	
	// Setting variables default values ----------------
	// -------------------------------------------------
	
	$CI =& get_instance();
	
	// verificando se o tema atual possui a view
	if ( file_exists( THEMES_PATH . theme_helpers_views_path() . 'vui_el' . DS . $f_params[ 'layout' ] . DS . 'checkbox.php' ) ){
		
		$view = $CI->load->view( theme_helpers_views_path() . 'vui_el' . DS . $f_params[ 'layout' ] . DS . 'checkbox', $f_params, TRUE );
		
	}
	// verificando se a view existe no diretório de views padrão
	else if ( file_exists( VIEWS_PATH . HELPERS_DIR_NAME . DS . 'vui_el' . DS . $f_params[ 'layout' ] . DS . 'checkbox.php' ) ){
		
		$view = $CI->load->view( HELPERS_DIR_NAME . DS . 'vui_el' . DS . $f_params[ 'layout' ] . DS . 'checkbox', $f_params, TRUE);
		
	}
	
	return $f_params[ 'minify' ] ? minify_html( $view ) : $view;
	
}

// ------------------------------------------------------------------------

/**
 * Function vui_el_radiobox
 *
 * Generates an HTML radio wrapped with a label ( should be styled by css ).
 * This function make a radio sctructure allowing icons and others cool things
 *
 * @access	public
 * @param	array	The array params
 * @return	string
 */

function vui_el_radiobox( $f_params = NULL ){

	// -------------------------------------------------
	// Setting variables default values ----------------
	
	$f_params[ 'checked' ] =											isset( $f_params[ 'checked' ] ) ? $f_params[ 'checked' ] : FALSE;
	$f_params[ 'url' ] =												isset( $f_params[ 'url' ] ) ? $f_params[ 'url' ] : '';
	$f_params[ 'text' ] =												isset( $f_params[ 'text' ] ) ? $f_params[ 'text' ] : '';
	$f_params[ 'icon' ] =												isset( $f_params[ 'icon' ] ) ? $f_params[ 'icon' ] : '';
	$f_params[ 'only_icon' ] =											isset( $f_params[ 'only_icon' ] ) ? $f_params[ 'only_icon' ] : FALSE;
	$f_params[ 'icon_as_check' ] =										isset( $f_params[ 'icon_as_check' ] ) ? $f_params[ 'icon_as_check' ] : FALSE;
	$f_params[ 'has_check' ] =											isset( $f_params[ 'has_check' ] ) ? $f_params[ 'has_check' ] : TRUE;
	$f_params[ 'title' ] =												isset( $f_params[ 'title' ] ) ? $f_params[ 'title' ] : '';
	$f_params[ 'ext_tip' ] =											isset( $f_params[ 'title' ] ) ? rawurlencode( $f_params[ 'title' ] ) : '';
	$f_params[ 'wrapper_class' ] =										isset( $f_params[ 'wrapper_class' ] ) ? $f_params[ 'wrapper_class' ] : '';
	$f_params[ 'class' ] =												isset( $f_params[ 'class' ] ) ? $f_params[ 'class' ] : '';
	$f_params[ 'id' ] =													isset( $f_params[ 'id' ] ) ? $f_params[ 'id' ] : '';
	$f_params[ 'name' ] =												isset( $f_params[ 'name' ] ) ? $f_params[ 'name' ] : '';
	$f_params[ 'form' ] =												isset( $f_params[ 'form' ] ) ? $f_params[ 'form' ] : '';
	$f_params[ 'attr' ] =												isset( $f_params[ 'attr' ] ) ? $f_params[ 'attr' ] : '';
	$f_params[ 'minify' ] =												isset( $f_params[ 'minify' ] ) ? $f_params[ 'minify' ] : TRUE; // if true, the html output will be minified
	$f_params[ 'layout' ] =												isset( $f_params[ 'layout' ] ) ? $f_params[ 'layout' ] : 'default';
	$f_params[ 'value' ] =												isset( $f_params[ 'value' ] ) ? $f_params[ 'value' ] : '1';
	
	// Setting variables default values ----------------
	// -------------------------------------------------
	
	$CI =& get_instance();
	
	// verificando se o tema atual possui a view
	if ( file_exists( THEMES_PATH . theme_helpers_views_path() . 'vui_el' . DS . $f_params[ 'layout' ] . DS . 'radiobox.php' ) ){
		
		$view = $CI->load->view( theme_helpers_views_path() . 'vui_el' . DS . $f_params[ 'layout' ] . DS . 'radiobox', $f_params, TRUE );
		
	}
	// verificando se a view existe no diretório de views padrão
	else if ( file_exists( VIEWS_PATH . HELPERS_DIR_NAME . DS . 'vui_el' . DS . $f_params[ 'layout' ] . DS . 'radiobox.php' ) ){
		
		$view = $CI->load->view( HELPERS_DIR_NAME . DS . 'vui_el' . DS . $f_params[ 'layout' ] . DS . 'radiobox', $f_params, TRUE);
		
	}
	
	return $f_params[ 'minify' ] ? minify_html( $view ) : $view;
	
}

// ------------------------------------------------------------------------

/**
 * Function vui_el_search
 *
 * @access	public
 * @param	array	The array params
 * @return	string
 */

function vui_el_search( $f_params = NULL ){

	// -------------------------------------------------
	// Setting variables default values ----------------

	$CI =& get_instance();

	$f_params[ 'url' ] =									( isset( $f_params[ 'url' ] ) AND is_string( $f_params[ 'url' ] ) ) ? $f_params[ 'url' ] : '';
	$f_params[ 'cancel_url' ] =								( isset( $f_params[ 'cancel_url' ] ) AND is_string( $f_params[ 'cancel_url' ] ) ) ? $f_params[ 'cancel_url' ] : NULL; // url wich the user will be redirected when cancel search
	$f_params[ 'live_search_url' ] =						( isset( $f_params[ 'live_search_url' ] ) AND is_string( $f_params[ 'live_search_url' ] ) ) ? $f_params[ 'live_search_url' ] : NULL; // if defined, load thw cool live search plugin
	$f_params[ 'live_search_min_length' ] =					( isset( $f_params[ 'live_search_min_length' ] ) AND is_numeric( $f_params[ 'live_search_min_length' ] ) ) ? $f_params[ 'live_search_min_length' ] : 3;
	$f_params[ 'text' ] =									isset( $f_params[ 'text' ] ) ? $f_params[ 'text' ] : lang( 'search' );
	$f_params[ 'icon' ] =									isset( $f_params[ 'icon' ] ) ? $f_params[ 'icon' ] : 'search';
	$f_params[ 'only_icon' ] =								isset( $f_params[ 'only_icon' ] ) ? $f_params[ 'only_icon' ] : NULL;
	$f_params[ 'title' ] =									isset( $f_params[ 'title' ] ) ? $f_params[ 'title' ] : '';
	$f_params[ 'wrapper_class' ] =							isset( $f_params[ 'wrapper_class' ] ) ? $f_params[ 'wrapper_class' ] : '';
	$f_params[ 'class' ] =									isset( $f_params[ 'class' ] ) ? ' ' . $f_params[ 'class' ] : '';
		$f_params[ 'class' ] =								isset( $f_params[ 'live_search_url' ] ) ? $f_params[ 'class' ] . ' live-search search-terms' : $f_params[ 'class' ];
	$f_params[ 'id' ] =										isset( $f_params[ 'id' ] ) ? $f_params[ 'id' ] : '';
	//$f_params[ 'check_current_url' ] =						isset( $f_params[ 'check_current_url' ] ) ? $f_params[ 'check_current_url' ] : TRUE;
	$f_params[ 'get_url' ] =								isset( $f_params[ 'get_url' ] ) ? $f_params[ 'get_url' ] : TRUE;
	$f_params[ 'name' ] =									isset( $f_params[ 'name' ] ) ? $f_params[ 'name' ] : 'search-terms';
	//$type = $f_params[ 'type' ] =							isset( $f_params[ 'type' ] ) ? $f_params[ 'type' ] : '';
	//$target = $f_params[ 'target' ] =						isset( $f_params[ 'target' ] ) ? $f_params[ 'target' ] : '';
	//$form = $f_params[ 'form' ] =							isset( $f_params[ 'form' ] ) ? $f_params[ 'form' ] : '';
	$f_params[ 'attr' ] =									isset( $f_params[ 'attr' ] ) ? $f_params[ 'attr' ] : ''; // extra attributes for the search input element
	$f_params[ 'minify' ] =									isset( $f_params[ 'minify' ] ) ? $f_params[ 'minify' ] : TRUE; // if true, the html output will be minified
	$f_params[ 'terms' ] =									( isset( $f_params[ 'terms' ] ) AND is_string( $f_params[ 'terms' ] ) ) ? $f_params[ 'terms' ] : NULL; // variable containing the search terms
		$f_params[ 'terms' ] =								( ! $f_params[ 'terms' ] AND $CI->input->post( 'terms' ) AND is_string( $CI->input->post( 'terms' ) ) ) ? $CI->input->post( 'terms' ) : $f_params[ 'terms' ];
		$f_params[ 'terms' ] =								( ! $f_params[ 'terms' ] AND $CI->input->get( 'q' ) AND is_string( $CI->input->get( 'q' ) ) ) ? $CI->input->get( 'q' ) : $f_params[ 'terms' ];
	$f_params[ 'layout' ] =									isset( $f_params[ 'layout' ] ) ? $f_params[ 'layout' ] : 'default';
	$f_params[ 'terms' ] =									isset( $f_params[ 'terms' ] ) ? $f_params[ 'terms' ] : '';

	// Setting variables default values ----------------
	// -------------------------------------------------

	// verificando se o tema atual possui a view
	if ( file_exists( THEMES_PATH . theme_helpers_views_path() . 'vui_el' . DS . $f_params[ 'layout' ] . DS . 'search_box.php' ) ){

		$view = $CI->load->view( theme_helpers_views_path() . 'vui_el' . DS . $f_params[ 'layout' ] . DS . 'search_box', $f_params, TRUE );

	}
	// verificando se a view existe no diretório de views padrão
	else if ( file_exists( VIEWS_PATH . HELPERS_DIR_NAME . DS . 'vui_el' . DS . $f_params[ 'layout' ] . DS . 'search_box.php' ) ){

		$view = $CI->load->view( HELPERS_DIR_NAME . DS . 'vui_el' . DS . $f_params[ 'layout' ] . DS . 'search_box', $f_params, TRUE);

	}

	return $f_params[ 'minify' ] ? minify_html( $view ) : $view;

}

// ------------------------------------------------------------------------

/**
 * Function vui_el_address_box
 *
 * Present a pre formated address set
 *
 * @access	public
 * @param	array	The array params
 * @return	string
 */

function vui_el_address_box( $f_params = NULL ){

	// -------------------------------------------------
	// Setting variables default values ----------------

	$text = $f_params[ 'text' ] =												isset( $f_params[ 'text' ] ) ? $f_params[ 'text' ] : '';
	$icon = $f_params[ 'icon' ] =												isset( $f_params[ 'icon' ] ) ? $f_params[ 'icon' ] : '';
	$title = $f_params[ 'title' ] =												isset( $f_params[ 'title' ] ) ? $f_params[ 'title' ] : '';
	$wrapper_class = $f_params[ 'wrapper_class' ] =								isset( $f_params[ 'wrapper_class' ] ) ? $f_params[ 'wrapper_class' ] : '';
	$class = $f_params[ 'class' ] =												isset( $f_params[ 'class' ] ) ? $f_params[ 'class' ] : '';
	$id = $f_params[ 'id' ] =													isset( $f_params[ 'id' ] ) ? $f_params[ 'id' ] : '';
	$name = $f_params[ 'name' ] =												isset( $f_params[ 'name' ] ) ? $f_params[ 'name' ] : '';
	$form = $f_params[ 'form' ] =												isset( $f_params[ 'form' ] ) ? $f_params[ 'form' ] : '';
	$attr = $f_params[ 'attr' ] =												isset( $f_params[ 'attr' ] ) ? $f_params[ 'attr' ] : ''; // extra attributes for the search input element
	$minify = $f_params[ 'minify' ] =											isset( $f_params[ 'minify' ] ) ? $f_params[ 'minify' ] : TRUE; // if true, the html output will be minified
	$layout = $f_params[ 'layout' ] =											isset( $f_params[ 'layout' ] ) ? $f_params[ 'layout' ] : 'default';
	$address = $f_params[ 'address' ] =											isset( $f_params[ 'address' ] ) ? $f_params[ 'address' ] : NULL;

	// Setting variables default values ----------------
	// -------------------------------------------------

	$CI =& get_instance();

	// verificando se o tema atual possui a view
	if ( file_exists( THEMES_PATH . theme_helpers_views_path() . 'vui_el' . DS . $layout . DS . 'address_box.php' ) ){

		$view = $CI->load->view( theme_helpers_views_path() . 'vui_el' . DS . $layout . DS . 'address_box', $f_params, TRUE );

	}
	// verificando se a view existe no diretório de views padrão
	else if ( file_exists( VIEWS_PATH . HELPERS_DIR_NAME . DS . 'vui_el' . DS . $layout . DS . 'address_box.php' ) ){

		$view = $CI->load->view( HELPERS_DIR_NAME . DS . 'vui_el' . DS . $layout . DS . 'address_box', $f_params, TRUE);

	}

	return $minify ? minify_html( $view ) : $view;

}

// ------------------------------------------------------------------------

/**
 * Function vui_el_thumb
 *
 * Present a pre formated thumb
 *
 * @access	public
 * @param	array	The array params
 * @return	string
 */

function vui_el_thumb( $f_params = NULL ){

	// -------------------------------------------------
	// Setting variables default values ----------------

	$text = $f_params[ 'text' ] =												isset( $f_params[ 'text' ] ) ? $f_params[ 'text' ] : '';
	$title = $f_params[ 'title' ] =												isset( $f_params[ 'title' ] ) ? $f_params[ 'title' ] : '';
	$wrapper_class = $f_params[ 'wrapper_class' ] =								isset( $f_params[ 'wrapper_class' ] ) ? $f_params[ 'wrapper_class' ] : '';
	$wrappers_el_type = $f_params[ 'wrappers_el_type' ] =						isset( $f_params[ 'wrappers_el_type' ] ) ? $f_params[ 'wrappers_el_type' ] : 'div'; // wrappers element type
	$class = $f_params[ 'class' ] =												isset( $f_params[ 'class' ] ) ? $f_params[ 'class' ] : '';
	$id = $f_params[ 'id' ] =													isset( $f_params[ 'id' ] ) ? $f_params[ 'id' ] : '';
	$src = $f_params[ 'src' ] =													isset( $f_params[ 'src' ] ) ? $f_params[ 'src' ] : '';
	$href = $f_params[ 'href' ] =												isset( $f_params[ 'href' ] ) ? $f_params[ 'href' ] : '';
	$rel = $f_params[ 'rel' ] =													isset( $f_params[ 'rel' ] ) ? $f_params[ 'rel' ] : FALSE;
	$target = $f_params[ 'target' ] =											isset( $f_params[ 'target' ] ) ? $f_params[ 'target' ] : '';
	$attr = $f_params[ 'attr' ] =												isset( $f_params[ 'attr' ] ) ? $f_params[ 'attr' ] : ''; // extra attributes for the image element
	$minify = $f_params[ 'minify' ] =											isset( $f_params[ 'minify' ] ) ? $f_params[ 'minify' ] : TRUE; // if true, the html output will be minified
	$layout = $f_params[ 'layout' ] =											isset( $f_params[ 'layout' ] ) ? $f_params[ 'layout' ] : 'default';
	$figure = $f_params[ 'figure' ] =											isset( $f_params[ 'figure' ] ) ? $f_params[ 'figure' ] : FALSE; // if true, output a figure tag
	$f_params[ 'prevent_cache' ] =												isset( $f_params[ 'prevent_cache' ] ) ? $f_params[ 'prevent_cache' ] : FALSE; // prevent image cache
	$modal = $f_params[ 'modal' ] =												( isset( $f_params[ 'modal' ] ) OR ( check_var( $href ) AND check_var( $src ) AND $href == $src ) ) ? TRUE : FALSE;

	// Setting variables default values ----------------
	// -------------------------------------------------

	$CI =& get_instance();

	// verificando se o tema atual possui a view
	if ( file_exists( THEMES_PATH . theme_helpers_views_path() . 'vui_el' . DS . $layout . DS . 'thumb.php' ) ){

		$view = $CI->load->view( theme_helpers_views_path() . 'vui_el' . DS . $layout . DS . 'thumb', $f_params, TRUE );

	}
	// verificando se a view existe no diretório de views padrão
	else if ( file_exists( VIEWS_PATH . HELPERS_DIR_NAME . DS . 'vui_el' . DS . $layout . DS . 'thumb.php' ) ){

		$view = $CI->load->view( HELPERS_DIR_NAME . DS . 'vui_el' . DS . $layout . DS . 'thumb', $f_params, TRUE);

	}

	return $minify ? minify_html( $view ) : $view;

}

// ------------------------------------------------------------------------


/* End of file vui_elements_helper.php */
/* Location: ./system/helpers/vui_elements_helper.php */
