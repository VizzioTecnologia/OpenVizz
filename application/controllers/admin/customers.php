<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'controllers/admin/main.php');

class Customers extends Main {
	
	public function __construct(){
		
		parent::__construct();
		
		$this->load->model(array('admin/customers_model'));
		
		set_current_component();
		
	}
	
	public function index(){
		$this->customers_management('customers_list');
	}
	
	/******************************************************************************/
	/******************************************************************************/
	/**************************** Customers management ****************************/
	
	public function customers_management($action = NULL, $var1 = NULL, $var2 = NULL, $var3 = NULL, $var4 = NULL, $var5 = NULL, $var6 = NULL){
		
		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;
		
		if ( ! $action ) redirect('admin/'.$this->component_name . '/' . __FUNCTION__ . '/' . 'companies_list');
		
		$url = get_url('admin'.$this->uri->ruri_string());
		
		// verifica se o usuário atual possui privilégios para gerenciar usuários, porém pode editar seu próprio usuário
		if ( ! $this->users->check_privileges('customers_management_customers_management') ){
			msg(('access_denied'),'title');
			msg(('access_denied_customers_management_customers_management'),'error');
			redirect('admin');
		};
		
		/**************************************************/
		/***************** Customers list *****************/
		
		if ($action == 'customers_list'){
			
			$this->load->helper(array('pagination'));
			
			// $var1 = página atual
			// $var2 = itens por página
			if ( $var1 < 1 OR ! gettype($var1) == 'int' ) $var1 = 1;
			if ( $var2 < 1 OR ! gettype($var2) == 'int' ) $var2 = $this->mcm->filtered_system_params['admin_items_per_page'];
			$offset = ($var1-1)*$var2;
			
			if ($customers = $this->customers_model->get_customers(NULL, $var2, $offset)){
				
				$data = array(
					'component_name' => $this->component_name,
					'customers' => $customers->result(),
					'pagination' => get_pagination('admin/customers/customers_management/customers_list/%p%/%ipp%', $var1, $var2, $this->customers_model->get_customers(NULL, NULL, NULL,'count_all_results')),
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
		
		/***************** Customers list *****************/
		/**************************************************/
		
		/**************************************************/
		/****************** Add customer ******************/
		
		else if ($action == 'add_customer'){
			
			//validação dos campos
			$this->form_validation->set_rules('title',lang('title'),'trim');
			$this->form_validation->set_rules('company_id',lang('company'),'trim|integer');
			if ( ! $this->input->post('company_id') )
				$this->form_validation->set_rules('contact_id',lang('contact'),'trim|required|integer');
			else
				$this->form_validation->set_rules('contact_id',lang('contact'),'trim|integer');
			
			$submit_action =
				$this->input->post('submit_cancel')?'cancel':
				($this->input->post('submit')?'submit':
				($this->input->post('submit_apply')?'apply':
				'none'));
			
			
			$categories = $this->customers_model->get_categories_tree(0,0,'list');
			
			$this->load->model('admin/companies_model');
			$companies = $this->companies_model->get_companies();
			
			$this->load->model('admin/contacts_model');
			$contacts = $this->contacts_model->get_contacts();
			
			$data = array(
				'component_name' => $this->component_name,
				'categories'=>$categories,
				'companies'=>$companies->result_array(),
				'contacts'=>$contacts->result_array(),
			);
			
			if( in_array($submit_action, array('cancel')) ){
				redirect_last_url();
			}
			// se a validação dos campos for negativa e mensagens de erro conter strings
			else if ( ( in_array($submit_action, array('submit','apply')) )
				AND ( ! $this->form_validation->run() AND validation_errors() != '' )
				){
				
				// verificando erros de validação do formulário
				msg(('update_customer_fail'),'title');
				msg(validation_errors('<div class="error">', '</div>'),'error');
				
			}
			// se a validação dos campos for bem sucedida
			else if ( $this->form_validation->run() AND (in_array($submit_action, array('submit','apply'))) ){
				
				$insert_data = elements(array(
					'title',
					'category_id',
					'company_id',
					'contact_id',
				),$this->input->post(NULL, TRUE));
				
				if ( ! $insert_data['title'] ){
					if ( $insert_data['company_id'] )
						$insert_data['title'] = $this->companies_model->get_companies( array( 't1.id' => $insert_data['company_id'] ) )->row()->trading_name;
					else if ( $insert_data['contact_id'] )
						$insert_data['title'] = $this->contacts_model->get_contacts( array( 't1.id' => $insert_data['contact_id'] ) )->row()->name;
				}
				
				$return_id=$this->customers_model->insert_customer($insert_data);
				if ($return_id){
					
					msg(('customer_added'),'success');
					if ($this->input->post('submit_apply')){
						redirect('admin/'.$this->component_name . '/' . __FUNCTION__.'/edit_customer/'.$return_id);
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
		
		/****************** Add customer ******************/
		/**************************************************/
		
		/**************************************************/
		/***************** Edit customer ******************/
		
		else if ($action == 'edit_customer' AND $var1 AND ($customer = $this->customers_model->get_customers(array('t1.id'=>$var1), 1))){
			
			//validação dos campos
			$this->form_validation->set_rules('title',lang('title'),'trim');
			$this->form_validation->set_rules('company_id',lang('company'),'trim|integer');
			if ( ! $this->input->post('company_id') )
				$this->form_validation->set_rules('contact_id',lang('contact'),'trim|required|integer');
			else
				$this->form_validation->set_rules('contact_id',lang('contact'),'trim|integer');
			
			$submit_action =
				$this->input->post('submit_cancel')?'cancel':
				($this->input->post('submit')?'submit':
				($this->input->post('submit_apply')?'apply':
				'none'));
			
			$categories = $this->customers_model->get_categories_tree(0,0,'list');
			
			$this->load->model('admin/companies_model');
			$companies = $this->companies_model->get_companies();
			
			$this->load->model('admin/contacts_model');
			$contacts = $this->contacts_model->get_contacts();
			
			$data = array(
				'component_name' => $this->component_name,
				'customer'=>$customer->row(),
				'categories'=>$categories,
				'companies'=>$companies->result_array(),
				'contacts'=>$contacts->result_array(),
			);
			
			if( in_array($submit_action, array('cancel')) ){
				redirect_last_url();
			}
			// se a validação dos campos for negativa e mensagens de erro conter strings
			else if ( ( in_array($submit_action, array('submit','apply')) )
				AND ( ! $this->form_validation->run() AND validation_errors() != '' )
				){
				
				// verificando erros de validação do formulário
				msg(('update_customer_fail'),'title');
				msg(validation_errors('<div class="error">', '</div>'),'error');
				
			}
			// se a validação dos campos for bem sucedida
			else if ( $this->form_validation->run() AND (in_array($submit_action, array('submit','apply'))) ){
				
				$update_data = elements(array(
					'title',
					'category_id',
					'company_id',
					'contact_id',
				),$this->input->post(NULL, TRUE));
				
				if ( ! $update_data['title'] ){
					if ( $update_data['company_id'] )
						$update_data['title'] = $this->companies_model->get_companies( array( 't1.id' => $update_data['company_id'] ) )->row()->trading_name;
					else if ( $update_data['contact_id'] )
						$update_data['title'] = $this->contacts_model->get_contacts( array( 't1.id' => $update_data['contact_id'] ) )->row()->name;
				}
				if ( $update_data['company_id'] AND $update_data['contact_id'] ){
					$update_data['contact_id'] = 0;
				}
				
				if ( $this->customers_model->update_customer($update_data, array('id'=>$var1)) ){
					msg(('customer_updated'),'success');
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
		
		/***************** Edit customer ******************/
		/**************************************************/
		
		/**************************************************/
		/**************** Remove customer *****************/
		
		else if ($action == 'remove_customer' AND $var1 AND ($customer = $this->customers_model->get_customers(array('t1.id' => $var1), 1)->row())){
			
			// $var1 = id do fornecedor
			
			if($this->input->post('submit_cancel')){
				redirect_last_url();
			}
			else if ($this->input->post('submit')){
				if ($this->customers_model->delete_customer(array('id'=>$var1))){
					msg(('customer_deleted'),'success');
					redirect_last_url();
				}
				else{
					msg(('customer_deleted_fail'),'error');
					redirect_last_url();
				}
			}
			else{
				$data=array(
					'component_name' => $this->component_name,
					'customer'=>$customer,
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
		
		/**************** Remove customer *****************/
		/**************************************************/
		
		/**************************************************/
		
	}
	
	/**************************** Customers management ****************************/
	/******************************************************************************/
	/******************************************************************************/
	
	/******************************************************************************/
	/******************************************************************************/
	/**************************** Categories management ***************************/
	
	public function categories_management($action = NULL, $var1 = NULL, $var2 = NULL, $var3 = NULL, $var4 = NULL, $var5 = NULL, $var6 = NULL){
		
		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;
		
		if ( ! $action ) redirect('admin/'.$this->component_name . '/' . __FUNCTION__ . '/' . 'categories_list');
		
		$url = get_url('admin'.$this->uri->ruri_string());
		
		// verifica se o usuário atual possui privilégios para gerenciar usuários, porém pode editar seu próprio usuário
		if ( ! $this->users->check_privileges('customers_management_customers_management') ){
			msg(('access_denied'),'title');
			msg(('access_denied_customers_management_customers_management'),'error');
			redirect('admin');
		};
		
		/**************************************************/
		/**************** Categories list *****************/
		
		if ($action == 'categories_list'){
			
			$this->load->helper(array('pagination'));
			
			// $var1 = página atual
			// $var2 = itens por página
			if ( $var1 < 1 OR ! gettype($var1) == 'int' ) $var1 = 1;
			if ( $var2 < 1 OR ! gettype($var2) == 'int' ) $var2 = $this->mcm->filtered_system_params['admin_items_per_page'];
			$offset = ($var1-1)*$var2;
			
			if ($categories = $this->customers_model->get_categories_tree(0,0,'list', NULL, $var2, $offset)){
				
				$data = array(
					'component_name' => $this->component_name,
					'categories' => $categories,
					'pagination' => get_pagination('admin/customers/customers_management/customers_list/%p%/%ipp%', $var1, $var2, $this->customers_model->get_customers(NULL, NULL, NULL,'count_all_results')),
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
		
		/**************** Categories list *****************/
		/**************************************************/
		
		/**************************************************/
		/****************** Add category ******************/
		
		else if ($action == 'add_category'){
			
			//validação dos campos
			$this->form_validation->set_rules('status',lang('status'),'trim|required|integer');
			$this->form_validation->set_rules('title',lang('title'),'trim|required');
			$this->form_validation->set_rules('alias',lang('alias'),'trim');
			$this->form_validation->set_rules('parent',lang('parent'),'trim|required|integer');
			$this->form_validation->set_rules('ordering',lang('ordering'),'trim|required|integer|greater_than[-1]');
			$this->form_validation->set_rules('description',lang('description'),'trim');
			
			$submit_action =
				$this->input->post('submit_cancel')?'cancel':
				($this->input->post('submit')?'submit':
				($this->input->post('submit_apply')?'apply':
				'none'));
			
			$categories = $this->customers_model->get_categories_tree(0,0,'list');
			
			$data = array(
				'component_name' => $this->component_name,
				'categories'=>$categories,
			);
			
			if( in_array($submit_action, array('cancel')) ){
				redirect_last_url();
			}
			// se a validação dos campos for negativa e mensagens de erro conter strings
			else if ( ( in_array($submit_action, array('submit','apply')) )
				AND ( ! $this->form_validation->run() AND validation_errors() != '' )
				){
				
				// verificando erros de validação do formulário
				msg(('add_customer_category_fail'),'title');
				msg(validation_errors('<div class="error">', '</div>'),'error');
				
			}
			// se a validação dos campos for bem sucedida
			else if ( $this->form_validation->run() AND (in_array($submit_action, array('submit','apply'))) ){
				
				$insert_data = elements(array(
					'alias',
					'title',
					'parent',
					'status',
					'ordering',
					'description',
				),$this->input->post(NULL, TRUE));
				
				if ($insert_data['alias'] == ''){
					$insert_data['alias'] = url_title($insert_data['title'],'-',TRUE);
				}
				
				$return_id=$this->customers_model->insert_category($insert_data);
				if ($return_id){
					
					msg(('customer_category_added'),'success');
					if ($this->input->post('submit_apply')){
						redirect('admin/'.$this->component_name . '/' . __FUNCTION__.'/edit_company/'.$return_id);
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
		
		/****************** Add customer ******************/
		/**************************************************/
		
		/**************************************************/
		/***************** Edit category ******************/
		
		else if ($action == 'edit_category' AND $var1 AND ($category = $this->customers_model->get_categories(array('t1.id'=>$var1), 1))){
			
			//validação dos campos
			$this->form_validation->set_rules('status',lang('status'),'trim|required|integer');
			$this->form_validation->set_rules('title',lang('title'),'trim|required');
			$this->form_validation->set_rules('alias',lang('alias'),'trim');
			$this->form_validation->set_rules('parent',lang('parent'),'trim|required|integer');
			$this->form_validation->set_rules('ordering',lang('ordering'),'trim|required|integer|greater_than[-1]');
			$this->form_validation->set_rules('description',lang('description'),'trim');
			
			$submit_action =
				$this->input->post('submit_cancel')?'cancel':
				($this->input->post('submit')?'submit':
				($this->input->post('submit_apply')?'apply':
				'none'));
			
			$data = array(
				'component_name' => $this->component_name,
				'category'=>$category->row(),
				'categories' => $this->customers_model->get_categories_as_list_childrens_hidden(0,$var1),
			);
			
			if( in_array($submit_action, array('cancel')) ){
				redirect_last_url();
			}
			// se a validação dos campos for negativa e mensagens de erro conter strings
			else if ( ( in_array($submit_action, array('submit','apply')) )
				AND ( ! $this->form_validation->run() AND validation_errors() != '' )
				){
				
				// verificando erros de validação do formulário
				msg(('update_customer_category_fail'),'title');
				msg(validation_errors('<div class="error">', '</div>'),'error');
				
			}
			// se a validação dos campos for bem sucedida
			else if ( $this->form_validation->run() AND (in_array($submit_action, array('submit','apply'))) ){
				
				$update_data = elements(array(
					'alias',
					'title',
					'parent',
					'status',
					'ordering',
					'description',
				),$this->input->post(NULL, TRUE));
				
				if ($update_data['alias'] == ''){
					$update_data['alias'] = url_title($update_data['title'],'-',TRUE);
				}
				
				if ( $this->customers_model->update_category($update_data, array('id'=>$var1)) ){
					msg(('customer_category_updated'),'success');
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
		
		/***************** Edit category ******************/
		/**************************************************/
		
		/**************************************************/
		/**************** Remove category *****************/
		
		else if ($action == 'remove_category' AND $var1 AND ($category = $this->customers_model->get_categories(array('t1.id'=>$var1), 1))){
			
			// $var1 = id do fornecedor
			
			if($this->input->post('submit_cancel')){
				redirect_last_url();
			}
			else if ( $this->input->post('category_id') > 0 AND $this->input->post('submit') ){
				
				$customers_update_data = array(
					'category_id' => 0,
				);
				$categories_update_data = array(
					'parent' => $category->row()->parent,
				);
				
				$this->customers_model->update_customer($customers_update_data, array('category_id' => $category->row()->id));
				$this->customers_model->update_category($categories_update_data, array('parent' => $category->row()->id));
				
				if ($this->customers_model->delete_category(array('id'=>$this->input->post('category_id')))){
					msg(('category_deleted'),'success');
					redirect_last_url();
				}
				else{
					msg($this->lang->line('category_deleted_fail'),'error');
					redirect_last_url();
				}
			}
			else{
				$customers = $this->customers_model->get_customers(array('t1.category_id' => $var1))->result();
				$childrens_categories = $this->customers_model->get_categories(array('t1.parent' => $var1))->result();
				
				if ($customers){
					
					$msg_customers = '<p>';
					$msg_customers .= lang('delete_warning_category_has_customers');
					$msg_customers .= '</p>';
					$msg_customers .= '<ul>';
					foreach($customers as $customer){
						$msg_customers .= '<li>';
						$msg_customers .= '<b>'.$customer->title.' (id '.$customer->id.')</b>';
						$msg_customers .= '</li>';
					};
					$msg_customers .= '</ul>';
					
					msg(('category_has_customers'),'title');
					msg($msg_customers,'warning');
				}
				
				if ($childrens_categories){
					
					$msg_childrens_categories = '<p>';
					$msg_childrens_categories .= lang('delete_warning_category_has_childrens');
					$msg_childrens_categories .= '</p>';
					$msg_childrens_categories .= '<ul>';
					foreach($childrens_categories as $children_category){
						$msg_childrens_categories .= '<li>';
						$msg_childrens_categories .= '<b>'.$children_category->title.' (id '.$children_category->id.')</b>';
						$msg_childrens_categories .= '</li>';
					};
					$msg_childrens_categories .= '</ul>';
					
					msg(('category_has_childrens_categories'),'title');
					msg($msg_childrens_categories,'warning');
				}
				
				$data=array(
					'component_name' => $this->component_name,
					'category'=>$category->row(),
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
		
		/**************** Remove category *****************/
		/**************************************************/
		
		/**************************************************/
		
	}
	
	/**************************** Categories management ***************************/
	/******************************************************************************/
	/******************************************************************************/
	
	/******************************************************************************/
	/******************************************************************************/
	/************************************ Ajax ************************************/
	
	public function ajax( $action = NULL, $var1 = NULL, $var2 = NULL ){
		
		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;
		
		/**************************************************/
		/**************** Get customer data ***************/
		
		if ($action == 'get_customer_data'){
			
			if ($customer = $this->customers_model->get_customers(array( 't1.id' => $var1 ), 1, NULL)->row_array()){
				
				$customer_type = $customer['company_id'] ? 'legal_entity' : ( $customer['contact_id'] ? 'individual_entity' : '' );
				
				$customer['contacts'] = json_decode($customer['contacts'], TRUE);
				
				$this->load->model('admin/contacts_model');
				if ( isset($customer['contacts']) AND $customer['contacts'] AND is_array($customer['contacts']) ) {
					foreach ($customer['contacts'] as $key => $contact) {
						$customer['contacts'][$key] = array_merge($contact, $this->contacts_model->get_contacts(array('t1.id'=>$contact['id']))->row_array());
					}
				}
				
				$customer['company_emails'] = json_decode($customer['company_emails'], TRUE);
				$customer['company_phones'] = json_decode($customer['company_phones'], TRUE);
				$customer['company_addresses'] = json_decode($customer['company_addresses'], TRUE);
				$customer['company_websites'] = json_decode($customer['company_websites'], TRUE);
				
				$customer['contact_emails'] = json_decode($customer['contact_emails'], TRUE);
				$customer['contact_phones'] = json_decode($customer['contact_phones'], TRUE);
				$customer['contact_addresses'] = json_decode($customer['contact_addresses'], TRUE);
				$customer['contact_websites'] = json_decode($customer['contact_websites'], TRUE);
				
				$data = array(
					'component_name' => $this->component_name,
					'f_action' => $action,
					'customer' => $customer,
				);
				
				$this->_page(
					
					array(
						
						'component_view_folder' => $this->component_view_folder,
						'function' => __FUNCTION__,
						'action' => $action,
						'layout' => 'default',
						'view' => $action . '_' . $customer_type,
						'data' => $data,
						'html' => FALSE,
						'load_index' => FALSE,
						
					)
					
				);
				
			}
		}
		
		/**************** Get customer data ***************/
		/**************************************************/
		
		else{
			
		}
		
	}
	
	/************************************ Ajax ************************************/
	/******************************************************************************/
	/******************************************************************************/
	
}
