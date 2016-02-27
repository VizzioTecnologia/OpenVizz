<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require(APPPATH.'controllers/admin/main.php');

class Vesm extends Main {

	public function __construct(){

		parent::__construct();

		$this->load->model(array('admin/vesm_model'));

		set_current_component();

		if ( ! $this->users->is_logged_in() ){

			redirect( 'admin/main/index/login' );

		}

		// verifica se o usuário atual possui privilégios para gerenciar artigos
		else if ( ! $this->users->check_privileges('vesm_management_vesm_management') ){
			msg(('access_denied'),'title');
			msg(('access_denied_vesm_management_vesm_management'),'error');
			redirect_last_url();
		};

	}

	public function index(){

		$this->tenders_management( 'tenders_list' );

	}

	public function temp(){

		header('Content-Type: text/html; charset=UTF-8');

		$xml = simplexml_load_file('produtoslista.bin.xml');

		if ($xml->getName() == 'Lista_de_Produtos'){
			$Lista_de_Produtos = array();
		}

		foreach($xml->children() as $product){

			foreach($product->children() as $product_attr){

				if ( $product_attr->getName() == 'Codigo' ){
					$value = 'http://www.allnations.com.br/ProdutosDetalhes.aspx?CodProd=YTD6UPROD_'.trim((string)$product_attr)."&#9;".trim((string)$product_attr)."&#9;";
				}
				else if ( $product_attr->getName() == 'Disponivel' ){
					$value = trim((string)$product_attr);
					$value = str_replace('Não', '0', $value);
					$value = str_replace('Sim', '1', $value);
					$value .= "&#9;";
				}
				else if ( $product_attr->getName() == 'Preco' ){
					$value = trim((string)$product_attr);
					$value = str_replace('.', '', $value);
					$value = str_replace(',', '.', $value);
					$value = str_replace('R$', '', $value);
					$value .= "&#9;";
				}
				else{
					$value = trim((string)$product_attr)."&#9;";
				}

				$value = str_replace('"', '&#8221;', $value);
				echo $value;

			}

			echo "<br/>";

		}

	}

	/******************************************************************************/
	/******************************************************************************/
	/***************************** Tenders management *****************************/

	public function tenders_management( $action = NULL, $var1 = NULL, $var2 = NULL, $var3 = NULL, $var4 = NULL, $var5 = NULL, $var6 = NULL ){

		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;

		if ( ! $action ) redirect( 'admin/' . $this->component_name . '/' . __FUNCTION__ . '/' . 'tenders_list' );

		$this->tenders_pdf_path = COMPONENTS_DOCUMENTS_PATH . $this->component_name . DS . 'tenders' . DS . 'pdf' . DS;
		$this->tenders_pdf_url = COMPONENTS_DOCUMENTS_URL .'/' . $this->component_name . '/tenders/pdf';

		$url = get_url( 'admin' . $this->uri->ruri_string() );

		// verifica se o usuário atual possui privilégios para gerenciar usuários, porém pode editar seu próprio usuário
		if ( ! $this->users->check_privileges( 'companies_management_companies_management' ) ){

			msg( ( 'access_denied' ), 'title' );
			msg( ( 'access_denied_companies_management_companies_management' ), 'error' );

			redirect_last_url();

		};

		$current_component = $this->main_model->get_component( array( 'unique_name' => $this->component_name ) );

		// obtendo os parâmetros globais do componente
		$component_params = json_decode( $current_component->row()->params, TRUE );

		/**************************************************/
		/************* Tenders list / search **************/

		if ( $action == 'tenders_list' OR $action == 'search' ){

			$this->load->model('admin/providers_model');
			$this->load->helper(array('pagination'));

			/******************************/
			/**** Ordenção por colunas ****/

			if ( ! ( ( $order_by_direction = $this->users->get_user_preference( 'tender_order_by_direction' ) ) != FALSE ) ){

				$order_by_direction = 'ASC';

			}

			if ( ( $order_by = $this->users->get_user_preference( 'tender_order_by' ) ) != FALSE ){

				$data['order_by'] = $order_by;

				switch ( $order_by ) {

					case 'tender_code':

						$order_by = 't1.tender_code';
						break;

					case 'title':

						$order_by = 't1.title';
						break;

					case 'date_creation':

						$order_by = 't1.date_creation';
						break;

					case 'date_tender_order':

						$order_by = 't1.date_tender_order';
						break;

					case 'date_issue':

						$order_by = 't1.date_issue';
						break;

					case 'customer':

						$order_by = 't2.title';
						break;

					case 'contact':

						$order_by = 't4.name';
						break;

					case 'status':

						$order_by = 't5.title';
						break;

				}

			}
			else{

				$order_by = 't1.tender_code';
				$data['order_by'] = 'tender_code';

			}

			$data['order_by_direction'] = $order_by_direction;

			$order_by = $order_by . ' ' . $order_by_direction;

			/**** Ordenção por colunas ****/
			/******************************/

			// carregando a lista de status
			$tenders_status = $this->vesm_model->get_tenders_status()->result_array();

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
				redirect( 'admin/'.$this->component_name . '/' . __FUNCTION__.'/tenders_list' );
			}

			$data['search']['terms'] = $terms;

			$this->form_validation->set_rules('terms',lang('terms'),'trim|min_length[2]');

			if( ( $this->input->post() OR $terms ) AND ! $errors){

				$condition = NULL;
				$or_condition = NULL;

				if( $terms ){

					$get_query = urlencode($terms);

					$full_term = $terms;

					$condition['fake_index_1'] = '';
					$condition['fake_index_1'] .= '(';
					$condition['fake_index_1'] .= '`t1`.`title` LIKE \'%'.$full_term.'%\' ';
					$condition['fake_index_1'] .= 'OR `t1`.`tender_code` LIKE \'%'.$full_term.'%\' ';
					$condition['fake_index_1'] .= 'OR `t2`.`title` LIKE \'%'.$full_term.'%\' ';
					$condition['fake_index_1'] .= 'OR `t3`.`trading_name` LIKE \'%'.$full_term.'%\' ';
					$condition['fake_index_1'] .= 'OR `t4`.`name` LIKE \'%'.$full_term.'%\' ';
					$condition['fake_index_1'] .= 'OR `t1`.`products` LIKE \'%'.$full_term.'%\' ';
					$condition['fake_index_1'] .= 'OR `t1`.`prov_conds` LIKE \'%'.$full_term.'%\' ';
					$condition['fake_index_1'] .= ')';

					$terms = str_replace('#', ' ', $terms);
					$terms = explode(" ", $terms);

					$and_operator = FALSE;
					$like_query = '';

					foreach ($terms as $key => $term) {

						$like_query .= $and_operator === TRUE ? 'AND ' : '';
						$like_query .= '(';
						$like_query.= '`t1`.`title` LIKE \'%'.$term.'%\' ';
						$like_query.= 'OR `t1`.`tender_code` LIKE \'%'.$term.'%\' ';
						$like_query.= 'OR `t1`.`date_creation` LIKE \'%'.$term.'%\' ';
						$like_query.= 'OR `t1`.`date_tender_order` LIKE \'%'.$term.'%\' ';
						$like_query.= 'OR `t1`.`date_issue` LIKE \'%'.$term.'%\' ';
						$like_query.= 'OR `t2`.`title` LIKE \'%'.$term.'%\' ';
						$like_query.= 'OR `t3`.`trading_name` LIKE \'%'.$term.'%\' ';
						$like_query.= 'OR `t4`.`name` LIKE \'%'.$term.'%\' ';
						$like_query.= 'OR `t1`.`products` LIKE \'%'.$term.'%\' ';
						$like_query.= 'OR `t1`.`prov_conds` LIKE \'%'.$term.'%\' ';
						$like_query .= ')';

						if ( ! $and_operator ){
							$and_operator = TRUE;
						}

					}

					$or_condition = '(' . $like_query . ')';

					$tenders = $this->vesm_model->get_tenders_search_results( $condition, $or_condition, $var2, $offset, NULL, $order_by, FALSE )->result_array();

					foreach ( $tenders as $key => & $tender ) {

						/******************************/
						/********* Parâmetros *********/

						$tender[ 'params' ] = json_decode( $tender[ 'params' ], TRUE );

						$tender[ 'params'] = filter_params( $component_params, $tender[ 'params' ] );

						/********* Parâmetros *********/
						/******************************/

						$tender[ 'providers' ] = array();

						if ( isset($tender[ 'prov_conds' ] ) AND $tender[ 'prov_conds' ] ){

							$tender[ 'prov_conds' ] = json_decode($tender[ 'prov_conds' ], TRUE);


							foreach ($tender[ 'prov_conds'] as $key_2 => $prov_cond) {

								$tender[ 'providers' ][] = $this->providers_model->get_providers( array( 't1.id'=>$prov_cond[ 'id' ] ), 1 )->row_array();

							}

						}

						// ajustando datas
						$tender[ 'date_creation'] = $tender['date_creation'];
						$tender[ 'date_tender_order'] = $tender['date_tender_order'];
						$tender[ 'date_issue'] = $tender['date_issue'];

						foreach ( $terms as $term ) {

							$tender[ 'title'] = str_highlight( $tender[ 'title'], $term );
							$tender[ 'tender_code'] = str_highlight( $tender[ 'tender_code'], $term );
							$tender[ 'date_creation'] = str_highlight( $tender[ 'date_creation'], $term );
							$tender[ 'date_tender_order'] = str_highlight( $tender[ 'date_tender_order'], $term );
							$tender[ 'date_issue'] = str_highlight( $tender[ 'date_issue'], $term );
							$tender[ 'customer_title'] = str_highlight( $tender[ 'customer_title'], $term );
							$tender[ 'trading_name'] = str_highlight( $tender[ 'trading_name'], $term );
							$tender[ 'contact_name'] = str_highlight( $tender[ 'contact_name'], $term );
							$tender[ 'products'] = str_highlight( $tender[ 'products'], $term );

							foreach ( $tender[ 'providers' ] as $key_2 => $prov_cond ) {

								$tender[ 'providers' ][ $key_2 ][ 'title' ] = str_highlight( $tender[ 'providers' ][ $key_2 ][ 'title' ], $term );

							}
						}

						/****************************************/
						/************* Arquivo PDF **************/

						$pdf_path = $this->tenders_pdf_path;

						$contact_name = explode(' ', $tender['contact_name']);

						$pdf_filename =
							$tender['tender_code'] .
							' - ' .
							str_replace('/', '-', $tender['title']) .
							' - ' .
							str_replace('/', '-', $contact_name[0] . ' ' . $contact_name[ count( $contact_name ) -1 ]) .
							' - ' .
							$tender['customer_title'] .
							' - ' .
							str_replace('/', '-', date( $tender['params'][ 'tender_date_format' ], strtotime( strip_tags( $tender[ 'date_issue' ] ) ) ) ) .
							'.pdf';

						$pdf_file = $pdf_path.$pdf_filename;

						if ( file_exists( $pdf_file ) ){

							$tender[ 'pdf_file'] = $this->tenders_pdf_url . '/' . $pdf_filename;

						}
						else{

							$tender[ 'pdf_file'] = '';

						}

						/************* Arquivo PDF **************/
						/****************************************/

					}

					$data['tenders'] = $tenders;
					$data['pagination'] = get_pagination('admin/'.$this->component_name . '/' . __FUNCTION__ . '/' . $action.'/%p%/%ipp%?q='.$get_query, $var1, $var2, $this->vesm_model->get_tenders_search_results($condition, $or_condition, NULL, NULL,'count_all_results'));

				}

			}
			else if ( $tenders = $this->vesm_model->get_tenders(NULL, $var2, $offset, NULL, $order_by )->result_array() ){

				foreach ( $tenders as $key => $tender ) {

					$tenders[$key]['date_creation'] = $tender['date_creation'];
					$tenders[$key]['date_tender_order'] = $tender['date_tender_order'];
					$tenders[$key]['date_issue'] = $tender['date_issue'];

					/******************************/
					/********* Parâmetros *********/

					$tenders[ $key][ 'params' ] = json_decode( $tenders[ $key ][ 'params' ], TRUE );

					$tenders[$key]['params'] = filter_params( $component_params, $tenders[$key]['params'] );

					/********* Parâmetros *********/
					/******************************/

					if ( isset($tenders[$key]['prov_conds']) AND $tenders[$key]['prov_conds'] ){

						$tenders[$key]['prov_conds'] = json_decode($tenders[$key]['prov_conds'], TRUE);
						$tenders[$key]['providers'] = array();

						foreach ($tenders[$key]['prov_conds'] as $key_2 => $prov_cond) {
							$tenders[$key]['providers'][] = $this->providers_model->get_providers(array('t1.id'=>$prov_cond['id']), 1)->row_array();

						}

					}

					/****************************************/
					/************* Arquivo PDF **************/

					$pdf_path = $this->tenders_pdf_path;

					$contact_name = explode(' ', $tender['contact_name']);

					$pdf_filename =
						$tender['tender_code'] .
						' - ' .
						str_replace('/', '-', $tender['title']) .
						' - ' .
						str_replace('/', '-', $contact_name[0] . ' ' . $contact_name[ count( $contact_name ) -1 ]) .
						' - ' .
						$tender['customer_title'] .
						' - ' .
						str_replace('/', '-', date( 'Y-m-d', strtotime( strip_tags( $tender[ 'date_issue' ] ) ) ) ) .
						'.pdf';

					$pdf_file = $pdf_path . $pdf_filename;

					if ( file_exists( $pdf_file ) ){

						$tenders[$key]['pdf_file'] = $this->tenders_pdf_url . '/' . $pdf_filename;

					}
					else{

						$tenders[$key]['pdf_file'] = '';

					}

					/************* Arquivo PDF **************/
					/****************************************/

					// ajustando datas
					$tenders[$key]['date_creation'] = $tender['date_creation'];
					$tenders[$key]['date_tender_order'] = $tender['date_tender_order'];
					$tenders[$key]['date_issue'] = $tender['date_issue'];

				}

				$data['tenders'] = $tenders;
				$data['pagination'] = get_pagination('admin/vesm/tenders_management/tenders_list/%p%/%ipp%', $var1, $var2, $this->vesm_model->get_tenders(NULL, NULL, NULL,'count_all_results'));

			}

			$data = array_merge($data, array(
				'component_name' => $this->component_name,
				'f_action' => $action,
				'tenders_status' => $tenders_status,
			));
			set_last_url($url.((isset($get_query) AND $get_query) ? '?q='.$get_query : ''));

			$this->_page(

				array(

					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => 'tenders_list',
					'layout' => 'default',
					'view' => 'tenders_list',
					'data' => $data,

				)

			);

		}

