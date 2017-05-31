<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main extends CI_controller {
	
	public $current_component = FALSE;
	public $component_view_folder = FALSE;

	public $menu_item = FALSE;
	public $menu_item_params = FALSE;
	public $html_data = array();

	public function __construct(){
		
		parent::__construct();

		/*
		 * -------------------------------------------------------------------------------------------------
		 * carregando helpers, libraries and models
		 */

		$this->load->database();

		$this->load->model( 'common/main_common_model', 'mcm' );
		$this->load->model( 'users_mdl', 'users' );

		/*
		 * -------------------------------------------------------------------------------------------------
		 * Definindo o ambiente
		 */

		$this->mcm->environment = SITE_ALIAS;

		/*
		 * -------------------------------------------------------------------------------------------------
		 */

		$this->load->helper(

			array(

				'form',
				'array',
				'text',
				'general',
				'msg',
				'params',
				'date',
				'menus',
				'html',
				'vui_elements',
				'directory',
				'language', 

			)

		);





		$this->mcm->check_session_config();

		$this->load->library(

			array(

				'voutput',
				'user_agent',
				'form_validation',
				'table',

			)

		);

		$this->load->model( 'plugins_mdl', 'plugins' );
		$this->load->model( 'cache_mdl', 'cache' );

		$this->load->model(

			array(

				SITE_DIR_NAME . '/main_model',
				SITE_DIR_NAME . '/users_model',
				'common/menus_common_model',
				'common/modules_common_model',
				'common/plugins_common_model',
				'common/urls_common_model',

			)

		);
		
		/*
		 * -------------------------------------------------------------------------------------------------
		 */
		
		/*
		 * -------------------------------------------------------------------------------------------------
		 * Obtendo as configurações iniciais do sistema a partir do arquivo de configurações do Codeigniter
		 */

		foreach ( $this->config->config as $key => $value) {

			$this->mcm->system_params[ $key ] = $value;

		}

		/*
		 * -------------------------------------------------------------------------------------------------
		 */


		
		// Se o usuário não está conectado, verificamos se existe algum usuário com o client_hash igual ao calculado,
		// se sim, quer dizer que está logado em modo persistente
		
		if ( ! $this->users->is_logged_in() ){
			
			$hash_user = $this->users->check_client_hash();
			
			if ( $hash_user ){
				
				// do login params
				$dlp = array(
					
					'user_data' => array(
						
						'username' => $hash_user[ 'username' ],
						
					),
					'login_mode' => 'force', // ignore password
					'session_mode' => 'persistent', // keep logged in
					
				);
				
				$this->users->do_login( $dlp );
				
			}
			
		}
		
		/*
		 * -------------------------------------------------------------------------------------------------
		 * Definindo as urls reversas
		 */

		$this->urls_common_model->setup_reverse_urls();

		/*
		 * -------------------------------------------------------------------------------------------------
		 */



		/*
		 * -------------------------------------------------------------------------------------------------
		 * Encurtando o acesso a library $this->user_agent
		 */

		$this->ua = &$this->agent;

		/*
		 * -------------------------------------------------------------------------------------------------
		 */
		
		
		
		/*
		 * -------------------------------------------------------------------------------------------------
		 * Encurtando o acesso ao model $this->modules_common_model
		 */
		
		$this->modc = &$this->modules_common_model;

		/*
		 * -------------------------------------------------------------------------------------------------
		 */
		
		
		
		/*
		 * -------------------------------------------------------------------------------------------------
		 * Definimos algumas variáveis referente ao componente atual, no primeiro momento o Main
		 */

		$this->component_name = get_class_name( get_class() );
		$this->current_component = $this->mcm->get_component( $this->component_name );
		$this->component_view_folder = $this->component_name;

		/*
		 * -------------------------------------------------------------------------------------------------
		 */



		/*
		 * -------------------------------------------------------------------------------------------------
		 * Se não for informada a última url acessada, definimos a mesma
		 * como sendo a url padrão ( default controller )
		 */

		if ( ! $this->session->envdata( 'last_url' ) ){

			set_last_url( get_url( $this->router->default_controller ) );

		}

		/*
		 * -------------------------------------------------------------------------------------------------
		 */



		/*
		 * -------------------------------------------------------------------------------------------------
		 * Carregando os arquivos de idiomas padrões
		 */

		$langs = array(

			'general',
			'messages',
			'date',
			'calendar',

		);

		/*
		 * -------------------------------------------------------------------------------------------------
		 */



		/*
		 * -------------------------------------------------------------------------------------------------
		 * Componentes
		 */

// 		echo '<pre>' . print_r( $this->current_component[ 'params' ], TRUE ) . '</pre>';
		
		$this->mcm->get_components();

		$this->mcm->system_params = array_merge( $this->mcm->system_params, $this->mcm->components[ 'main' ][ 'params' ] );

		$this->mcm->system_params[ 'language' ] = $this->mcm->system_params[ $this->mcm->environment . '_language' ];

		$this->mcm->filtered_system_params = filter_params( $this->mcm->system_params, $this->current_component[ 'params' ] );
		
		
		
		/*
		 * -------------------------------------------------------------------------------------------------
		 * Setting the site theme
		 */
		
		if ( $this->input->get( 'theme' ) ) {
			
			echo $this->config->set_item( 'site_theme', $this->input->get( 'theme' ) );
			$this->mcm->filtered_system_params[ 'site_theme' ] = $this->input->get( 'theme' );
			
		}
		
		/*
		 * -------------------------------------------------------------------------------------------------
		 */
		
		
		
		foreach ( $this->config->config as $key => $value) {
			
			if ( check_var( $this->mcm->filtered_system_params[ $key ] ) ){
				
				$this->config->set_item( $key, $this->mcm->filtered_system_params[ $key ] );
				
			}
			
		}
		
		// Obtemos os componentes ativos
		// Os componentes obtidos estarão disponíveis através do array $this->mcm->components
		foreach ( $this->mcm->components as $key => $component ) {
			
			// adicionamos ao array de idomas a serem carregados os arquivos de idiomas dos componentes ativos
			// estes arquivos são carregados mais a frente
			if ( file_exists( APPPATH . 'language' . DS . $this->mcm->filtered_system_params[ $this->mcm->environment . '_language' ] . DS . $component[ 'unique_name' ] . '_lang.php' ) ) {
				$langs[] = $component[ 'unique_name' ];
			}
			
		}
		
		/*
		 * -------------------------------------------------------------------------------------------------
		 */
		
		
		
		/*
		 * -------------------------------------------------------------------------------------------------
		 * Obtemos os dados do item de menu atual.
		 *
		 * O objetivo deste procedimento é guardar as informações do item de menu atual em um array para uso
		 * no decorrer da aplicação.
		 *
		 * Se não há registro no BD do item de menu atual, nenhum dado de item de menu é carregado. Isto pode afetar
		 * por exemplo, se um componente solicitar os parâmetros do item de menu atual, caso não seja encontrado nenhum registro,
		 * o componente pode obter os parâmetros a partir de outra origem.
		 *
		 * Os paraâmetros do item de menu estão em formato json não decodado, ou seja, deve ser trabalhada no componente
		 */

		$current_menu_item_params = array();

		$this->mcm->current_menu_item = get_current_menu_item();
		//var_dump($this->mcm->current_menu_item);
		if ( $this->mcm->current_menu_item AND $this->mcm->current_menu_item[ 'status' ] ){
			
			$current_menu_item_params = $this->mcm->current_menu_item[ 'params' ] = get_params( $this->mcm->current_menu_item[ 'params' ] );
			
		}
		// se o item de menu atual estiver desativado, exibimos a tela 404
		else if ( ( $this->mcm->current_menu_item !== 0 AND $this->mcm->current_menu_item !== FALSE ) AND ( ! $this->mcm->current_menu_item[ 'status' ] ) ){

			show_404();

		}
		
		$this->mcm->filtered_system_params = $this->mcm->parse_params( filter_params( $this->mcm->filtered_system_params, $current_menu_item_params ) );

		// descomente para ver os dados
		//print_r( $this->mcm->current_menu_item );
		/*
		 * -------------------------------------------------------------------------------------------------
		 */



		/*
		 * -------------------------------------------------------------------------------------------------
		 * Login
		 */
		
		// Se o usuário estiver logado
		if ( $this->users->is_logged_in() ){
			
			// removemos a variável que indica a url o qual o usuário deve ser redirecionado após o login
			$this->session->unset_envdata( 'uri_after_login_' . $this->mcm->environment );
			
			// obtemos o id do usuário da session. O único dado do usuário que guardamos na variável de session "site_user_data" é a sua id, por segurança
			$user_data = $this->session->envdata( 'user' );
			
			// enviando as informações do usuário para a variável global do usuário
			$this->users->user_data = $this->users->get_user( array( 't1.id' => $user_data[ 'id' ] ) )->row_array();
			
			// obtendo os privilégios
			$this->users->user_data[ 'privileges' ] = json_decode( $this->users->user_data[ 'privileges' ], TRUE );
			// aqui transformamos o array multidimensional de privilégios em um array simples
			// $this->users->user_data[ 'privileges' ] = array_flatten( $this->users->user_data[ 'privileges' ] );
			
			// obtendo os parâmetros do usuário em formato de array
			$this->users->user_data[ 'params' ] = json_decode( $this->users->user_data[ 'params' ], TRUE );
			
			// tratamos os parâmetros do usuário
			$this->users->user_data[ 'params' ] = $this->main_model->parse_params( $this->users->user_data[ 'params' ] );
			
			// definimos os parâmetros globais e do usuário como sendo o resultado filtrado entre os mesmos,
			// a prioridade é para o usuário, exceto, claro, se alguma configuração for global
			$this->mcm->user_params = $this->users->user_data[ 'params' ];
			$this->mcm->filtered_system_params = filter_params( $this->mcm->filtered_system_params, $this->users->user_data[ 'params' ] );
			
			// descomente para ver os parâmetros
			// print_r( $this->mcm->system_params );
			
		}
		
		// definindo o idioma do site de acordo com os parâmetros obtidos
		$this->config->set_item( 'language', $this->mcm->filtered_system_params[ $this->mcm->environment . '_language' ] );
		$this->config->set_item( 'time_zone', $this->mcm->filtered_system_params[ 'time_zone' ] );
		
		/*
		 * -------------------------------------------------------------------------------------------------
		 */
		
		$this->mcm->filtered_system_params = $this->mcm->parse_params( $this->mcm->filtered_system_params );
		
		
		
		foreach ( $this->config->config as $key => $value) {
			
			$this->config->set_item( $key, $this->mcm->filtered_system_params[ $key ] );
			
		}
		
		
		
		
		/*
		 * -------------------------------------------------------------------------------------------------
		 * Módulos
		 */

		// Obtemos os módulos ativos
		// Os componentes obtidos estarão disponíveis através do array $this->modc->modules_types

		$this->modc->get_modules_types();

		// idiomas dos tipos de módulos
		foreach ( $this->mcm->modules_types as $key => &$module_type ) {

			// adicionamos ao array de idomas a carregar os arquivos de idiomas dos componentes ativos
			if ( file_exists( APPPATH . 'language' . DS . $this->mcm->filtered_system_params[ 'language' ] . DS . 'modules' . DS . $module_type[ 'alias' ] . '_lang.php' ) ) {

				$langs[] = 'modules' . DS . $module_type[ 'alias' ];
			}

		}
		
		/*
		 * -------------------------------------------------------------------------------------------------
		 */
		
		
		
		/*
		 * -------------------------------------------------------------------------------------------------
		 * Carregando os arquivos de idiomas de cada componente ativo
		 */

		$this->load->language( $langs );

		/*
		 * -------------------------------------------------------------------------------------------------
		 */



		/*
		 * -------------------------------------------------------------------------------------------------
		 * Lista das configurações obtidas, descomente para ver
		 */

		// print_r( $this->mcm->components );
		// print_r( $this->mcm->system_params );
		// print_r( $this->mcm->user_params );
		// print_r( $this->mcm->filtered_system_params );
		// print_r( $this->mcm->current_menu_item );
		
		/*
		 * -------------------------------------------------------------------------------------------------
		 */
		
		
		
		/*
		* -------------------------------------------------------------------------------------------------
		* Profiler
		*/
		
		if ( check_var( $this->mcm->filtered_system_params[ 'site_enable_profiler' ] ) AND $this->users->check_privileges( 'site_can_view_profiler' ) ) {
			
			$this->output->enable_profiler( TRUE );
			
		}
		
		/*
		* -------------------------------------------------------------------------------------------------
		*/
		
		
		
	}
	
	// função index vazia para impedir acesso direto ao componente main
	public function index(){
		
		show_404();
		
	}
	
	// função index vazia para impedir acesso direto ao componente main
	public function errors(){
		
		show_404();
		
	}
	
	// função index vazia para impedir acesso direto ao componente main
	public function csv_lucas(){
		
		$csv = array_map( 'str_getcsv', file( 'emails.csv' ) );
		
		echo 'total antes da validação: ' . count( $csv );
		
		$i = 0;
		$limite = 8000;
		
		foreach( $csv as & $row ) {
			
			$row = explode( ';', $row[ 0 ] );
			
			$row = array(
				
				'nome' => $row[ 3 ],
				'email' => $row[ 6 ],
				'estado' => $row[ 11 ],
				'telefone' => $row[ 17 ],
				
			);
			
			if ( ! $this->form_validation->valid_email_dns( $row[ 'email' ] ) ) {
				
				$row = NULL;
				unset( $row );
				
			}
			else {
				
				$txt = $row[ 'nome' ] . ';' . $row[ 'email' ] . ';' . $row[ 'estado' ] . ';' . $row[ 'telefone' ];
				file_put_contents( FCPATH . 'novos_emails.csv', $txt.PHP_EOL , FILE_APPEND );
				
				echo $txt . "\n\r";
				
			}
			
			$i++;
			
			if ( $i == $limite ) break;
			
		}
		
		echo 'total após validação: ' . count( $csv );
		
		echo '<pre>' . print_r( $csv, TRUE ) . '</pre>';
		
	}
	
	// função para conteúdo em branco
	public function bc(){
		
		$this->component_function = __FUNCTION__;
		$this->component_function_action = 'null';
		
		$params = array(
			
			'blank_content' => TRUE,
			
		);
		
		$this->_page( $params );
		
	}
	
	// função para conteúdo do tipo html
	public function hc(){
		
		$this->component_function = __FUNCTION__;
		$this->component_function_action = 'null';

		$data[ 'params' ] = $this->mcm->filtered_system_params;

		$params = array(

			'html_content' => TRUE,
			'component_view_folder' => 'main',
			'function' => 'html_content',
			'action' => 'html_content',
			'layout' => 'default',
			'view' => 'html_content',
			'data' => $data,

		);

		$this->_page( $params );

	}

	// função para conteúdo do tipo html
	public function temp(){
		
		$this->load->library( 'form_validation' );
		
		$this->load->model( 'common/main_common_model', 'mcm' );
		
		$this->load->model( 'common/submit_forms_common_model', 'sfcm' );
		
		$find = array(
			
			'  ',
			'  ',
			'essencia',
			'narguile',
			'plastico',
			'acessorio',
			'macarico',
			'carvao',
			'pinca',
			'medio',
			'saida',
			'numero',
			' mao',
			' mao ',
			'coracao',
			'pacoq',
			' pe ',
			'prestigio',
			'cacau',
			'hortela',
			'limao',
			'caju',
			'guarana antartica',
			'citrica',
			'gas ',
			'partagás',
			'longás',
			'recarregavel',
			'portatil',
			'fogao',
			'melao',
			'pessego',
			'guarana',
			'parmesao',
			'sensacoes',
			'sensacao',
			'requeijao',
			
		);
		
		$replace = array(
			
			' ',
			' ',
			'essência',
			'narguilé',
			'plástico',
			'acessório',
			'maçarico',
			'carvão',
			'pinça',
			'médio',
			'saída',
			'número',
			' mão',
			' mão ',
			'coração',
			'paçoq',
			' pé ',
			'prestígio',
			'cacáu',
			'hortelã',
			'limão',
			'cajú',
			'guaraná antarctica',
			'cítrica',
			'gás ',
			'partagas',
			'longas',
			'recarregável',
			'portátil',
			'fogão',
			'melão',
			'pêssego',
			'guaraná',
			'parmesão',
			'sensações',
			'sensação',
			'requeijão',
			
		);
		
		$produtos = array();
		
		$produtos_novos = 0;
		$produtos_atualizados = 0;
		$produtos_sem_fotos = 0;
		$produtos_com_fotos = 0;
		
		$csv = array_map('str_getcsv', file( APPPATH . 'controllers' . DS . 'produtos.csv'));
		
// 		echo '<h1>$csv</h1><pre>' . print_r( $csv, TRUE ) . '</pre><br/>'; 
		
		foreach( $csv as $k => & $row ) {
			
			$produtos[ $row[ 0 ] ] = array();
			
			$produto = & $produtos[ $row[ 0 ] ];
			
			$produto[ 'sku' ] = $row[ 0 ];
			$produto[ 'brand' ] = $row[ 3 ];
			$produto[ 'unit' ] = $this->form_validation->normalizar_nome_ptbr( $row[ 4 ] );
			$produto[ 'bar_code' ] = $row[ 34 ];
			
			$produto[ 'title' ] = strtolower( trim( $row[ 1 ] ) );
			$produto[ 'title' ] = str_replace( $find, $replace, $produto[ 'title' ] );
			$produto[ 'title' ] = $this->form_validation->normalizar_nome_ptbr( $produto[ 'title' ] );
			
			$produto[ 'price' ] = str_replace( array( '.', ',' ), array( '', '.' ), trim( $row[ 11 ] ) );
			$produto[ 'status' ] = "168";
			
			if ( check_var( $produto[ 'brand' ] ) ) {
				
				$filters = array(
					
					array(
						
						'alias' => 'name',
						'comp_op' => '=',
						'value' => $produto[ 'brand' ],
						
					)
					
				);
				
				$search_config = array(
					
					'plugins' => 'sf_us_search',
					'allow_empty_terms' => TRUE,
					'terms' => $produto[ 'brand' ],
					'ignore_terms' => FALSE,
					'ipp' => 1,
					'cp' => NULL,
					'plugins_params' => array(
						
						'sf_us_search' => array(
							
							'sf_id' => 15,
							'filters' => $filters,
							
						),
						
					),
					
				);
				
// 				echo '<pre>' . print_r( $search_config, TRUE ) . '</pre><br/>'; 
				
				$this->load->library( 'search' );
				$this->search->reset_config();
				$this->search->config( $search_config );
				
				$_users_submits = $this->search->get_full_results( 'sf_us_search', TRUE );
				
				if ( ! check_var( $_users_submits ) AND $produto[ 'brand' ] ) {
					
					$ud_d_db_insert_data = array(
						
						'submit_form_id' => 15,
						
						'data' => array(
							
							'name' => $produto[ 'brand' ],
							
						),
						
						'params' => array(
							
							'created_by_user_id' => 1,
							
						),
						
					);
					
					$submit_datetime = gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
					$submit_datetime = ov_strftime( '%Y-%m-%d %T', $submit_datetime );
					
					$ud_d_db_insert_data[ 'submit_datetime' ] = $ud_d_db_insert_data[ 'mod_datetime' ] = $submit_datetime;
					
					$ud_d_db_insert_data[ 'data' ] = json_encode( $ud_d_db_insert_data[ 'data' ] );
					$ud_d_db_insert_data[ 'params' ] = json_encode( $ud_d_db_insert_data[ 'params' ] );
					
// 					echo '<pre>' . print_r( $ud_d_db_insert_data, TRUE ) . '</pre><br/>'; 
					
					if ( $ud_data_id = $this->sfcm->insert_user_submit( $ud_d_db_insert_data ) ){
						
						$produto[ 'brand' ] = $ud_data_id;
						
// 						echo '<pre>' . print_r( $ud_data_id, TRUE ) . '</pre><br/>'; 
						
					}
					
				}
				else {
					
					$produto[ 'brand' ] = $_users_submits[ 0 ][ 'id' ];
					
// 					echo '<pre>' . print_r( $_users_submits[ 0 ], TRUE ) . '</pre><br/>'; 
					
				}
				
			}
			
			
			
			$_filters = array(
				
				array(
					
					'alias' => 'sku',
					'comp_op' => '=',
					'value' => $produto[ 'sku' ],
					
				)
				
			);
			
			$_search_config = array(
				
				'plugins' => 'sf_us_search',
				'allow_empty_terms' => TRUE,
				'ignore_terms' => TRUE,
				'ipp' => 1,
				'cp' => NULL,
				'plugins_params' => array(
					
					'sf_us_search' => array(
						
						'sf_id' => 3,
						'filters' => $_filters,
						
					),
					
				),
				
			);
			
// 				echo '<pre>' . print_r( $_search_config, TRUE ) . '</pre><br/>'; 
			
			$this->load->library( 'search' );
			$this->search->reset_config();
			$this->search->config( $_search_config );
			
			$_users_submits = $this->search->get_full_results( 'sf_us_search', TRUE );
			
			$insert = FALSE;
			
			$ud_d_db_data = array(
				
				'submit_form_id' => 3,
				
				'data' => $produto,
				
				'params' => array(
					
					'created_by_user_id' => 1,
					
				),
				
			);
			
			$submit_datetime = gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
			$submit_datetime = ov_strftime( '%Y-%m-%d %T', $submit_datetime );
			
			$ud_d_db_data[ 'submit_datetime' ] = $ud_d_db_data[ 'mod_datetime' ] = $submit_datetime;
			
// 				echo print_r( $produto[ 'sku' ], TRUE ) . '<br/>'; 
			
			$dir = MEDIA_PATH . 'produtos' . DS . $produto[ 'sku' ] . DS;
			
			if ( file_exists( $dir ) ) {
				
				$i = 0;
				
				foreach( glob( $dir . '*.jpg' ) as $file ) {
					
					$i++;
					
					$file = pathinfo( $file );
					
					$old_name = $file[ 'basename' ];
					$new_name = url_title( $produto[ 'sku' ] . '-' . $i . '-' . $produto[ 'title' ] ) . '.' . $file[ 'extension' ];
					
					if ( ! file_exists( $file[ 'dirname' ] . '/' . $new_name ) ) {
						
						rename( $file[ 'dirname' ] . '/' . $old_name, $file[ 'dirname' ] . '/' . $new_name );
						
					}
					
					$file = $file[ 'basename' ];
					
					$ud_d_db_data[ 'data' ][ 'image_' . $i ] = MEDIA_DIR_NAME . '/produtos/' . $produto[ 'sku' ] . '/' . $new_name;
					
				}
				
				if ( $i == 0 ) {
					
					$ud_d_db_data[ 'data' ][ 'status' ] = "169";
					
					$produtos_sem_fotos++;
					
				}
				else {
					
					$produtos_com_fotos++;
					
				}
				
			}
			else {
				
				$ud_d_db_data[ 'data' ][ 'status' ] = "169";
				
				$produtos_sem_fotos++;
				
			}
			
			
			if ( check_var( $_users_submits ) ) {
				
				$ud_d_db_data[ 'id' ] = $_users_submits[ 0 ][ 'id' ];
				$ud_d_db_data[ 'data' ] = array_merge_recursive_distinct( $_users_submits[ 0 ][ 'data' ], $ud_d_db_data[ 'data' ] );
				$ud_d_db_data[ 'params' ] = array_merge_recursive_distinct( $_users_submits[ 0 ][ 'params' ], $ud_d_db_data[ 'params' ] );
				$ud_d_db_data[ 'submit_datetime' ] = $_users_submits[ 0 ][ 'submit_datetime' ];
				$ud_d_db_data[ 'params' ][ 'modified_by_user_id' ] = 1;
				
// 				echo '<h1>' . ( $insert ? 'Inserir:' : 'Atualizar:' ) . '</h1><pre>' . print_r( $ud_d_db_data[ 'data' ], TRUE ) . '</pre><br/>'; 
				
// 				echo '<pre>' . print_r( $_users_submits[ 0 ][ 'data' ], TRUE ) . '</pre><br/>';
				
			}
			else {
				
				$insert = TRUE;
				
			}
			
			$ud_d_db_data[ 'data' ] = json_encode( $ud_d_db_data[ 'data' ] );
			$ud_d_db_data[ 'params' ] = json_encode( $ud_d_db_data[ 'params' ] );
			
			if ( $insert ) {
				
				$produtos_novos++;
				
				$this->sfcm->insert_user_submit( $ud_d_db_data );
				
			}
			else {
				
				$produtos_atualizados++;
				
				$this->sfcm->update_user_submit( $ud_d_db_data, array( 'id' => $ud_d_db_data[ 'id' ] ) );
				
			}
			
		}
		
		echo '<h1>novos</h1><pre>' . print_r( $produtos_novos, TRUE ) . '</pre><br/>'; 
		
		echo '<h1>atualizados</h1><pre>' . print_r( $produtos_atualizados, TRUE ) . '</pre><br/>'; 
		
		echo '<h1>produtos com fotos</h1><pre>' . print_r( $produtos_com_fotos, TRUE ) . '</pre><br/>'; 
		
		echo '<h1>produtos sem fotos</h1><pre>' . print_r( $produtos_sem_fotos, TRUE ) . '</pre><br/>'; 
		
	}

	public function plg(){

		$get = $this->input->get();
		$post = $this->input->post();

		// -------------------------------------------------
		// Parsing vars ------------------------------------

		$f_params = $this->uri->ruri_to_assoc();

		$plugin_name =							isset( $f_params['pn'] ) ? $f_params['pn'] : NULL; // plugin name
		$plugin_type =							isset( $f_params['pt'] ) ? $f_params['pt'] : NULL; // plugin type

		// Parsing vars ------------------------------------
		// -------------------------------------------------

		$params[ 'get' ] = $get;
		$params[ 'post' ] = $post;

		if ( $plugin_name ){

			// load params
			$lp = array(

				'name' => $plugin_name,
				'params' => $params,

			);

			$this->plugins->load( $lp );
		}
		else if ( $plugin_type ){

			// load params
			$lp = array(

				'type' => $plugin_type,
				'params' => $params,

			);

			$this->plugins->load( $lp );

		}

	}
	
	// função para montar páginas
	protected function _page( $f_params = NULL ){
		
		// -------------------------------------------------
		// Parsing vars ------------------------------------
		
		// atribuindo valores às variávies
		$component_view_folder =				isset( $f_params[ 'component_view_folder' ] ) ? $f_params[ 'component_view_folder' ] : 'main';
		$function =								isset( $f_params[ 'function' ] ) ? $f_params[ 'function' ] : 'errors';
		$action =								isset( $f_params[ 'action' ] ) ? $f_params[ 'action' ] : 'error_404';
		$layout =								isset( $f_params[ 'layout' ] ) ? $f_params[ 'layout' ] : 'default';
		$view =									isset( $f_params[ 'view' ] ) ? $f_params[ 'view' ] : '404';
		$data =									isset( $f_params[ 'data' ] ) ? $f_params[ 'data' ] : array();
		$html =									isset( $f_params[ 'html' ] ) ? $f_params[ 'html' ] : FALSE;
		$load_index =							isset( $f_params[ 'load_index' ] ) ? $f_params[ 'load_index' ] : TRUE;
		$blank_content =						isset( $f_params[ 'blank_content' ] ) ? $f_params[ 'blank_content' ] : FALSE;
		$html_content =							isset( $f_params[ 'html_content' ] ) ? $f_params[ 'html_content' ] : FALSE;
		
		// Parsing vars ------------------------------------
		// -------------------------------------------------
		
		if ( ( $component_view_folder AND $function AND $action AND $view ) OR $blank_content ){
			
			$data[ 'component_name' ] = ( isset( $this->current_component ) AND $this->current_component ) ? $this->current_component[ 'unique_name' ] : 'main';
			$data[ 'current_component' ] = ( isset( $this->current_component ) AND $this->current_component ) ? $this->current_component : array();
			$data[ 'component_function' ] = ( isset( $this->component_function ) AND $this->component_function ) ? $this->component_function : '';
			$data[ 'component_function_action' ] = ( isset( $this->component_function_action ) AND $this->component_function_action ) ? $this->component_function_action : '';;
			
			/*-------------------------------------------------------
			 * Parâmetros do item de menu atual
			 */
			
			if ( ! check_var( $data[ 'params' ] ) AND $this->mcm->current_menu_item ){
				
				$data[ 'params' ] = get_params( $this->mcm->current_menu_item[ 'params' ] );
				
			}
			
			/*
			 * html_data var list:
			 *
			 * 		$html_data																				array
			 * 		$html_data[ 'head' ]																	array
			 * 		$html_data[ 'head' ][ 'title' ]															string
			 * 		$html_data[ 'head' ][ 'favicon' ]														array
			 * 		$html_data[ 'head' ][ 'favicon' ][ '1' ] ... [ 'n' ]									string
			 * 		$html_data[ 'head' ][ 'favicon' ][ 'html' ]												string
			 *
			 * 		$html_data[ 'head' ][ 'meta' ]															array
			 * 		$html_data[ 'head' ][ 'meta' ][ 'charset' ]												string
			 * 		$html_data[ 'head' ][ 'meta' ][ 'cache-control' ]										string
			 * 		$html_data[ 'head' ][ 'meta' ][ 'X-UA-Compatible' ]										string
			 * 		$html_data[ 'head' ][ 'meta' ][ 'content-type' ]										string
			 * 		$html_data[ 'head' ][ 'meta' ][ 'copyright' ]											string
			 * 		$html_data[ 'head' ][ 'meta' ][ 'author' ]												string
			 * 		$html_data[ 'head' ][ 'meta' ][ 'keywords' ]											string
			 * 		$html_data[ 'head' ][ 'meta' ][ 'viewport' ]											string
			 * 		$html_data[ 'head' ][ 'meta' ][ 'HandheldFriendly' ]									string // Similar to the MobileOptimized tag, this tells devices such as Blackberrys that the site is optimised for mobile browsing.
			 * 		$html_data[ 'head' ][ 'meta' ][ 'MobileOptimized' ]										string // This tells the mobile browser which mobile width the site is best optimised for. It's an extra failsafe for mobile browser rendering.
			 * 		$html_data[ 'head' ][ 'meta' ][ 'google-site-verification' ]							string
			 * 		$html_data[ 'head' ][ 'meta' ][ 'custom' ]												string
			 * 		$html_data[ 'head' ][ 'meta' ][ 'content-language' ]									string
			 * 		$html_data[ 'head' ][ 'meta' ][ 'html' ]												string
			 *
			 * 		$html_data[ 'head' ][ 'stylesheets' ]													array
			 * 		$html_data[ 'head' ][ 'stylesheets' ][ '1' ] ... [ 'n' ]								string
			 * 		$html_data[ 'head' ][ 'stylesheets' ][ 'html' ]											string
			 *
			 * 		$html_data[ 'head' ][ 'scripts' ]														array
			 * 		$html_data[ 'head' ][ 'scripts' ][ '1' ] ... [ 'n' ]									string
			 * 		$html_data[ 'head' ][ 'scripts' ][ 'html' ]												string
			 *
			 * 		$html_data[ 'head' ][ 'scripts_declaration' ]											array
			 * 		$html_data[ 'head' ][ 'scripts_declaration' ][ '1' ] ... [ 'n' ]						string
			 * 		$html_data[ 'head' ][ 'scripts_declaration' ][ 'custom' ]								string
			 * 		$html_data[ 'head' ][ 'scripts_declaration' ][ 'html' ]									string
			 *
			 *
			 *
			 *
			 * 		$html_data[ 'content' ]																	array
			 * 		$html_data[ 'content' ][ 'title' ]														string
			 *
			 */

			// default meta tags
			$this->voutput->append_head_meta( 'base', '<base href="' . BASE_URL . '/" ><!--[if lte IE 6]></base><![endif]-->', NULL, NULL );
			$this->voutput->append_head_meta( 'charset', 'charset="utf-8"' );
			$this->voutput->append_head_meta( 'content-type', 'http-equiv="content-type" content="text/html; charset=UTF-8"' );
			$this->voutput->append_head_meta( 'content-language', 'name="content-language" content="' . @$this->mcm->filtered_system_params[ $this->mcm->environment . '_language' ] . '"' );
			$this->voutput->append_head_meta( 'cache-control', 'http-equiv="cache-control" content="public"' );
			$this->voutput->append_head_meta( 'X-UA-Compatible', '<!-- Always force latest IE rendering engine (even in intranet) & Chrome Frame. Remove this if you use the .htaccess -->', NULL, NULL );
			$this->voutput->append_head_meta( 'X-UA-Compatible', 'http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"' );
			$this->voutput->append_head_meta( 'copyright', 'name="copyright" content="' . @$this->mcm->filtered_system_params[ 'site_copyright' ] . '"' );
			$this->voutput->append_head_meta( 'author', 'name="author" content="' . @$this->mcm->filtered_system_params[ 'author_name' ] . '"' );
			
			$this->voutput->append_head_meta( 'site_name', 'itemprop="name" content="' . @$this->mcm->filtered_system_params[ 'site_name' ] . '"' );
			$this->voutput->append_head_meta( 'site_url', 'itemprop="url" content="' . BASE_URL . '"' );
			
			$this->voutput->append_head_meta( 'viewport', 'name="viewport" content="width=device-width, initial-scale=1.0"' );
			$this->voutput->append_head_meta( 'HandheldFriendly', 'name="HandheldFriendly" content="True"' );
			$this->voutput->append_head_meta( 'MobileOptimized', 'name="MobileOptimized" content="320"' );
			$this->voutput->append_head_meta( 'keywords', 'name="keywords" content="' . ( @$data[ 'params' ][ 'meta_keywords' ] ? $data[ 'params' ][ 'meta_keywords' ] : ( @$this->mcm->filtered_system_params[ 'meta_keywords' ] ? $this->mcm->filtered_system_params[ 'meta_keywords' ] : '' ) ) . '"' );
			$this->voutput->append_head_meta( 'description', 'name="description" content="' . ( @$data[ 'params' ][ 'meta_description' ] ? $data[ 'params' ][ 'meta_description' ] : ( @$this->mcm->filtered_system_params[ 'meta_description' ] ? $this->mcm->filtered_system_params[ 'meta_description' ] : '' ) ) . '"' );
			$this->voutput->append_head_meta( 'google-site-verification', 'name="google-site-verification" content="' . @$this->mcm->filtered_system_params[ 'google_site_verification' ] . '"' );
			$this->voutput->append_head_meta( 'custom', @$this->mcm->filtered_system_params[ 'meta_custom' ], NULL, NULL );

			// favicons
			$this->voutput->append_favicon( 'apple-touch-icon-152x152.png', 'apple-touch-icon', '152x152', 'iPad iOS7+ com Retina Display' );
			$this->voutput->append_favicon( 'apple-touch-icon-144x144.png', 'apple-touch-icon', '144x144', 'iPad iOS7- com Retina Display' );
			$this->voutput->append_favicon( 'apple-touch-icon-120x120.png', 'apple-touch-icon', '120z120', 'iPhone iOS7+ com Retina Display' );
			$this->voutput->append_favicon( 'apple-touch-icon-76x76.png', 'apple-touch-icon', '76x76', 'iPad iOS7+ sem retina display e iPad Mini' );
			$this->voutput->append_favicon( 'apple-touch-icon-72x72.png', 'apple-touch-icon', '72x72', 'iPad iOS7- sem retina display' );
			$this->voutput->append_favicon( 'apple-touch-icon.png', 'apple-touch-icon', NULL, 'iPhone iOS7-, iPod Touch e Android 2.2+' );
			$this->voutput->append_favicon( 'favicon.png', NULL, NULL, 'Default favicon' );
			$this->voutput->append_favicon( 'favicon-16.png', 'icon', '16x16', 'Default favicon 16x16' );
			$this->voutput->append_favicon( 'favicon-24.png', 'icon', '24x24', 'Default favicon 24x24' );
			$this->voutput->append_favicon( 'favicon-32.png', 'icon', '32x32', 'Default favicon 32x32' );
			$this->voutput->append_favicon( 'favicon-48.png', 'icon', '48x48', 'Default favicon 48x48' );
			$this->voutput->append_favicon( 'favicon-64.png', 'icon', '64x64', 'Default favicon 64x64' );
			$this->voutput->append_favicon( 'favicon-128.png', 'icon', '128x128', 'Default favicon 128x128' );

			// custom scripts
			$this->voutput->append_head_script_declaration( 'custom', @$this->mcm->filtered_system_params[ 'meta_scripts_declaration_custom' ] );

			/*
			 * -------------------------------------------------------------------------------------------------
			 * Título da página ( head )
			 * Se já estiver definido o título da página, usa-se este, caso contrário se estiver definido um item de menu
			 * para a página, verifica se o parâmetro de título personalizado de página está sendo utilizado, se sim, utiliza este,
			 * caso contrário, do mesmo item de menu, utiliza-se o seu título, e por último, se não estiver sendo utilizado item de menu,
			 * utiliza-se o nome do site.
			 *
			 * Resumo da prioridade:
			 * $this->mcm->html_data[ 'head' ][ 'title' ] >> $data[ 'params' ][ 'custom_page_title' ] >> $this->mcm->current_menu_item[ 'title' ] >> $this->mcm->filtered_system_params[ 'site_name' ]
			 */

			$head_title_prefix = @$this->mcm->filtered_system_params[ 'seo_title_prefix' ];
			$head_title_sufix = @$this->mcm->filtered_system_params[ 'seo_title_suffix' ];
			$head_title_separator = @$this->mcm->filtered_system_params[ 'seo_title_separator' ];

			$head_title = $this->voutput->get_head_title() ? $this->voutput->get_head_title() : ( @$data[ 'params' ][ 'custom_page_title' ] ? @$data[ 'params' ][ 'custom_page_title' ] : ( @$this->mcm->current_menu_item[ 'title' ] ? @$this->mcm->current_menu_item[ 'title' ] : $this->mcm->filtered_system_params[ 'site_name' ] ) );
			$head_title = $head_title_prefix . ( $head_title_prefix ? $head_title_separator : '' ) . $head_title . ( $head_title_sufix ? $head_title_separator : '' ) . $head_title_sufix;

			$this->voutput->set_head_title( $head_title );

			/*
			 * -------------------------------------------------------------------------------------------------
			 */



			if ( @$data[ 'params' ][ 'show_page_content_title' ] AND ! @$this->mcm->html_data[ 'content' ][ 'title' ] ){

				if ( @$data[ 'params' ][ 'custom_page_content_title' ] ){

					$this->mcm->html_data[ 'content' ][ 'title' ] =											$data[ 'params' ][ 'custom_page_content_title' ];

				}
				else{

					$this->mcm->html_data[ 'content' ][ 'title' ] =											$this->mcm->current_menu_item[ 'title' ];

				}

			}

			/*
			 * Parâmetros do item de menu atual
			 *-------------------------------------------------------*/

			$data[ 'user_data' ] = $this->session->envdata( 'user' );
			$data[ 'component_view_folder' ] = $component_view_folder;
			$data[ 'view' ] = $view;
			$data[ 'function' ] = $function;
			$data[ 'msg' ] = loadMsg();
			
			$this->modc->get_modules();
			
			// se a saida for ajax, escreve apenas a saida das mensagens, ajustar isto no futuro
			if ( $this->input->get('ajax') ){

				echo $data[ 'msg' ];

			}

			if ( $blank_content ){

				$data[ 'content' ] = '';

			}
			else{
				
				$this->load->helper( 'file' );
				
				$theme_views_url = call_user_func( $this->mcm->environment . '_theme_components_views_url' ) . '/' . $component_view_folder . '/' . $function . '/' . $action . '/' . $layout;
				$theme_load_views_path = call_user_func( $this->mcm->environment . '_theme_components_views_path' ) . $component_view_folder . DS . $function . DS . $action . DS . $layout . DS;
				$theme_views_path = THEMES_PATH . $theme_load_views_path;
				
				$default_component_views_styles_path = SITE_COMPONENTS_VIEWS_STYLES_PATH . $component_view_folder . DS . $function . DS . $action . DS . $layout . DS;
				$default_component_views_styles_url = SITE_COMPONENTS_VIEWS_STYLES_URL . '/' . $component_view_folder . '/' . $function . '/' . $action . '/' . $layout;
				
				$content = $this->mcm->load_view( $this->mcm->environment, COMPONENTS_DIR_NAME . DS . $component_view_folder . DS . $function . DS . $action . DS . $layout . DS, $view, $data, TRUE );
				
				$this->voutput->append_content( $content );
				
				// verificando se o tema atual possui a folha de estilo da view
				if ( file_exists( $theme_views_path . $view . '.css' ) OR file_exists( $theme_views_path . $view . '.css.php' ) ){

					if ( file_exists( $theme_views_path . $view . '.css' ) ){

						$this->voutput->append_head_stylesheet( ( $theme_views_url . '/' . $view . '.css' ), $theme_views_url . '/' . $view . '.css' );

					}
					else if ( file_exists( $theme_views_path . $view . '.css.php' ) ){

						$this->voutput->append_head_stylesheet( ( $theme_views_url . '/' . $view . '.css.php' ), $theme_views_url . '/' . $view . '.css.php' );

					}

				}
				// verificando se a folha de estilo existe no diretório de views padrão
				else if ( file_exists( $default_component_views_styles_path . $view . '.css' ) OR file_exists( $default_component_views_styles_path . $view . '.css.php' ) ){

					if ( file_exists( $default_component_views_styles_path . $view . '.css' ) ){

						$this->voutput->append_head_stylesheet( ( $default_component_views_styles_url . '/' . $view . '.css'), $default_component_views_styles_url . '/' . $view . '.css' );

					}
					else if ( file_exists( $default_component_views_styles_path . $view . '.css.php' ) ){

						$this->voutput->append_head_stylesheet( ( $default_component_views_styles_url . '/' . $view . '.css.php'), $default_component_views_styles_url . '/' . $view . '.css.php' );

					}

				}

			}


			/*
			 * -------------------------------------------------------------------------------------------------
			 * Loading content plugins
			 */
			
			$this->plugins->load( NULL, 'content' );
			
			/*
			 * -------------------------------------------------------------------------------------------------
			 */
			
			if ( $load_index ){
				
				$this->load->view( get_constant_name( $this->mcm->environment . '_DIR_NAME' ) . '/' . call_user_func( $this->mcm->environment . '_theme' ) . '/' . 'index' , $data, $html );
				
			}
			
		}
		else {
			
			redirect();
			
		}
		
	}

	public function update_urls_cache(){

		$this->load->model( 'common/urls_common_model' );

		$urls = $this->urls_common_model->update_urls_cache();

		if ( $urls ){

			redirect();

		}
		else{

			msg( lang( 'default_controller_not_informed' ), 'error' );
			$this->bc();

			//$error =& load_class( 'Exceptions', 'core' );
			//echo $error->show_error( lang( 'default_controller_not_informed' ), 'oiiiiii', 'error_general', 500 );

			//$this->load->view( 'error_404' );
		}

	}

}
