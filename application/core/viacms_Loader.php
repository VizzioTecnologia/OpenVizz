<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Viacms_Loader extends CI_Loader{
	
	function __construct(){
		
		parent::__construct();
		
		$this->_ci_ob_level  = ob_get_level();
		$this->_ci_library_paths = array(APPPATH, BASEPATH);
		$this->_ci_helper_paths = array(APPPATH, BASEPATH);
		$this->_ci_model_paths = array(APPPATH);
		$this->_ci_view_path = VIEWS_PATH;
		$this->_ci_view_paths = array(
			THEMES_PATH => TRUE,
			VIEWS_PATH => TRUE,
			APPPATH .  DS . 'errors' . DS => TRUE,
		);
		
		log_message('debug', "Loader Class Initialized");
		
	}
	
	/**
	 * Returns true if the model with the given name is loaded; false otherwise.
	 *
	 * @param   string  name for the model
	 * @return  bool
	 */
	public function is_model_loaded( $name ) {
		
		return in_array( $name, $this->_ci_models, TRUE );
		
	}
	
}
