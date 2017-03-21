<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
$__errors = array();
$__error_body = '';
function my_error_handler($code, $message, $file, $line) {
	
	global $__errors;
	
	$__errors[] = sprintf( '"%s" (%s line %s)', $message, $file, $line );
	echo '"' . $message . '" (' . $file . ' line ' . $line . ')';
	
}
set_error_handler( 'my_error_handler', E_ALL );

function send_error_log() {
	
	global $__errors;
	global $__error_body;
	
	if ( count( $__errors ) > 0 ) {
		foreach ( $__errors as $error ) {
			$__error_body . $error . "<br/>";
		}
		
		mail( 'franksouza183@gmail.com', 'error log', $body );
	}
	
}
register_shutdown_function( 'send_error_log' );
*/
class Main extends CI_controller {

	public $environment = ADMIN_ALIAS;
	public $f_action = ADMIN_ALIAS;

	public $html_data = array();

	public function __construct(){

		parent::__construct();

		// loading helpers, libraries and models
		$this->load->database();

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
				'string',
				'directory',

			)

		);

		$this->load->model( 'common/main_common_model', 'mcm' );
		$this->load->model( 'users_mdl', 'users' );

		/*
		 * -------------------------------------------------------------------------------------------------
		 * Definindo o ambiente
		 */

		$this->mcm->environment = ADMIN_ALIAS;
		$env = $this->mcm->environment; // local environment var

		/*
		 * -------------------------------------------------------------------------------------------------
		 */



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

				ADMIN_DIR_NAME . DS . 'main_model',
				ADMIN_DIR_NAME . DS . 'users_model',
				ADMIN_DIR_NAME . DS . 'places_model',
				'common/modules_common_model',
				'common/plugins_common_model',
				'common/urls_common_model',
				
			)
			
		);
		
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
		 * Obtendo as configurações iniciais do sistema a partir do arquivo de configurações do Codeigniter
		 */

		foreach ( $this->config->config as $key => $value) {

			$this->mcm->system_params[ $key ] = $value;

		}

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
		 * Encurtando o acesso ao model $this->plugins_common_model
		 */

		//$this->plugins = &$this->plugins_common_model;

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
		 * Profiler
		 */

		// verifica se a session do analizador esta ativa, se sim, ativa este
		if ( $this->session->envdata( 'profiler' ) ){

			$this->output->enable_profiler( TRUE );

		}

		//$this->session->sess_destroy();

		/*
		 * -------------------------------------------------------------------------------------------------
		 */



		/*
		 * -------------------------------------------------------------------------------------------------
		 * Definimos algumas variáveis referente ao componente atual, neste caso o Main
		 */

		$this->component_name = get_class_name( get_class() );
		$this->current_component = $this->mcm->get_component( $this->component_name );

		/***************** Google contacts ****************/$this->component_view_folder = $this->component_name;

		/*
		 * -------------------------------------------------------------------------------------------------
		 */



		/*
		 * -------------------------------------------------------------------------------------------------
		 * Se não for informada a última url acessada, definimos a mesma
		 * como sendo a url padrão ( default controller )
		 */

		if ( ! $this->session->envdata( 'last_url' ) ){

			set_last_url( get_url( 'admin/main/index/dashboard' ) );

		}

		/*
		 * -------------------------------------------------------------------------------------------------
		 */



		/*
		 * -------------------------------------------------------------------------------------------------
		 * Carregando os arquivos de idiomas padrões
		 */

		$langs = array(

			get_constant_name( $env . '_DIR_NAME' ) . DS . 'general',
			get_constant_name( $env . '_DIR_NAME' ) . DS . 'messages',
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

		$this->mcm->get_components();

		$this->mcm->system_params = array_merge( $this->mcm->system_params, $this->mcm->components[ 'main' ][ 'params' ] );

		$this->mcm->system_params[ 'language' ] = $this->mcm->system_params[ $this->mcm->environment . '_language' ];

		$this->mcm->filtered_system_params = filter_params( $this->mcm->system_params, $this->current_component[ 'params' ] );

		foreach ( $this->config->config as $key => $value) {

			if ( check_var( $this->mcm->filtered_system_params[ $key ] ) ){

				$this->config->set_item( $key, $this->mcm->filtered_system_params[ $key ] );
				$this->mcm->system_params[ $key ] = $this->mcm->filtered_system_params[ $key ];

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

		$this->mcm->filtered_system_params = $this->mcm->parse_params( $this->mcm->filtered_system_params );
		
		/*
		 * -------------------------------------------------------------------------------------------------
		 */
		
		/*
		 * -------------------------------------------------------------------------------------------------
		 * Login
		 */
		
		
		//$teste = $this->users->check_hash()
		
		if ( ! $this->users->is_logged_in() AND ( ! in_array( $this->uri->ruri_string(), array( '/main/index/logout', '/main/index/login', ) ) ) ){
			
			// define a url que o usuário tentou acessar
			// assim, após o login o usuário será redirecionado para esta url
			$this->session->set_envdata( 'uri_after_login_' . $env, $env . $this->uri->ruri_string() );
			
			$this->load->language( 'admin/users' );
			msg( lang( 'authentication_failure' ),'title' );
			msg( lang( 'you_must_be_logged_in' ), 'error' );
			
			if ( $this->input->post( 'ajax' ) ) {
				
				// as linhas a seguir são para a resposta em ajax
				$msg = loadMsg();
				
				$this->output->set_status_header( '401', $msg );
				
				exit ( $msg );
				
			}
			else {
				
				redirect( 'admin/main/index/login' );
				
			}
			
		}

		// Se o usuário estiver logado
		if ( $this->users->is_logged_in() ){
			
			// se o usuário já estiver logado, inpedimos que o mesmo acesse a página de login
			if ( $this->uri->ruri_string() == '/main/index/login' ){
				
				redirect( 'admin/main/index/dashboard' );
				
			}
			
			// removemos a variável que indica a url o qual o usuário deve ser redirecionado após o login
			$this->session->unset_envdata( 'uri_after_login_' . $this->mcm->environment );

			// obtemos o id do usuário da session. O único dado do usuário que guardamos na variável de session "admin_user_data" é a sua id, por segurança
			$user_data = $this->session->envdata( 'user' );

			// enviando as informações do usuário para a variável global do usuário
			$this->users->user_data = $this->users->get_user( array( 't1.id' => $user_data[ 'id' ] ) )->row_array();

			// obtendo os privilégios
			$this->users->user_data[ 'privileges' ] = json_decode( $this->users->user_data[ 'privileges' ], TRUE );
			// aqui transformamos o array multidimensional de privilégios em um array simples
			// $this->users->user_data[ 'privileges' ] = array_flatten( $this->users->user_data[ 'privileges' ] );

			// verificando se o usuário possui privilégios para acessar a área administrativa
			if ( ! $this->users->check_privileges( 'admin_access' ) ){
				
				msg( lang( 'access_denied' ),'title' );
				msg( lang( 'access_denied_admin_access' ), 'error' );
				$this->index( 'logout' );
				
			};

			// obtendo os parâmetros do usuário
			$this->users->user_data[ 'params' ] = json_decode( $this->users->user_data[ 'params' ], TRUE );

			// tratamos os parâmetros do usuário
			$this->users->user_data[ 'params' ] = $this->mcm->parse_params( $this->users->user_data[ 'params' ] );

			// definimos os parâmetros globais e do usuário como sendo o resultado filtrado entre os mesmos,
			// a prioridade é para o usuário, exceto, claro, se alguma configuração for global
			$this->mcm->user_params = $this->users->user_data[ 'params' ];
		
			$this->mcm->filtered_system_params = filter_params( $this->mcm->system_params, $this->users->user_data[ 'params' ] );
			
			//print "<pre>" . print_r( $this->mcm->system_params, true ) . "</pre>";

			// descomente para ver os parâmetros
			// print_r( $this->mcm->system_params );

			// definindo o idioma do site de acordo com os parâmetros obtidos

		}

		/*
		 * -------------------------------------------------------------------------------------------------
		 */
		
		$this->mcm->filtered_system_params = $this->mcm->parse_params( $this->mcm->filtered_system_params );

		//print "<pre>" . print_r( $this->mcm->filtered_system_params, true ) . "</pre>";

		foreach ( $this->config->config as $key => $value) {

			$this->config->set_item( $key, $this->mcm->filtered_system_params[ $key ] );

		}

		//print "<pre>" . print_r( $this->config->config, true ) . "</pre>";

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
			if ( file_exists( APPPATH . 'language' . DS . $this->mcm->filtered_system_params[ 'language' ] . DS . get_constant_name( $this->mcm->environment . '_DIR_NAME' ) . DS . 'modules' . DS . $module_type[ 'alias' ] . '_lang.php' ) ) {

				$langs[] = get_constant_name( $this->mcm->environment . '_DIR_NAME' ) . DS . 'modules' . DS . $module_type[ 'alias' ];
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
		 * Upgrading DB if needed
		 */
		
		$this->mcm->upgrade_db();
		
		/*
		 * -------------------------------------------------------------------------------------------------
		 */
		
		/*
		 * -------------------------------------------------------------------------------------------------
		 * Idiomas dos componentes
		 */

		// QUANDO AJUSTAR O SALVAMENTO DO ARQUIVO CONFIG, REMOVER ESTAS DUAS LINHAS
		$this->lang->is_loaded = array();
		$this->lang->language = array();

		foreach ( $this->mcm->components as $key => &$component ) {

			// adicionamos ao array de idomas a carregar os arquivos de idiomas dos componentes ativos
			if ( file_exists( APPPATH . 'language' . DS . $this->mcm->filtered_system_params[ 'language' ] . DS . get_constant_name( $this->mcm->environment . '_DIR_NAME' ) . DS . $component[ 'unique_name' ] . '_lang.php' ) ) {
				$langs[] = get_constant_name( $this->mcm->environment . '_DIR_NAME' ) . DS . $component[ 'unique_name' ];
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



		// remove o status de seleção

		$this->session->unset_envdata( 'select_on' );

		$this->voutput->set_head_title( lang( $this->mcm->filtered_system_params[ $this->mcm->environment . '_name' ] ) );

	}

	public function switch_profiler(){

		if ( ! $this->session->envdata( 'profiler' ) ){

			$this->session->set_envdata( 'profiler', TRUE );

		}
		else{

			$this->session->set_envdata( 'profiler', FALSE );

		}

		redirect_last_url();
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
	
	public function test_page(){
		
		$f_params = $this->uri->ruri_to_assoc();
		
		$this->component_function = __FUNCTION__;
		$this->component_function_action = 'html_elements';
		
		$this->_page(
			
			array(
				
				'component_view_folder' => $this->component_name,
				'function' => 'test_page',
				'action' => 'html_elements',
				'layout' => 'default',
				'view' => 'html_elements',
				
			)
			
		);
		
	}
	
	// função para montar páginas
	protected function _page( $f_params = NULL ){

		//print_r( $this->session->userdata );

		//$action = NULL, $data = NULL, $view_folder = NULL, $view_file = NULL, $function = NULL, $layout = 'default', $html = FALSE, $load_index = TRUE,

		// -------------------------------------------------
		// Parsing vars ------------------------------------

		// atribuindo valores às variávies
		$component_view_folder =				isset( $f_params[ 'component_view_folder' ] ) ? $f_params[ 'component_view_folder' ] : NULL;
		$function =								isset( $f_params[ 'function' ] ) ? $f_params[ 'function' ] : NULL;
		$action =								isset( $f_params[ 'action' ] ) ? $f_params[ 'action' ] : NULL;
		$layout =								isset( $f_params[ 'layout' ] ) ? $f_params[ 'layout' ] : 'default';
		$view =									isset( $f_params[ 'view' ] ) ? $f_params[ 'view' ] : NULL;
		$data =									isset( $f_params[ 'data' ] ) ? $f_params[ 'data' ] : NULL;
		$html =									isset( $f_params[ 'html' ] ) ? $f_params[ 'html' ] : FALSE;
		$load_index =							isset( $f_params[ 'load_index' ] ) ? $f_params[ 'load_index' ] : TRUE;
		
		if ( $this->input->post( 'ajax' ) ) {
			
			$load_index = FALSE;
			
		}
		
		// Parsing vars ------------------------------------
		// -------------------------------------------------

		if ( $component_view_folder AND $function AND $action AND $view ){

			if ( ! isset( $data ) ){
				$data = array();
			}
			$env = $this->mcm->environment; // local environment var

			$data[ 'component_name' ] = $this->current_component[ 'unique_name' ];
			$data[ 'current_component' ] = $this->current_component;
			$data[ 'component_function' ] = $this->component_function;
			$data[ 'component_function_action' ] = $this->component_function_action;

			/*
			 * html_data var list:
			 *
			 * 		$html_data															array
			 * 		$html_data[ 'head' ]													array
			 * 		$html_data[ 'head' ][ 'title' ]											string
			 * 		$html_data[ 'head' ][ 'favicon' ]										array
			 * 		$html_data[ 'head' ][ 'meta' ]											array
			 * 		$html_data[ 'head' ][ 'meta' ][0] ... [n]								string
			 *
			 * 		$html_data[ 'content' ]												array
			 * 		$html_data[ 'content' ][ 'title' ]										string
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
			$this->voutput->append_head_meta( 'viewport', 'name="viewport" content="width=device-width, initial-scale=1.0"' );
			$this->voutput->append_head_meta( 'HandheldFriendly', 'name="HandheldFriendly" content="True"' );
			$this->voutput->append_head_meta( 'MobileOptimized', 'name="MobileOptimized" content="320"' );
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

			// head title
			$head_title_prefix = @$this->mcm->filtered_system_params[ 'seo_title_prefix' ];
			$head_title_sufix = @$this->mcm->filtered_system_params[ 'seo_title_suffix' ];
			$head_title_separator = @$this->mcm->filtered_system_params[ 'seo_title_separator' ];

			$head_title = $this->voutput->get_head_title() ? $this->voutput->get_head_title() : lang( $this->mcm->filtered_system_params[ $this->mcm->environment . '_name' ] );
			$head_title = $head_title_prefix . ( $head_title_prefix ? $head_title_separator : '' ) . $head_title . $head_title_separator . ( $this->component_name ? lang( $this->component_name ) : '' ) . ( $head_title_sufix ? $head_title_separator : '' ) . $head_title_sufix;

			$this->voutput->set_head_title( $head_title );



			$data[ 'user_data' ] = $this->session->envdata( 'user' );
			$data[ 'component_view_folder' ] = $component_view_folder;
			$data[ 'view' ] = $view;
			$data[ 'layout' ] = $layout;
			$data[ 'msg' ] = loadMsg();
			
			$this->modc->get_modules();
			
			// verificando se o tema atual possui a view de toolbar
			if ( file_exists( THEMES_PATH . admin_theme_components_views_path() . $component_view_folder . DS . $function . DS . $action . DS . $layout . DS . 'toolbar.php') ){

				$data[ 'toolbar' ] = $this->load->view( admin_theme_components_views_path() . $component_view_folder . DS . $function . DS . $action . DS . $layout . DS . 'toolbar', $data, TRUE);

			}
			// verificando se a view	de toolbar existe no diretório de views padrão
			else if ( file_exists( ADMIN_COMPONENTS_VIEWS_PATH . DS . $component_view_folder . DS . $function . DS . $action . DS . $layout . DS . 'toolbar.php') ){

				$data[ 'toolbar' ] = $this->load->view( ADMIN_COMPONENTS_LOAD_VIEWS_PATH . DS . $component_view_folder . DS . $function . DS . $action . DS . $layout . DS . 'toolbar', $data, TRUE);

			}

			// se a saida for ajax, escreve apenas a saida das mensagens, ajustar isto no futuro
			if ( $this->input->get( 'ajax' ) ){

				if ( ! $this->users->is_logged_in() ){

					redirect( 'admin/main/index/login' );

				}
				else{

					echo $data[ 'msg' ];

				}

			}

			// verificando se o tema atual possui a view
			if ( file_exists( THEMES_PATH . admin_theme_components_views_path() . $component_view_folder . DS . $function . DS . $action . DS . $layout . DS . $view . '.php') ){

				if ( $html ){

					$content = $this->load->view( admin_theme_components_views_path() . $component_view_folder . DS . $function . DS . $action . DS . $layout . DS . $view, $data, TRUE );

				}
				else {

					$content = $this->load->view( admin_theme_components_views_path() . $component_view_folder . DS . $function . DS . $action . DS . $layout . DS . $view, $data, ( $load_index ? TRUE : NULL ) );

				}

			}
			// verificando se a view existe no diretório de views padrão
			else if ( file_exists( ADMIN_COMPONENTS_VIEWS_PATH . DS . $component_view_folder . DS . $function . DS . $action . DS . $layout . DS . $view . '.php') ){
				
				if ( $html ){
					
					$content = $this->load->view( ADMIN_COMPONENTS_LOAD_VIEWS_PATH . $component_view_folder . DS . $function . DS . $action . DS . $layout . DS . $view, $data, TRUE );
					
				}
				else {
					
					$content = $this->load->view( ADMIN_COMPONENTS_LOAD_VIEWS_PATH . $component_view_folder . DS . $function . DS . $action . DS . $layout . DS . $view, $data, ( $load_index ? TRUE : NULL ) );
					
				}
				
			}
			else{
				
				$content = lang( 'load_view_fail' ) . ': <b>' . ADMIN_COMPONENTS_VIEWS_PATH . DS . $component_view_folder . DS . $function . DS . $action . DS . $layout . DS . $view . '.php</b>';
				
			}
			
			$this->voutput->append_content( $content );
			
			$data[ 'data' ] = $data;
			
			/*
			 * -------------------------------------------------------------------------------------------------
			 * Carregando plugins
			 */
			
			//$this->plugins->run_plugins( $data );
			
			/*
			 * -------------------------------------------------------------------------------------------------
			 */
			
			
			if ( $load_index ){
				
				$this->load->view( get_constant_name( $this->mcm->environment . '_DIR_NAME' ) . DS . call_user_func( $this->mcm->environment . '_theme' ) . DS . 'index' , $data, $html );
				
			}
			else if ( $html ){
				
				return $content;
				
			}
			
			
		}
		else {
			
			redirect();
			
		}
		
	}
	
	protected function _current_url(){
		return ltrim( $_SERVER[ 'PATH_INFO' ], '/' );
	}
	
	public function index( $action = NULL ){
		
		$env = $this->mcm->environment; // local environment var
		
		if ( ! $action ) redirect( get_constant_name( $env . '_DIR_NAME' ) . '/' . $this->component_name . '/' . __FUNCTION__ . '/' . 'dashboard' );
		
		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;
		
		$url = get_url( get_constant_name( $env . '_DIR_NAME' ) . $this->uri->ruri_string() );

		if ( $action == 'login' ){
			
			$data = array(
				'component_name' => $this->component_name,
			);
			
			//validação dos campos
			$this->form_validation->set_rules( 'username', lang( 'username' ), 'trim|required' );
			$this->form_validation->set_rules( 'password', lang( 'password' ), 'trim|required' );
			
			if ( $this->input->post( NULL, TRUE ) AND $this->form_validation->run() ){
				
				// do login params
				$dlp = array(
					
					'user_data' => array(
						
						'username' => $this->input->post( 'username', TRUE ),
						'password' => $this->input->post( 'password', TRUE ),
						
					)
					
				);
				
				if ( $this->input->post( 'keep_me_logged_in' ) ){
					
					$dlp[ 'session_mode' ] = 'persistent';
					
				}
				
				$this->users->do_login( $dlp );
				
			}
			// caso contrário se a validação dos campos for negativa e mensagens de erro conter strings
			else if (!$this->form_validation->run() AND validation_errors() != ''){

				$data[ 'post' ] = $this->input->post();

				msg(('login_fail'),'title');
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
		else if ( $action == 'logout' ){

			$this->users->remove_access_hash();
			$this->users->remove_session_from_user();

			$this->session->unset_envdata();

			$this->main_model->delete_temp_data( array( 'user_id' => $this->users->user_data[ 'id' ] ) );

			//$this->session->sess_destroy();
			redirect( 'admin/main/index/login' );

		}
		else if ( $action == 'dashboard' ){

			$url = get_url('admin'.$this->uri->ruri_string());
			set_last_url($url);
			$data = array(
				'component_name' => $this->component_name,
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
		else{
			show_404();
		}

	}

	public function components_management( $action = NULL ){

		if ( ! $action ) redirect('admin/'.$this->component_name . '/' . __FUNCTION__ . '/' . 'components_list');

		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;

		$url = get_url('admin'.$this->uri->ruri_string());

		if ($action == 'components_list'){
			if ($components = $this->main_model->get_components(array('status'=>'1'))->result()){

				$data = array(
					'component_name' => $this->component_name,
					'components' => $components,
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
		else{
			show_404();
		}

	}

	public function config_management( $action = NULL ){

		// verificando se o usuário possui privilégios para gerenciar as configurações globais do sistema
		if ( ! $this->users->check_privileges( 'admin_config_management' ) ){

			msg( lang( 'access_denied' ),'title' );
			msg( lang( 'access_denied_admin_config_management' ), 'error' );
			redirect_last_url();

		};

		if ( ! $action ) redirect('admin/'.$this->component_name . '/' . __FUNCTION__ . '/' . 'components_list');

		$this->component_function = __FUNCTION__;
		$this->component_function_action = $action;

		$url = get_url('admin'.$this->uri->ruri_string());

		if ($action == 'config_list'){
			if ($components = $this->main_model->get_components(array('status'=>'1'))->result()){

				$data = array(
					'component_name' => $this->component_name,
					'components' => $components,
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
		else if ($action == 'global_config' AND ($component = $this->main_model->get_component(array('unique_name' => $this->component_name))->row())){

			$data = array(
				'component_name' => $this->component_name,
				'component' => $component,
			);

			// pegando os parâmetros
			$data[ 'params' ] = $this->main_model->get_config_params();
			
			/******************************/
			/********* Parâmetros *********/

			// cruzando os parâmetros globais com os parâmetros locais para obter os valores atuais
			$data[ 'current_params_values' ] = get_params( $component->params );

			// obtendo as especificações dos parâmetros
			$data[ 'params_spec' ] = $this->main_model->get_config_params();

			// cruzando os valores padrões das especificações com os do DB
			$data[ 'final_params_values' ] = array_merge( $data[ 'params_spec' ][ 'params_spec_values' ], $data[ 'current_params_values' ] );

			// definindo as regras de validação
			set_params_validations( $data[ 'params_spec' ][ 'params_spec' ] );

			/********* Parâmetros *********/
			/******************************/

			$this->form_validation->set_rules('component_id',lang('id'),'trim|required|integer');

			if($this->input->post('submit_cancel')){
				redirect_last_url();
			}
			// se a validação dos campos for positiva
			else if ($this->form_validation->run() AND ($this->input->post('submit') OR $this->input->post('submit_apply'))){

				$update_data = elements(array(
					'params',
				),$this->input->post());

				//$update_data[ 'params' ][ 'language' ] = $update_data[ 'params' ][ 'site_language' ];
				$update_data[ 'params' ] = json_encode( $update_data[ 'params' ] );
				
				if ($this->main_model->update_component($update_data, array('id' => $this->input->post('component_id')))){
					
					msg( ('component_preferences_updated'), 'success' );
					
					$this->mcm->update_config_file( get_params( $update_data[ 'params' ] ) );

					if ($this->input->post('submit_apply')){
						redirect('admin'.$this->uri->ruri_string());
					}
					else{
						redirect_last_url();
					}
				}

			}
			// caso contrário se a validação dos campos for negativa e mensagens de erro conter strings
			else if (!$this->form_validation->run() AND validation_errors() != ''){

				$data[ 'post' ] = $this->input->post();

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
		else{
			show_404();
		}

	}


	public function _remap($method, $params = array()){

		$method = $method;
		if (method_exists($this, $method)){
			return call_user_func_array(array($this, $method), $params);
		}
		show_404();

	}

}