		/************* Tenders list / search **************/
		/**************************************************/

		else if ( ( $action == 'copy_tender' ) AND $var1 ){

			if ( $this->vesm_model->copy_tender( $var1 ) ){

				msg( 'tender_copied_success', 'success' );

				redirect_last_url();

			}

		}
		/**************************************************/
		/***************** Change order by ****************/

		else if ( ( $action == 'change_order_by' ) AND $var1 ){

			$this->users->set_user_preferences( array( 'tender_order_by' => $var1 ) );

			if ( ( $order_by_direction = $this->users->get_user_preference( 'tender_order_by_direction' ) ) != FALSE ){

				switch ( $order_by_direction ) {

					case 'ASC':

						$order_by_direction = 'DESC';
						break;

					case 'DESC':

						$order_by_direction = 'ASC';
						break;

				}

				$this->users->set_user_preferences( array( 'tender_order_by_direction' => $order_by_direction ) );

			}
			else {

				$this->users->set_user_preferences( array( 'tender_order_by_direction' => 'ASC' ) );

			}

			redirect( get_last_url() );

		}

		/***************** Change order by ****************/
		/**************************************************/

		/**************************************************/
		/****************** Change status *****************/

		else if ( ( $action == 'change_status' ) ){

			if ( $this->input->post('tender_id') AND $this->input->post('status_id') ){

				$update_data = array(
					'status_id' => $this->input->post('status_id'),
				);

				if ( $this->vesm_model->update_tender( $update_data, array('id' => $this->input->post( 'tender_id' ) ) ) ){

					msg( ('short_tender_status_changed' ), 'success' );

				}
				else{

					msg( ( 'change_tender_status_error' ), 'error' );

				}

			}

			if ( $this->input->get( 'ajax' ) ){

				// não usar redirect quando se deseja receber mensagens do retorno via ajax
				$this->tenders_management( 'tenders_list' );

			} else {

				redirect( get_last_url() );

			}

		}

		/****************** Change status *****************/
		/**************************************************/

		/**************************************************/
		/**************** Add / Edit tender ***************/

		else if ( $action == 'add_tender' OR $action == 'edit_tender' ){

			if ( $action == 'add_tender' ){

				$insert_data['tender_code'] = $this->vesm_model->get_new_tender_code();
				$insert_data['date_creation'] = date( 'Y-m-d H:i:s', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) );
				$insert_data['date_issue'] = date( 'Y-m-d H:i:s', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) );
				$insert_data['date_tender_order'] = $insert_data['date_creation'];
				$insert_data['title'] = lang('untitled_tender');
				$insert_data['status_id'] = 9;

				$insert_data['params'] = filter_params($component_params, array());
				$insert_data['params'] = json_encode($insert_data['params']);


				$insert_data = elements(array(
					'tender_code',
					'title',
					'date_creation',
					'date_issue',
					'date_tender_order',
					'status_id',
					'params'
				),$insert_data);

				$id = $this->vesm_model->insert_tender($insert_data);

