<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'controllers/admin/main.php');

class Responsive_file_manager extends Main {
	
	public function __construct(){
		
		parent::__construct();
		
		$this->load->model(array('admin/contacts_model'));
		
		set_current_component();
		
	}
	
	public function index(){
		
		// -------------------------------------------------
		// Parsing vars ------------------------------------
		
		$f_params = $this->uri->ruri_to_assoc();
		
		$action =								@isset( $f_params[ 'a' ] ) ? $f_params[ 'a' ] : 'rfm'; // action
		
		// Parsing vars ------------------------------------
		// -------------------------------------------------
		
		// atualizando informações do componente atual
		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;
		
		// verifica se o usuário atual possui privilégios para gerenciar contatos
		if ( ! $this->users->check_privileges('media_management') ){
			msg(('access_denied'),'title');
			msg(('access_denied_media_management'),'error');
			
			redirect('admin');
		};
		if ( ! $this->users->check_privileges('responsive_file_manager_management') ){
			msg(('access_denied'),'title');
			msg(('access_denied_responsive_file_manager'),'error');
			
			redirect('admin');
			
		};
		
		$base_link_prefix = 'admin/' . $this->component_name . '/' . __FUNCTION__ . '/';
		
		$base_link_array = array(
			
		);
		
		$add_link_array = $base_link_array + array(
			
			'a' => 'am',
			'sa' => 'select_module_type',
			
		);
		
		$modules_list_link_array = $base_link_array + array(
			
			'a' => 'ml',
			
		);
		
		$data[ 'add_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $add_link_array );
		$data[ 'modules_list_link' ] = $base_link_prefix . $this->uri->assoc_to_uri( $modules_list_link_array );
		
		$url = get_url( 'admin' . $this->uri->ruri_string() );
		
		/*
		 ********************************************************
		 --------------------------------------------------------
		 Change ordering, up or down
		 --------------------------------------------------------
		 */
		
		if ( $action == 'rfm' ){
			
			$this->_page(
				
				array(
					
					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => 'responsive_file_manager',
					'layout' => 'default',
					'view' => 'responsive_file_manager',
					'data' => $data,
					
				)
				
			);
			
		}
		
		/*
		 --------------------------------------------------------
		 Change ordering, up or down
		 --------------------------------------------------------
		 ********************************************************
		 */
		
		else{
			show_404();
		}
	}
	
	
}
