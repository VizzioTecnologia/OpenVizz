<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'controllers/admin/main.php');

class Providers extends Main {
	
	public function __construct(){
		
		parent::__construct();
		
		$this->load->model(array('admin/providers_model'));
		
		set_current_component();
		
	}
	
	public function index(){
		$this->providers_management('providers_list');
	}
	
	/******************************************************************************/
	/******************************************************************************/
	/**************************** Providers management ****************************/
	
	public function providers_management($action = NULL, $var1 = NULL, $var2 = NULL, $var3 = NULL, $var4 = NULL, $var5 = NULL, $var6 = NULL){
		
		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;
		
		if ( ! $action ) redirect('admin/'.$this->component_name . '/' . __FUNCTION__ . '/' . 'companies_list');
		
		$url = get_url('admin'.$this->uri->ruri_string());
		
		// verifica se o usuário atual possui privilégios para gerenciar usuários, porém pode editar seu próprio usuário
		if ( ! $this->users->check_privileges('providers_management_providers_management') ){
			msg(('access_denied'),'title');
			msg(('access_denied_providers_management_providers_management'),'error');
			redirect('admin');
		};
		
		/**************************************************/
		/***************** Providers list *****************/
		
		if ($action == 'providers_list'){
			
			$this->load->helper(array('pagination'));
			
			// $var1 = página atual
			// $var2 = itens por página
			if ( $var1 < 1 OR ! gettype($var1) == 'int' ) $var1 = 1;
			if ( $var2 < 1 OR ! gettype($var2) == 'int' ) $var2 = $this->mcm->filtered_system_params['admin_items_per_page'];
			$offset = ($var1-1)*$var2;
			
			if ($providers = $this->providers_model->get_providers(NULL, $var2, $offset)){
				
				$data = array(
					'component_name' => $this->component_name,
					'providers' => $providers->result_array(),
					'pagination' => get_pagination('admin/providers/providers_management/providers_list/%p%/%ipp%', $var1, $var2, $this->providers_model->get_providers(NULL, NULL, NULL,'count_all_results')),
				);
				
				set_last_url($url);
				
				$this->_page(
					
					array(
						
						'component_view_folder' => $this->component_view_folder,
						'function' => __FUNCTION__,
						'action' => $action,
						'layout' => 'default',
						'view' => $action,
						'data' => $data,
						
					)
					
				);
				
			}
		}
		
		/***************** Providers list *****************/
		/**************************************************/
		
		/**************************************************/
		/****************** Add provider ******************/
		
		else if ($action == 'add_provider'){
			
			//validação dos campos
			$this->form_validation->set_rules('title',lang('title'),'trim');
			$this->form_validation->set_rules('company_id',lang('company'),'trim|required|integer');
			
			$submit_action =
				$this->input->post('submit_cancel')?'cancel':
				($this->input->post('submit')?'submit':
				($this->input->post('submit_apply')?'apply':
				'none'));
			
			
			$this->load->model('admin/companies_model');
			$companies = $this->companies_model->get_companies();
			
			$data = array(
				'component_name' => $this->component_name,
				'companies'=>$companies->result_array(),
			);
			
			if( in_array($submit_action, array('cancel')) ){
				redirect_last_url();
			}
			// se a validação dos campos for negativa e mensagens de erro conter strings
			else if ( ( in_array($submit_action, array('submit','apply')) )
				AND ( ! $this->form_validation->run() AND validation_errors() != '' )
				){
				
				// verificando erros de validação do formulário
				msg(('update_company_fail'),'title');
				msg(validation_errors('<div class="error">', '</div>'),'error');
				
			}
			// se a validação dos campos for bem sucedida
			else if ( $this->form_validation->run() AND (in_array($submit_action, array('submit','apply'))) ){
				
				$insert_data = elements(array(
					'title',
					'company_id',
					'default_provider_tax',
				),$this->input->post(NULL, TRUE));
				
				$provider = $this->companies_model->get_companies(array('t1.id'=>$insert_data['company_id']), 1);
				if ( $insert_data['title'] == '' ) $insert_data['title'] = $provider->row()->trading_name;
				
				$return_id=$this->providers_model->insert_provider($insert_data);
				if ($return_id){
					
					msg(('company_added'),'success');
					if ($this->input->post('submit_apply')){
						redirect('admin/'.$this->component_name . '/' . __FUNCTION__.'/edit_provider/'.$return_id);
					}
					else{
						redirect_last_url();
					}
				}
				
			}
			
			$this->_page(
				
				array(
					
					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => $action,
					'layout' => 'default',
					'view' => $action,
					'data' => $data,
					
				)
				
			);
			
		}
		
		/****************** Add provider ******************/
		/**************************************************/
		
		/**************************************************/
		/***************** Edit provider ******************/
		
		else if ($action == 'edit_provider' AND $var1 AND ($provider = $this->providers_model->get_providers(array('t1.id'=>$var1), 1))){
			
			//validação dos campos
			$this->form_validation->set_rules('title',lang('title'),'trim');
			$this->form_validation->set_rules('company_id',lang('company'),'trim|required|integer');
			
			$submit_action =
				$this->input->post('submit_cancel')?'cancel':
				($this->input->post('submit')?'submit':
				($this->input->post('submit_apply')?'apply':
				'none'));
			
			$this->load->model('admin/companies_model');
			$companies = $this->companies_model->get_companies();
			
			$data = array(
				'component_name' => $this->component_name,
				'provider'=>$provider->row(),
				'companies'=>$companies->result_array(),
			);
			
			if( in_array($submit_action, array('cancel')) ){
				redirect_last_url();
			}
			// se a validação dos campos for negativa e mensagens de erro conter strings
			else if ( ( in_array($submit_action, array('submit','apply')) )
				AND ( ! $this->form_validation->run() AND validation_errors() != '' )
				){
				
				// verificando erros de validação do formulário
				msg(('update_provider_fail'),'title');
				msg(validation_errors('<div class="error">', '</div>'),'error');
				
			}
			// se a validação dos campos for bem sucedida
			else if ( $this->form_validation->run() AND (in_array($submit_action, array('submit','apply'))) ){
				
				$update_data = elements(array(
					'title',
					'company_id',
					'default_provider_tax',
				),$this->input->post(NULL, TRUE));
				
				if ( $update_data['title'] == '' ) $update_data['title'] = $provider->row()->trading_name;
				
				if ( $this->providers_model->update_provider($update_data, array('id'=>$var1)) ){
					msg(('provider_updated'),'success');
					if ($this->input->post('submit_apply')){
						redirect('admin/'.$this->uri->ruri_string());
					}
					else{
						redirect_last_url();
					}
				}
				
			}
			
			$this->_page(
				
				array(
					
					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => $action,
					'layout' => 'default',
					'view' => $action,
					'data' => $data,
					
				)
				
			);
			
		}
		
		/***************** Edit provider ******************/
		/**************************************************/
		
		/**************************************************/
		/**************** Remove provider *****************/
		
		else if ($action == 'remove_provider' AND $var1 AND ($provider = $this->providers_model->get_providers(array('t1.id' => $var1), 1)->row())){
			
			// $var1 = id do fornecedor
			
			if($this->input->post('submit_cancel')){
				redirect_last_url();
			}
			else if ($this->input->post('submit')){
				if ($this->providers_model->delete_provider(array('id'=>$var1))){
					msg(('provider_deleted'),'success');
					redirect_last_url();
				}
				else{
					msg(('provider_deleted_fail'),'error');
					redirect_last_url();
				}
			}
			else{
				$data=array(
					'component_name' => $this->component_name,
					'provider'=>$provider,
				);
				
				$this->_page(
					
					array(
						
						'component_view_folder' => $this->component_view_folder,
						'function' => __FUNCTION__,
						'action' => $action,
						'layout' => 'default',
						'view' => $action,
						'data' => $data,
						
					)
					
				);
				
			}
			
		}
		
		/**************** Remove provider *****************/
		/**************************************************/
		
		/**************************************************/
		
	}
	
	/***************************** Contacts management ****************************/
	/******************************************************************************/
	/******************************************************************************/
	
}