				if ( $id ){
					redirect('admin/'.$this->component_name . '/' . __FUNCTION__.'/edit_tender/'.$id);
				}

			} else if ( $action == 'edit_tender' AND $tender = $this->vesm_model->get_tenders(array('t1.id'=>$var1), 1)->row_array() ){

				// carregando os models necessários
				$this->load->model('admin/customers_model');
				$this->load->model('admin/providers_model');
				$this->load->model('admin/places_model');
				$this->load->model('admin/contacts_model');

				// carregando a lista de fornecedores
				$providers = $this->providers_model->get_providers()->result_array();

				// carregando a lista de estados
				$states = $this->places_model->get_states(array('t2.alias'=>'brazil'))->result_array();

				// carregando a lista de categorias de clientes
				$customers_categories = $this->customers_model->get_categories_tree(0,0,'list');

				// carregando a lista de status
				$tenders_status = $this->vesm_model->get_tenders_status()->result_array();


				//print_r($tender['contact']);

				// carregando as garantias
				$warranties = $this->vesm_model->get_warranties()->result_array();

				//dynamic_product_field_identifier
				$dpf_idf = 'df_product_';

				// inicializando o índice atual dos produtos
				// np_key quer dizer next_product_key
				$np_key = 0;

				// capturando os dados obtidos via post, e guardando-os na variável $post_data
				$post_data = $this->input->post();

				// aqui definimos as ações obtidas via post, ex.: ao salvar, cancelar, adicionar um produto, etc.
				// acionados ao submeter os forms
				$submit_action =
					$this->input->post('submit_add_product')?'add_product':
					($this->input->post('submit_cancel')?'cancel':
					($this->input->post('submit')?'submit':
					($this->input->post('submit_apply')?'apply':
					($this->input->post('submit_preview_tender_pdf')?'preview_tender_pdf':
					'none'))));


				// se houver dados de referência, ou seja, se está vindo de uma seleção de itens...
				if ( $this->session->envdata('reference_data_id') ){

					$reference_data_id = $this->session->envdata('reference_data_id');
					//echo $reference_data_id;
					$this->session->unset_envdata('reference_data_id');

					$temp_data = $this->main_model->get_temp_data(array('id' =>$reference_data_id))->row();

					$this->main_model->delete_temp_data(array('id' =>$reference_data_id));

					if ( $temp_data ){

						$post_data = json_decode($temp_data->data, TRUE);

						if ( isset( $post_data['products'] ) ){

							end($post_data['products']);
							$np_key = key($post_data['products']);

						}

						if ( isset($post_data['selection']) AND $post_data['selection'] != 'canceled' ){

							$np_key++;

							// key é a ordem do produto na listagem
							$post_data['products'][$np_key]['key'] = $np_key;

						}

						if ( isset($post_data['selected_products']) ){

							// varre os produtos selecionados
							foreach ($post_data['selected_products'] as $product_key => $product) {

								// para cada produto selecionado, cria-se um novo produto
								foreach ($product as $key => $value) {
									$post_data['products'][$np_key][$key] = (isset($product[$key]) AND $product[$key] != '') ? $product[$key] : NULL;
								}

								$np_key++;

							}
						}
						//$tender['products_array'][$np_key][$dpf_idf.$np_key.'_product_profit_factor'] = $post_data[PARAM_PREFIX.'product_profit_factor'];

					}

				}


				if ( ! $post_data ){

					/******************************/
					/********* Parâmetros *********/

					$tender['params'] = get_params( $tender['params'] );

					$tender[ 'params'] = filter_params( $component_params, $tender[ 'params' ] );

					/********* Parâmetros *********/
					/******************************/

					// verifica se $tender['products'] é um json válido
					if ( get_json_array($tender['products']) ){

						$products = json_decode($tender['products'], TRUE);

						// inicializando o array de produtos
						// o índice 0 é temporário, definido aqui apenas para evitar que seja preenchido pelo json_decode($tender['products'], TRUE)
						// a ideia é reservar o espaço, e em seguida excluí-lo
						$tender['products'] = array_merge(array(0), $products);
						// aqui, excluo o primeiro índice, deixando o array começando de 1, e não de 0
						unset($tender['products'][0]);
					}
					else{
						$tender['products'] = array();
					}

					// verifica se $tender['prov_conds'] é um json válido
					if ( get_json_array($tender['prov_conds']) ){

						$prov_conds = json_decode($tender['prov_conds'], TRUE);

						// inicializando o array de produtos
						// o índice 0 é temporário, definido aqui apenas para evitar que seja preenchido pelo json_decode($tender['products'], TRUE)
						// a ideia é reservar o espaço, e em seguida excluí-lo
						$tender['prov_conds'] = array_merge(array(0), $prov_conds);
						// aqui, excluo o primeiro índice, deixando o array começando de 1, e não de 0
						unset($tender['prov_conds'][0]);
					}
					else{
						$tender['prov_conds'] = array();
					}

				}
				else{

					// verifica se há pedido de remoção de campos dinâmicos
					if ( isset($post_data['submit_remove_product']) ){

						// obtem o primeiro índice do array de remoção de produtos
						reset($post_data['submit_remove_product']);
						unset($post_data['products'][key($post_data['submit_remove_product'])]);
					}

					// reordenando os índices dos produtos
					$post_data['products'] = isset($post_data['products']) ? $post_data['products'] : array();
					$post_data['products'] = array_merge(array(0), $post_data['products']);
					$post_data['products'] = array_values($post_data['products']);
					unset($post_data['products'][0]);


					/********************************************/
					/********* Fornecedores e condições *********/

					if ( ! isset($post_data['prov_conds']) ){
						$post_data['prov_conds'] = array();
					}

					$old_prov_conds = $post_data['prov_conds'];

					// obtendo os prov_conds a partir dos produtos, para futura comparação
					$new_prov_conds = array();
					foreach ($post_data['products'] as $key => $product) {

						if ( isset($product['provider_id']) ){

							$new_prov_conds[$product['provider_id']] = $this->providers_model->get_providers(array('t1.id' => $product['provider_id']), 1)->row_array();

						}

					}

					//print_r($new_prov_conds);

					// comparando os prov_conds
					foreach ($old_prov_conds as $key => $prov_cond) {

						// se o indice antigo não estiver na nova lista, quer dizer que o fornecedor não está sendo usado por nenhum produto, ou seja, foi removido
						if ( ! isset($new_prov_conds[$key]) ){
							unset($old_prov_conds[$key]);
						}

					}
					// o sinal de + preserva os indices
					$post_data['prov_conds'] = $old_prov_conds + $new_prov_conds;

					/********* Fornecedores e condições *********/
					/********************************************/

					//print_r($post_data['prov_conds']);

					if ( in_array($submit_action, array('add_product'))){

						$post_data = json_encode($post_data);
						$return_id = $this->main_model->insert_temp_data(get_url('admin'.$this->uri->ruri_string()), $post_data);

						$this->session->set_envdata('reference_data_id',$return_id);

						redirect(get_url('admin/vesm/products_select/select_product'));

					}

					$tender = array_merge($tender, $post_data);

					//print_r($tender);

				}

				// carregando a lista de clientes
				if ( $tender['customers_category_id'] ){

					$temp = $this->customers_model->get_categories_tree($tender['customers_category_id'],0,'list');

					$customers = $this->customers_model->get_customers(array( 't1.category_id' => $tender['customers_category_id'] ))->result_array();

					if ( ! $customers ){
						$customers = array();
					}

					if ( $temp )
						foreach ($temp as $key => $value) {
							$customers = array_merge($customers, $this->customers_model->get_customers(array( 't1.category_id' => $value['id'] ))->result_array());
						}

				}
				else
					$customers = FALSE;

				// determinando se o cliente atual está na lista de clientes da categoria selecionada
				if ( isset($customers) AND $customers AND is_array($customers) AND ! empty($customers) ){

					foreach ($customers as $key => $customer) {
						if ( $customer['id'] == $tender['customer_id'] ){

							// carregando os dados do cliente atual
							$tender['customer'] = $this->customers_model->get_customers(array( 't1.id' => $tender['customer_id'] ), 1, NULL)->row_array();

							// se o cliente for uma empresa, continua
							if ( $tender['customer']['company_id'] ){


								// carregando os telefones
								$tender['customer']['phones'] = json_decode($tender['customer']['company_phones'], TRUE);
								unset($tender['customer']['company_phones']);

								// preparando o array de telefones, o qual estarão lado a lado com os do contato
								$tender['phones_options'] = array();
								// adicionando os telefones do cliente ao array de opções do combobox
								if ( isset($tender['customer']['phones']) AND $tender['customer']['phones'] AND is_array($tender['customer']['phones']) ){
									$tender['phones_options'][lang('customer_phones')] = array();
									foreach ($tender['customer']['phones'] as $key => $phone) {
										$tender['phones_options'][lang('customer_phones')]['customer_' . $tender['customer']['id'] . '_phone_'.$phone['key']] = ($phone['area_code'] ? '('.$phone['area_code'].') ' : '') . ($phone['number'] ? $phone['number'] : '') . ($phone['extension_number'] ? ' '.$phone['extension_number'] : '') . ' - ' . $phone['title'];
										if ( $tender['phone_key'] == 'customer_' . $tender['customer']['id'] . '_phone_'.$phone['key'] ){
											$tender['phone'] = $phone;
										}
									}
								}

								// preparando o array de emails, o qual estarão lado a lado com os do contato
								$tender['emails_options'] = array();
								// adicionando os emails do cliente ao array de opções do combobox
								if ( isset($tender['customer']['emails']) AND $tender['customer']['emails'] AND is_array($tender['customer']['emails']) ){
									$tender['emails_options'][lang('customer_emails')] = array();
									foreach ($tender['customer']['emails'] as $key => $email) {
										$tender['emails_options'][lang('customer_emails')]['customer_' . $tender['customer']['id'] . '_email_'.$email['key']] = ($email['area_code'] ? '('.$email['area_code'].') ' : '') . ($email['number'] ? $email['number'] : '') . ($email['extension_number'] ? ' '.$email['extension_number'] : '') . ' - ' . $email['title'];
										if ( $tender['email_key'] == 'customer_' . $tender['customer']['id'] . '_email_'.$email['key'] ){
											$tender['email'] = $email;
										}
									}
								}

								// carregando os endereços
								$tender['customer']['addresses'] = json_decode($tender['customer']['company_addresses'], TRUE);
								unset($tender['customer']['company_addresses']);

								// preparando o array de endereços, o qual estarão lado a lado com os do contato
								$tender['addresses_options'] = array();
								// adicionando os endereços do cliente ao array de opções do combobox
								if ( isset($tender['customer']['addresses']) AND $tender['customer']['addresses'] AND is_array($tender['customer']['addresses']) ){
									$tender['addresses_options'][lang('customer_addresses')] = array();
									foreach ($tender['customer']['addresses'] as $key => $address) {
										$tender['addresses_options'][lang('customer_addresses')]['customer_' . $tender['customer']['id'] . '_address_'.$address['key']] = $address['title'];
										if ( $tender['address_key'] == 'customer_' . $tender['customer']['id'] . '_address_'.$address['key'] ){
											$tender['address'] = $address;
										}
									}
								}

								// carregando os contatos
								$tender['customer']['contacts'] = json_decode($tender['customer']['contacts'], TRUE);
								foreach ($tender['customer']['contacts'] as $key => $contact) {

									$tender['customer']['contacts'][$key] = array_merge($contact, $this->contacts_model->get_contacts(array('t1.id'=>$contact['id']))->row_array());

									// se o contato atual for igual ao contato selecionado na proposta,
									// obtem os dados completos deste contato
									if ( $tender['customer']['contacts'][$key]['id'] == $tender['contact_id'] ){
										$tender['customer']['contacts'][$key]['phones'] = json_decode($tender['customer']['contacts'][$key]['phones'], TRUE);
										$tender['customer']['contacts'][$key]['emails'] = json_decode($tender['customer']['contacts'][$key]['emails'], TRUE);
										$tender['customer']['contacts'][$key]['addresses'] = json_decode($tender['customer']['contacts'][$key]['addresses'], TRUE);
										$tender['contact'] = $tender['customer']['contacts'][$key];

										// adicionando os telefones do contato ao array de opções do combobox
										if ( isset($tender['contact']['phones']) AND $tender['contact']['phones'] AND is_array($tender['contact']['phones']) ){
											$tender['phones_options'][lang('contact_phones')] = array();
											foreach ($tender['contact']['phones'] as $key => $phone) {
												$tender['phones_options'][lang('contact_phones')]['contact_' . $tender['contact']['id'] . '_phone_'.$phone['key']] = ($phone['area_code'] ? '('.$phone['area_code'].') ' : '') . ($phone['number'] ? $phone['number'] : '') . ($phone['extension_number'] ? ' '.$phone['extension_number'] : '') . ' - ' . $phone['title'];
												if ( $tender['phone_key'] == 'contact_' . $tender['contact']['id'] . '_phone_'.$phone['key'] ){
													$tender['phone'] = $phone;
												}
											}
										}

										// adicionando os emails do contato ao array de opções do combobox
										if ( isset($tender['contact']['emails']) AND $tender['contact']['emails'] AND is_array($tender['contact']['emails']) ){
											$tender['emails_options'][lang('contact_emails')] = array();
											foreach ($tender['contact']['emails'] as $key => $email) {
												$tender['emails_options'][lang('contact_emails')]['contact_' . $tender['contact']['id'] . '_email_'.$email['key']] = ($email['email'] ? $email['email'] : '') . ' - ' . $email['title'];
												if ( $tender['email_key'] == 'contact_' . $tender['contact']['id'] . '_email_'.$email['key'] ){
													$tender['email'] = $email;
												}
											}
										}

										// adicionando os endereços do contato ao array de opções do combobox
										if ( isset($tender['contact']['addresses']) AND $tender['contact']['addresses'] AND is_array($tender['contact']['addresses']) ){
											$tender['addresses_options'][lang('contact_addresses')] = array();
											foreach ($tender['contact']['addresses'] as $key => $address) {
												$tender['addresses_options'][lang('contact_addresses')]['contact_' . $tender['contact']['id'] . '_address_'.$address['key']] = $address['title'];
												if ( $tender['address_key'] == 'contact_' . $tender['contact']['id'] . '_address_'.$address['key'] ){
													$tender['address'] = $address;
												}
											}
										}


									}

								}

								break;

							}
							else {
								$tender['customer'] = FALSE;
							}

						}
						else
							$tender['customer'] = FALSE;
					}

				}

				$data = array(
					'component_name' => $this->component_name,
					'f_action' => $action,
					'tender' => $tender,
					'warranties' => $warranties,
					'states' => $states,
					'providers' => $providers,
					'tenders_status' => $tenders_status,
					'customers_categories' => $customers_categories,
					'customers' => $customers,
				);

				/******************************/
				/********* Parâmetros *********/

				// cruzando os parâmetros globais com os parâmetros locais para obter os valores atuais
				$data['current_params_values'] = $data['tender']['params'];

				// obtendo as especificações dos parâmetros
				$data['params_spec'] = $this->vesm_model->get_tender_params();

				// cruzando os valores padrões das especificações com os do DB
				$data['final_params_values'] = array_merge( $data['params_spec']['params_spec_values'], $data['current_params_values'] );

				// definindo as regras de validação
				set_params_validations( $data['params_spec']['params_spec'] );

				/********* Parâmetros *********/
				/******************************/

				//validação dos campos
				$this->form_validation->set_rules('title',lang('title'),'trim|required');
				$this->form_validation->set_rules('address_id',lang('address'),'trim|integer');

				// se houver pedido de adição de campos de produtos em branco
				if( in_array($submit_action, array('add_product_fields')) ){
					msg(($submit_action.'_success_message'),'success');
				}
				// se houver cancelamento
				else if( in_array($submit_action, array('cancel')) ){
					redirect_last_url();
				}
				// se a validação dos campos falhar e mensagens de erro conter strings
				else if ( (in_array($submit_action, array('submit','apply','preview_tender_pdf')))
					AND ( ! $this->form_validation->run() AND validation_errors() != '' )
					){

					// gerando mensagens de erro
					msg(('update_tender_fail'),'title');
					msg(validation_errors('<div class="error">', '</div>'),'error');


				}
				// se a validação dos campos for bem sucedida
				else if ( $this->form_validation->run() AND (in_array($submit_action, array('submit','apply','preview_tender_pdf'))) ){

					$msg_update_products = '';
					$msg_added_products = '';

					// atualizando, inserindo produtos
					foreach ( $post_data['products'] as $key => $product ) {

						// somente efetua a inserção / atualização se o Part number for informado
						// e se o produto foi marcado para inserção / atualização
						if ( $product[ 'code' ] AND isset( $product[ 'update_product' ] ) AND $product[ 'update_product' ] ){

							// carregando o model de produtos
							$this->load->model('admin/providers_model');

							// efetuando a busca do produto

							$db_product = $this->vesm_model->get_products(
								array(
									't1.code' => $product['code'],
									't1.provider_id' => $product['provider_id'],
								), 1)->row_array();



							// obtendo dados para o db
							// caso esteja marcado para não atualizar os campos em branco, selecionamos os campos com conteúdo
							if ( isset($product['dont_update_blank_fields']) ){

								$db_data = array();
								$db_data['code'] = 																		$product['code'];
								if ( $product['code_on_provider'] ) $db_data['code_on_provider'] = 						$product['code_on_provider'];
								if ( $product['title'] ) $db_data['title'] = 											$product['title'];
								if ( $product['provider_id'] ) $db_data['provider_id'] = 								$product['provider_id'];
								if ( $product['mcn'] ) $db_data['mcn'] = 												$product['mcn'];
								if ( $product['cost_price'] ) $db_data['cost_price'] = 									$product['cost_price'];
								if ( $product['unit'] ) $db_data['unit'] = 												$product['unit'];
								if ( $product['warranty'] ) $db_data['warranty'] = 										$product['warranty'];
								if ( $product['product_provider_tax'] ) $db_data['product_provider_tax'] = 				$product['product_provider_tax'];
								if ( $product['external_url'] ) $db_data['external_url'] = 								$product['external_url'];
								if ( $product['delivery_time'] ) $db_data['delivery_time'] = 							$product['delivery_time'];

							}
							else{

								$db_data = elements(array(
									'code',
									'code_on_provider',
									'title',
									'provider_id',
									'mcn',
									'cost_price',
									'delivery_time',
									'unit',
									'warranty',
									'product_provider_tax',
									'external_url',
								),$product);

							}

							// ajustando valores
							// ajustando o imposto no fornecedor
							if ( isset($db_data['product_provider_tax']) ){

								$db_data['product_provider_tax'] = str_replace(',', '.', $db_data['product_provider_tax']);
								$db_data['product_provider_tax'] = getFloat($db_data['product_provider_tax']);

							}

							// ajustando o preço de custo
							if ( isset($db_data['cost_price']) ){

								$db_data['cost_price'] = str_replace(',', '.', $db_data['cost_price']);
								$db_data['cost_price'] = getFloat($db_data['cost_price']);

							}


							// atualiza o produto, caso o encontre
							if ( $db_product ){

								$this->vesm_model->update_product($db_data, array('code' => $product['code'], 'provider_id' => $product['provider_id']));
								$msg_update_products .= '<li>' . $product['code'] . ' - ' . $product['title'] . '</li>';

							}
							else{

								$this->vesm_model->insert_product($db_data);
								$msg_added_products .= '<li>' . $product['code'] . ' - ' . $product['title'] . '</li>';

							}

						}

						unset($post_data['products'][$key]['update_product']);

					}


					// convertendo o array de produtos para json para inserção no db
					$post_data['products'] = json_encode($post_data['products']);

					// convertendo o array de fornecedores e condições para json para inserção no db
					$post_data['prov_conds'] = json_encode($post_data['prov_conds']);

					$update_data = elements(array(
						'title',
						'products',
						'prov_conds',
						'customers_category_id',
						'customer_id',
						'contact_id',
						'phone_key',
						'email_key',
						'address_key',
						'status_id',
						'params',
					),$post_data);

					// ajustando datas
					$update_data['date_tender_order'] = $post_data['date_tender_order'] . ' 00:00:00';

					$update_data['date_issue'] = $post_data['date_issue'] . ' 00:00:00';

					$update_data['params'] = json_encode($update_data['params']);

					if ( $this->vesm_model->update_tender($update_data, array('id'=>$var1)) ){

						if ( in_array($submit_action, array('apply','preview_tender_pdf')) ){

							if ( in_array($submit_action, array('preview_tender_pdf')) ){

								$pdf_error = FALSE;
								$pdf_error_msg = '<ul>';

								if ( ! isset($tender['customer']) ){
									$pdf_error = TRUE;
									$pdf_error_msg .= '<li>'.lang('msg_tender_no_customer_selected').'</li>';
								}

								if ( ! isset($tender['contact']) ){
									$pdf_error = TRUE;
									$pdf_error_msg .= '<li>'.lang(print_r($tender, TRUE)).'</li>';
								}

								$pdf_error_msg .= '</ul>';

								if ( ! $pdf_error ){

									$pdf_path = $this->tenders_pdf_path;

									$contact_name = explode(' ', $tender['contact']['name']);

									$pdf_filename =
										$tender['tender_code'] .
										' - ' .
										str_replace('/', '-', $tender['title']) .
										' - ' .
										str_replace('/', '-', $contact_name[0] . ' ' . $contact_name[ count( $contact_name ) -1 ]) .
										' - ' .
										$tender['customer']['title'] .
										' - ' .
										str_replace('/', '-', $tender['date_issue']) .
										'.pdf';

									$data['filename'] = $pdf_path.$pdf_filename;

									$this->vesm_model->generate_tender_pdf(
										$pdf_path . $pdf_filename,
										$data,
										'F'
									);

									msg( lang( 'tender_preview_pdf_generated' ) . '<br/><a target="_blank" title="'.lang('click_to_view_the_pdf_generated').'" href="'. $this->tenders_pdf_url . '/' . $pdf_filename.'">'.$pdf_filename.'</a>','success');

									if ( ! $this->input->get( 'ajax' ) ){

										redirect( $this->tenders_pdf_url . '/' . $pdf_filename );

									}

								}
								else{
									msg(('tender_preview_pdf_generated_fail'),'title');
									msg($pdf_error_msg,'error');
								}

							}
							else{
								msg(('tender_updated'),'success');

								if ( $msg_update_products ){
									msg(('info_products_updated'),'success');
									msg( '<ul>' . $msg_update_products . '</ul>','success');
								}

								if ( $msg_added_products ){
									msg(('info_products_added'),'success');
									msg( '<ul>' . $msg_added_products . '</ul>','success');
								}

							}

							//redirect('admin/'.$this->uri->ruri_string());
						}
						else{
							redirect_last_url();
						}
					}

				}

			}
			else{
				redirect_last_url();
			}

			$this->_page(

				array(

					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => 'tender_form',
					'layout' => 'default',
					'view' => 'tender_form',
					'data' => $data,

				)

			);

		}

		/**************** Add / Edit tender ***************/
		/**************************************************/

		/**************************************************/
		/****************** Remove tender *****************/

		else if ($action == 'remove_tender' AND $var1 AND ($tender = $this->companies_model->get_companies(array('t1.id' => $var1), 1)->row())){

			// $var1 = id do contato

			if($this->input->post('submit_cancel')){
				redirect_last_url();
			}
			else if ($this->input->post('submit')){
				if ($this->companies_model->delete_tender(array('id'=>$var1))){
					msg(('tender_deleted'),'success');
					redirect_last_url();
				}
				else{
					msg(('tender_deleted_fail'),'error');
					redirect_last_url();
				}
			}
			else{
				$data=array(
					'component_name' => $this->component_name,
					'f_action' => $action,
					'tender'=>$tender,
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

		/****************** Remove tender *****************/
		/**************************************************/

		/**************************************************/

	}

	/***************************** Tenders management *****************************/
	/******************************************************************************/
	/******************************************************************************/

	/******************************************************************************/
	/******************************************************************************/
	/***************************** Products management ****************************/

	public function products_management($action = NULL, $var1 = NULL, $var2 = NULL, $var3 = NULL, $var4 = NULL, $var5 = NULL, $var6 = NULL){

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
		/************ Products list / search **************/

		if ( $action == 'products_list' OR $action == 'search' ){

			$this->load->helper(array('pagination'));

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
				redirect( 'admin/'.$this->component_name . '/' . __FUNCTION__.'/products_list' );
			}

			$data['search']['terms'] = $terms;

			$this->form_validation->set_rules('terms',lang('terms'),'trim|min_length[2]');

			if( ( $this->input->post() OR $terms ) AND ! $errors){

				$condition = NULL;
				$or_condition = NULL;

				if( $terms ){

					$get_query = urlencode($terms);

					$full_term = $terms;
					$order_by = 'FIELD(t1.title, \''.$full_term.'\') ASC, t1.title ASC';

					$condition['fake_index_1'] = '';
					$condition['fake_index_1'] .= '(';
					$condition['fake_index_1'] .= '`t1`.`title` LIKE \'%'.$full_term.'%\' ';
					$condition['fake_index_1'] .= 'OR `t1`.`code_on_provider` LIKE \'%'.$full_term.'%\' ';
					$condition['fake_index_1'] .= 'OR `t1`.`code` LIKE \'%'.$full_term.'%\' ';
					$condition['fake_index_1'] .= 'OR `t1`.`description` LIKE \'%'.$full_term.'%\' ';
					$condition['fake_index_1'] .= 'OR `t1`.`mcn` LIKE \'%'.$full_term.'%\' ';
					$condition['fake_index_1'] .= 'OR `t2`.`title` LIKE \'%'.$full_term.'%\' ';
					$condition['fake_index_1'] .= ')';

					$terms = str_replace('#', ' ', $terms);
					$terms = explode(" ", $terms);

					$and_operator = FALSE;
					$like_query = '';

					foreach ($terms as $key => $term) {

						$like_query .= $and_operator === TRUE ? 'AND ' : '';
						$like_query .= '(';
						$like_query .= '`t1`.`title` LIKE \'%'.$term.'%\' ';
						$like_query .= 'OR `t1`.`code_on_provider` LIKE \'%'.$term.'%\' ';
						$like_query .= 'OR `t1`.`code` LIKE \'%'.$term.'%\' ';
						$like_query .= 'OR `t1`.`description` LIKE \'%'.$term.'%\' ';
						$like_query .= 'OR `t1`.`mcn` LIKE \'%'.$term.'%\' ';
						$like_query .= 'OR `t2`.`title` LIKE \'%'.$term.'%\' ';
						$like_query .= ')';

						if ( ! $and_operator ){
							$and_operator = TRUE;
						}

					}

					$or_condition = '(' . $like_query . ')';

					$products = $this->vesm_model->get_products_search_results($condition, $or_condition, $var2, $offset, NULL, $order_by, FALSE)->result();

					foreach ($products as $key => $product) {

						foreach ($terms as $term) {

							$product->code_on_provider = str_highlight( $product->code_on_provider, $term );
							$product->code = str_highlight( $product->code, $term );
							$product->title = str_highlight( $product->title, $term );
							$product->description = str_highlight( $product->description, $term );
							$product->provider_title = str_highlight( $product->provider_title, $term );

						}

					}

					$data['products'] = $products;
					$data['pagination'] = get_pagination('admin/'.$this->component_name . '/' . __FUNCTION__ . '/' . $action.'/%p%/%ipp%?q='.$get_query, $var1, $var2, $this->vesm_model->get_products_search_results($condition, $or_condition, NULL, NULL,'count_all_results'));

				}

			}
			else if ($products = $this->vesm_model->get_products(NULL, $var2, $offset)){

				if ( $errors ){

					$data['post'] = $this->input->post();

					msg(('search_fail'),'title');
					msg($errors_msg,'error');
				}

				$data = array(
					'products' => $products->result(),
					'pagination' => get_pagination('admin/vesm/products_management/products_list/%p%/%ipp%', $var1, $var2, $this->vesm_model->get_products(NULL, NULL, NULL,'count_all_results')),
				);

			}

			$data = array_merge($data, array(
				'component_name' => $this->component_name,
				'f_action' => $action,
			));
			set_last_url($url.((isset($get_query) AND $get_query) ? '?q='.$get_query : ''));

			$this->_page(

				array(

					'component_view_folder' => $this->component_view_folder,
					'function' => __FUNCTION__,
					'action' => 'products_list',
					'layout' => 'default',
					'view' => 'products_list',
					'data' => $data,

				)

			);

		}

		/************ Products list / search **************/
		/**************************************************/

		/**************************************************/
		/************** Switch country status *************/

		else if (($action == 'fetch_publish' OR $action == 'fetch_unpublish') AND $var1){

			$update_data = array(
				'status' => $action == 'fetch_publish'?'1':'0',
			);

			if ($this->places_model->update_country($update_data, array('id' => $var1))){
				msg(('country_'.($action == 'fetch_publish'?'published':'unpublished')),'success');
				redirect_last_url();
			}
		}

		/************** Switch country status *************/
		/**************************************************/

		/**************************************************/
		/*************** Add / edit product ***************/

		else if ( $action == 'add_product' OR $action == 'edit_product'){

			//validação dos campos
			$this->form_validation->set_rules('code',lang('product_code'),'trim|required');
			$this->form_validation->set_rules('title',lang('title'),'trim|required');
			$this->form_validation->set_rules('provider_id',lang('provider_id'),'trim|integer');
			$this->form_validation->set_rules('warranty',lang('warranty'),'trim');

			$product = FALSE;
			$fields_array = array();

			if ( $action == 'edit_product' ){
				$product = $this->vesm_model->get_products(array('t1.id'=>$var1), 1);
				$fields_array = $product->row_array();
			}

			$post_data = $this->input->post(NULL, TRUE);
			$submit_action =
				$this->input->post('submit_cancel')?'cancel':
				($this->input->post('submit')?'submit':
				($this->input->post('submit_apply')?'apply':
				'none'));

			if ( $post_data ){
				if ( $post_data['warranty'] === '0' AND ! $post_data['custom_warranty'] ){
					$this->form_validation->set_rules('custom_warranty',lang('custom_warranty'),'trim|required');
				}
				$fields_array = array_merge($fields_array, $post_data);
			}

			$this->load->model('admin/providers_model');
			$providers = $this->providers_model->get_providers();

			$warranties = $this->vesm_model->get_warranties();

			$data = array(
				'component_name' => $this->component_name,
				'product' => $product,
				'f_action' => $action,
				'providers'=>$providers->result_array(),
				'warranties'=>$warranties->result_array(),
				'fields_array' => $fields_array,
			);

			if( in_array($submit_action, array('cancel')) ){
				redirect_last_url();
			}
			// se a validação dos campos for negativa e mensagens de erro conter strings
			else if ( (in_array($submit_action, array('submit','apply')))
				AND ( ! $this->form_validation->run() AND validation_errors() != '' )
				){

				// verificando erros de validação do formulário
				if ( $action == 'add_product' )
					msg(('add_product_fail'),'title');
				else
					msg(('update_product_fail'),'title');
				msg(validation_errors('<div class="error">', '</div>'),'error');

			}
			// se a validação dos campos for bem sucedida
			else if ( $this->form_validation->run() AND (in_array($submit_action, array('submit','apply'))) ){

				$db_data = elements(array(
					'code',
					'title',
					'description',
					'provider_id',
					'mcn',
					'cost_price',
					'unit',
					'warranty',
					'product_provider_tax',
					'external_url',
				),$post_data);

				$db_data['product_provider_tax'] = str_replace(',', '.', $db_data['product_provider_tax']);
				$db_data['product_provider_tax'] = getFloat($db_data['product_provider_tax']);
				$db_data['cost_price'] = str_replace('.', '', $db_data['cost_price']);
				$db_data['cost_price'] = str_replace(',', '.', $db_data['cost_price']);
				$db_data['cost_price'] = getFloat($db_data['cost_price']);

				if ( $db_data['warranty'] === '0' ){
					$db_data['warranty'] = $post_data['custom_warranty'];

					$warranty_data = array(
						'title' => $db_data['warranty'],
					);

					if ( ! $this->vesm_model->get_warranties(array('title'=>$post_data['custom_warranty']), 1)->row() ){
						$this->vesm_model->insert_warranty($warranty_data);
					}

				}

				if ( $action == 'add_product' ){
					$id = $this->vesm_model->insert_product($db_data);
				}
				else {
					$id = $var1;
				}

				if ($id){

					if ( $action == 'add_product' ){
						msg(('product_added'),'success');
					}
					else if ( $this->vesm_model->update_product($db_data, array('id'=>$id)) ){
						msg(('product_updated'),'success');
						$id = $var1;
					}

					if ($this->input->post('submit_apply')){
						redirect('admin/'.$this->component_name . '/' . __FUNCTION__.'/edit_product/'.$id);
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
					'action' => 'product_form',
					'layout' => 'default',
					'view' => 'product_form',
					'data' => $data,

				)

			);

		}

		/*************** Add / edit product ***************/
		/**************************************************/

		/**************************************************/
		/***************** Remove product *****************/

		else if ($action == 'remove_product' AND $var1 AND ($product = $this->vesm_model->get_products(array('t1.id' => $var1), 1)->row())){

			// $var1 = id do contato

			if($this->input->post('submit_cancel')){
				redirect_last_url();
			}
			else if ($this->input->post('submit')){
				if ($this->vesm_model->delete_product(array('id'=>$var1))){
					msg(('product_deleted'),'success');
					redirect_last_url();
				}
				else{
					msg(('product_deleted_fail'),'error');
					redirect_last_url();
				}
			}
			else{
				$data=array(
					'component_name' => $this->component_name,
					'f_action' => $action,
					'product'=>$product,
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

		/***************** Remove product *****************/
		/**************************************************/

		/**************************************************/

	}

	/***************************** Products management ****************************/
	/******************************************************************************/
	/******************************************************************************/

	/******************************************************************************/
	/******************************************************************************/
	/**************************** External components *****************************/

	// funcções utilizadas por outros componentes para obter dados
	// os campos aqui definidos são padronizados, e nunca definidos pelo componente requisitante
	// o componente requisitante deve obedecer estes padrões
	public function products_select( $action = NULL, $var1 = NULL, $var2 = NULL, $var3 = NULL, $var4 = NULL, $var5 = NULL, $var6 = NULL ){

		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;

		if ( ! $action ) redirect_last_url();

		$url = get_url('admin'.$this->uri->ruri_string());

		if ( $this->session->envdata('reference_data_id') ){

			$this->session->set_envdata('select_on', TRUE);

			$reference_data_id = $this->session->envdata('reference_data_id');
			//echo $reference_data_id;
			$temp_data = $this->main_model->get_temp_data(array('id' =>$reference_data_id))->row();
			$temp_data->data = json_decode($temp_data->data, TRUE);

			$reference = $temp_data->reference;

			if( $this->input->post('submit_ok') ){

				$data = array(
					'data' => array(
						'selection' => 'ok',
					),
				);

				$data['data'] = json_encode(array_merge($data['data'], $temp_data->data));

				$this->main_model->update_temp_data($data, array('id' =>$reference_data_id));
				redirect($reference);

			}
			// se o usuário cancelar a escolha
			else if( $this->input->post('submit_cancel') ){

				$data = array(
					'data' => array(
						'selection' => 'canceled',
					),
				);

				$data['data'] = json_encode(array_merge($data['data'], $temp_data->data));

				$this->main_model->update_temp_data($data, array('id' =>$reference_data_id));
				redirect($reference);

			}

			$data = array(
				'component_name' => $this->component_name,
				'search_fields' => array(
					'terms' => '',
				),
			);


			/**************************************************/
			/********************* Search *********************/

			if ( $action == 'search' ){

				$this->load->helper(array('pagination'));

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
					redirect( 'admin/'.$this->component_name . '/' . __FUNCTION__.'/select_product' );
				}

				$data['search_fields']['terms'] = $terms;

				$this->form_validation->set_rules('terms',lang('terms'),'trim|min_length[2]');

				if( ( $this->input->post() OR $terms ) AND ! $errors){

					$condition = NULL;
					$or_condition = NULL;

					if( $terms ){

						$get_query = urlencode($terms);

						$full_term = $terms;
						$order_by = 'FIELD(t1.title, \''.$full_term.'\') ASC, t1.title ASC';

						$condition['fake_index_1'] = '';
						$condition['fake_index_1'] .= '(';
						$condition['fake_index_1'] .= '`t1`.`title` LIKE \'%'.$full_term.'%\' ';
						$condition['fake_index_1'] .= 'OR `t1`.`code_on_provider` LIKE \'%'.$full_term.'%\' ';
						$condition['fake_index_1'] .= 'OR `t1`.`code` LIKE \'%'.$full_term.'%\' ';
						$condition['fake_index_1'] .= 'OR `t1`.`description` LIKE \'%'.$full_term.'%\' ';
						$condition['fake_index_1'] .= 'OR `t1`.`mcn` LIKE \'%'.$full_term.'%\' ';
						$condition['fake_index_1'] .= 'OR `t2`.`title` LIKE \'%'.$full_term.'%\' ';
						$condition['fake_index_1'] .= ')';

						$terms = str_replace('#', ' ', $terms);
						$terms = explode(" ", $terms);

						$and_operator = FALSE;
						$like_query = '';

						foreach ($terms as $key => $term) {

							$like_query .= $and_operator === TRUE ? 'AND ' : '';
							$like_query .= '(';
							$like_query .= '`t1`.`title` LIKE \'%'.$term.'%\' ';
							$like_query .= 'OR `t1`.`code_on_provider` LIKE \'%'.$term.'%\' ';
							$like_query .= 'OR `t1`.`code` LIKE \'%'.$term.'%\' ';
							$like_query .= 'OR `t1`.`description` LIKE \'%'.$term.'%\' ';
							$like_query .= 'OR `t1`.`mcn` LIKE \'%'.$term.'%\' ';
							$like_query .= 'OR `t2`.`title` LIKE \'%'.$term.'%\' ';
							$like_query .= ')';

							if ( ! $and_operator ){
								$and_operator = TRUE;
							}

						}

						$or_condition = '(' . $like_query . ')';

						$products = $this->vesm_model->get_products_search_results($condition, $or_condition, $var2, $offset, NULL, $order_by, FALSE)->result();

						foreach ($products as $key => $product) {

							foreach ($terms as $term) {

								$product->code_on_provider = str_highlight( $product->code_on_provider, $term );
								$product->code = str_highlight( $product->code, $term );
								$product->title = str_highlight( $product->title, $term );
								$product->description = str_highlight( $product->description, $term );
								$product->provider_title = str_highlight( $product->provider_title, $term );

							}

						}

						$data['products'] = $products;
						$data['pagination'] = get_pagination('admin/'.$this->component_name . '/' . __FUNCTION__ . '/' . $action.'/%p%/%ipp%?q='.$get_query, $var1, $var2, $this->vesm_model->get_products_search_results($condition, $or_condition, NULL, NULL,'count_all_results'));

					}
					else if($this->input->post('submit_select')){

						$product = $this->vesm_model->get_products(array('t1.id' => $this->input->post('product_id')));

						$selected_products = array();

						$selected_products[] = array(
							'id' => $product->row()->id,
							'code_on_provider' => $product->row()->code_on_provider,
							'code' => $product->row()->code,
							'title' => $product->row()->title,
							'description' => $product->row()->description,
							'provider_id' => $product->row()->provider_id,
							'mcn' => $product->row()->mcn,
							'cost_price' => $product->row()->cost_price,
							'warranty' => $product->row()->warranty,
							'product_provider_tax' => $product->row()->product_provider_tax,
							'delivery_time' => $product->row()->delivery_time,
							'unit' => $product->row()->unit,
							'status' => $product->row()->status,
							'external_url' => $product->row()->external_url,
							'provider_title' => $product->row()->provider_title,
						);

						$data = array(
							'data' => array(
								'selected_products' => $selected_products,
								'selection' => 'selected',
							),
						);

						$data['data'] = json_encode(array_merge($data['data'], $temp_data->data));

						$this->main_model->update_temp_data($data, array('id' =>$reference_data_id));
						redirect($temp_data->reference);
					}

				}
				else if ( $errors ){

					$data['post'] = $this->input->post();

					msg(('search_fail'),'title');
					msg($errors_msg,'error');
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

			/********************* Search *********************/
			/**************************************************/

			/**************************************************/

			/**************************************************/
			/***************** Select product *****************/

			else if ($action == 'select_product'){

				$this->load->helper(array('pagination'));

				// $var1 = página atual
				// $var2 = itens por página
				if ( $var1 < 1 OR ! gettype($var1) == 'int' ) $var1 = 1;
				if ( $var2 < 1 OR ! gettype($var2) == 'int' ) $var2 = $this->mcm->filtered_system_params['admin_items_per_page'];
				$offset = ($var1-1)*$var2;

				if ($products = $this->vesm_model->get_products(NULL, $var2, $offset)){

					if( $this->input->post('submit_select') ){

						$product = $this->vesm_model->get_products(array('t1.id' => $this->input->post('product_id')));

						$selected_products = array();

						$selected_products[] = array(
							'id' => $product->row()->id,
							'code_on_provider' => $product->row()->code_on_provider,
							'code' => $product->row()->code,
							'title' => $product->row()->title,
							'description' => $product->row()->description,
							'provider_id' => $product->row()->provider_id,
							'mcn' => $product->row()->mcn,
							'cost_price' => $product->row()->cost_price,
							'warranty' => $product->row()->warranty,
							'product_provider_tax' => $product->row()->product_provider_tax,
							'delivery_time' => $product->row()->delivery_time,
							'unit' => $product->row()->unit,
							'status' => $product->row()->status,
							'external_url' => $product->row()->external_url,
							'provider_title' => $product->row()->provider_title,
						);

						$data = array(
							'data' => array(
								'selected_products' => $selected_products,
								'selection' => 'selected',
							),
						);

						$data['data'] = json_encode(array_merge($data['data'], $temp_data->data));

						$this->main_model->update_temp_data($data, array('id' =>$reference_data_id));
						redirect( $reference );
					}

					$data['products'] = $products->result();
					$data['pagination'] = get_pagination('admin/'.$this->component_name . '/' . __FUNCTION__ . '/' . $action.'/%p%/%ipp%', $var1, $var2, $this->vesm_model->get_products(NULL, NULL, NULL,'count_all_results'));

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

			/***************** Select product *****************/
			/**************************************************/

			else{
				show_404();
			}

		} else {
			msg(('no_reference_informed'),'error');
			redirect_last_url();
		}

	}

	/**************************** External components *****************************/
	/******************************************************************************/
	/******************************************************************************/


	/******************************************************************************/
	/******************************************************************************/
	/*************************** Global configurations ****************************/

	public function component_config($action = NULL, $layout = 'default'){

		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;

		/**************************************************/
		/************** Tender configurations *************/

		if ($action == 'edit_tender_config' AND ($component = $this->main_model->get_component(array('unique_name' => $this->component_name))->row())){

			$data = array(
				'component_name' => $this->component_name,
				'component' => $component,
			);



			/******************************/
			/********* Parâmetros *********/

			// obtendo os valores atuais dos parâmetros
			$data['current_params_values'] = json_decode( $data['component']->params, TRUE );

			// obtendo as especificações dos parâmetros
			$data['params_spec'] = $this->vesm_model->get_tender_params();

			// cruzando os valores padrões das especificações com os atuais
			$data['final_params_values'] = array_merge( $data['params_spec']['params_spec_values'], $data['current_params_values'] );

			// definindo as regras de validação
			set_params_validations( $data['params_spec']['params_spec'] );

			/********* Parâmetros *********/
			/******************************/

			$this->form_validation->set_rules('component_id',lang('id'),'trim|required|integer');

			// obtendo as validações dos parâmetros
			//generate_params_validations($data['params']);

			if($this->input->post('submit_cancel')){
				redirect_last_url();
			}
			// se a validação dos campos for positiva
			else if ($this->form_validation->run() AND ($this->input->post('submit') OR $this->input->post('submit_apply'))){

				$update_data = elements(array(
					'params',
				),$this->input->post());

				$update_data['params'] = json_encode($update_data['params']);

				if ($this->main_model->update_component($update_data, array('id' => $this->input->post('component_id')))){
					msg(('component_preferences_updated'),'success');

					if ($this->input->post('submit_apply')){
						//redirect('admin/'.$this->component_name . '/' . __FUNCTION__ . '/' . $action);
					}
					else{
						redirect_last_url();
					}
				}

			}
			// caso contrário se a validação dos campos for negativa e mensagens de erro conter strings
			else if (!$this->form_validation->run() AND validation_errors() != ''){

				$data['post'] = $this->input->post();

				msg(('update_component_preferences_fail'),'title');
				msg(validation_errors('<div class="error">', '</div>'),'error');
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

		/************** Tender configurations *************/
		/**************************************************/

		else{
			redirect('admin/'.$this->component_name.'/articles_management/articles_list/1');
		}

	}

	/*************************** Global configurations ****************************/
	/******************************************************************************/
	/******************************************************************************/

	/******************************************************************************/
	/******************************************************************************/
	/************************************ JSON ************************************/

	public function json($action = NULL){

		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;

		/**************************************************/
		/****************** Get customers *****************/

		if ( $action == 'get_customers' AND $this->input->post('category_id') ){

			$category_id = $this->input->post('category_id');

			// carregando model dos clientes
			$this->load->model('admin/customers_model');

			// carregando a lista de categorias de clientes
			$customers_categories = $this->customers_model->get_categories_tree( 0,0,'list' );

			// carregando a lista de clientes
			if ( $category_id ){

				$temp = $this->customers_model->get_categories_tree( $category_id,0,'list' );

				$customers = $this->customers_model->get_customers_simple(array( 't1.category_id' => $category_id ))->result_array();

				if ( ! $customers ){
					$customers = array();
				}

				if ( $temp)
					foreach ($temp as $key => $value) {
						$customers = array_merge($customers, $this->customers_model->get_customers_simple(array( 't1.category_id' => $value['id'] ))->result_array());
					}

			}

			if ( $customers ){

				$data['customers'] = $customers;

				header('Content-type: application/json');
				echo json_encode($data);

			}
			else{
				echo 'no_data';
			}
		}

		/****************** Get customers *****************/
		/**************************************************/

		/**************************************************/
		/**************** Get provider data ***************/

		else if ( $action == 'get_provider_data' AND $this->input->post('provider_id') ){

			$provider_id = $this->input->post('provider_id');

			// carregando model dos fornecedores
			$this->load->model('admin/providers_model');

			// carregando o fornecedor
			if ( $provider_id ){

				$provider = $this->providers_model->get_providers(array( 't1.id' => $provider_id ), 1)->row_array();

			}

			if ( $provider ){

				$provider['addresses'] = json_decode($provider['addresses'], TRUE);

				// carregando model dos locais para obtenção do id do estado do primeiro endereço da empresa
				$this->load->model('admin/places_model');
				$state = $this->places_model->get_states(array( 't1.acronym' => $provider['addresses'][1]['state_acronym'] ), 1)->row_array();
				$provider['state_origin_id'] = $state['id'];

				$data['provider'] = $provider;

				header('Content-type: application/json');
				echo json_encode($data);

			}
			else{
				echo 'no_data';
			}
		}

		/**************** Get provider data ***************/
		/**************************************************/

		/**************************************************/
		/******************* Get phones *******************/

		if ($action == 'get_phones'){

			$customer_id = $this->input->post('customer_id');
			$contact_id = $this->input->post('contact_id');
			//$customer_id = 4;
			//$contact_id = 3;

			$this->load->model('admin/customers_model');
			$this->load->model('admin/contacts_model');
			if ($customer = $this->customers_model->get_customers(array( 't1.id' => $customer_id ), 1, NULL)->row()){

				$phones = array();

				// se o cliente for uma empresa e possuir telefones
				if ( $customer->company_id ){

					if ( $customer->company_phones ){

						$customer->company_phones = explode("|", $customer->company_phones);
						foreach ($customer->company_phones as $key => $phone) {
							$customer->company_phones[$key] = explode(":::", $phone);
							$customer->company_phones['company'.$customer->company_phones[$key][0]] = ($customer->company_phones[$key][2] ? '(' . $customer->company_phones[$key][2] . ') ' : '') . $customer->company_phones[$key][3] . ' - ' . $customer->company_phones[$key][1];
							unset($customer->company_phones[$key]);
						}

						$phones[lang('company_phones')] = $customer->company_phones;

					}
				}

				// obtendo os telefones do contato
				if ( $contact_id ){
					$contact = $this->contacts_model->get_contacts(array('t1.id' => $contact_id), 1)->row_array();

					if ( $contact['phones'] ){

						$contact_phones = explode("|", $contact['phones']);
						foreach ($contact_phones as $key => $phone) {
							$contact_phones[$key] = explode(":::", $phone);
							$contact_phones['contact'.$contact_phones[$key][0]] = ($contact_phones[$key][2] ? '(' . $contact_phones[$key][2] . ') ' : '') . $contact_phones[$key][3] . ' - ' . $contact_phones[$key][1];
							unset($contact_phones[$key]);
						}

						$phones[lang('contact_phones')] = $contact_phones;

					}
				}

				$phones_options = '';
				foreach($phones as $key => $value) {
					$phones_options .= '<optgroup label="'.$key.'">';

					foreach($value as $key_2 => $value_2):
						$phones_options .= '<option value="'.$key_2.'">';
						$phones_options .= $value_2;
						$phones_options .= '</option>';
					endforeach;

					$phones_options .= '</optgroup>';

				};

				if ( $phones ){

					$phones['phones'] = $phones;
					$phones['phones_options'] = $phones_options;

					header('Content-type: application/json');
					echo json_encode($phones);

				}
				else{
					echo 'no_phones';
				}
			}
		}

		/******************* Get phones *******************/
		/**************************************************/
	}

	/************************************ JSON ************************************/
	/******************************************************************************/
	/******************************************************************************/

	/******************************************************************************/
	/******************************************************************************/
	/************************************ Ajax ************************************/

	public function ajax( $action = NULL, $var1 = NULL, $var2 = NULL ){

		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;

		/**************************************************/
		/******** Get tender customer fields merged *******/

		if ( $action == 'get_tender_customer_fields_merged' AND $var1 AND $var2 ){

			$this->load->model('admin/customers_model');
			if ($customer = $this->customers_model->get_customers(array( 't1.id' => $var1 ), 1, NULL)->row_array()){

				$customer_type = $customer['company_id'] ? 'legal_entity' : ( $customer['contact_id'] ? 'individual_entity' : '' );

				// se o cliente for empresa
				if ( $customer_type == 'legal_entity' ){

					$customer['phones'] = array();
					$customer['emails'] = array();
					$customer['addresses'] = array();

					$customer['phones']['company_phones'] = json_decode($customer['company_phones'], TRUE);
					$customer['emails']['company_emails'] = json_decode($customer['company_emails'], TRUE);
					$customer['addresses']['company_addresses'] = json_decode($customer['company_addresses'], TRUE);

					$this->load->model('admin/contacts_model');
					if ($contact = $this->contacts_model->get_contacts(array( 't1.id' => $var2 ), 1, NULL)->row_array()){

						$customer['contact'] = $contact;

						$customer['phones']['contact_phones'] = json_decode($contact['phones'], TRUE);
						$customer['emails']['contact_emails'] = json_decode($contact['emails'], TRUE);
						$customer['addresses']['contact_addresses'] = json_decode($contact['addresses'], TRUE);

					}

				}

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
						'view' => $action,
						'data' => $data,
						'html' => FALSE,
						'load_index' => FALSE,

					)

				);

			}
		}

		/******** Get tender customer fields merged *******/
		/**************************************************/

		else{

		}

	}

	/************************************ Ajax ************************************/
	/******************************************************************************/
	/******************************************************************************/


	// função para carregar as views para pdf
	private function pdf_page($view = NULL, $data = NULL){

		if ($view){
			//$saida = $this->load->view('pdf/header',$data,true);
			$saida = $this->load->view($view,$data,true);
			//$saida .= $this->load->view('pdf/footer',$data,true);
			return $saida;
		}
		else {
			redirect();
		}

	}


}





