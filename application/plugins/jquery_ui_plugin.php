<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jquery_ui_plugin extends Jquery_plugin{
	
	public function run( &$data, $params = NULL ){
		
		$return = FALSE;
		
		if ( parent::_performed( 'jquery' ) ){
			
			log_message( 'debug', '[Plugins] jQuery UI plugin initialized' );
			
			if ( ! defined( 'JS_JQUERY_UI' ) ) define( 'JS_JQUERY_UI', TRUE );
			
			if ( ! defined( 'JS_JQUERY_UI_JS_URL' ) ) define( 'JS_JQUERY_UI_JS_URL', JS_DIR_URL . '/plugins/jquery-ui/jquery-ui-1.10.3/ui' );
			if ( ! defined( 'JS_JQUERY_UI_CSS_URL' ) ) define( 'JS_JQUERY_UI_CSS_URL', JS_DIR_URL . '/plugins/jquery-ui/jquery-ui-1.10.3/themes' );
			
			if ( ! defined( 'JS_JQUERY_UI_LOAD_ALL_COMPONENTS' ) ) define( 'JS_JQUERY_UI_LOAD_ALL_COMPONENTS', TRUE );
			
			if ( JS_JQUERY_UI_LOAD_ALL_COMPONENTS ) {
				
				$this->voutput->append_head_stylesheet( 'jquery_ui_all', JS_JQUERY_UI_CSS_URL . '/base/jquery.ui.all.css' );
				$this->voutput->append_head_script( 'jquery_ui', JS_JQUERY_UI_JS_URL . '/jquery-ui.js' );
				
			}
			else {
				
				// CSS
				
				// Base
				$this->voutput->append_head_stylesheet( 'jquery_ui', JS_JQUERY_UI_CSS_URL . '/base/jquery.ui.base.css' );
				// Datepicker
				$this->voutput->append_head_stylesheet( 'jquery_ui_datepicker', JS_JQUERY_UI_CSS_URL . '/jquery.ui.datepicker.css' );
				// Slider
				$this->voutput->append_head_stylesheet( 'jquery_ui_slider', JS_JQUERY_UI_CSS_URL . '/base/jquery.ui.slider.css' );
				
				
				// JS
				
				// Core
				// The core of jQuery UI, required for all interactions and widgets.
				$this->voutput->append_head_script( 'jquery_ui_core', JS_JQUERY_UI_JS_URL . '/jquery.ui.core.js' );
				// Widget
				// Provides a factory for creating stateful widgets with a common API.
				$this->voutput->append_head_script( 'jquery_ui_widget', JS_JQUERY_UI_JS_URL . '/jquery.ui.widget.js' );
				// Mouse
				// Abstracts mouse-based interactions to assist in creating certain widgets.
				$this->voutput->append_head_script( 'jquery_ui_mouse', JS_JQUERY_UI_JS_URL . '/jquery.ui.mouse.js' );
				// Position
				// Positions elements relative to other elements.
				$this->voutput->append_head_script( 'jquery_ui_position', JS_JQUERY_UI_JS_URL . '/jquery.ui.position.js' );
				// Draggable
				// Enables dragging functionality for any element.
				$this->voutput->append_head_script( 'jquery_ui_draggable', JS_JQUERY_UI_JS_URL . '/jquery.ui.draggable.js' );
				// Droppable
				// Enables drop targets for draggable elements.
				$this->voutput->append_head_script( 'jquery_ui_droppable', JS_JQUERY_UI_JS_URL . '/jquery.ui.droppable.js' );
				// Datepicker
				// Displays a calendar from an input or inline for selecting dates.
				$this->voutput->append_head_script( 'jquery_ui_datepicker', JS_JQUERY_UI_JS_URL . '/jquery.ui.datepicker.js' );
				// Slider
				// Displays a flexible slider with ranges and accessibility via keyboard.
				$this->voutput->append_head_script( 'jquery_ui_slider', JS_JQUERY_UI_JS_URL . '/jquery.ui.slider.js' );
				
			}
			
			$this->voutput->append_head_script_declaration( 'jquery_ui', $this->load->view( 'plugins/jquery_ui/default/jquery_ui', NULL, TRUE ), NULL, NULL );
			
			$return = TRUE;
			
		}
		else {
			
			log_message( 'debug', '[Plugins] jQuery UI plugin could not be executed! jQuery plugin not performed!' );
			
			$return = FALSE;
			
		}
		
		parent::_set_performed( 'jquery_ui' );
		
		return $return;
		
	}
	
}
