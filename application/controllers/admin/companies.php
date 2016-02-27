<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'controllers/admin/main.php');

class Companies extends Main {

	public function __construct(){

		parent::__construct();

		$this->load->model(array('admin/companies_model'));

		set_current_component();

	}

	public function index(){
		$this->companies_management('companies_list');
	}

	/******************************************************************************/
	/******************************************************************************/
	/**************************** Companies management ****************************/

	public function companies_management( $action = NULL, $var1 = NULL, $var2 = NULL, $var3 = NULL, $var4 = NULL, $var5 = NULL, $var6 = NULL ){

		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;

		if ( ! $action ) redirect('admin/'.$this->component_name . '/' . __FUNCTION__ . '/' . 'companies_list');

		$url = get_url('admin'.$this->uri->ruri_string());

		// verifica se o usuário atual possui privilégios para gerenciar usuários, porém pode editar seu próprio usuário
		if ( ! $this->users->check_privileges('companies_management_companies_management') ){
			msg(('access_denied'),'title');
			msg(('access_denied_companies_management_companies_management'),'error');
			redirect('admin');
		};

		/**************************************************/
		/************* Companies list/ Search *************/

		if ( $action == 'companies_list' OR $action == 'search' ){

			$this->load->library(array('str_utils'));
			$this->load->helper(array('pagination'));
			$this->load->model('admin/contacts_model');

			// -------------------------------------------------
			// Columns ordering --------------------------------

			if ( ! ( ( $order_by_direction = $this->users->get_user_preference( 'companies_order_by_direction' ) ) != FALSE ) ){

				$order_by_direction = 'ASC';

			}

			// order by complement
			$comp_ob = '';

			if ( ( $order_by = $this->users->get_user_preference( 'companies_order_by' ) ) != FALSE ){

				$data[ 'order_by' ] = $order_by;

				switch ( $order_by ) {

					case 'id':

						$order_by = 't1.id';
						break;

					case 'trading_name':

						$order_by = 't1.trading_name';
						break;

					case 'company_name':

						$order_by = 't1.company_name';
						break;

					case 'contacts':

						$order_by = 't1.contacts';
						break;

				}

			}
			else{

				$order_by = 't1.trading_name';
				$data[ 'order_by' ] = 'trading_name';

			}

			$data[ 'order_by_direction' ] = $order_by_direction;

			$order_by = $order_by . ' ' . $order_by_direction . $comp_ob;

			// Columns ordering --------------------------------
			// -------------------------------------------------

			$cp = $var1;
			$ipp = $var2;

			if ( $cp < 1 ) $cp = 1;
			if ( $ipp < 1 ) $ipp = $this->mcm->filtered_system_params[ $this->mcm->environment . '_items_per_page' ];

			$offset = ( $cp - 1 ) * $ipp;

			// -------------------------------------------------
			// list / search -----------------------------------

			$terms = trim( $this->input->post( 'terms', TRUE ) ? $this->input->post( 'terms', TRUE ) : ( ( $this->input->get( 'q' ) != FALSE ) ? $this->input->get( 'q' ) : FALSE ) );

			$q_terms = $terms ? '?q=' . $terms : '';

			$search_config = array(

				'plugins' => 'companies_search',
				'ipp' => $ipp,
				'cp' => $cp,
				'terms' => $terms,
				'order_by' => array(

					'companies_search' => $order_by,

				),

			);

			$this->load->library( 'search', $search_config );

			$companies = $this->search->get_full_results( 'companies_search' );
			$pagination = get_pagination( 'admin/' . $this->component_name . '/' . __FUNCTION__ . '/' . $action . '/%p%/%ipp%?q=' . $q_terms, $cp, $ipp, $this->search->count_all_results( 'companies_search' ) );

			// list / search -----------------------------------
			// -------------------------------------------------

			if ( $this->input->post( 'submit_cancel_search', TRUE ) ) {

				redirect( 'admin/' . $this->component_name . '/' . __FUNCTION__ . '/companies_list' );

			}

			$data[ 'search' ][ 'terms' ] = $terms;

			$this->form_validation->set_rules( 'terms', lang( 'terms' ), 'trim|min_length[2]' );

			$data['component_name'] = $this->component_name;
			$data['companies'] = $companies;
			$data['pagination'] = $pagination;

			set_last_url($url.((isset($get_query) AND $get_query) ? '?q='.$get_query : ''));

			$this->_page(

				array(

					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => 'companies_list',
					//'layout' => 'vui_data_list',
					'layout' => 'default',
					'view' => 'companies_list',
					'data' => $data,

				)

			);

		}

		/************* Companies list/ Search *************/
		/**************************************************/

		/**************************************************/
		/***************** Change order by ****************/

		else if ( ( $action == 'change_order_by' ) AND $var1 ){

			$this->users->set_user_preferences( array( 'companies_order_by' => $var1 ) );

			if ( ( $order_by_direction = $this->users->get_user_preference( 'companies_order_by_direction' ) ) != FALSE ){

				switch ( $order_by_direction ) {

					case 'ASC':

						$order_by_direction = 'DESC';
						break;

					case 'DESC':

						$order_by_direction = 'ASC';
						break;

				}

				$this->users->set_user_preferences( array( 'companies_order_by_direction' => $order_by_direction ) );

			}
			else {

				$this->users->set_user_preferences( array( 'companies_order_by_direction' => 'ASC' ) );

			}

			redirect( get_last_url() );

		}

		/***************** Change order by ****************/
		/**************************************************/

		/**************************************************/
		/*************** Add / Edit company ***************/

		else if ( $action == 'add_company' OR ( $action == 'edit_company' AND $var1 AND $company = $this->companies_model->get_companies(array('t1.id'=>$var1), 1)->row_array() ) ){

			if ( $action == 'add_company' )
				$company = array();

			// carregando os models necessários
			$this->load->model('admin/places_model');
			$this->load->model('admin/contacts_model');

			// carregando a lista de contatos
			$contacts = $this->contacts_model->get_contacts()->result_array();

			// capturando os dados obtidos via post, e guardando-os na variável $post_data
			$post_data = $this->input->post();

			// aqui definimos as ações obtidas via post, ex.: ao salvar, cancelar, adicionar um contato, telefone, endereço, etc.
			// acionados ao submeter os forms
			$submit_action =
				$this->input->post('submit_add_contact')?'add_contact':
				($this->input->post('submit_add_email')?'add_email':
				($this->input->post('submit_add_phone')?'add_phone':
				($this->input->post('submit_add_address')?'add_address':
				($this->input->post('submit_add_website')?'add_website':
				($this->input->post('submit_cancel')?'cancel':
				($this->input->post('submit')?'submit':
				($this->input->post('submit_apply')?'apply':
				'none')))))));

			// se houver dados de referência, ou seja, se está vindo de uma seleção de itens...
			if ( $this->session->envdata( 'reference_data_id' ) ){

				$reference_data_id = $this->session->envdata('reference_data_id');

				$this->session->unset_envdata( 'reference_data_id' );

				$temp_data = $this->main_model->get_temp_data( array( 'id' =>$reference_data_id) )->row_array();

				$this->main_model->delete_temp_data( array( 'id' =>$reference_data_id ) );

				if ( $temp_data ){

					$post_data = json_decode( $temp_data[ 'data' ], TRUE );

					// se os dados de referência refere-se a endereços
					if ( isset( $post_data['addresses'] ) ){

						end($post_data['addresses']);
						$np_key = key($post_data['addresses']);

					}

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

			}

			if ( ! $post_data ){

				if ( $action == 'edit_company' ) {
					/*************************************/
					/************** contatos *************/

					$company['contacts'] = get_params( $company['contacts'] );
					// verifica se $company['contacts'] é um json válido
					if ( $company['contacts'] ){
						// inicializando o array de contatos
						// o índice 0 é temporário, definido aqui apenas para evitar que seja preenchido pelo json_decode($company['contacts'], TRUE)
						// a ideia é reservar o espaço, e em seguida excluí-lo
						$company['contacts'] = array_merge( array( 0 ), $company[ 'contacts' ] );
						// aqui, excluo o primeiro índice, deixando o array começando de 1, e não de 0
						unset($company['contacts'][0]);
					}
					else{
						$company['contacts'] = array();
					}

					/************** contatos *************/
					/*************************************/

					/*************************************/
					/************** websites *************/

					$company['websites'] = json_decode($company['websites'], TRUE);
					// verifica se $company['websites'] é um json válido
					if ( $company['websites'] ){
						// inicializando o array de website
						// o índice 0 é temporário, definido aqui apenas para evitar que seja preenchido pelo json_decode($company['websites'], TRUE)
						// a ideia é reservar o espaço, e em seguida excluí-lo
						$company['websites'] = array_merge(array(0), $company['websites']);
						// aqui, excluo o primeiro índice, deixando o array começando de 1, e não de 0
						unset($company['websites'][0]);
					}
					else{
						$company['websites'] = array();
					}

					/************** websites *************/
					/*************************************/

					/*************************************/
					/*************** emails **************/

					$company['emails'] = json_decode($company['emails'], TRUE);
					// verifica se $company['emails'] é um json válido
					if ( $company['emails'] ){
						// inicializando o array de email
						// o índice 0 é temporário, definido aqui apenas para evitar que seja preenchido pelo json_decode($company['emails'], TRUE)
						// a ideia é reservar o espaço, e em seguida excluí-lo
						$company['emails'] = array_merge(array(0), $company['emails']);
						// aqui, excluo o primeiro índice, deixando o array começando de 1, e não de 0
						unset($company['emails'][0]);
					}
					else{
						$company['emails'] = array();
					}

					/*************** emails **************/
					/*************************************/

					/*************************************/
					/************* telefones *************/

					$company['phones'] = json_decode($company['phones'], TRUE);
					// verifica se $company['phones'] é um json válido
					if ( $company['phones'] ){
						// inicializando o array de telefone
						// o índice 0 é temporário, definido aqui apenas para evitar que seja preenchido pelo json_decode($company['phones'], TRUE)
						// a ideia é reservar o espaço, e em seguida excluí-lo
						$company['phones'] = array_merge(array(0), $company['phones']);
						// aqui, excluo o primeiro índice, deixando o array começando de 1, e não de 0
						unset($company['phones'][0]);
					}
					else{
						$company['phones'] = array();
					}

					/************* telefones *************/
					/*************************************/

					/*************************************/
					/************* endereços *************/

					$company['addresses'] = json_decode($company['addresses'], TRUE);
					// verifica se $company['addresses'] é um json válido
					if ( $company['addresses'] ){
						// inicializando o array de endereços
						// o índice 0 é temporário, definido aqui apenas para evitar que seja preenchido pelo json_decode($company['addresses'], TRUE)
						// a ideia é reservar o espaço, e em seguida excluí-lo
						$company['addresses'] = array_merge(array(0), $company['addresses']);
						// aqui, excluo o primeiro índice, deixando o array começando de 1, e não de 0
						unset($company['addresses'][0]);
					}
					else{
						$company['addresses'] = array();
					}

					/************* endereços *************/
					/*************************************/

				}

			}
			else{

				/*************************************/
				/************** contatos *************/

				// verifica se há pedido de remoção de campos de contatos
				if ( isset($post_data['submit_remove_contact']) ){
					// obtem o primeiro índice do array de remoção de contatos
					reset($post_data['submit_remove_contact']);
					unset($post_data['contacts'][key($post_data['submit_remove_contact'])]);
				}

				// verifica se há pedido de adição de campos de contatos
				if( in_array($submit_action, array('add_contact')) ){

					$ctc_key = 0;

					// se existem campos de contato, obtem o último índice
					if ( isset( $post_data['contacts'] ) ){

						end($post_data['contacts']);
						$ctc_key = key($post_data['contacts']);

					}

					for ($i=$ctc_key; $i < $ctc_key + $post_data['contact_fields_to_add']; $i++) {

						$post_data['contacts'][$i+1] = array();

						// key é a ordem do contato na listagem
						$post_data['contacts'][$i+1]['key'] = $i+1;

					}

				}

				// reordenando os índices dos contatos
				$post_data['contacts'] = isset($post_data['contacts']) ? $post_data['contacts'] : array();
				$post_data['contacts'] = array_merge(array(0), $post_data['contacts']);
				$post_data['contacts'] = array_values($post_data['contacts']);
				unset($post_data['contacts'][0]);


				/************** contatos *************/
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

				$company = array_merge($company, $post_data);

			}

			if ( isset( $company[ 'contacts' ] ) AND ! empty( $company[ 'contacts' ] ) ){

				// procura pelo contato no array de contatos ( os contatos adquiridos do componente contato )
				// Se encontrado, mescla com o contato atual do foreach
				foreach ( $company[ 'contacts' ] as $key => & $contact ) {

					if ( isset( $contact[ 'id' ] ) ){

						foreach ( $contacts as $key => $row ) {

							if ( $row[ 'id' ] === $contact[ 'id' ] ){

								$contact = array_merge( $contacts[ $key ], $contact );

							}

						}

					}

				}

			}

			if ( $action == 'add_company' ){

				if ( empty($company['emails']) ){

					$company['emails'][1]['key'] = 1;

				}

				if ( empty($company['contacts']) ){

					$company['contacts'][1]['key'] = 1;

				}

				if ( empty($company['phones']) ){

					$company['phones'][1]['key'] = 1;

				}

				if ( empty($company['websites']) ){

					$company['websites'][1]['key'] = 1;

				}

			}

			if ( $action == 'add_company' AND ! $post_data ) {

				$rand = md5( rand( 2000, 15223 ) );

				if( ! is_dir( FCPATH . 'tmp' ) ){

					mkdir( FCPATH . 'tmp', 0777, TRUE );

				}

				$company_image_path = 'tmp' . DS . $rand . DS;

			}
			else if ( $post_data AND isset( $post_data[ 'company_image_path' ] ) ) {

				$company_image_path = $post_data[ 'company_image_path' ];

			}
			else if ( $action == 'edit_company' ) {

				$company_image_path = 'assets' . DS . 'images' . DS . 'components' . DS . 'companies' . DS . $var1;

			}

			// criando o diretório destino das imagens, caso este não exista
			if( ! is_dir( $company_image_path ) ){

				if ( ! @mkdir( $company_image_path, 0777, TRUE ) ){

					msg( 'unable_to_create_directory', 'title' );
					msg( sprintf( lang( 'unable_to_create_company_temporary_images_directory' ), $company_image_path ), 'error' );

				}

			}

			$data = array(

				'component_name' => $this->component_name,
				'f_action' => $action,
				'company' => $company,
				'contacts' => $contacts,
				'company_image_path' => $company_image_path,

			);

			//validação dos campos
			$this->form_validation->set_rules( 'trading_name', lang( 'trading_name' ), 'trim|required' );
			$this->form_validation->set_rules( 'company_name', lang( 'company_name' ), 'trim' );


			if( in_array( $submit_action, array( 'add_contact', 'remove_contact', 'add_phone', 'remove_phone' ) ) ){

				msg( ( $submit_action . '_success_message' ), 'success' );

			}
			else if( in_array( $submit_action, array( 'cancel' ) ) ){

				redirect_last_url();

			}
			else if ( ( in_array( $submit_action, array( 'submit', 'apply' ) ) ) AND ( ! $this->form_validation->run() AND validation_errors() != '' ) ){

				// verificando erros de validação do formulário
				msg( ( 'update_company_fail' ), 'title' );
				msg(validation_errors( '<div class="error">', '</div>' ), 'error' );

			}
			// se a validação dos campos for bem sucedida
			else if ( $this->form_validation->run() AND (in_array($submit_action, array('submit','apply'))) ){

				// convertendo os arrays de campos dinâmicos em json para inserção no db
				$post_data['contacts'] = json_encode($post_data['contacts']);
				$post_data['websites'] = json_encode($post_data['websites']);
				$post_data['emails'] = json_encode($post_data['emails']);
				$post_data['phones'] = json_encode($post_data['phones']);
				$post_data['addresses'] = json_encode($post_data['addresses']);

				$db_data = elements( array(

					'trading_name',
					'company_name',
					'state_registration',
					'sic',
					'corporate_tax_register',
					'foundation_date',
					'contacts',
					'websites',
					'emails',
					'phones',
					'addresses',
					'logo_thumb',
					'logo',

				), $post_data );

				/********************************************************/
				/********** removendo campos dinâmicos vazios ***********/

				if ( $db_data[ 'logo' ] == '' ){

					$db_data[ 'logo_thumb' ] = '';

				}

				// contatos
				$db_data['contacts'] = json_encode( clear_dinamics_fields( json_decode( $db_data['contacts'], TRUE ), 'key', array('id') ) );

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

				if ( $action == 'add_company' ) {

					$return_id = $this->companies_model->insert_company( $db_data );

					if ( $return_id ){

						$file = explode( '/', $db_data[ 'logo_thumb' ] );
						$file = $file[ count( $file ) - 1 ];

						if ( $db_data[ 'logo_thumb' ] ){

							if( ! is_dir( 'thumbs' . DS . 'assets' . DS . 'images' . DS . 'components' . DS . 'companies' . DS . $return_id ) ){

								mkdir( 'thumbs' . DS . 'assets' . DS . 'images' . DS . 'components' . DS . 'companies' . DS . $return_id, 0777, TRUE );
								copy( $db_data[ 'logo_thumb' ], 'thumbs' . DS . 'assets' . DS . 'images' . DS . 'components' . DS . 'companies' . DS . $return_id . '/' . $file );
								$new_db_data[ 'logo_thumb' ] = 'thumbs/assets/images/components/companies/' . $return_id . '/' . $file;

							}

						}

						if ( $db_data[ 'logo' ] ){

							if( ! is_dir( 'assets' . DS . 'images' . DS . 'components' . DS . 'companies' . DS . $return_id ) ){

								mkdir( 'assets' . DS . 'images' . DS . 'components' . DS . 'companies' . DS . $return_id, 0777, TRUE );
								copy( $db_data[ 'logo' ], 'assets' . DS . 'images' . DS . 'components' . DS . 'companies' . DS . $return_id . '/' . $file );
								$new_db_data[ 'logo' ] = 'assets/images/components/companies/' . $return_id . '/' . $file;

							}

						}

						if ( check_var( $new_db_data ) ){

							$this->companies_model->update_company( $new_db_data, array( 'id' => $return_id ) );

						}

						msg(('company_added'),'success');
						if ($this->input->post('submit_apply')){
							redirect('admin/'.$this->component_name . '/' . __FUNCTION__.'/edit_company/'.$return_id);
						}
						else{
							redirect_last_url();
						}
					}

				}
				else if ( $action == 'edit_company' ) {

					if ( $this->companies_model->update_company($db_data, array('id'=>$var1)) ){
						msg(('company_updated'),'success');
						if ($this->input->post('submit_apply')){
							redirect('admin/'.$this->uri->ruri_string());
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
					'action' => 'company_form',
					'layout' => 'default',
					'view' => 'company_form',
					'data' => $data,

				)

			);

		}

		/*************** Add / Edit company ***************/
		/**************************************************/

		/**************************************************/
		/***************** Remove company *****************/

		else if ($action == 'remove_company' AND $var1 AND ($company = $this->companies_model->get_companies(array('t1.id' => $var1), 1)->row())){

			// $var1 = id do contato

			if($this->input->post('submit_cancel')){
				redirect_last_url();
			}
			else if ($this->input->post('submit')){
				if ($this->companies_model->delete_company(array('id'=>$var1))){
					msg(('company_deleted'),'success');
					redirect_last_url();
				}
				else{
					msg(('company_deleted_fail'),'error');
					redirect_last_url();
				}
			}
			else{
				$data=array(
					'component_name' => $this->component_name,
					'company'=>$company,
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

		/***************** Remove company *****************/
		/**************************************************/

		/**************************************************/

		else{

			redirect_last_url();

		}

	}

	/**************************** Companies management ****************************/
	/******************************************************************************/
	/******************************************************************************/

	/******************************************************************************/
	/******************************************************************************/
	/**************************** External components *****************************/

	// funcções utilizadas por outros componentes para obter dados
	// os campos aqui definidos são padronizados, e nunca definidos pelo componente requisitante
	// o componente requisitante deve obedecer estes padrões
	public function companies_select($action = NULL, $var1 = NULL, $var2 = NULL, $var3 = NULL, $var4 = NULL, $var5 = NULL, $var6 = NULL){

		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;

		if ( ! $action ) $action = 'select_company';

		$url = get_url('admin'.$this->uri->ruri_string());

		if ( $this->session->envdata('reference_data_id') ){

			// define o estado do site para seleção
			$this->session->set_envdata('select_on', TRUE);

			// obtém o id de referência
			$reference_data_id = $this->session->envdata('reference_data_id');

			// obtém os dados temporários
			$temp_data = $this->main_model->get_temp_data( array('id' =>$reference_data_id) )->row_array();
			$temp_data['data'] = json_decode( $temp_data['data'], TRUE );

			$reference = $temp_data['reference'];

			// iniciando a variável de dados
			$data['data']['component_selection'] = 'companies';

			if( $this->input->post('submit_ok') ){

				$data['data'] = array(
					'selection' => 'ok',
				);

				$data['data'] = json_encode(array_merge($data['data'], $temp_data['data']));

				$this->main_model->update_temp_data( $data, array('id' =>$reference_data_id) );
				redirect($reference);

			}
			// se o usuário cancelar a escolha
			else if( $this->input->post('submit_cancel') ){

				$data['data'] = array(
					'selection' => 'canceled',
				);

				$data['data'] = json_encode(array_merge($data['data'], $temp_data['data']));

				$this->main_model->update_temp_data($data, array('id' =>$reference_data_id));
				redirect( $reference );

			}

			$data['search_fields']['terms'] = '';

			/**************************************************/
			/************* Select company / Search ************/

			if ( $action == 'select_company' OR $action == 'search' ){

				$this->load->helper(array('pagination'));
				$this->load->model('admin/contacts_model');

				/******************************/
				/**** Ordenção por colunas ****/

				if ( ! ( ( $order_by_direction = $this->users->get_user_preference( 'companies_order_by_direction' ) ) != FALSE ) ){

					$order_by_direction = 'ASC';

				}

				if ( ( $order_by = $this->users->get_user_preference( 'companies_order_by' ) ) != FALSE ){

					$data['order_by'] = $order_by;

					switch ( $order_by ) {

						case 'trading_name':

							$order_by = 't1.trading_name';
							break;

						case 'company_name':

							$order_by = 't1.company_name';
							break;

						case 'contacts':

							$order_by = 't1.contacts';
							break;

					}

				}
				else{

					$order_by = 't1.trading_name';
					$data['order_by'] = 'trading_name';

				}

				$data['order_by_direction'] = $order_by_direction;

				$order_by = $order_by . ' ' . $order_by_direction;

				/**** Ordenção por colunas ****/
				/******************************/

				// definindo os campos no db para a escolha da(s) empresa(s)
				$db_fields = array(
					'id',
					'user_id',
					'trading_name',
					'company_name',
					'contacts',
					'phones',
					'emails',
					'addresses',
					'state_registration',
					'sic',
					'corporate_tax_register',
					'foundation_date',
					'favicon',
					'logo_thumb',
					'logo',
					'websites',
				);

				// $var1 = página atual
				// $var2 = itens por página
				if ( $var1 < 1 OR ! gettype($var1) == 'int' ) $var1 = 1;
				if ( $var2 < 1 OR ! gettype($var2) == 'int' ) $var2 = $this->mcm->filtered_system_params['admin_items_per_page'];
				$offset = ($var1-1)*$var2;

				//validação dos campos
				$errors = FALSE;
				$errors_msg = '';
				$terms = trim($this->input->post('terms', TRUE) ? $this->input->post('terms', TRUE) : ($this->input->get('q') ? urldecode($this->input->get('q')) : FALSE) );
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
					redirect( 'admin/'.$this->component_name . '/' . __FUNCTION__.'/select_company' );
				}

				$data['search_fields']['terms'] = $terms;

				$this->form_validation->set_rules('terms',lang('terms'),'trim|min_length[2]');

				// se houver busca
				if( ( $this->input->post('submit_search') OR $terms ) AND ! $errors){

					$condition = NULL;
					$or_condition = NULL;

					if( $terms ){

						$get_query = urlencode($terms);

						$full_term = $terms;

						$condition['fake_index_1'] = '';
						$condition['fake_index_1'] .= '(';
						$condition['fake_index_1'] .= '`t1`.`trading_name` LIKE \'%'.$full_term.'%\' ';
						$condition['fake_index_1'] .= 'OR `t1`.`company_name` LIKE \'%'.$full_term.'%\' ';
						$condition['fake_index_1'] .= 'OR `t1`.`contacts` LIKE \'%"name":"%'.$full_term.'%"%\' ';
						$condition['fake_index_1'] .= 'OR `t1`.`phones` LIKE \'%'.$full_term.'%\' ';
						$condition['fake_index_1'] .= 'OR `t1`.`emails` LIKE \'%'.$full_term.'%\' ';
						$condition['fake_index_1'] .= 'OR `t1`.`addresses` LIKE \'%'.$full_term.'%\' ';
						$condition['fake_index_1'] .= 'OR `t1`.`websites` LIKE \'%'.$full_term.'%\' ';
						$condition['fake_index_1'] .= 'OR `t1`.`state_registration` LIKE \'%'.$full_term.'%\' ';
						$condition['fake_index_1'] .= 'OR `t1`.`sic` LIKE \'%'.$full_term.'%\' ';
						$condition['fake_index_1'] .= 'OR `t1`.`corporate_tax_register` LIKE \'%'.$full_term.'%\' ';
						$condition['fake_index_1'] .= 'OR `t1`.`foundation_date` LIKE \'%'.$full_term.'%\' ';
						$condition['fake_index_1'] .= ')';

						$terms = str_replace('#', ' ', $terms);
						$terms = explode(" ", $terms);

						$and_operator = FALSE;
						$like_query = '';

						foreach ($terms as $key => $term) {

							$like_query .= $and_operator === TRUE ? 'AND ' : '';
							$like_query .= '(';
							$like_query .= '`t1`.`trading_name` LIKE \'%'.$full_term.'%\' ';
							$like_query .= 'OR `t1`.`company_name` LIKE \'%'.$full_term.'%\' ';
							$like_query .= 'OR `t1`.`contacts` LIKE \'%"name":"%'.$full_term.'%"%\' ';
							$like_query .= 'OR `t1`.`phones` LIKE \'%'.$full_term.'%\' ';
							$like_query .= 'OR `t1`.`emails` LIKE \'%'.$full_term.'%\' ';
							$like_query .= 'OR `t1`.`addresses` LIKE \'%'.$full_term.'%\' ';
							$like_query .= 'OR `t1`.`websites` LIKE \'%'.$full_term.'%\' ';
							$like_query .= 'OR `t1`.`state_registration` LIKE \'%'.$full_term.'%\' ';
							$like_query .= 'OR `t1`.`sic` LIKE \'%'.$full_term.'%\' ';
							$like_query .= 'OR `t1`.`corporate_tax_register` LIKE \'%'.$full_term.'%\' ';
							$like_query .= 'OR `t1`.`foundation_date` LIKE \'%'.$full_term.'%\' ';
							$like_query .= ')';

							if ( ! $and_operator ){
								$and_operator = TRUE;
							}

						}

						$or_condition = '(' . $like_query . ')';

						$companies = $this->companies_model->get_companies_search_results($condition, $or_condition, $var2, $offset, NULL, $order_by, FALSE)->result_array();

						foreach ($companies as $key => $company) {

							$companies[$key]['contacts'] = json_decode($company['contacts'], TRUE);
							$companies[$key]['phones'] = json_decode($companies[$key]['phones'], TRUE);
							$companies[$key]['addresses'] = json_decode($companies[$key]['addresses'], TRUE);
							$companies[$key]['emails'] = json_decode($companies[$key]['emails'], TRUE);
							$companies[$key]['websites'] = json_decode($companies[$key]['websites'], TRUE);

							if ( isset($companies[$key]['contacts']) AND $companies[$key]['contacts'] AND is_array($companies[$key]['contacts']) ) {
								foreach ($companies[$key]['contacts'] as $key_2 => $contact) {
									$companies[$key]['contacts'][$key_2] = array_merge($contact, $this->contacts_model->get_contacts(array('t1.id'=>$contact['id']))->row_array());
								}
							}

							foreach ($terms as $term) {

								$companies[$key]['trading_name'] = str_highlight( $companies[$key]['trading_name'], $term );
								$companies[$key]['company_name'] = str_highlight( $companies[$key]['company_name'], $term );

								if ( isset($companies[$key]['phones']) AND $companies[$key]['phones'] AND is_array($companies[$key]['phones']) ) {
									foreach ($companies[$key]['phones'] as $key_2 => $phone) {

										$companies[$key]['phones'][$key_2]['title'] = str_highlight( $companies[$key]['phones'][$key_2]['title'], $term );
										$companies[$key]['phones'][$key_2]['area_code'] = str_highlight( $companies[$key]['phones'][$key_2]['area_code'], $term );
										$companies[$key]['phones'][$key_2]['number'] = str_highlight( $companies[$key]['phones'][$key_2]['number'], $term );
										$companies[$key]['phones'][$key_2]['extension_number'] = str_highlight( $companies[$key]['phones'][$key_2]['extension_number'], $term );

									}
								}

								if ( isset($companies[$key]['contacts']) AND $companies[$key]['contacts'] AND is_array($companies[$key]['contacts']) ) {
									foreach ($companies[$key]['contacts'] as $key_2 => $contact) {

										if ( isset($companies[$key]['contacts'][$key_2]['name']) AND $companies[$key]['contacts'][$key_2]['name'] ){

											$companies[$key]['contacts'][$key_2]['name'] = str_highlight( $companies[$key]['contacts'][$key_2]['name'], $term );

										}

									}
								}

							}

						}

						$data['companies'] = $companies;
						$data['pagination'] = get_pagination('admin/'.$this->component_name . '/' . __FUNCTION__ . '/' . $action.'/%p%/%ipp%?q='.$get_query, $var1, $var2, $this->companies_model->get_companies_search_results($condition, $or_condition, NULL, NULL,'count_all_results'));

					}
					else if ( $this->input->post('submit_select') ){

						$company = $this->companies_model->get_companies( array('t1.id' => $this->input->post('company_id')) )->row_array();

						$selected_companies = array();

						$selected_companies[] = elements( $db_fields, $company);

						$data['data']['selected_companies'] = $selected_companies;
						$data['data']['selection'] = 'selected';

						$data['data'] = json_encode( array_merge( $data['data'], $temp_data['data'] ) );

						$this->main_model->update_temp_data($data, array('id' =>$reference_data_id));
						redirect( $temp_data['reference'] );

					}

				}
				// se houver erros na busca
				else if ( $errors ){

					$data['post'] = $this->input->post();

					msg(('search_fail'),'title');
					msg($errors_msg,'error');
				}
				// se não houver busca
				else if ( $companies = $this->companies_model->get_companies( NULL, $var2, $offset, NULL, $order_by)->result_array() ){

					//validação dos campos
					$this->form_validation->set_rules('public_area_id',lang('public_area_id'),'trim|integer|required');

					if( $this->input->post('submit_select') ){

						$company = $this->companies_model->get_companies( array('t1.id' => $this->input->post('company_id')) )->row_array();

						$selected_companies = array();

						$selected_companies[] = elements( $db_fields, $company);

						$data['data']['selected_companies'] = $selected_companies;
						$data['data']['selection'] = 'selected';

						$temp_data['data'] = array_merge( $temp_data['data'], $data['data'] );

						$temp_data['data'] = json_encode( array_merge( $temp_data['data'], $data['data'] ) );

						$this->main_model->update_temp_data($temp_data, array('id' =>$reference_data_id));
						redirect( $reference );
					}

					foreach ($companies as $key => $company) {

						$companies[$key]['contacts'] = json_decode($company['contacts'], TRUE);

						if ( isset($companies[$key]['contacts']) AND $companies[$key]['contacts'] AND is_array($companies[$key]['contacts']) ) {
							foreach ($companies[$key]['contacts'] as $key_2 => $contact) {
								$companies[$key]['contacts'][$key_2] = array_merge($contact, $this->contacts_model->get_contacts(array('t1.id'=>$contact['id']))->row_array());
							}
						}

						$companies[$key]['phones'] = json_decode($company['phones'], TRUE);

						$companies[$key]['addresses'] = json_decode($company['addresses'], TRUE);

					}

					$data['companies'] = $companies;
					$data['pagination'] = get_pagination('admin/'.$this->component_name . '/' . __FUNCTION__ . '/' . $action.'/%p%/%ipp%', $var1, $var2, $this->companies_model->get_companies(NULL, NULL, NULL,'count_all_results'));

				}

				//set_last_url($url.((isset($get_query) AND $get_query) ? '?q='.$get_query : ''));

				$this->_page(

					array(

						'component_view_folder' => $this->component_view_folder,
						'function' => 'companies_management',
						'action' => 'companies_list',
						'layout' => 'default',
						'view' => 'companies_list',
						'data' => $data,

					)

				);

			}

			/************* Select company / Search ************/
			/**************************************************/

			/**************************************************/
			/***************** Change order by ****************/

			else if ( ( $action == 'change_order_by' ) AND $var1 ){

				$this->users->set_user_preferences( array( 'companies_order_by' => $var1 ) );

				if ( ( $order_by_direction = $this->users->get_user_preference( 'companies_order_by_direction' ) ) != FALSE ){

					switch ( $order_by_direction ) {

						case 'ASC':

							$order_by_direction = 'DESC';
							break;

						case 'DESC':

							$order_by_direction = 'ASC';
							break;

					}

					$this->users->set_user_preferences( array( 'companies_order_by_direction' => $order_by_direction ) );

				}
				else {

					$this->users->set_user_preferences( array( 'companies_order_by_direction' => 'ASC' ) );

				}

				redirect( get_last_url() );

			}

			/***************** Change order by ****************/
			/**************************************************/

			else{
				show_404();
			}

		} else {
			msg(('no_reference_informed'),'error');
			redirect( get_url('admin/' . $this->current_component[ 'unique_name' ] . '/companies_management/companies_list') );
		}

	}

	/**************************** External components *****************************/
	/******************************************************************************/
	/******************************************************************************/


	/******************************************************************************/
	/******************************************************************************/
	/************************************ Ajax ************************************/

	public function ajax( $action = NULL, $var1 = NULL ){

		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;

		if ( $this->input->post('ajax') ){

			/**************************************************/
			/***************** Get company data ***************/

			if ($action == 'get_company_data'){

				if ($company = $this->companies_model->get_companies(array( 't1.id' => $this->input->post('company_id') ), 1, NULL)->row_array()){

					$company['contacts'] = json_decode($company['contacts'], TRUE);

					$this->load->model('admin/contacts_model');
					if ( isset($company['contacts']) AND $company['contacts'] AND is_array($company['contacts']) ) {
						foreach ($company['contacts'] as $key => $contact) {
							$company['contacts'][$key] = array_merge($contact, $this->contacts_model->get_contacts(array('t1.id'=>$contact['id']))->row_array());
						}
					}

					$company['phones'] = json_decode($company['phones'], TRUE);
					$company['emails'] = json_decode($company['emails'], TRUE);
					$company['addresses'] = json_decode($company['addresses'], TRUE);
					$company['websites'] = json_decode($company['websites'], TRUE);

					$data = array(
						'component_name' => $this->component_name,
						'f_action' => $action,
						'company' => $company,
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
			else{

			}

			/***************** Get contact data ***************/
			/**************************************************/

		}
		else{

			redirect( get_last_url() );

		}

	}

	/************************************ Ajax ************************************/
	/******************************************************************************/
	/******************************************************************************/

}
