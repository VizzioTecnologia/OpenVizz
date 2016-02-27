<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'controllers/admin/main.php');

class Contacts extends Main {

	public function __construct(){

		parent::__construct();

		$this->load->model( array( 'admin/contacts_model' ) );

		set_current_component();

	}

	public function index(){
		$this->contacts_management('contacts_list');
	}

	/******************************************************************************/
	/******************************************************************************/
	/***************************** Contacts management ****************************/

	public function contacts_management($action = NULL, $var1 = NULL, $var2 = NULL, $var3 = NULL, $var4 = NULL, $var5 = NULL, $var6 = NULL){

		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;

		if ( ! $action ) redirect('admin/'.$this->component_name . '/' . __FUNCTION__ . '/' . 'countries_list');

		$url = get_url('admin'.$this->uri->ruri_string());

		// verifica se o usuário atual possui privilégios para gerenciar contatos
		if ( $action != 'edit_contact' AND ! $this->users->check_privileges('contacts_management_contacts_management') ){
			msg(('access_denied'),'title');
			msg(('access_denied_contacts_management_contacts_management'),'error');
			redirect('admin');
		};

		/**************************************************/
		/************* Contacts list / search *************/

		if ( $action == 'contacts_list' OR $action == 'search' ){

			$this->load->helper(array('pagination'));

			// $var1 = página atual
			// $var2 = itens por página
			if ( $var1 < 1 OR ! gettype($var1) == 'int' ) $var1 = 1;
			if ( $var2 < 1 OR ! gettype($var2) == 'int' ) $var2 = $this->mcm->filtered_system_params['admin_items_per_page'];
			$offset = ($var1-1)*$var2;

			//validação dos campos
			$errors = FALSE;
			$errors_msg = '';
			$terms = trim($this->input->post('terms', TRUE) ? $this->input->post('terms', TRUE) : ($this->input->get('q') ? $this->input->get('q') : FALSE) );
			if ( ( $this->input->post('submit_search', TRUE) AND ( $terms OR $terms == 0) ) ){

				if ( strlen($terms) == 0 ){
					$errors = TRUE;
					$errors_msg .= '<div class="error">'.lang('validation_error_terms_not_blank').'</div>';
				}
				if ( strlen($terms) < 2 ){
					$errors = TRUE;
					$errors_msg .= '<div class="error">'.sprintf(lang('validation_error_terms_min_lenght'), 2).'</div>';
				}

			}
			else if ( $this->input->post('submit_cancel_search', TRUE) ){
				redirect( 'admin/'.$this->component_name . '/' . __FUNCTION__.'/contacts_list' );
			}

			$data['search']['terms'] = $terms;

			$this->form_validation->set_rules('terms',lang('terms'),'trim|min_length[2]');

			if( ( $this->input->post() OR $terms ) AND ! $errors){

				$condition = NULL;
				$or_condition = NULL;

				if( $terms ){

					$get_query = urlencode($terms);

					$full_term = $terms;
					$order_by = 'FIELD(t1.name, \''.$full_term.'\') ASC, t1.id ASC';

					$condition['fake_index_1'] = '';
					$condition['fake_index_1'] .= '(';
					$condition['fake_index_1'] .= '`t1`.`name` LIKE \'%'.$full_term.'%\' ';
					$condition['fake_index_1'] .= 'OR `t1`.`phones` LIKE \'%'.$full_term.'%\' ';
					$condition['fake_index_1'] .= 'OR `t1`.`addresses` LIKE \'%'.$full_term.'%\' ';
					$condition['fake_index_1'] .= 'OR `t1`.`emails` LIKE \'%'.$full_term.'%\' ';
					$condition['fake_index_1'] .= ')';

					$terms = str_replace('#', ' ', $terms);
					$terms = explode(" ", $terms);

					$and_operator = FALSE;
					$like_query = '';

					foreach ($terms as $key => $term) {

						$like_query .= $and_operator === TRUE ? 'AND ' : '';
						$like_query .= '(';
						$like_query .= '`t1`.`name` LIKE \'%'.$term.'%\' ';
						$like_query .= 'OR `t1`.`phones` LIKE \'%'.$term.'%\' ';
						$like_query .= 'OR `t1`.`addresses` LIKE \'%'.$term.'%\' ';
						$like_query .= 'OR `t1`.`emails` LIKE \'%'.$term.'%\' ';
						$like_query .= ')';

						if ( ! $and_operator ){
							$and_operator = TRUE;
						}

					}

					$or_condition = '(' . $like_query . ')';

					$contacts = $this->contacts_model->get_contacts_search_results($condition, $or_condition, $var2, $offset, NULL, $order_by, FALSE)->result_array();

					foreach ($contacts as $key => $contact) {

						$contacts[$key]['phones'] = json_decode($contacts[$key]['phones'], TRUE);
						$contacts[$key]['addresses'] = json_decode($contacts[$key]['addresses'], TRUE);
						$contacts[$key]['emails'] = json_decode($contacts[$key]['emails'], TRUE);

						foreach ($terms as $term) {

							$contacts[$key]['name'] = str_highlight( $contacts[$key]['name'], $term );

							if ( isset($contacts[$key]['phones']) AND $contacts[$key]['phones'] AND is_array($contacts[$key]['phones']) ){

								foreach ($contacts[$key]['phones'] as $key_2 => $phone) {

									$contacts[$key]['phones'][$key_2]['title'] = str_highlight( $contacts[$key]['phones'][$key_2]['title'], $term );
									$contacts[$key]['phones'][$key_2]['number'] = str_highlight( $contacts[$key]['phones'][$key_2]['number'], $term );
									$contacts[$key]['phones'][$key_2]['extension_number'] = str_highlight( $contacts[$key]['phones'][$key_2]['extension_number'], $term );

								}

							}

							if ( isset($contacts[$key]['addresses']) AND $contacts[$key]['addresses'] AND is_array($contacts[$key]['addresses']) ){

								foreach ($contacts[$key]['addresses'] as $key_2 => $address) {

									$contacts[$key]['addresses'][$key_2]['title'] = str_highlight( $contacts[$key]['addresses'][$key_2]['title'], $term );
									$contacts[$key]['addresses'][$key_2]['state_acronym'] = str_highlight( $contacts[$key]['addresses'][$key_2]['state_acronym'], $term );
									$contacts[$key]['addresses'][$key_2]['city_title'] = str_highlight( $contacts[$key]['addresses'][$key_2]['city_title'], $term );
									$contacts[$key]['addresses'][$key_2]['neighborhood_title'] = str_highlight( $contacts[$key]['addresses'][$key_2]['neighborhood_title'], $term );
									$contacts[$key]['addresses'][$key_2]['public_area_title'] = str_highlight( $contacts[$key]['addresses'][$key_2]['public_area_title'], $term );
									$contacts[$key]['addresses'][$key_2]['postal_code'] = str_highlight( $contacts[$key]['addresses'][$key_2]['postal_code'], $term );
									$contacts[$key]['addresses'][$key_2]['complement'] = str_highlight( $contacts[$key]['addresses'][$key_2]['complement'], $term );

								}

							}

							if ( isset($contacts[$key]['emails']) AND $contacts[$key]['emails'] AND is_array($contacts[$key]['emails']) ){

								foreach ($contacts[$key]['emails'] as $key_2 => $email) {

									$contacts[$key]['emails'][$key_2]['title'] = str_highlight( $contacts[$key]['emails'][$key_2]['title'], $term );
									$contacts[$key]['emails'][$key_2]['email'] = str_highlight( $contacts[$key]['emails'][$key_2]['email'], $term );

								}

							}

						}

						$contacts[$key]['phones'] = json_encode($contacts[$key]['phones']);
						$contacts[$key]['addresses'] = json_encode($contacts[$key]['addresses']);
						$contacts[$key]['emails'] = json_encode($contacts[$key]['emails']);

					}

					$data['contacts'] = $contacts;
					$data['pagination'] = get_pagination('admin/'.$this->component_name . '/' . __FUNCTION__ . '/' . $action.'/%p%/%ipp%?q='.$get_query, $var1, $var2, $this->contacts_model->get_contacts_search_results($condition, $or_condition, NULL, NULL,'count_all_results'));

				}

			}
			else if ($contacts = $this->contacts_model->get_contacts(NULL, $var2, $offset)){

				if ( $errors ){

					$data['post'] = $this->input->post();

					msg(('search_fail'),'title');
					msg($errors_msg,'error');
				}

				$data = array_merge($data, array(
					'contacts' => $contacts->result_array(),
					'pagination' => get_pagination('admin/'.$this->component_name . '/' . __FUNCTION__ . '/' . $action.'/%p%/%ipp%', $var1, $var2, $this->contacts_model->get_contacts(NULL, NULL, NULL,'count_all_results')),
				));

			}

			$data = array_merge( $data, array(

				'component_name' => $this->component_name,
				'f_action' => $action,

			) );
			set_last_url( $url . ( ( isset( $get_query ) AND $get_query ) ? '?q=' . $get_query : '' ) );

			$this->_page(

				array(

					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => 'contacts_list',
					'layout' => 'default',
					'view' => 'contacts_list',
					'data' => $data,

				)

			);

		}

		/************* Contacts list / search *************/
		/**************************************************/

		/**************************************************/
		/*************** Add / Edit contact ***************/

		else if ($action == 'add_contact' OR ( $action == 'edit_contact' AND $var1 AND $contact = $this->contacts_model->get_contacts(array('t1.id'=>$var1), 1)->row_array() ) ){

			if ( $action == 'add_contact' ){

				$contact = array();

			}
			else if ( $action == 'edit_contact' ){

				$this->load->model('admin/companies_model');

			}

			// capturando os dados obtidos via post, e guardando-os na variável $post_data
			$post_data = $this->input->post();

			// inicializando a variável das empresas
			$contact['companies'] = array();

			// aqui definimos as ações obtidas via post, ex.: ao salvar, cancelar, adicionar um contato, telefone, endereço, etc.
			// acionados ao submeter os forms
			$submit_action =
				$this->input->post('submit_add_email')?'add_email':
				($this->input->post('submit_add_phone')?'add_phone':
				($this->input->post('submit_add_address')?'add_address':
				($this->input->post('submit_add_website')?'add_website':
				($this->input->post('submit_add_company')?'add_company':
				($this->input->post('submit_cancel')?'cancel':
				($this->input->post('submit')?'submit':
				($this->input->post('submit_apply')?'apply':
				'none')))))));

			// se houver dados de referência, ou seja, se está vindo de uma seleção de itens...
			if ( $this->session->envdata( 'reference_data_id' ) ){

				$reference_data_id = $this->session->envdata( 'reference_data_id' );

				$this->session->unset_envdata('reference_data_id');

				$temp_data = $this->main_model->get_temp_data(array('id' =>$reference_data_id))->row();

				$this->main_model->delete_temp_data(array('id' =>$reference_data_id));

				if ( $temp_data ){

					$post_data = json_decode($temp_data->data, TRUE);

					// se os dados de referência refere-se a endereços
					if ( isset( $post_data['component_selection'] ) AND $post_data['component_selection'] == 'places' ){

						end($post_data['addresses']);
						$np_key = key($post_data['addresses']);

						// adiciona campos em branco
						if ( isset($post_data['selection']) AND $post_data['selection'] != 'canceled' ){

							$np_key++;

							// key é a ordem do produto na listagem
							$post_data['addresses'][$np_key]['key'] = $np_key;

						}

						if ( isset($post_data['selected_addresses']) ){

							// varre os endereços selecionados
							foreach ($post_data['selected_addresses'] as $address_key => $address) {

								// para cada endereço selecionado, cria-se os endereços produtos
								foreach ($address as $key => $value) {
									$post_data['addresses'][$np_key][$key] = isset($address[$key]) ? $address[$key] : '';
								}

								$np_key++;

							}
						}

					}
					// se os dados de referência refere-se a empresas
					if ( isset( $post_data['component_selection'] ) AND $post_data['component_selection'] == 'companies' ){

						if ( ! isset($post_data['companies']) ){

							$post_data['companies'] = array();

						}

						end( $post_data['companies'] );
						$current_companies_key = key($post_data['companies']) ? key($post_data['companies']) : 0 ;

						$current_companies_key++;

						if ( isset($post_data['selection']) AND $post_data['selection'] == 'selected' ){

							$this->load->model('admin/companies_model');

							// varre as empresas selecionados
							foreach ($post_data['selected_companies'] as $company_key => $company) {

								/*****************************************************/
								/********** Adicionando o contato a empresa **********/

								$company_contact_db_data = array(

									'company_id' => $company['id'],
									'contact_data' => array(

										'id' => $contact['id'],
										'title' => lang('contact'),
										'name' => $contact['name'],

									),
									'op' => 'add',

								);

								$this->companies_model->company_contact_operation( $company_contact_db_data );

								/********** Adicionando o contato a empresa **********/
								/*****************************************************/

							}

						}

					}

					//print_r($post_data);

				}

			}

			if ( ! $post_data ){

				if ( $action == 'edit_contact' ) {

					/*************************************/
					/*************** emails **************/

					$contact['emails'] = json_decode($contact['emails'], TRUE);
					// verifica se $contact['emails'] é um json válido
					if ( $contact['emails'] ){
						// inicializando o array de email
						// o índice 0 é temporário, definido aqui apenas para evitar que seja preenchido pelo json_decode($contact['emails'], TRUE)
						// a ideia é reservar o espaço, e em seguida excluí-lo
						$contact['emails'] = array_merge(array(0), $contact['emails']);
						// aqui, excluo o primeiro índice, deixando o array começando de 1, e não de 0
						unset($contact['emails'][0]);
					}
					else{
						$contact['emails'] = array();
					}

					/*************** emails **************/
					/*************************************/

					/*************************************/
					/************* telefones *************/

					$contact['phones'] = json_decode($contact['phones'], TRUE);
					// verifica se $contact['phones'] é um json válido
					if ( $contact['phones'] ){
						// inicializando o array de telefone
						// o índice 0 é temporário, definido aqui apenas para evitar que seja preenchido pelo json_decode($contact['phones'], TRUE)
						// a ideia é reservar o espaço, e em seguida excluí-lo
						$contact['phones'] = array_merge(array(0), $contact['phones']);
						// aqui, excluo o primeiro índice, deixando o array começando de 1, e não de 0
						unset($contact['phones'][0]);
					}
					else{
						$contact['phones'] = array();
					}

					/************* telefones *************/
					/*************************************/

					/*************************************/
					/************* endereços *************/

					$contact['addresses'] = json_decode($contact['addresses'], TRUE);
					// verifica se $contact['addresses'] é um json válido
					if ( $contact['addresses'] ){
						// inicializando o array de endereços
						// o índice 0 é temporário, definido aqui apenas para evitar que seja preenchido pelo json_decode($contact['addresses'], TRUE)
						// a ideia é reservar o espaço, e em seguida excluí-lo
						$contact['addresses'] = array_merge(array(0), $contact['addresses']);
						// aqui, excluo o primeiro índice, deixando o array começando de 1, e não de 0
						unset($contact['addresses'][0]);
					}
					else{
						$contact['addresses'] = array();
					}

					/************* endereços *************/
					/*************************************/

					/*************************************/
					/************** websites *************/

					$contact['websites'] = json_decode($contact['websites'], TRUE);
					// verifica se $contact['websites'] é um json válido
					if ( $contact['websites'] ){
						// inicializando o array de website
						// o índice 0 é temporário, definido aqui apenas para evitar que seja preenchido pelo json_decode($contact['websites'], TRUE)
						// a ideia é reservar o espaço, e em seguida excluí-lo
						$contact['websites'] = array_merge(array(0), $contact['websites']);
						// aqui, excluo o primeiro índice, deixando o array começando de 1, e não de 0
						unset($contact['websites'][0]);
					}
					else{
						$contact['websites'] = array();
					}

					/************** websites *************/
					/*************************************/

				}

			}
			else{

				/*************************************/
				/************** empresas *************/

				/*****************************************************/
				/********* Atualizando o contato as empresas *********/

				if ( isset ( $post_data['companies'] ) ){

					$this->load->model('admin/companies_model');

					foreach ( $post_data['companies'] as $company_key => $company ) {

						foreach ( $company['relationships'] as $relationship_key => $relationship ) {

							$relationship['name'] = isset( $post_data['name'] ) ? $post_data['name'] : $contact['name'];

							$company_contact_db_data = array(

								'company_id' => $company['id'],
								'contact_data' => $relationship,
								'op' => 'edit',

							);

							$this->companies_model->company_contact_operation( $company_contact_db_data );

						}

					}

				}

				/********* Atualizando o contato as empresas *********/
				/*****************************************************/

				// verifica se há pedido de remoção de campos de empresas
				if ( isset($post_data['submit_remove_company']) ){
					// obtem o primeiro índice do array de remoção de empresas
					reset($post_data['submit_remove_company']);

					$company_key = key($post_data['submit_remove_company']);

					foreach ($post_data['companies'][$company_key]['relationships'] as $relationship_key => $relationship) {

						/*****************************************************/
						/*********** Removendo o contato da empresa **********/

						$company_contact_db_data = array(

							'company_id' => $post_data['companies'][$company_key]['id'],
							'contact_data' => array(

								'key' => $relationship['key'],
								'id' => $contact['id'],
								'title' => lang('contact'),
								'name' => isset( $post_data['name'] ) ? $post_data['name'] : $contact['name'],

							),
							'op' => 'remove',

						);

						$this->companies_model->company_contact_operation( $company_contact_db_data );

						/*********** Removendo o contato da empresa **********/
						/*****************************************************/

					}

				}

				// verifica se há pedido de remoção de campos de relação com a empresa
				if ( isset($post_data['submit_remove_company_relationship']) ){
					// obtem o primeiro índice do array de remoção de empresas
					reset($post_data['submit_remove_company_relationship']);

					$company_key = key($post_data['submit_remove_company_relationship']);
					$relationship_key = key($post_data['submit_remove_company_relationship'][$company_key]);

					/*****************************************************/
					/*********** Removendo o contato da empresa **********/

					$company_contact_db_data = array(

						'company_id' => $post_data['companies'][$company_key]['id'],
						'contact_data' => array(

							'id' => $contact['id'],
							'title' => lang('contact'),
							'key' => $relationship_key,

						),
						'op' => 'remove',

					);

					$this->companies_model->company_contact_operation( $company_contact_db_data );

					/*********** Removendo o contato da empresa **********/
					/*****************************************************/

				}
				/*
				// reordenando os índices das empresas
				$post_data['companies'] = isset($post_data['companies']) ? $post_data['companies'] : array();
				$post_data['companies'] = array_merge(array(0), $post_data['companies']);
				$post_data['companies'] = array_values($post_data['companies']);
				unset($post_data['companies'][0]);
				*/
				// verifica se há pedido de adição de campo de endereço, redireciona para tela de seleção
				if ( in_array($submit_action, array('add_company'))){

					$post_data = json_encode($post_data);
					$return_id = $this->main_model->insert_temp_data(get_url('admin'.$this->uri->ruri_string()), $post_data);

					$this->session->set_envdata('reference_data_id',$return_id);

					redirect(get_url('admin/companies/companies_select'));

				}

				/************** empresas *************/
				/*************************************/

				/*************************************/
				/*************** emails **************/

				// verifica se há pedido de remoção de campos de emails
				if ( isset($post_data['submit_remove_email']) ){
					// obtem o primeiro índice do array de remoção de emails
					reset($post_data['submit_remove_email']);
					unset($post_data['emails'][key($post_data['submit_remove_email'])]);
				}

				// verifica se há pedido de adição de campos de email
				if( in_array($submit_action, array('add_email')) ){

					$ph_key = 0;

					// se existem campos de email, obtem o último índice
					if ( isset( $post_data['emails'] ) ){

						end($post_data['emails']);
						$ph_key = key($post_data['emails']);

					}

					for ($i=$ph_key; $i < $ph_key + $post_data['email_fields_to_add']; $i++) {

						$post_data['emails'][$i+1] = array();

						// key é a ordem do email na listagem
						$post_data['emails'][$i+1]['key'] = $i+1;

					}

				}

				// reordenando os índices dos emails
				$post_data['emails'] = isset($post_data['emails']) ? $post_data['emails'] : array();
				$post_data['emails'] = array_merge(array(0), $post_data['emails']);
				$post_data['emails'] = array_values($post_data['emails']);
				unset($post_data['emails'][0]);

				/*************** emails **************/
				/*************************************/

				/*************************************/
				/************* telefones *************/

				// verifica se há pedido de remoção de campos de telefones
				if ( isset($post_data['submit_remove_phone']) ){
					// obtem o primeiro índice do array de remoção de telefones
					reset($post_data['submit_remove_phone']);
					unset($post_data['phones'][key($post_data['submit_remove_phone'])]);
				}

				// verifica se há pedido de adição de campos de telefone
				if( in_array($submit_action, array('add_phone')) ){

					$ph_key = 0;

					// se existem campos de telefone, obtem o último índice
					if ( isset( $post_data['phones'] ) ){

						end($post_data['phones']);
						$ph_key = key($post_data['phones']);

					}

					for ($i=$ph_key; $i < $ph_key + $post_data['phone_fields_to_add']; $i++) {

						$post_data['phones'][$i+1] = array();

						// key é a ordem do telefone na listagem
						$post_data['phones'][$i+1]['key'] = $i+1;

					}

				}

				// reordenando os índices dos telefones
				$post_data['phones'] = isset($post_data['phones']) ? $post_data['phones'] : array();
				$post_data['phones'] = array_merge(array(0), $post_data['phones']);
				$post_data['phones'] = array_values($post_data['phones']);
				unset($post_data['phones'][0]);

				/************* telefones *************/
				/*************************************/

				/*************************************/
				/************* endereços *************/

				// verifica se há pedido de remoção de campos de endereços
				if ( isset($post_data['submit_remove_address']) ){
					// obtem o primeiro índice do array de remoção de endereços
					reset($post_data['submit_remove_address']);
					unset($post_data['addresses'][key($post_data['submit_remove_address'])]);
				}

				// reordenando os índices dos endereços
				$post_data['addresses'] = isset($post_data['addresses']) ? $post_data['addresses'] : array();
				$post_data['addresses'] = array_merge(array(0), $post_data['addresses']);
				$post_data['addresses'] = array_values($post_data['addresses']);
				unset($post_data['addresses'][0]);

				// verifica se há pedido de adição de campo de endereço, redireciona para tela de seleção
				if ( in_array($submit_action, array('add_address'))){

					$post_data = json_encode($post_data);
					$return_id = $this->main_model->insert_temp_data(get_url('admin'.$this->uri->ruri_string()), $post_data);

					$this->session->set_envdata('reference_data_id',$return_id);

					redirect(get_url('admin/places/places_select'));

				}

				/************* endereços *************/
				/*************************************/

				/*************************************/
				/************** websites *************/

				// verifica se há pedido de remoção de campos de websites
				if ( isset($post_data['submit_remove_website']) ){
					// obtem o primeiro índice do array de remoção de websites
					reset($post_data['submit_remove_website']);
					unset($post_data['websites'][key($post_data['submit_remove_website'])]);
				}

				// verifica se há pedido de adição de campos de website
				if( in_array($submit_action, array('add_website')) ){

					$ph_key = 0;

					// se existem campos de website, obtem o último índice
					if ( isset( $post_data['websites'] ) ){

						end($post_data['websites']);
						$ph_key = key($post_data['websites']);

					}

					for ($i=$ph_key; $i < $ph_key + $post_data['website_fields_to_add']; $i++) {

						$post_data['websites'][$i+1] = array();

						// key é a ordem do website na listagem
						$post_data['websites'][$i+1]['key'] = $i+1;

					}

				}

				// reordenando os índices dos websites
				$post_data['websites'] = isset($post_data['websites']) ? $post_data['websites'] : array();
				$post_data['websites'] = array_merge(array(0), $post_data['websites']);
				$post_data['websites'] = array_values($post_data['websites']);
				unset($post_data['websites'][0]);

				/************** websites *************/
				/*************************************/

				$contact = array_merge($contact, $post_data);

			}

			if ( $action == 'add_contact' ){

				if ( empty($contact['emails']) ){

					$contact['emails'][1]['key'] = 1;

				}

				if ( empty($contact['phones']) ){

					$contact['phones'][1]['key'] = 1;

				}

				if ( empty($contact['websites']) ){

					$contact['websites'][1]['key'] = 1;

				}

			}

			if ( $action == 'edit_contact' ){

				/*************************************/
				/************** empresas *************/

				// Inicialmente, buscamos no DB as empresas que possuam o contato atual
				$or_condition = '`t1`.`contacts` LIKE \'%"id":"' . $contact['id'] . '"%\' ';
				$companies = $this->companies_model->get_companies_search_results(NULL, $or_condition)->result_array();

				// para cada empresa, não queremos que os outros contatos sejam visíveis, logo filtramos a lista de contatos e pegamos apenas o conato atual
				foreach ( $companies as $key => $company ) {

					$companies[$key]['contacts'] = json_decode( $companies[$key]['contacts'], TRUE );

					foreach ( $companies[$key]['contacts'] as $key_2 => $company_contact ) {

						if ( $company_contact['id'] == $contact['id'] ){

							// como um contato pode estar mais de uma vez em uma mesma empresa (várias relações), criamos
							// mais um índice no array de empresas "relationships"
							$companies[$key]['relationships'][] = $company_contact;

						}

					}

				}

				if ( $companies ){

					$contact['companies'] = $companies;
					// o índice 0 é temporário, definido aqui apenas para evitar que seja preenchido pelo json_decode($contact['companies'], TRUE)
					// a ideia é reservar o espaço, e em seguida excluí-lo
					array_unshift($contact['companies'], 0);
					// aqui, excluo o primeiro índice, deixando o array começando de 1, e não de 0
					unset($contact['companies'][0]);

				}
				else{
					$contact['companies'] = array();
				}

				/************** empresas *************/
				/*************************************/

			}

			if ( $post_data ){

				$post_data['companies'] = $contact['companies'];

			}



			if ( $action == 'add_contact' AND ! $post_data ) {

				$rand = md5( rand( 2000, 15223 ) );

				if( ! is_dir( FCPATH . 'tmp' ) ){

					mkdir( FCPATH . 'tmp', 0777, TRUE );

				}

				$contact_image_path = 'tmp' . DS . $rand . DS;

			}
			else if ( $post_data AND isset( $post_data[ 'contact_image_path' ] ) ) {

				$contact_image_path = $post_data[ 'contact_image_path' ];

			}
			else if ( $action == 'edit_contact' ) {

				$contact_image_path = 'assets' . DS . 'images' . DS . 'components' . DS . 'contacts' . DS . $var1;

			}

			// criando o diretório destino das imagens, caso este não exista
			if( ! is_dir( $contact_image_path ) ){

				if ( ! @mkdir( $contact_image_path, 0777, TRUE ) ){

					msg( 'unable_to_create_directory', 'title' );
					msg( sprintf( lang( 'unable_to_create_company_temporary_images_directory' ), $contact_image_path ), 'error' );

					log_message( 'error', sprintf( lang( 'unable_to_create_company_temporary_images_directory' ), $contact_image_path ) );

				}

			}




			$data = array(

				'component_name' => $this->component_name,
				'f_action' => $action,
				'contact' => $contact,
				'contact_image_path' => $contact_image_path,

			);

			//validação dos campos
			$this->form_validation->set_rules( 'name', lang( 'name' ), 'trim|required|max_legth[100]' );
			$this->form_validation->set_rules( 'birthday_date', lang( 'birthday_date' ), 'trim' );
			$this->form_validation->set_rules( 'company_id' , lang( 'company_id' ), 'trim|integer' );

			if( in_array($submit_action, array( 'add_email', 'remove_email', 'add_phone', 'remove_phone' ) ) ){

				msg( ( $submit_action . '_success_message' ),'success' );

			}
			else if( in_array( $submit_action, array( 'cancel' ) ) ){

				redirect_last_url();

			}
			// se a validação dos campos for negativa e mensagens de erro conter strings
			else if ( ( in_array( $submit_action, array( 'submit', 'apply' ) ) ) AND ( ! $this->form_validation->run() AND validation_errors() != '' ) ){

				// verificando erros de validação do formulário
				msg( ( 'update_contact_fail' ), 'title' );
				msg(validation_errors( '<div class="error">', '</div>' ), 'error' );

			}
			// se a validação dos campos for bem sucedida
			else if ( $this->form_validation->run() AND ( in_array( $submit_action, array( 'submit', 'apply' ) ) ) ){

				// convertendo os arrays de campos dinâmicos em json para inserção no db
				$post_data['emails'] = json_encode($post_data['emails']);
				$post_data['phones'] = json_encode($post_data['phones']);
				$post_data['addresses'] = json_encode($post_data['addresses']);
				$post_data['websites'] = json_encode($post_data['websites']);

				$db_data = elements( array(

					'name',
					'birthday_date',
					'emails',
					'phones',
					'addresses',
					'thumb_local',
					'photo_local',
					'websites',

				), $post_data );

				if ( $db_data[ 'photo_local' ] == '' ){

					$db_data[ 'thumb_local' ] = '';

				}

				/********************************************************/
				/********** removendo campos dinâmicos vazios ***********/

				// emails
				$db_data['emails'] = json_encode( clear_dinamics_fields( json_decode( $db_data['emails'], TRUE ), 'key', array('email') ) );

				// telefones
				$db_data['phones'] = json_encode( clear_dinamics_fields( json_decode( $db_data['phones'], TRUE ), 'key', array('number') ) );

				// websites
				$db_data['websites'] = json_encode( clear_dinamics_fields( json_decode( $db_data['websites'], TRUE ), 'key', array('url') ) );

				// websites
				$db_data['addresses'] = json_encode( clear_dinamics_fields( json_decode( $db_data['addresses'], TRUE ), 'key', array(
					'country_title',
					'state_acronym',
					'city_title',
					'neighborhood_title',
					'public_area_title',
					'postal_code',
					'number',
					'complement',
				) ) );

				/********** removendo campos dinâmicos vazios ***********/
				/********************************************************/

				if ( $action == 'add_contact' ) {

					$return_id = $this->contacts_model->insert_contact( $db_data );

					if ( $return_id ){

						$file = explode( '/', $db_data[ 'thumb_local' ] );
						$file = $file[ count( $file ) - 1 ];

						if ( $db_data[ 'thumb_local' ] ){

							if( ! is_dir( 'thumbs' . DS . 'assets' . DS . 'images' . DS . 'components' . DS . 'contacts' . DS . $return_id ) ){

								mkdir( 'thumbs' . DS . 'assets' . DS . 'images' . DS . 'components' . DS . 'contacts' . DS . $return_id, 0777, TRUE );
								copy( $db_data[ 'thumb_local' ], 'thumbs' . DS . 'assets' . DS . 'images' . DS . 'components' . DS . 'contacts' . DS . $return_id . '/' . $file );
								$new_db_data[ 'thumb_local' ] = 'thumbs/assets/images/components/contacts/' . $return_id . '/' . $file;

							}

						}

						if ( $db_data[ 'photo_local' ] ){

							if( ! is_dir( 'assets' . DS . 'images' . DS . 'components' . DS . 'contacts' . DS . $return_id ) ){

								mkdir( 'assets' . DS . 'images' . DS . 'components' . DS . 'contacts' . DS . $return_id, 0777, TRUE );
								copy( $db_data[ 'photo_local' ], 'assets' . DS . 'images' . DS . 'components' . DS . 'contacts' . DS . $return_id . '/' . $file );
								$new_db_data[ 'photo_local' ] = 'assets/images/components/contacts/' . $return_id . '/' . $file;

							}

						}

						if ( check_var( $new_db_data ) ){

							$this->contacts_model->update_contact( $new_db_data, array( 'id' => $return_id ) );

						}

						msg( ( 'contact_added' ), 'success' );

						if ( $this->input->post( 'submit_apply' ) ){

							redirect( 'admin/contacts/contacts_management/edit_contact/' . $return_id );

						}
						else{

							redirect_last_url();

						}
					}

				}
				else if ( $action == 'edit_contact' ) {

					if ( $this->contacts_model->update_contact( $db_data, array( 'id' => $var1 ) ) ){

						msg( ( 'contact_updated' ), 'success' );

						if ( $this->input->post( 'submit_apply' ) ){

							redirect( 'admin/' . $this->uri->ruri_string() );

						}
						else{

							redirect_last_url();

						}
					}

				}

			}

			$this->_page(

				array(

					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => 'contact_form',
					'layout' => 'default',
					'view' => 'contact_form',
					'data' => $data,

				)

			);

		}

		/*************** Add / Edit contact ***************/
		/**************************************************/

		/**************************************************/
		/***************** Remove contact *****************/

		else if ($action == 'remove_contact' AND $var1 AND ($contact = $this->contacts_model->get_contacts(array('t1.id' => $var1), 1)->row())){

			// $var1 = id do contato

			if($this->input->post('submit_cancel')){
				redirect_last_url();
			}
			else if ($this->input->post('submit')){
				if ($this->contacts_model->delete_contact(array('id'=>$var1))){
					msg(('contact_deleted'),'success');
					redirect_last_url();
				}
				else{
					msg(('contact_deleted_fail'),'error');
					redirect_last_url();
				}
			}
			else{
				$data=array(
					'component_name' => $this->component_name,
					'f_action' => $action,
					'contact'=>$contact,
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

		/***************** Remove contact *****************/
		/**************************************************/

		/**************************************************/
		/***************** Google contacts ****************/

		else if ( $action == 'google_contacts' ){

			$this->load->library( 'google' );
			require_once 'Google/Cache/Null.php';
			require_once 'Google/Service/Oauth2.php';

			/*****************************************/
			/***************** Config ****************/

			$token = $this->google->get_token_from_db();
			$get = $this->input->get();
			$currentUrl = current_url();
			$currentUrl = explode( '?', $currentUrl );
			$items_per_page = check_var( $get[ 'ipp' ] ) ? ( int ) $get[ 'ipp' ] : 20;
			$current_page = check_var( $get[ 'cp' ] ) ? ( int ) $get[ 'cp' ] : 1;

			$redirect_uri = $currentUrl[ 0 ];
			$app_name = lang( 'login_to' ) . ' ' . $this->mcm->filtered_system_params[ 'google_base_app_name' ];
			$client_id = $this->mcm->filtered_system_params[ 'google_client_id' ];
			$developer_key = $this->mcm->filtered_system_params[ 'google_developer_key' ];
			$client_secret = $this->mcm->filtered_system_params[ 'google_client_secret' ];
			$email_address = $this->mcm->filtered_system_params[ 'google_email_address' ];

			$service_client_id = $this->mcm->filtered_system_params[ 'google_service_client_id' ]; // service account
			$service_email_address = $this->mcm->filtered_system_params[ 'google_service_email_address' ];
			$service_key_file = APPPATH . 'libraries/google-api-php-client/decrypt';
			$service_key_file = file_get_contents( $service_key_file );
			$service_sub = $this->users->user_data[ 'email' ];

			/***************** Config ****************/
			/*****************************************/

			/*****************************************/
			/*************** Pagination **************/

			$this->load->helper( array( 'pagination' ) );

			if ( $current_page < 1 OR ! gettype( $current_page ) == 'int' ) $current_page = 1;
			if ( $items_per_page < 1 OR ! gettype( $items_per_page ) == 'int' ) $items_per_page = $this->mcm->filtered_system_params[ 'admin_items_per_page' ];
			$offset = ( $current_page - 1 ) * $items_per_page + 1;

			/*************** Pagination **************/
			/*****************************************/

			$client = $this->google->client();
			$null_cache = new Google_Cache_Null( $client );

			$client->setCache( $null_cache );
			$client->setApplicationName( $app_name );
			$client->setScopes( array(

				'https://www.google.com/m8/feeds',
				'https://www.googleapis.com/auth/plus.login',
				'https://www.googleapis.com/auth/userinfo.email',

			) );
			$client->setClientId( $client_id );
			$client->setClientSecret( $client_secret );
			$client->setRedirectUri( $redirect_uri );
			$client->setDeveloperKey( $developer_key );




			if ( check_var( $get[ 'code' ] ) ) {

				$client->authenticate( $get[ 'code' ] );
				$token = $client->getAccessToken();
				$this->google->save_token_on_db( $token );
				$this->session->set_envdata( 'token', $client->getAccessToken() );
				redirect( $redirect_uri );

			}

			if ( $token ){

				$client->setAccessToken( $token );
				$auth = new Google_Auth_AssertionCredentials( $service_email_address, array( 'https://www.google.com/m8/feeds', 'https://www.googleapis.com/auth/plus.login', 'https://www.googleapis.com/auth/userinfo.email', ), $service_key_file );
				$auth->sub = $service_email_address;
				$client->setAssertionCredentials( $auth );

			}

			if ( isset( $get[ 'logout' ] ) ) {

				$this->session->unset_envdata( 'token' );
				$client->revokeToken();

			}

			$google_oauthV2 = new Google_Service_Oauth2( $client );

			/*****************************************/
			/******** Is access token expired? *******/

			if ( $token AND $client->getAuth()->isAccessTokenExpired() ) {

				try{

					$client->getAuth()->refreshTokenWithAssertion();
					$token = $client->getAccessToken();
					$this->google->save_token_on_db( $token );

				}
				catch ( Google_Auth_Exception $e ){

					$error_msg = "[Google Api] Error code :" . $e->getCode() . " --- Error message: " . $e->getMessage();

					log_message( 'error', $error_msg );
					//msg( "[Google Api] Error code :" . $e->getCode(), 'title' );
					//msg( $e->getMessage(), 'error' );

				}
				catch ( Google_IO_Exception $e ){

					$error_msg = "[Google Api] Error code :" . $e->getCode() . " --- Error message: " . $e->getMessage();

					log_message( 'error', $error_msg );
					//msg( "[Google Api] Error code :" . $e->getCode(), 'title' );
					//msg( $e->getMessage(), 'error' );

				}

			}

			/******** Is access token expired? *******/
			/*****************************************/

			if ( $token ) {


				try{

					$user                 = $google_oauthV2->userinfo->get();
					$user_id              = $user['id'];
					$name                 = filter_var($user['name'], FILTER_SANITIZE_SPECIAL_CHARS);
					$email                = filter_var($user['email'], FILTER_SANITIZE_EMAIL);
					$profile_url          = filter_var($user['link'], FILTER_VALIDATE_URL);
					$profile_image_url    = filter_var($user['picture'], FILTER_VALIDATE_URL);
					$personMarkup         = "$email<div><img src='$profile_image_url?sz=50'></div>";

					//print "<pre>" . print_r( $user, true ) . "</pre>";

				}
				catch ( Google_Auth_Exception $e ){

					$error_msg = "[Google Api] Error code :" . $e->getCode() . " --- Error message: " . $e->getMessage();

					log_message( 'error', $error_msg );
					//msg( "[Google Api] Error code :" . $e->getCode(), 'title' );
					//msg( $e->getMessage(), 'error' );
					$auth = $client->createAuthUrl();
					//redirect( $auth );

				}
				catch ( Google_IO_Exception $e ){

					$error_msg = "[Google Api] Error code :" . $e->getCode() . " --- Error message: " . $e->getMessage();

					log_message( 'error', $error_msg );
					//msg( "[Google Api] Error code :" . $e->getCode(), 'title' );
					//msg( $e->getMessage(), 'error' );
					$auth = $client->createAuthUrl();
					//redirect( $auth );

				}
				catch ( Google_Service_Exception $e ){

					$error_msg = "[Google Api] Error code :" . $e->getCode() . " --- Error message: " . $e->getMessage();

					log_message( 'error', $error_msg );
					//msg( "[Google Api] Error code :" . $e->getCode(), 'title' );
					//msg( $e->getMessage(), 'error' );
					$auth = $client->createAuthUrl();
					//redirect( $auth );

				}


				$req = new Google_Http_Request( "https://www.google.com/m8/feeds/contacts/default/full?alt=json&max-results=$items_per_page&start-index=$offset" );

				try {

					$result_json = $client->getAuth()->authenticatedRequest( $req )->getResponseBody();
					//$client->getAuth()->sign( $req );
					//$io = $client->getIo();
					//$result_json = $io->makeRequest( $req )->getResponseBody();

				} catch ( Google_Auth_Exception $e ) {

					$error_msg = "[Google Api] Error code :" . $e->getCode() . " --- Error message: " . $e->getMessage();

					log_message( 'error', $error_msg );
					//msg( "[Google Api] Error code :" . $e->getCode(), 'title' );
					//msg( $e->getMessage(), 'error' );
					$auth = $client->createAuthUrl();
					redirect( $auth );

				} catch ( Google_ServiceException $e ) {

					$error_msg = "[Google Api] Error code :" . $e->getCode() . " --- Error message: " . $e->getMessage();

					log_message( 'error', $error_msg );
					//msg( "[Google Api] Error code :" . $e->getCode(), 'title' );
					//msg( $e->getMessage(), 'error' );
					$auth = $client->createAuthUrl();
					redirect( $auth );

				} catch ( Google_Exception $e ) {

					$error_msg = "[Google Api] Error code :" . $e->getCode() . " --- Error message: " . $e->getMessage();

					log_message( 'error', $error_msg );
					//msg( "[Google Api] Error code :" . $e->getCode(), 'title' );
					//msg( $e->getMessage(), 'error' );
					$auth = $client->createAuthUrl();
					redirect( $auth );

				}

				$response = json_decode( $result_json, true );

				foreach ( $response as $key => $resp ) {

					if ( $key == 'error' ){

						if ( $resp[ 'code' ] == '401' AND $resp[ 'message' ] == 'Login Required'  ){

							$this->session->unset_envdata( 'token' );
							$this->session->set_envdata( 'google_error_login_required', TRUE );
							$auth = $client->createAuthUrl();
							redirect( $auth );

						}
						else if ( $resp[ 'code' ] == '401' AND $resp[ 'message' ] == 'Invalid Credentials'  ){

							$this->session->unset_envdata( 'token' );
							$this->session->set_envdata( 'google_error_invalid_credentials', TRUE );
							$auth = $client->createAuthUrl();
							redirect( $auth );

						}

					}

				}

				//print "<pre>" . print_r( $response, true ) . "</pre>";

				$total_results = $response[ 'feed' ][ 'openSearch$totalResults' ][ '$t' ];
				$data[ 'pagination' ] = get_pagination( 'admin/'.$this->component_name . '/' . __FUNCTION__ . '/' . $action.'?cp=%p%&%ipp%', $current_page, $items_per_page, $total_results );

				foreach ( $response[ 'feed' ][ 'entry' ] as $key => $contact ) {

					$google_contacts[ $key ][ 'name' ] = $contact[ 'title' ][ '$t' ];
					$google_contacts[ $key ][ 'gc_id' ] = $contact[ 'id' ][ '$t' ];

					//print "<pre>" . print_r( $contact, true ) . "</pre>";

					foreach ( $contact[ 'link' ] as $link_key => $link ) {

						if ( $link[ 'rel' ] === 'http://schemas.google.com/contacts/2008/rel#photo' ){

							$req2 = new Google_Http_Request( $link[ 'href' ] );
							$val2 = $client->getAuth()->authenticatedRequest( $req2 );



							$img = $val2->getResponseBody();

							// requires php5
							if ( ! defined( 'UPLOAD_DIR' ) ) define( 'UPLOAD_DIR', APPPATH.'../assets/images/google/' );
							$file = UPLOAD_DIR . uniqid() . '.jpg';
							$success = file_put_contents( $file, $img );
							//print $success ? $file : 'Unable to save the file.';

							$google_contacts[ $key ][ 'thumb_local' ] = $file;

						}

					}

					if ( check_var( $contact[ 'gd$email' ] ) ){

						foreach ( $contact[ 'gd$email' ] as $email_key => $email ) {

							$k = $email_key + 1;

							$google_contacts[ $key ][ 'emails' ][ $k ][ 'key' ] = $k;
							$google_contacts[ $key ][ 'emails' ][ $k ][ 'email' ] = $email[ 'address' ];

							if ( check_var( $email[ 'label' ] ) ){

								$google_contacts[ $key ][ 'emails' ][ $k ][ 'title' ] = $email[ 'label' ];

							}
							else if ( check_var( $email[ 'rel' ] ) AND $email[ 'rel' ] === 'http://schemas.google.com/g/2005#home' ){

								$google_contacts[ $key ][ 'emails' ][ $k ][ 'title' ] = lang( 'email_kind_home' );

							}
								else if ( check_var( $email[ 'rel' ] ) AND $email[ 'rel' ] === 'http://schemas.google.com/g/2005#work' ){

									$google_contacts[ $key ][ 'emails' ][ $k ][ 'title' ] = lang( 'phone_kind_work' );

								}
							else{

								$google_contacts[ $key ][ 'emails' ][ $k ][ 'title' ] = lang( 'email' ) . ' ' . $k;

							}

							unset( $k );

						}

					}

					$google_contacts[ $key ][ 'phones' ] = check_var( $contact[ 'gd$phoneNumber' ] ) ? $contact[ 'gd$phoneNumber' ] : FALSE;

					if ( $google_contacts[ $key ][ 'phones' ] ){

						foreach ( $google_contacts[ $key ][ 'phones' ] as $phone_key => & $phone ) {

							$k = $phone_key + 1;

							$phone[ 'key' ] = $k;
							$phone[ 'number' ] = check_var( $phone[ '$t' ] ) ? $phone[ '$t' ] : '';
							$phone[ 'uri' ] = check_var( $phone[ 'uri' ] ) ? $phone[ 'uri' ] : '';

							if ( check_var( $phone[ 'label' ] ) ){

								$phone[ 'title' ] = $phone[ 'label' ];

							}
							else if ( check_var( $phone[ 'rel' ] ) AND $phone[ 'rel' ] === 'http://schemas.google.com/g/2005#home' ){

								$phone[ 'title' ] = lang( 'phone_kind_home' );

							}
							else if ( check_var( $phone[ 'rel' ] ) AND $phone[ 'rel' ] === 'http://schemas.google.com/g/2005#work' ){

								$phone[ 'title' ] = lang( 'phone_kind_work' );

							}
							else{

								$phone[ 'title' ] = lang( 'phone' ) . ' ' . $k;

							}

							unset( $k );

						}

					}

					//print "<pre>" . print_r( $google_contacts[ $key ], true) . "</pre>";

				}

			}
			else {

				$auth = $client->createAuthUrl();
				redirect( $auth );

			}

			$data[ 'component_name' ] = $this->component_name;
			$data[ 'f_action' ] = $action;
			$data[ 'google_contacts' ] = $google_contacts;

			$this->_page(

				array(

					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => 'google_contacts',
					'layout' => 'default',
					'view' => 'google_contacts',
					'data' => $data,

				)

			);

		}

		/***************** Google contacts ****************/
		/**************************************************/

		/**************************************************/

	}

	/***************************** Contacts management ****************************/
	/******************************************************************************/
	/******************************************************************************/

	/******************************************************************************/
	/******************************************************************************/
	/************************************ Ajax ************************************/

	public function ajax( $action = NULL, $var1 = NULL ){

		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;

		$get = $this->input->get();
		$post = $this->input->post();

		if ( $action ){

			$data = array();

		}
		else{

			show_404();

		}

		$base_link = base_url() . $this->mcm->environment . '/' . $this->component_name . '/' . $this->component_function . '/';

		// live update uri
		$data[ 'lu_url' ] = array(

			'insert' => $base_link . 'live_update?ajax=true&lua=insert',
			'update' => $base_link . 'live_update?ajax=true&lua=update',

		);

		/**************************************************/
		/******************* Live update ******************/

		if ( $action == 'live_update' ){

			if ( check_var( $post[ 'lu' ] ) AND check_var( $post[ 'lu' ][ 'condition' ][ 'id' ] ) ){

				$contact_id = $post[ 'lu' ][ 'condition' ][ 'id' ];

				$fields = array(

					'name',
					'birthday_date',
					'emails',
					'phones',
					'addresses',
					'thumb_local',
					'photo_local',
					'websites',

				);

				foreach ( $post[ 'lu' ][ 'update_data' ] as $key => $value ) {

					if ( in_array( $key, $fields ) ){
						/*
						if ( $key == 'emails' ) {

							$emails = get_params( $value );

							$contact = $this->contacts_model->get_contacts( array( 't1.id' => $contact_id ), 1, NULL )->row_array();

							$emails = array_merge_recursive( $emails, get_params( $contact[ 'emails' ] ) );

							$emails = json_encode( $emails );

						}
						*/
						$db_data[ $key ] = $value;

					}

				}

				if ( check_var( $db_data ) AND $this->contacts_model->update_contact( $db_data, array( 'id' => $contact_id ) ) ){

					$data[ 'result' ] = json_encode( array(

						'result-type' => 'success',
						'result-msg' => lang( 'field_updated' ),
						'received-data' => $post,
						'temp' => json_encode( array_merge( array( 'id' => $contact_id ), $post[ 'lu' ][ 'condition' ] ) )

					) );

				}

				$this->_page(

					array(

						'component_view_folder' => $this->component_view_folder,
						'function' => __FUNCTION__,
						'action' => $action,
						'layout' => 'default',
						'view' => $action,
						'data' => $data,
						'html' => FALSE,
						'load_index' => FALSE,

					)

				);

			}

		}

		/******************* Live update ******************/
		/**************************************************/

		/**************************************************/
		/***************** Get contact data ***************/

		else if ( $action == 'get_contact_data' AND isset( $get[ 'contact_id' ] ) ){

			if ( $contact = $this->contacts_model->get_contacts( array( 't1.id' => $get[ 'contact_id' ] ), 1, NULL )->row_array() ){

				$contact['phones'] = json_decode($contact['phones'], TRUE);
				$contact['emails'] = json_decode($contact['emails'], TRUE);
				$contact['addresses'] = json_decode($contact['addresses'], TRUE);
				$contact['websites'] = json_decode($contact['websites'], TRUE);

				$data = array(

					'contact' => $contact,

				);

				$this->_page(

					array(

						'component_view_folder' => $this->component_view_folder,
						'function' => __FUNCTION__,
						'action' => $action,
						'layout' => 'default',
						'view' => $action,
						'data' => $data,
						'html' => FALSE,
						'load_index' => FALSE,

					)

				);

			}

		}

		/***************** Get contact data ***************/
		/**************************************************/

		/**************************************************/
		/******************* Live search ******************/

		else if ( $action == 'live_search' ){

			$terms = trim( $this->input->get( 'q' ) ? $this->input->get( 'q' ) : FALSE );

			$condition = NULL;
			$or_condition = NULL;

			if ( $terms ){

				$data = array();

				$get_query = urlencode( $terms );

				$full_term = $terms;
				$order_by = 'FIELD(t1.name, \''.$full_term.'\') ASC, t1.id ASC';

				$condition[ 'fake_index_1' ] = '';
				$condition[ 'fake_index_1' ] .= '(';
				$condition[ 'fake_index_1' ] .= '`t1`.`name` LIKE \'%'.$full_term.'%\' ';
				$condition[ 'fake_index_1' ] .= 'OR `t1`.`phones` LIKE \'%'.$full_term.'%\' ';
				$condition[ 'fake_index_1' ] .= 'OR `t1`.`addresses` LIKE \'%'.$full_term.'%\' ';
				$condition[ 'fake_index_1' ] .= 'OR `t1`.`emails` LIKE \'%'.$full_term.'%\' ';
				$condition[ 'fake_index_1' ] .= ')';

				$terms = str_replace( '#', ' ', $terms );
				$terms = explode( " ", $terms );

				$and_operator = FALSE;
				$like_query = '';

				foreach ( $terms as $key => $term ) {

					$like_query .= $and_operator === TRUE ? 'AND ' : '';
					$like_query .= '(';
					$like_query .= '`t1`.`name` LIKE \'%'.$term.'%\' ';
					$like_query .= 'OR `t1`.`phones` LIKE \'%'.$term.'%\' ';
					$like_query .= 'OR `t1`.`addresses` LIKE \'%'.$term.'%\' ';
					$like_query .= 'OR `t1`.`emails` LIKE \'%'.$term.'%\' ';
					$like_query .= ')';

					if ( ! $and_operator ){
						$and_operator = TRUE;
					}

				}

				$or_condition = '(' . $like_query . ')';

				$contacts = $this->contacts_model->get_contacts_search_results( $condition, $or_condition, NULL, NULL, NULL, $order_by, FALSE )->result_array();

				foreach ( $contacts as $key => $contact ) {

					$contacts[ $key ][ 'phones' ] = get_params( $contacts[ $key ][ 'phones' ] );
					$contacts[ $key ][ 'addresses' ] = get_params($contacts[ $key ][ 'addresses' ] );
					$contacts[ $key ][ 'emails' ] = get_params( $contacts[ $key ][ 'emails' ] );

					foreach ($terms as $term) {

						$contacts[ $key ][ 'name' ] = str_highlight( $contacts[ $key ][ 'name' ], $term );

						if ( isset( $contacts[ $key ][ 'phones' ] ) AND $contacts[ $key ][ 'phones' ] AND is_array( $contacts[ $key ][ 'phones' ] ) ){

							foreach ( $contacts[ $key ][ 'phones' ] as $key_2 => $phone ) {

								$contacts[ $key ][ 'phones' ][ $key_2 ][ 'title' ] = str_highlight( $contacts[ $key ][ 'phones' ][ $key_2 ][ 'title' ], $term );
								$contacts[ $key ][ 'phones' ][ $key_2 ][ 'number' ] = str_highlight( $contacts[ $key ][ 'phones' ][ $key_2 ][ 'number' ], $term );
								$contacts[ $key ][ 'phones' ][ $key_2 ][ 'extension_number' ] = str_highlight( $contacts[ $key ][ 'phones' ][ $key_2 ][ 'extension_number' ], $term );

							}

						}

						if ( isset( $contacts[  $key  ][  'addresses'  ] ) AND $contacts[  $key  ][  'addresses'  ] AND is_array( $contacts[  $key  ][  'addresses'  ] ) ){

							foreach ( $contacts[  $key  ][  'addresses'  ] as $key_2 => $address ) {

								$contacts[ $key ][ 'addresses' ][ $key_2 ][ 'title' ] = str_highlight( $contacts[ $key ][ 'addresses' ][ $key_2 ][ 'title' ], $term );
								$contacts[ $key ][ 'addresses' ][ $key_2 ][ 'state_acronym' ] = str_highlight( $contacts[ $key ][ 'addresses' ][ $key_2 ][ 'state_acronym' ], $term );
								$contacts[ $key ][ 'addresses' ][ $key_2 ][ 'city_title' ] = str_highlight( $contacts[ $key ][ 'addresses' ][ $key_2 ][ 'city_title' ], $term );
								$contacts[ $key ][ 'addresses' ][ $key_2 ][ 'neighborhood_title' ] = str_highlight( $contacts[ $key ][ 'addresses' ][ $key_2 ][ 'neighborhood_title' ], $term );
								$contacts[ $key ][ 'addresses' ][ $key_2 ][ 'public_area_title' ] = str_highlight( $contacts[ $key ][ 'addresses' ][ $key_2 ][ 'public_area_title' ], $term );
								$contacts[ $key ][ 'addresses' ][ $key_2 ][ 'postal_code' ] = str_highlight( $contacts[ $key ][ 'addresses' ][ $key_2 ][ 'postal_code' ], $term );
								$contacts[ $key ][ 'addresses' ][ $key_2 ][ 'complement' ] = str_highlight( $contacts[ $key ][ 'addresses' ][ $key_2 ][ 'complement' ], $term );

							}

						}

						if ( isset( $contacts[ $key ][ 'emails' ] ) AND $contacts[ $key ][ 'emails' ] AND is_array( $contacts[ $key ][ 'emails' ] ) ){

							foreach ( $contacts[ $key ][ 'emails' ] as $key_2 => $email ) {

								$contacts[ $key ][ 'emails' ][ $key_2 ][ 'title' ] = str_highlight( $contacts[ $key ][ 'emails' ][ $key_2 ][ 'title' ], $term );
								$contacts[ $key ][ 'emails' ][ $key_2 ][ 'email' ] = str_highlight( $contacts[ $key ][ 'emails' ][ $key_2 ][ 'email' ], $term );

							}

						}

					}

					$contacts[ $key ][ 'phones' ] = json_encode( $contacts[ $key ][ 'phones' ] );
					$contacts[ $key ][ 'addresses' ] = json_encode( $contacts[ $key ][ 'addresses' ] );
					$contacts[ $key ][ 'emails' ] = json_encode( $contacts[ $key ][ 'emails' ] );

				}

				$data[ 'contacts' ] = $contacts;


				$this->_page(

					array(

						'component_view_folder' => $this->component_view_folder,
						'function' => __FUNCTION__,
						'action' => $action,
						'layout' => 'default',
						'view' => $action,
						'data' => $data,
						'html' => FALSE,
						'load_index' => FALSE,

					)

				);

			}

		}

		/******************* Live search ******************/
		/**************************************************/

		/**************************************************/
		/*********************** Form *********************/

		else if ( $action == 'add' OR $action == 'edit' ){

			if ( $action == 'add_contact' AND ! $post_data ) {

				$rand = md5( rand( 2000, 15223 ) );

				if( ! is_dir( FCPATH . 'tmp' ) ){

					mkdir( FCPATH . 'tmp', 0777, TRUE );

				}

				$contact_image_path = 'tmp' . DS . $rand . DS;

			}
			else if ( $action == 'edit' AND isset( $get[ 'contact_id' ] ) ) {

				$contact = $this->contacts_model->get_contacts( array( 't1.id' => $get[ 'contact_id' ] ), 1, NULL )->row_array();

				$contact[ 'emails' ] = get_params( $contact[ 'emails' ] );
				$contact[ 'phones' ] = get_params( $contact[ 'phones' ] );
				$contact[ 'addresses' ] = get_params( $contact[ 'addresses' ] );
				$contact[ 'websites' ] = get_params( $contact[ 'websites' ] );

				$data[ 'lu_url' ][ 'update' ] .= '&id=' . $get[ 'contact_id' ];

				$data[ 'contact' ] = $contact;

				$contact_image_path = 'assets' . DS . 'images' . DS . 'components' . DS . 'contacts' . DS . $get[ 'contact_id' ];

			}

			// criando o diretório destino das imagens, caso este não exista
			if( ! is_dir( $contact_image_path ) ){

				if ( ! @mkdir( $contact_image_path, 0777, TRUE ) ){

					msg( 'unable_to_create_directory', 'title' );
					msg( sprintf( lang( 'unable_to_create_company_temporary_images_directory' ), $contact_image_path ), 'error' );

					log_message( 'error', sprintf( lang( 'unable_to_create_company_temporary_images_directory' ), $contact_image_path ) );

				}

			}




			$data[ 'component_name' ] = $this->component_name;
			$data[ 'f_action' ] = $action;
			$data[ 'contact_image_path' ] = $contact_image_path;

			$this->_page(

				array(

					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => 'form',
					'layout' => 'default',
					'view' => 'form',
					'data' => $data,
					'html' => FALSE,
					'load_index' => FALSE,

				)

			);

		}

		/*********************** Form *********************/
		/**************************************************/

	}

	/************************************ Ajax ************************************/
	/******************************************************************************/
	/******************************************************************************/

}
