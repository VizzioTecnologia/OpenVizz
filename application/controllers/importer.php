<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Importer extends CI_controller {
	
	public function __construct(){
		
		parent::__construct();
		
		$this->load->database();
		$this->load->helper( 'date' );
		$this->load->helper( 'array' );
		$this->load->helper( 'params' );
		$this->load->helper( 'general_helper' );
		$this->load->library( 'Str_utils' );
		
	}
	
	public function index(){
		
		exit;
		
		$this->load->model( 'common/submit_forms_common_model', 'sfcm' );
		
		$this->db->select( 'id, data' );
		$this->db->from( 'tb_submit_forms_us' );
		$users_submits = $this->db->get()->result_array();
		
		$db_data = array();
		
		foreach( $users_submits as $k => $us ) {
			
			$n_us[ 'xml_data' ] = $this->sfcm->us_json_data_to_xml( $us );
			
			$this->db->update( 'tb_submit_forms_us', $n_us, array( 'id' => $us[ 'id' ] ) );
			
		}
		
		
		print_r( $db_data );
		
		/*
		
		$array_db1 = $array_db2 = array(
			
			'hostname' => 'localhost',
			'username' => 'root',
			'password' => '18372955',
			'database' => 'ande2',
			'dbdriver' => 'mysql',
			'dbprefix' => '',
			'pconnect' => TRUE,
			'db_debug' => TRUE,
			'cache_on' => FALSE,
			'cachedir' => 'application/cache/database',
			'char_set' => 'utf8',
			'dbcollat' => 'utf8_general_ci',
			'swap_pre' => '',
			'autoinit' => TRUE,
			'stricton' => FALSE,
			
		);
		
		
		$DB1 = $this->load->database( $array_db1, TRUE );
		
		$DB1->select( '*' );
		$DB1->where( 'submit_form_id', 10 );
		
		$DB1->from( 'tb_submit_forms_us' );
		
		//print_r( $DB1->get()->result_array() );
		
		echo '<br/><br/><br/>---------------<br/><br/><br/>';
		
		$array_db2[ 'database' ] = 'equoterapia_old3';
		$DB2 = $this->load->database( $array_db2, TRUE );
		
		$DB2->select('*');
		
		
		
		
		
		
		$db_data = array();
		
		
		
		
		
		/*
		
		//Curso básico
		
		$DB2->from( 'calendariosCursoBasico' );
		
		$result_db2 = $DB2->get()->result_array();
		foreach( $result_db2 as $key => $result ) {
			
			$_new = & $db_data[];
			
			$_new = array(
				
				'submit_form_id' => 10,
				'submit_datetime' => '2015-03-15 20:27:30',
				'mod_datetime' => '2015-03-15 20:27:30',
				'data' => array(
					
					'numero-do-curso' => $result[ 'idCurso' ],
					'titulo' => $result[ 'idCurso' ] . 'º Curso Básico de Equoterapia',
					'data-de-inicio' => $result[ 'dtePeriodoI' ],
					'data-de-termino' => $result[ 'dtePeriodoF' ],
					'data-de-selecao' => $result[ 'dteSelecao' ],
					'data-de-inscricao' => $result[ 'dteInscricao' ],
					'data-de-pagamento' => $result[ 'dtePagamento' ],
					'modalidade' => "506",
					'status' => $result[ 'chrStatusCurso' ],
					
				),
				
			);
			
			$_sc = $_new[ 'data' ][ 'status' ];
			$_new[ 'data' ][ 'status' ] = ( trim( $_sc ) == 'A' ) ? '501' : '502';
			
		}
		
		
		// Curso avançado
		
		$DB2->from( 'calendariosCursoAvancado' );
		
		$result_db2 = $DB2->get()->result_array();
		foreach( $result_db2 as $key => $result ) {
			
			$_new = & $db_data[];
			
			$_new = array(
				
				'submit_form_id' => 10,
				'submit_datetime' => '2015-03-15 20:27:30',
				'mod_datetime' => '2015-03-15 20:27:30',
				'data' => array(
					
					'numero-do-curso' => $result[ 'idCurso' ],
					'titulo' => $result[ 'idCurso' ] . 'º Curso Avançado de Equoterapia',
					'data-de-inicio' => $result[ 'dtePeriodoI' ],
					'data-de-termino' => $result[ 'dtePeriodoF' ],
					'data-de-selecao' => $result[ 'dteSelecao' ],
					'data-de-inscricao' => $result[ 'dteInscricao' ],
					'data-de-pagamento' => $result[ 'dtePagamento' ],
					'modalidade' => "507",
					'status' => $result[ 'chrStatusCurso' ],
					
				),
				
			);
			
			$_sc = $_new[ 'data' ][ 'status' ];
			$_new[ 'data' ][ 'status' ] = ( trim( $_sc ) == 'A' ) ? '501' : '502';
			
		}
		
		
		
		// Cursos de gestão
		
		$DB2->from( 'calendariosCursoGestao' );
		
		$result_db2 = $DB2->get()->result_array();
		
		foreach( $result_db2 as $key => $result ) {
			
			$_new = & $db_data[];
			
			$_new = array(
				
				'submit_form_id' => 10,
				'submit_datetime' => '2015-03-15 20:27:30',
				'mod_datetime' => '2015-03-15 20:27:30',
				'data' => array(
					
					'numero-do-curso' => $result[ 'idCurso' ],
					'titulo' => $result[ 'idCurso' ] . 'º Curso de Gestão em Equoterapia',
					'data-de-inicio' => $result[ 'dtePeriodoI' ],
					'data-de-termino' => $result[ 'dtePeriodoF' ],
					'data-de-selecao' => $result[ 'dteSelecao' ],
					'data-de-inscricao' => $result[ 'dteInscricao' ],
					'data-de-pagamento' => $result[ 'dtePagamento' ],
					'modalidade' => "2311",
					'status' => $result[ 'chrStatusCurso' ],
					
				),
				
			);
			
			$_sc = $_new[ 'data' ][ 'status' ];
			$_new[ 'data' ][ 'status' ] = ( trim( $_sc ) == 'A' ) ? '501' : '502';
			
		}
		
		
		// Curso de Equitação
		
		$DB2->from( 'calendariosCursoEquitacao' );
		
		$result_db2 = $DB2->get()->result_array();
		foreach( $result_db2 as $key => $result ) {
			
			$_new = & $db_data[];
			
			$_new = array(
				
				'submit_form_id' => 10,
				'submit_datetime' => '2015-03-15 20:27:30',
				'mod_datetime' => '2015-03-15 20:27:30',
				'data' => array(
					
					'numero-do-curso' => $result[ 'idCurso' ],
					'titulo' => $result[ 'idCurso' ] . 'º Curso de Equitação para Equoterapia',
					'data-de-inicio' => $result[ 'dtePeriodoI' ],
					'data-de-termino' => $result[ 'dtePeriodoF' ],
					'data-de-selecao' => $result[ 'dteSelecao' ],
					'data-de-inscricao' => $result[ 'dteInscricao' ],
					'data-de-pagamento' => $result[ 'dtePagamento' ],
					'modalidade' => "2310",
					'status' => $result[ 'chrStatusCurso' ],
					
				),
				
			);
			
			$_sc = $_new[ 'data' ][ 'status' ];
			$_new[ 'data' ][ 'status' ] = ( trim( $_sc ) == 'A' ) ? '501' : '502';
			
		}
		
		
		
		// Curso de aprimoramento
		
		$DB2->from( 'calendariosCursoAprimoramento' );
		
		$result_db2 = $DB2->get()->result_array();
		foreach( $result_db2 as $key => $result ) {
			
			$_new = & $db_data[];
			
			$_new = array(
				
				'submit_form_id' => 10,
				'submit_datetime' => '2015-03-15 20:27:30',
				'mod_datetime' => '2015-03-15 20:27:30',
				'data' => array(
					
					'numero-do-curso' => $result[ 'idCurso' ],
					'titulo' => $result[ 'idCurso' ] . 'º Curso de Aprimoramento Técnico em Equoterapia',
					'data-de-inicio' => $result[ 'dtePeriodoI' ],
					'data-de-termino' => $result[ 'dtePeriodoF' ],
					'data-de-selecao' => $result[ 'dteSelecao' ],
					'data-de-inscricao' => $result[ 'dteInscricao' ],
					'data-de-pagamento' => $result[ 'dtePagamento' ],
					'modalidade' => "2309",
					'status' => $result[ 'chrStatusCurso' ],
					
				),
				
			);
			
			$_sc = $_new[ 'data' ][ 'status' ];
			$_new[ 'data' ][ 'status' ] = ( trim( $_sc ) == 'A' ) ? '501' : '502';
			
		}
		
		
		
		
		
		
		
		
		// Cursos externos
		
		$DB2->from( 'cursosexternos' );
		
		$result_db2 = $DB2->get()->result_array();
		foreach( $result_db2 as $key => $result ) {
			
			$_new = & $db_data[];
			
			$_new = array(
				
				'submit_form_id' => 9,
				'submit_datetime' => '2015-03-15 20:27:30',
				'mod_datetime' => '2015-03-15 20:27:30',
				'data' => array(
					
					'capa' => str_replace( 'images/folders/', 'media/cursos-externos/', $result[ 'strFolder' ] ),
					'titulo-do-curso' => $this->str_utils->normalizarNome( $result[ 'strTitulo' ] ),
					'nome-do-centro' => $this->str_utils->normalizarNome( $result[ 'strCentro' ] ),
					'coordenador-a' => $this->str_utils->normalizarNome( $result[ 'strCoordenador' ] ),
					'data-do-curso' => $result[ 'strDataCurso' ],
					'data-de-inicio' => $result[ 'dteDataInicio' ],
					'data-de-termino' => $result[ 'dteDataFim' ],
					'telefone' => $result[ 'strTelefone' ],
					'fax' => $result[ 'strFax' ],
					'e-mail' => $result[ 'strEmail' ],
					'site' => $result[ 'strSite' ],
					'cidade' => $this->str_utils->normalizarNome( $result[ 'strCidade' ] ),
					'estado' => $result[ 'UF_idUF' ],
					'tipo-de-curso' => $result[ 'charTipoCurso' ],
					'modalidade' => $result[ 'intTipoCurso2' ],
					'ordem' => $result[ 'intOrd' ],
					'status' => $result[ 'status' ],
					
				),
				
			);
			
			$_sc = $_new[ 'data' ][ 'status' ];
			$_new[ 'data' ][ 'status' ] = (
				
				( strtolower( trim( $_sc ) ) == 'a' ) ? '501' : (
				( strtolower( trim( $_sc ) ) == 'e' ) ? '502' : (
				( strtolower( trim( $_sc ) ) == 'c' ) ? '503' : ''
				
				)));
			$_mc = $_new[ 'data' ][ 'modalidade' ];
			$_new[ 'data' ][ 'modalidade' ] = (
				
				( strtolower( trim( $_mc ) ) == '1' ) ? '506' : (
				( strtolower( trim( $_mc ) ) == '2' ) ? '507' : ''
				
				));
			$_tc = $_new[ 'data' ][ 'tipo-de-curso' ];
			$_new[ 'data' ][ 'tipo-de-curso' ] = (
				
				( strtolower( trim( $_tc ) ) == 'r' ) ? '504' : (
				( strtolower( trim( $_tc ) ) == 'a' ) ? '505' : ''
				
				));
			$_ec = $_new[ 'data' ][ 'estado' ];
			$_new[ 'data' ][ 'estado' ] = (
				
				( strtolower( trim( $_ec ) ) == 'ac' ) ? '2313' : (
				( strtolower( trim( $_ec ) ) == 'al' ) ? '2314' : (
				( strtolower( trim( $_ec ) ) == 'ap' ) ? '2315' : (
				( strtolower( trim( $_ec ) ) == 'am' ) ? '2316' : (
				( strtolower( trim( $_ec ) ) == 'ba' ) ? '2317' : (
				( strtolower( trim( $_ec ) ) == 'ce' ) ? '2318' : (
				( strtolower( trim( $_ec ) ) == 'df' ) ? '2319' : (
				( strtolower( trim( $_ec ) ) == 'es' ) ? '2320' : (
				( strtolower( trim( $_ec ) ) == 'go' ) ? '2321' : (
				( strtolower( trim( $_ec ) ) == 'ma' ) ? '2322' : (
				( strtolower( trim( $_ec ) ) == 'mt' ) ? '2323' : (
				( strtolower( trim( $_ec ) ) == 'ms' ) ? '2324' : (
				( strtolower( trim( $_ec ) ) == 'mg' ) ? '2325' : (
				( strtolower( trim( $_ec ) ) == 'pa' ) ? '2326' : (
				( strtolower( trim( $_ec ) ) == 'pb' ) ? '2327' : (
				( strtolower( trim( $_ec ) ) == 'pr' ) ? '2328' : (
				( strtolower( trim( $_ec ) ) == 'pe' ) ? '2329' : (
				( strtolower( trim( $_ec ) ) == 'pi' ) ? '2330' : (
				( strtolower( trim( $_ec ) ) == 'rj' ) ? '2331' : (
				( strtolower( trim( $_ec ) ) == 'rn' ) ? '2332' : (
				( strtolower( trim( $_ec ) ) == 'rs' ) ? '2333' : (
				( strtolower( trim( $_ec ) ) == 'ro' ) ? '2334' : (
				( strtolower( trim( $_ec ) ) == 'rr' ) ? '2335' : (
				( strtolower( trim( $_ec ) ) == 'sc' ) ? '2336' : (
				( strtolower( trim( $_ec ) ) == 'sp' ) ? '2337' : (
				( strtolower( trim( $_ec ) ) == 'se' ) ? '2338' : (
				( strtolower( trim( $_ec ) ) == 'to' ) ? '2339' : ''
				
				)))))))))))))))))))))))))));
			$_new[ 'data' ][ 'estado' ] = strtoupper( $_new[ 'data' ][ 'estado' ] );
			
		}
		
		
		
		
		
		
		/*
		
		// Centros
		
		$DB2->from( 'centros' );
		
		$result_db2 = $DB2->get()->result_array();
		
		foreach( $result_db2 as $key => $result ) {
			
			$_new = & $db_data[];
			
			$_new = array(
				
				'submit_form_id' => 3,
				'submit_datetime' => '2015-03-15 20:27:30',
				'mod_datetime' => '2015-03-15 20:27:30',
				'data' => array(
					
					'nome' => $this->str_utils->normalizarNome( $result[ 'centro' ] ),
					'estado' => $result[ 'uf' ],
					'status' => $result[ 'status' ],
					'endereco' => $this->str_utils->normalizarNome( $result[ 'endereco' ] ),
					'bairro' => $this->str_utils->normalizarNome( $result[ 'bairro' ] ),
					'cidade' => $this->str_utils->normalizarNome( $result[ 'cidade' ] ),
					'cep' => $result[ 'cep' ],
					'ddd' => $result[ 'ddd' ],
					'telefone-1' => $result[ 'telefone1' ],
					'telefone-2' => $result[ 'telefone2' ],
					'fax' => $result[ 'fax' ],
					'e-mail' => $result[ 'email' ],
					'site' => $result[ 'site' ],
					'filiado-ate' => $result[ 'filiacao' ],
					
				),
				
			);
			
			$_sc = $_new[ 'data' ][ 'status' ];
			$_new[ 'data' ][ 'status' ] = ( trim( $_sc ) == '1' OR $_sc == 1 ) ? '3' : ( ( trim( $_sc ) == '2' OR $_sc == 2 ) ? '4' : '5' );
			$_ec = $_new[ 'data' ][ 'estado' ];
			$_new[ 'data' ][ 'estado' ] = (
				
				( strtolower( trim( $_ec ) ) == 'ac' ) ? '2313' : (
				( strtolower( trim( $_ec ) ) == 'al' ) ? '2314' : (
				( strtolower( trim( $_ec ) ) == 'ap' ) ? '2315' : (
				( strtolower( trim( $_ec ) ) == 'am' ) ? '2316' : (
				( strtolower( trim( $_ec ) ) == 'ba' ) ? '2317' : (
				( strtolower( trim( $_ec ) ) == 'ce' ) ? '2318' : (
				( strtolower( trim( $_ec ) ) == 'df' ) ? '2319' : (
				( strtolower( trim( $_ec ) ) == 'es' ) ? '2320' : (
				( strtolower( trim( $_ec ) ) == 'go' ) ? '2321' : (
				( strtolower( trim( $_ec ) ) == 'ma' ) ? '2322' : (
				( strtolower( trim( $_ec ) ) == 'mt' ) ? '2323' : (
				( strtolower( trim( $_ec ) ) == 'ms' ) ? '2324' : (
				( strtolower( trim( $_ec ) ) == 'mg' ) ? '2325' : (
				( strtolower( trim( $_ec ) ) == 'pa' ) ? '2326' : (
				( strtolower( trim( $_ec ) ) == 'pb' ) ? '2327' : (
				( strtolower( trim( $_ec ) ) == 'pr' ) ? '2328' : (
				( strtolower( trim( $_ec ) ) == 'pe' ) ? '2329' : (
				( strtolower( trim( $_ec ) ) == 'pi' ) ? '2330' : (
				( strtolower( trim( $_ec ) ) == 'rj' ) ? '2331' : (
				( strtolower( trim( $_ec ) ) == 'rn' ) ? '2332' : (
				( strtolower( trim( $_ec ) ) == 'rs' ) ? '2333' : (
				( strtolower( trim( $_ec ) ) == 'ro' ) ? '2334' : (
				( strtolower( trim( $_ec ) ) == 'rr' ) ? '2335' : (
				( strtolower( trim( $_ec ) ) == 'sc' ) ? '2336' : (
				( strtolower( trim( $_ec ) ) == 'sp' ) ? '2337' : (
				( strtolower( trim( $_ec ) ) == 'se' ) ? '2338' : (
				( strtolower( trim( $_ec ) ) == 'to' ) ? '2339' : ''
				
				)))))))))))))))))))))))))));
			$_new[ 'data' ][ 'estado' ] = strtoupper( $_new[ 'data' ][ 'estado' ] );
			
		}
		
		*/
		
		
		
		
		
		
		/*
		
		// Inscrições
		
		$result_db2 = array();
		
		$DB2->from( 'inscricoesCursoBasico' );
		$result_db2 = $result_db2 + $DB2->get()->result_array();
		$DB2->from( 'inscricoesCursoAvancado' );
		$result_db2 = $result_db2 + $DB2->get()->result_array();
		$DB2->from( 'inscricoesCursoGestao' );
		$result_db2 = $result_db2 + $DB2->get()->result_array();
		$DB2->from( 'inscricoesCursoAprimoramento' );
		$result_db2 = $result_db2 + $DB2->get()->result_array();
		$DB2->from( 'inscricoesCursoEquitacao' );
		$result_db2 = $result_db2 + $DB2->get()->result_array();
		
		echo '<pre>' . print_r( $result_db2, TRUE ) . '</pre>'; exit;
		
		foreach( $result_db2 as $key => $result ) {
			
			$_new = & $db_data[];
			
			$_new = array(
				
				'submit_form_id' => 9,
				'submit_datetime' => '2015-03-15 20:27:30',
				'mod_datetime' => '2015-03-15 20:27:30',
				'data' => array(
					
					'nome-completo' => @$result[ 'strNome' ],
					'cpf' => @$result[ 'strCPF' ],
					'identidade' => @$result[ 'strRG' ],
					'data-de-inicio' => @$result[ 'strEmissor' ],
					'data-de-termino' => $result[ 'dteDataFim' ],
					'telefone' => $result[ 'strTelefone' ],
					'fax' => $result[ 'strFax' ],
					'e-mail' => $result[ 'strEmail' ],
					'site' => $result[ 'strSite' ],
					'cidade' => $result[ 'strCidade' ],
					'estado' => $result[ 'UF_idUF' ],
					'tipo-de-curso' => $result[ 'charTipoCurso' ],
					'modalidade' => $result[ 'intTipoCurso2' ],
					'ordem' => $result[ 'intOrd' ],
					'status' => $result[ 'status' ],
					
				),
				
			);
			
			$_sc = $_new[ 'data' ][ 'status' ];
			$_new[ 'data' ][ 'status' ] = (
				
				( strtolower( trim( $_sc ) ) == 'a' ) ? '501' : (
				( strtolower( trim( $_sc ) ) == 'e' ) ? '502' : (
				( strtolower( trim( $_sc ) ) == 'c' ) ? '503' : ''
				
				)));
			$_mc = $_new[ 'data' ][ 'modalidade' ];
			$_new[ 'data' ][ 'modalidade' ] = (
				
				( strtolower( trim( $_mc ) ) == '1' ) ? '506' : (
				( strtolower( trim( $_mc ) ) == '2' ) ? '507' : ''
				
				));
			$_tc = $_new[ 'data' ][ 'tipo-de-curso' ];
			$_new[ 'data' ][ 'tipo-de-curso' ] = (
				
				( strtolower( trim( $_tc ) ) == 'r' ) ? '504' : (
				( strtolower( trim( $_tc ) ) == 'a' ) ? '505' : ''
				
				));
			$_ec = $_new[ 'data' ][ 'estado' ];
			$_new[ 'data' ][ 'estado' ] = (
				
				( strtolower( trim( $_ec ) ) == 'ac' ) ? '2313' : (
				( strtolower( trim( $_ec ) ) == 'al' ) ? '2314' : (
				( strtolower( trim( $_ec ) ) == 'ap' ) ? '2315' : (
				( strtolower( trim( $_ec ) ) == 'am' ) ? '2316' : (
				( strtolower( trim( $_ec ) ) == 'ba' ) ? '2317' : (
				( strtolower( trim( $_ec ) ) == 'ce' ) ? '2318' : (
				( strtolower( trim( $_ec ) ) == 'df' ) ? '2319' : (
				( strtolower( trim( $_ec ) ) == 'es' ) ? '2320' : (
				( strtolower( trim( $_ec ) ) == 'go' ) ? '2321' : (
				( strtolower( trim( $_ec ) ) == 'ma' ) ? '2322' : (
				( strtolower( trim( $_ec ) ) == 'mt' ) ? '2323' : (
				( strtolower( trim( $_ec ) ) == 'ms' ) ? '2324' : (
				( strtolower( trim( $_ec ) ) == 'mg' ) ? '2325' : (
				( strtolower( trim( $_ec ) ) == 'pa' ) ? '2326' : (
				( strtolower( trim( $_ec ) ) == 'pb' ) ? '2327' : (
				( strtolower( trim( $_ec ) ) == 'pr' ) ? '2328' : (
				( strtolower( trim( $_ec ) ) == 'pe' ) ? '2329' : (
				( strtolower( trim( $_ec ) ) == 'pi' ) ? '2330' : (
				( strtolower( trim( $_ec ) ) == 'rj' ) ? '2331' : (
				( strtolower( trim( $_ec ) ) == 'rn' ) ? '2332' : (
				( strtolower( trim( $_ec ) ) == 'rs' ) ? '2333' : (
				( strtolower( trim( $_ec ) ) == 'ro' ) ? '2334' : (
				( strtolower( trim( $_ec ) ) == 'rr' ) ? '2335' : (
				( strtolower( trim( $_ec ) ) == 'sc' ) ? '2336' : (
				( strtolower( trim( $_ec ) ) == 'sp' ) ? '2337' : (
				( strtolower( trim( $_ec ) ) == 'se' ) ? '2338' : (
				( strtolower( trim( $_ec ) ) == 'to' ) ? '2339' : ''
				
				)))))))))))))))))))))))))));
			$_new[ 'data' ][ 'estado' ] = strtoupper( $_new[ 'data' ][ 'estado' ] );
			
		}
		
		
		
		
		
		echo '<pre>' . print_r( $db_data, TRUE ) . '</pre>'; exit;
		
		foreach( $db_data as $key => $result ) {
			
			$result[ 'xml_data' ] = $this->sfcm->us_json_data_to_xml( $result );
			$result[ 'data' ] = json_encode( $result[ 'data' ] );
			
			$data = elements( array(
				
				'submit_form_id',
				'submit_datetime',
				'mod_datetime',
				'data',
				'xml_data',
				
			), $result );
			
			//echo '<pre>' . print_r( $data, TRUE ) . '</pre>'; exit;
			
			if ( $data != NULL ){
				
				if ( $this->db->insert( 'ande2.tb_submit_forms_us', $data ) ){
					
					echo '<h5>inserindo:</h5> ' . '<pre>' . print_r( $data, TRUE ) . '</pre>';
					
					$return_id = $this->db->insert_id();
					
					echo 'RETURN ID: ' . $return_id . '<br/>';
					
				}
				else {
					
					echo '<h5>FALHA:</h5> ' . '<pre>' . print_r( $data, TRUE ) . '</pre>';
					
				}
				
			}
			
		}
		
		*/
		
	}
	
	public function importar_depoimentos(){
		
		$this->load->model( 'common/submit_forms_common_model', 'sfcm' );
		
		$this->db->select( 'id, data' );
		$this->db->from( 'tb_submit_forms_us' );
		$users_submits = $this->db->get()->result_array();
		
		$db_data = array();
		
		
		$array_db1 = $array_db2 = array(
			
			'hostname' => 'localhost',
			'username' => 'root',
			'password' => '18372955',
			'database' => 'corposempar',
			'dbdriver' => 'mysql',
			'dbprefix' => '',
			'pconnect' => TRUE,
			'db_debug' => TRUE,
			'cache_on' => FALSE,
			'cachedir' => 'application/cache/database',
			'char_set' => 'utf8',
			'dbcollat' => 'utf8_general_ci',
			'swap_pre' => '',
			'autoinit' => TRUE,
			'stricton' => FALSE,
			
		);
		
		
		$DB1 = $this->load->database( $array_db1, TRUE );
		
		$DB1->select( '*' );
		$DB1->where( 'submit_form_id', 10 );
		
		$DB1->from( 'tb_submit_forms_us' );
		
		//print_r( $DB1->get()->result_array() );
		
		echo '<br/><br/><br/>---------------<br/><br/><br/>';
		
		$array_db2[ 'database' ] = 'corposempar_antigo';
		$DB2 = $this->load->database( $array_db2, TRUE );
		
		$DB2->select('*');
		
		
		
		
		
		
		$db_data = array();
		
		
		
		
		
		//Curso básico
		
		$DB2->from( 'corposempar_rsmonials' );
		
		$result_db2 = $DB2->get()->result_array();
		
		foreach( $result_db2 as $key => $result ) {
			
			$_new = & $db_data[];
			
			$_new = array(
				
				'submit_form_id' => 16,
				'submit_datetime' => $result[ 'date' ] . ' 00:00:00',
				'mod_datetime' => $result[ 'date' ] . ' 00:00:00',
				'data' => array(
					
					'status' => $result[ 'status' ] == 2 ? 10356 : 10355, // 10355 = ativo
					'nome-completo' => $this->str_utils->normalizarNome( $result[ 'fname' ] . ' ' . $result[ 'lname' ] ),
					'e-mail' => strtolower( $result[ 'email' ] ),
					'estilo-da-turma' => $this->str_utils->normalizarNome( $result[ 'about' ] ),
					'dias-da-semana' => $this->str_utils->normalizarNome( $result[ 'location' ] ),
					'horario' => $result[ 'website' ],
					'depoimento' => $result[ 'comment' ],
					
				),
				
			);
			
		}
		
		echo '<pre>' . print_r( $db_data, TRUE ) . '</pre>'; exit;
		
		foreach( $db_data as $key => $result ) {
			
			$result[ 'xml_data' ] = $this->sfcm->us_json_data_to_xml( $result[ 'data' ] );
			$result[ 'data' ] = json_encode( $result[ 'data' ] );
			
			$data = elements( array(
				
				'submit_form_id',
				'submit_datetime',
				'mod_datetime',
				'data',
				'xml_data',
				
			), $result );
			
			//echo '<pre>' . print_r( $data, TRUE ) . '</pre>'; exit;
			
			if ( $data != NULL ){
				
				if ( $this->db->insert( 'corposempar.tb_submit_forms_us', $data ) ){
					
					echo '<h5>inserindo:</h5> ' . '<pre>' . print_r( $data, TRUE ) . '</pre>';
					
					$return_id = $this->db->insert_id();
					
					echo 'RETURN ID: ' . $return_id . '<br/>';
					
				}
				else {
					
					echo '<h5>FALHA:</h5> ' . '<pre>' . print_r( $data, TRUE ) . '</pre>';
					
				}
				
			}
			
		}
		
	}
	
	public function importar_matriculas(){
		
		$this->load->model( 'common/submit_forms_common_model', 'sfcm' );
		
		$this->db->select( 'id, data' );
		$this->db->from( 'tb_submit_forms_us' );
		$users_submits = $this->db->get()->result_array();
		
		$db_data = array();
		
		
		$array_db1 = $array_db2 = array(
			
			'hostname' => 'localhost',
			'username' => 'root',
			'password' => '18372955',
			'database' => 'corposempar',
			'dbdriver' => 'mysql',
			'dbprefix' => '',
			'pconnect' => TRUE,
			'db_debug' => TRUE,
			'cache_on' => FALSE,
			'cachedir' => 'application/cache/database',
			'char_set' => 'utf8',
			'dbcollat' => 'utf8_general_ci',
			'swap_pre' => '',
			'autoinit' => TRUE,
			'stricton' => FALSE,
			
		);
		
		$array_db2[ 'hostname' ] = 'viaeletronica.com.br';
		$array_db2[ 'username' ] = 'corposempar';
		$array_db2[ 'password' ] = 'Frank18372955';
		$array_db2[ 'database' ] = 'zadmin_corposempar2';
		
		
		$DB1 = $this->load->database( $array_db1, TRUE );
		
		$DB1->select( '*' );
		$DB1->where( 'submit_form_id', 10 );
		
		$DB1->from( 'tb_submit_forms_us' );
		
		//print_r( $DB1->get()->result_array() );
		
		echo '<br/><br/><br/>---------------<br/><br/><br/>';
		
		$DB2 = $this->load->database( $array_db2, TRUE );
		
		$db_data = array();
		
		$DB2->from( 'tb_submit_forms_us' );
		$DB2->select('*');
		$DB2->where( 'submit_form_id', 10 );
		
		$result_db2 = $DB2->get()->result_array();
		
		foreach( $result_db2 as $key => $result ) {
			
			$_new = & $db_data[];
			
			$_new = $result;
			
			$_new[ 'data' ] = json_decode( $result[ 'data' ], TRUE );
			
			$_new[ 'data' ][ 'nome-completo' ] = isset( $_new[ 'data' ][ 'nome-completo' ] ) ? $this->str_utils->normalizarNome( $_new[ 'data' ][ 'nome-completo' ] ) : NULL;
			$_new[ 'data' ][ 'nome-do-pai' ] = isset( $_new[ 'data' ][ 'nome-do-pai' ] ) ? $this->str_utils->normalizarNome( $_new[ 'data' ][ 'nome-do-pai' ] ) : NULL;
			$_new[ 'data' ][ 'nome-da-mae' ] = isset( $_new[ 'data' ][ 'nome-da-mae' ] ) ? $this->str_utils->normalizarNome( $_new[ 'data' ][ 'nome-da-mae' ] ) : NULL;
			$_new[ 'data' ][ 'apelido' ] = isset( $_new[ 'data' ][ 'apelido' ] ) ? $this->str_utils->normalizarNome( $_new[ 'data' ][ 'apelido' ] ) : NULL;
			$_new[ 'data' ][ 'nome-completo-doa-parceiroa' ] = isset( $_new[ 'data' ][ 'nome-completo-doa-parceiroa' ] ) ? $this->str_utils->normalizarNome( $_new[ 'data' ][ 'nome-completo-doa-parceiroa' ] ) : NULL;
			$_new[ 'data' ][ 'nome-completo-do-conjuge' ] = isset( $_new[ 'data' ][ 'nome-completo-do-conjuge' ] ) ? $this->str_utils->normalizarNome( $_new[ 'data' ][ 'nome-completo-do-conjuge' ] ) : NULL;
			$_new[ 'data' ][ 'nome-do-aluno' ] = isset( $_new[ 'data' ][ 'nome-do-aluno' ] ) ? $this->str_utils->normalizarNome( $_new[ 'data' ][ 'nome-do-aluno' ] ) : NULL;
			$_new[ 'data' ][ 'e-mail' ] = strtolower( $_new[ 'data' ][ 'e-mail' ] );
			
			$_new[ 'data' ][ 'data-de-nascimento' ] = str_replace( '/', '-',  $_new[ 'data' ][ 'data-de-nascimento' ] );
			
			
			
			if ( strpos( $_new[ 'data' ][ 'data-de-nascimento' ], '/' ) === FALSE AND strpos( $_new[ 'data' ][ 'data-de-nascimento' ], '-' ) === FALSE ) {
				
				$_new[ 'data' ][ 'data-de-nascimento' ] = substr_replace( $_new[ 'data' ][ 'data-de-nascimento' ], '-', -4, 0);
				$_new[ 'data' ][ 'data-de-nascimento' ] = substr_replace( $_new[ 'data' ][ 'data-de-nascimento' ], '-', -7, 0);
				
				if ( strlen( $_new[ 'data' ][ 'data-de-nascimento' ] ) < 10 ) {
					
					$_new[ 'data' ][ 'data-de-nascimento' ] = '0' . $_new[ 'data' ][ 'data-de-nascimento' ];
					
				}
				
			}
			
			if ( strlen( $_new[ 'data' ][ 'data-de-nascimento' ] ) == 8 ) {
				
				$_new[ 'data' ][ 'data-de-nascimento' ] = substr_replace( $_new[ 'data' ][ 'data-de-nascimento' ], '19', -2, 0);
				
			}
			
			$_new[ 'data' ][ 'data-de-nascimento' ] = explode( '-', $_new[ 'data' ][ 'data-de-nascimento' ] );
			$_new[ 'data' ][ 'data-de-nascimento' ] = array_reverse( $_new[ 'data' ][ 'data-de-nascimento' ] );
			$_new[ 'data' ][ 'data-de-nascimento' ] = join( '-', $_new[ 'data' ][ 'data-de-nascimento' ] );
			
			if ( isset( $_new[ 'data' ][ 'estilo-da-turma' ] ) ) {
				
				switch ( $_new[ 'data' ][ 'estilo-da-turma' ] ) {
					
					case 'Calientes':
						
						$_new[ 'data' ][ 'estilo-da-turma' ] = 'Zouk';
						break;
						
					case 'Country':
						
						$_new[ 'data' ][ 'estilo-da-turma' ] = 'Sertanejo/Country';
						break;
						
					case 'Forró':
						
						$_new[ 'data' ][ 'estilo-da-turma' ] = 'Forró tradicional';
						break;
						
					
				}
				
			}
			
			
			$_new[ 'data' ][ 'como-conheceu-a-cped' ] = ( isset( $_new[ 'data' ][ 'como-conheceu-a-cped' ] ) AND $_new[ 'data' ][ 'como-conheceu-a-cped' ] ) ? $_new[ 'data' ][ 'como-conheceu-a-cped' ] : 'Outro meio';
			
			$_new[ 'xml_data' ] = $this->sfcm->us_json_data_to_xml( $_new[ 'data' ] );
			
			$_new[ 'data' ] = json_encode( $_new[ 'data' ] );
			
		}
		
		//echo '<pre>' . print_r( $db_data, TRUE ) . '</pre>'; exit;
		
		foreach( $db_data as $key => $result ) {
			
			$data = elements( array(
				
				'id',
				'submit_form_id',
				'submit_datetime',
				'mod_datetime',
				'data',
				'xml_data',
				
			), $result );
			
			echo '<pre>' . print_r( $data[ 'data' ], TRUE ) . '</pre>';
			
			if ( $data != NULL ){
				
				if ( $this->db->update( 'tb_submit_forms_us', $data, array( 'id' => $data[ 'id' ], ) ) ){
					
					echo '<h5>inserindo:</h5> ' . '<pre>' . print_r( $data, TRUE ) . '</pre>';
					// confirm update for controller
					echo '[ok]';
					
				}
				else {
					
					// case update fails, return false
					echo '[erro]';;
					
				}
				
			}
			
		}
		
	}
	
	public function importar_recadastramento(){
		
		$this->load->model( 'common/submit_forms_common_model', 'sfcm' );
		
		$this->db->select( 'id, data' );
		$this->db->from( 'tb_submit_forms_us' );
		$users_submits = $this->db->get()->result_array();
		
		$db_data = array();
		
		
		$array_db1 = $array_db2 = array(
			
			'hostname' => 'localhost',
			'username' => 'root',
			'password' => '18372955',
			'database' => 'corposempar',
			'dbdriver' => 'mysql',
			'dbprefix' => '',
			'pconnect' => TRUE,
			'db_debug' => TRUE,
			'cache_on' => FALSE,
			'cachedir' => 'application/cache/database',
			'char_set' => 'utf8',
			'dbcollat' => 'utf8_general_ci',
			'swap_pre' => '',
			'autoinit' => TRUE,
			'stricton' => FALSE,
			
		);
		
		$array_db2[ 'hostname' ] = 'viaeletronica.com.br';
		$array_db2[ 'username' ] = 'corposempar';
		$array_db2[ 'password' ] = 'Frank18372955';
		$array_db2[ 'database' ] = 'zadmin_corposempar2';
		
		
		$DB1 = $this->load->database( $array_db1, TRUE );
		
		$DB1->select( '*' );
		$DB1->where( 'submit_form_id', 11 );
		
		$DB1->from( 'tb_submit_forms_us' );
		
		//print_r( $DB1->get()->result_array() );
		
		echo '<br/><br/><br/>---------------<br/><br/><br/>';
		
		$DB2 = $this->load->database( $array_db2, TRUE );
		
		$db_data = array();
		
		$DB2->from( 'tb_submit_forms_us' );
		$DB2->select('*');
		$DB2->where( 'submit_form_id', 11 );
		
		$result_db2 = $DB2->get()->result_array();
		
		foreach( $result_db2 as $key => $result ) {
			
			$_new = & $db_data[];
			
			$_new = $result;
			
			$_new[ 'data' ] = json_decode( $result[ 'data' ], TRUE );
			
			$_new[ 'data' ][ 'nome-completo' ] = isset( $_new[ 'data' ][ 'nome-completo' ] ) ? $this->str_utils->normalizarNome( $_new[ 'data' ][ 'nome-completo' ] ) : NULL;
			$_new[ 'data' ][ 'nome-do-pai' ] = isset( $_new[ 'data' ][ 'nome-do-pai' ] ) ? $this->str_utils->normalizarNome( $_new[ 'data' ][ 'nome-do-pai' ] ) : NULL;
			$_new[ 'data' ][ 'nome-da-mae' ] = isset( $_new[ 'data' ][ 'nome-da-mae' ] ) ? $this->str_utils->normalizarNome( $_new[ 'data' ][ 'nome-da-mae' ] ) : NULL;
			$_new[ 'data' ][ 'apelido' ] = isset( $_new[ 'data' ][ 'apelido' ] ) ? $this->str_utils->normalizarNome( $_new[ 'data' ][ 'apelido' ] ) : NULL;
			$_new[ 'data' ][ 'nome-completo-doa-parceiroa' ] = isset( $_new[ 'data' ][ 'nome-completo-doa-parceiroa' ] ) ? $this->str_utils->normalizarNome( $_new[ 'data' ][ 'nome-completo-doa-parceiroa' ] ) : NULL;
			$_new[ 'data' ][ 'nome-completo-do-conjuge' ] = isset( $_new[ 'data' ][ 'nome-completo-do-conjuge' ] ) ? $this->str_utils->normalizarNome( $_new[ 'data' ][ 'nome-completo-do-conjuge' ] ) : NULL;
			$_new[ 'data' ][ 'nome-do-aluno' ] = isset( $_new[ 'data' ][ 'nome-do-aluno' ] ) ? $this->str_utils->normalizarNome( $_new[ 'data' ][ 'nome-do-aluno' ] ) : NULL;
			$_new[ 'data' ][ 'e-mail' ] = strtolower( $_new[ 'data' ][ 'e-mail' ] );
			
			$_new[ 'data' ][ 'data-de-nascimento' ] = str_replace( '/', '-',  $_new[ 'data' ][ 'data-de-nascimento' ] );
			
			
			
			if ( strpos( $_new[ 'data' ][ 'data-de-nascimento' ], '/' ) === FALSE AND strpos( $_new[ 'data' ][ 'data-de-nascimento' ], '-' ) === FALSE ) {
				
				$_new[ 'data' ][ 'data-de-nascimento' ] = substr_replace( $_new[ 'data' ][ 'data-de-nascimento' ], '-', -4, 0);
				$_new[ 'data' ][ 'data-de-nascimento' ] = substr_replace( $_new[ 'data' ][ 'data-de-nascimento' ], '-', -7, 0);
				
				if ( strlen( $_new[ 'data' ][ 'data-de-nascimento' ] ) < 10 ) {
					
					$_new[ 'data' ][ 'data-de-nascimento' ] = '0' . $_new[ 'data' ][ 'data-de-nascimento' ];
					
				}
				
			}
			
			if ( strlen( $_new[ 'data' ][ 'data-de-nascimento' ] ) == 8 ) {
				
				$_new[ 'data' ][ 'data-de-nascimento' ] = substr_replace( $_new[ 'data' ][ 'data-de-nascimento' ], '19', -2, 0);
				
			}
			
			$_new[ 'data' ][ 'data-de-nascimento' ] = explode( '-', $_new[ 'data' ][ 'data-de-nascimento' ] );
			$_new[ 'data' ][ 'data-de-nascimento' ] = array_reverse( $_new[ 'data' ][ 'data-de-nascimento' ] );
			$_new[ 'data' ][ 'data-de-nascimento' ] = join( '-', $_new[ 'data' ][ 'data-de-nascimento' ] );
			
			if ( isset( $_new[ 'data' ][ 'estilo-da-turma' ] ) ) {
				
				switch ( $_new[ 'data' ][ 'estilo-da-turma' ] ) {
					
					case 'Calientes':
						
						$_new[ 'data' ][ 'estilo-da-turma' ] = 'Zouk';
						break;
						
					case 'Country':
						
						$_new[ 'data' ][ 'estilo-da-turma' ] = 'Sertanejo/Country';
						break;
						
					case 'Forró':
						
						$_new[ 'data' ][ 'estilo-da-turma' ] = 'Forró tradicional';
						break;
						
					
				}
				
			}
			
			
			$_new[ 'data' ][ 'como-conheceu-a-cped' ] = ( isset( $_new[ 'data' ][ 'como-conheceu-a-cped' ] ) AND $_new[ 'data' ][ 'como-conheceu-a-cped' ] ) ? $_new[ 'data' ][ 'como-conheceu-a-cped' ] : 'Outro meio';
			
			$_new[ 'xml_data' ] = $this->sfcm->us_json_data_to_xml( $_new[ 'data' ] );
			
			$_new[ 'data' ] = json_encode( $_new[ 'data' ] );
			
		}
		
		//echo '<pre>' . print_r( $db_data, TRUE ) . '</pre>'; exit;
		
		foreach( $db_data as $key => $result ) {
			
			$data = elements( array(
				
				'id',
				'submit_form_id',
				'submit_datetime',
				'mod_datetime',
				'data',
				'xml_data',
				
			), $result );
			
			echo '<pre>' . print_r( $data[ 'data' ], TRUE ) . '</pre>';
			
			if ( $data != NULL ){
				
				if ( $this->db->update( 'tb_submit_forms_us', $data, array( 'id' => $data[ 'id' ], ) ) ){
					
					echo '<h5>inserindo:</h5> ' . '<pre>' . print_r( $data, TRUE ) . '</pre>';
					// confirm update for controller
					echo '[ok]';
					
				}
				else {
					
					// case update fails, return false
					echo '[erro]';;
					
				}
				
			}
			
		}
		
	}
	
	public function importar_intensivos(){
		
		$this->load->model( 'common/submit_forms_common_model', 'sfcm' );
		
		$this->db->select( 'id, data' );
		$this->db->from( 'tb_submit_forms_us' );
		$users_submits = $this->db->get()->result_array();
		
		$db_data = array();
		
		
		$array_db1 = $array_db2 = array(
			
			'hostname' => 'localhost',
			'username' => 'root',
			'password' => '18372955',
			'database' => 'corposempar',
			'dbdriver' => 'mysql',
			'dbprefix' => '',
			'pconnect' => TRUE,
			'db_debug' => TRUE,
			'cache_on' => FALSE,
			'cachedir' => 'application/cache/database',
			'char_set' => 'utf8',
			'dbcollat' => 'utf8_general_ci',
			'swap_pre' => '',
			'autoinit' => TRUE,
			'stricton' => FALSE,
			
		);
		
		$array_db2[ 'hostname' ] = 'viaeletronica.com.br';
		$array_db2[ 'username' ] = 'corposempar';
		$array_db2[ 'password' ] = 'Frank18372955';
		$array_db2[ 'database' ] = 'zadmin_corposempar2';
		
		
		$DB1 = $this->load->database( $array_db1, TRUE );
		
		$DB1->select( '*' );
		$DB1->where( 'submit_form_id', 15 );
		
		$DB1->from( 'tb_submit_forms_us' );
		
		//print_r( $DB1->get()->result_array() );
		
		echo '<br/><br/><br/>---------------<br/><br/><br/>';
		
		$DB2 = $this->load->database( $array_db2, TRUE );
		
		$db_data = array();
		
		$DB2->from( 'tb_submit_forms_us' );
		$DB2->select('*');
		$DB2->where( 'submit_form_id', 15 );
		
		$result_db2 = $DB2->get()->result_array();
		
		foreach( $result_db2 as $key => $result ) {
			
			$_new = & $db_data[];
			
			$_new = $result;
			
			$_new[ 'data' ] = json_decode( $result[ 'data' ], TRUE );
			
			$_new[ 'data' ][ 'nome-completo' ] = isset( $_new[ 'data' ][ 'nome-completo' ] ) ? $this->str_utils->normalizarNome( $_new[ 'data' ][ 'nome-completo' ] ) : NULL;
			$_new[ 'data' ][ 'nome-do-pai' ] = isset( $_new[ 'data' ][ 'nome-do-pai' ] ) ? $this->str_utils->normalizarNome( $_new[ 'data' ][ 'nome-do-pai' ] ) : NULL;
			$_new[ 'data' ][ 'nome-da-mae' ] = isset( $_new[ 'data' ][ 'nome-da-mae' ] ) ? $this->str_utils->normalizarNome( $_new[ 'data' ][ 'nome-da-mae' ] ) : NULL;
			$_new[ 'data' ][ 'apelido' ] = isset( $_new[ 'data' ][ 'apelido' ] ) ? $this->str_utils->normalizarNome( $_new[ 'data' ][ 'apelido' ] ) : NULL;
			$_new[ 'data' ][ 'nome-completo-doa-parceiroa' ] = isset( $_new[ 'data' ][ 'nome-completo-doa-parceiroa' ] ) ? $this->str_utils->normalizarNome( $_new[ 'data' ][ 'nome-completo-doa-parceiroa' ] ) : NULL;
			$_new[ 'data' ][ 'nome-completo-do-conjuge' ] = isset( $_new[ 'data' ][ 'nome-completo-do-conjuge' ] ) ? $this->str_utils->normalizarNome( $_new[ 'data' ][ 'nome-completo-do-conjuge' ] ) : NULL;
			$_new[ 'data' ][ 'nome-do-aluno' ] = isset( $_new[ 'data' ][ 'nome-do-aluno' ] ) ? $this->str_utils->normalizarNome( $_new[ 'data' ][ 'nome-do-aluno' ] ) : NULL;
			$_new[ 'data' ][ 'e-mail' ] = strtolower( $_new[ 'data' ][ 'e-mail' ] );
			
			$_new[ 'data' ][ 'data-de-nascimento' ] = str_replace( '/', '-',  $_new[ 'data' ][ 'data-de-nascimento' ] );
			
			
			
			if ( strpos( $_new[ 'data' ][ 'data-de-nascimento' ], '/' ) === FALSE AND strpos( $_new[ 'data' ][ 'data-de-nascimento' ], '-' ) === FALSE ) {
				
				$_new[ 'data' ][ 'data-de-nascimento' ] = substr_replace( $_new[ 'data' ][ 'data-de-nascimento' ], '-', -4, 0);
				$_new[ 'data' ][ 'data-de-nascimento' ] = substr_replace( $_new[ 'data' ][ 'data-de-nascimento' ], '-', -7, 0);
				
				if ( strlen( $_new[ 'data' ][ 'data-de-nascimento' ] ) < 10 ) {
					
					$_new[ 'data' ][ 'data-de-nascimento' ] = '0' . $_new[ 'data' ][ 'data-de-nascimento' ];
					
				}
				
			}
			
			if ( strlen( $_new[ 'data' ][ 'data-de-nascimento' ] ) == 8 ) {
				
				$_new[ 'data' ][ 'data-de-nascimento' ] = substr_replace( $_new[ 'data' ][ 'data-de-nascimento' ], '19', -2, 0);
				
			}
			
			$_new[ 'data' ][ 'data-de-nascimento' ] = explode( '-', $_new[ 'data' ][ 'data-de-nascimento' ] );
			$_new[ 'data' ][ 'data-de-nascimento' ] = array_reverse( $_new[ 'data' ][ 'data-de-nascimento' ] );
			$_new[ 'data' ][ 'data-de-nascimento' ] = join( '-', $_new[ 'data' ][ 'data-de-nascimento' ] );
			
			if ( isset( $_new[ 'data' ][ 'estilo-da-turma' ] ) ) {
				
				switch ( $_new[ 'data' ][ 'estilo-da-turma' ] ) {
					
					case 'Calientes':
						
						$_new[ 'data' ][ 'estilo-da-turma' ] = 'Zouk';
						break;
						
					case 'Country':
						
						$_new[ 'data' ][ 'estilo-da-turma' ] = 'Sertanejo/Country';
						break;
						
					case 'Forró':
						
						$_new[ 'data' ][ 'estilo-da-turma' ] = 'Forró tradicional';
						break;
						
					
				}
				
			}
			
			
			$_new[ 'data' ][ 'como-conheceu-a-cped' ] = ( isset( $_new[ 'data' ][ 'como-conheceu-a-cped' ] ) AND $_new[ 'data' ][ 'como-conheceu-a-cped' ] ) ? $_new[ 'data' ][ 'como-conheceu-a-cped' ] : 'Outro meio';
			
			$_new[ 'xml_data' ] = $this->sfcm->us_json_data_to_xml( $_new[ 'data' ] );
			
			$_new[ 'data' ] = json_encode( $_new[ 'data' ] );
			
		}
		
		//echo '<pre>' . print_r( $db_data, TRUE ) . '</pre>'; exit;
		
		foreach( $db_data as $key => $result ) {
			
			$data = elements( array(
				
				'id',
				'submit_form_id',
				'submit_datetime',
				'mod_datetime',
				'data',
				'xml_data',
				
			), $result );
			
			echo '<pre>' . print_r( $data[ 'data' ], TRUE ) . '</pre>';
			
			if ( $data != NULL ){
				
				if ( $this->db->update( 'tb_submit_forms_us', $data, array( 'id' => $data[ 'id' ], ) ) ){
					
					echo '<h5>inserindo:</h5> ' . '<pre>' . print_r( $data, TRUE ) . '</pre>';
					// confirm update for controller
					echo '[ok]';
					
				}
				else {
					
					// case update fails, return false
					echo '[erro]';;
					
				}
				
			}
			
		}
		
	}
	
	public function importar_contato(){
		
		$this->load->model( 'common/submit_forms_common_model', 'sfcm' );
		
		$this->db->select( 'id, data' );
		$this->db->from( 'tb_submit_forms_us' );
		$users_submits = $this->db->get()->result_array();
		
		$db_data = array();
		
		
		$array_db1 = $array_db2 = array(
			
			'hostname' => 'localhost',
			'username' => 'root',
			'password' => '18372955',
			'database' => 'corposempar',
			'dbdriver' => 'mysql',
			'dbprefix' => '',
			'pconnect' => TRUE,
			'db_debug' => TRUE,
			'cache_on' => FALSE,
			'cachedir' => 'application/cache/database',
			'char_set' => 'utf8',
			'dbcollat' => 'utf8_general_ci',
			'swap_pre' => '',
			'autoinit' => TRUE,
			'stricton' => FALSE,
			
		);
		
		$array_db2[ 'hostname' ] = 'viaeletronica.com.br';
		$array_db2[ 'username' ] = 'corposempar';
		$array_db2[ 'password' ] = 'Frank18372955';
		$array_db2[ 'database' ] = 'zadmin_corposempar2';
		
		
		$DB1 = $this->load->database( $array_db1, TRUE );
		
		$DB1->select( '*' );
		$DB1->where( 'submit_form_id', 14 );
		
		$DB1->from( 'tb_submit_forms_us' );
		
		//print_r( $DB1->get()->result_array() );
		
		echo '<br/><br/><br/>---------------<br/><br/><br/>';
		
		$DB2 = $this->load->database( $array_db2, TRUE );
		
		$db_data = array();
		
		$DB2->from( 'tb_submit_forms_us' );
		$DB2->select('*');
		$DB2->where( 'submit_form_id', 14 );
		
		$result_db2 = $DB2->get()->result_array();
		
		foreach( $result_db2 as $key => $result ) {
			
			$_new = & $db_data[];
			
			$_new = $result;
			
			$_new[ 'data' ] = json_decode( $result[ 'data' ], TRUE );
			
			$_new[ 'data' ][ 'nome-completo' ] = isset( $_new[ 'data' ][ 'nome-completo' ] ) ? $this->str_utils->normalizarNome( $_new[ 'data' ][ 'nome-completo' ] ) : NULL;
			$_new[ 'data' ][ 'e-mail' ] = strtolower( $_new[ 'data' ][ 'e-mail' ] );
			
			$_new[ 'xml_data' ] = $this->sfcm->us_json_data_to_xml( $_new[ 'data' ] );
			
			$_new[ 'data' ] = json_encode( $_new[ 'data' ] );
			
		}
		
		//echo '<pre>' . print_r( $db_data, TRUE ) . '</pre>'; exit;
		
		foreach( $db_data as $key => $result ) {
			
			$data = elements( array(
				
				'id',
				'submit_form_id',
				'submit_datetime',
				'mod_datetime',
				'data',
				'xml_data',
				
			), $result );
			
			echo '<pre>' . print_r( $data[ 'data' ], TRUE ) . '</pre>';
			
			if ( $data != NULL ){
				
				if ( $this->db->update( 'tb_submit_forms_us', $data, array( 'id' => $data[ 'id' ], ) ) ){
					
					echo '<h5>inserindo:</h5> ' . '<pre>' . print_r( $data, TRUE ) . '</pre>';
					// confirm update for controller
					echo '[ok]';
					
				}
				else {
					
					// case update fails, return false
					echo '[erro]';;
					
				}
				
			}
			
		}
		
	}
	
	public function importar_shows(){
		
		$this->load->model( 'common/submit_forms_common_model', 'sfcm' );
		
		$this->db->select( 'id, data' );
		$this->db->from( 'tb_submit_forms_us' );
		$users_submits = $this->db->get()->result_array();
		
		$db_data = array();
		
		
		$array_db1 = $array_db2 = array(
			
			'hostname' => 'localhost',
			'username' => 'root',
			'password' => '18372955',
			'database' => 'corposempar',
			'dbdriver' => 'mysql',
			'dbprefix' => '',
			'pconnect' => TRUE,
			'db_debug' => TRUE,
			'cache_on' => FALSE,
			'cachedir' => 'application/cache/database',
			'char_set' => 'utf8',
			'dbcollat' => 'utf8_general_ci',
			'swap_pre' => '',
			'autoinit' => TRUE,
			'stricton' => FALSE,
			
		);
		
		$array_db2[ 'hostname' ] = 'viaeletronica.com.br';
		$array_db2[ 'username' ] = 'corposempar';
		$array_db2[ 'password' ] = 'Frank18372955';
		$array_db2[ 'database' ] = 'zadmin_corposempar2';
		
		
		$DB1 = $this->load->database( $array_db1, TRUE );
		
		$DB1->select( '*' );
		$DB1->where( 'submit_form_id', 8 );
		
		$DB1->from( 'tb_submit_forms_us' );
		
		//print_r( $DB1->get()->result_array() );
		
		echo '<br/><br/><br/>---------------<br/><br/><br/>';
		
		$DB2 = $this->load->database( $array_db2, TRUE );
		
		$db_data = array();
		
		$DB2->from( 'tb_submit_forms_us' );
		$DB2->select('*');
		$DB2->where( 'submit_form_id', 8 );
		
		$result_db2 = $DB2->get()->result_array();
		
		foreach( $result_db2 as $key => $result ) {
			
			$_new = & $db_data[];
			
			$_new = $result;
			
			$_new[ 'data' ] = json_decode( $result[ 'data' ], TRUE );
			
			$_new[ 'data' ][ 'nome-completo' ] = isset( $_new[ 'data' ][ 'nome-completo' ] ) ? $this->str_utils->normalizarNome( $_new[ 'data' ][ 'nome-completo' ] ) : NULL;
			$_new[ 'data' ][ 'e-mail' ] = strtolower( $_new[ 'data' ][ 'e-mail' ] );
			
			$_new[ 'data' ][ 'data-do-evento' ] = str_replace( '/', '-',  $_new[ 'data' ][ 'data-do-evento' ] );
			
			if ( strpos( $_new[ 'data' ][ 'data-do-evento' ], '/' ) === FALSE AND strpos( $_new[ 'data' ][ 'data-do-evento' ], '-' ) === FALSE ) {
				
				$_new[ 'data' ][ 'data-do-evento' ] = substr_replace( $_new[ 'data' ][ 'data-do-evento' ], '-', -4, 0);
				$_new[ 'data' ][ 'data-do-evento' ] = substr_replace( $_new[ 'data' ][ 'data-do-evento' ], '-', -7, 0);
				
				if ( strlen( $_new[ 'data' ][ 'data-do-evento' ] ) < 10 ) {
					
					$_new[ 'data' ][ 'data-do-evento' ] = '0' . $_new[ 'data' ][ 'data-do-evento' ];
					
				}
				
			}
			
			if ( strlen( $_new[ 'data' ][ 'data-do-evento' ] ) == 8 ) {
				
				$_new[ 'data' ][ 'data-do-evento' ] = substr_replace( $_new[ 'data' ][ 'data-do-evento' ], '19', -2, 0);
				
			}
			
			$_new[ 'data' ][ 'data-do-evento' ] = explode( '-', $_new[ 'data' ][ 'data-do-evento' ] );
			$_new[ 'data' ][ 'data-do-evento' ] = array_reverse( $_new[ 'data' ][ 'data-do-evento' ] );
			$_new[ 'data' ][ 'data-do-evento' ] = join( '-', $_new[ 'data' ][ 'data-do-evento' ] );
			
			$_new[ 'xml_data' ] = $this->sfcm->us_json_data_to_xml( $_new[ 'data' ] );
			
			$_new[ 'data' ] = json_encode( $_new[ 'data' ] );
			
		}
		
		//echo '<pre>' . print_r( $db_data, TRUE ) . '</pre>'; exit;
		
		foreach( $db_data as $key => $result ) {
			
			$data = elements( array(
				
				'id',
				'submit_form_id',
				'submit_datetime',
				'mod_datetime',
				'data',
				'xml_data',
				
			), $result );
			
			echo '<pre>' . print_r( $data[ 'data' ], TRUE ) . '</pre>';
			
			if ( $data != NULL ){
				
				if ( $this->db->update( 'tb_submit_forms_us', $data, array( 'id' => $data[ 'id' ], ) ) ){
					
					echo '<h5>inserindo:</h5> ' . '<pre>' . print_r( $data, TRUE ) . '</pre>';
					// confirm update for controller
					echo '[ok]';
					
				}
				else {
					
					// case update fails, return false
					echo '[erro]';;
					
				}
				
			}
			
		}
		
	}
	
	public function importar_coreografias(){
		
		$this->load->model( 'common/submit_forms_common_model', 'sfcm' );
		
		$this->db->select( 'id, data' );
		$this->db->from( 'tb_submit_forms_us' );
		$users_submits = $this->db->get()->result_array();
		
		$db_data = array();
		
		
		$array_db1 = $array_db2 = array(
			
			'hostname' => 'localhost',
			'username' => 'root',
			'password' => '18372955',
			'database' => 'corposempar',
			'dbdriver' => 'mysql',
			'dbprefix' => '',
			'pconnect' => TRUE,
			'db_debug' => TRUE,
			'cache_on' => FALSE,
			'cachedir' => 'application/cache/database',
			'char_set' => 'utf8',
			'dbcollat' => 'utf8_general_ci',
			'swap_pre' => '',
			'autoinit' => TRUE,
			'stricton' => FALSE,
			
		);
		
		$array_db2[ 'hostname' ] = 'viaeletronica.com.br';
		$array_db2[ 'username' ] = 'corposempar';
		$array_db2[ 'password' ] = 'Frank18372955';
		$array_db2[ 'database' ] = 'zadmin_corposempar2';
		
		
		$DB1 = $this->load->database( $array_db1, TRUE );
		
		$DB1->select( '*' );
		$DB1->where( 'submit_form_id', 20 );
		
		$DB1->from( 'tb_submit_forms_us' );
		
		//print_r( $DB1->get()->result_array() );
		
		echo '<br/><br/><br/>---------------<br/><br/><br/>';
		
		$DB2 = $this->load->database( $array_db2, TRUE );
		
		$db_data = array();
		
		$DB2->from( 'tb_submit_forms_us' );
		$DB2->select('*');
		$DB2->where( 'submit_form_id', 20 );
		
		$result_db2 = $DB2->get()->result_array();
		
		foreach( $result_db2 as $key => $result ) {
			
			$_new = & $db_data[];
			
			$_new = $result;
			
			$_new[ 'data' ] = json_decode( $result[ 'data' ], TRUE );
			
			$_new[ 'data' ][ 'nome-completo' ] = isset( $_new[ 'data' ][ 'nome-completo' ] ) ? $this->str_utils->normalizarNome( $_new[ 'data' ][ 'nome-completo' ] ) : NULL;
			$_new[ 'data' ][ 'e-mail' ] = strtolower( $_new[ 'data' ][ 'e-mail' ] );
			
			$_new[ 'data' ][ 'data-do-evento' ] = str_replace( '/', '-',  $_new[ 'data' ][ 'data-do-evento' ] );
			
			if ( strpos( $_new[ 'data' ][ 'data-do-evento' ], '/' ) === FALSE AND strpos( $_new[ 'data' ][ 'data-do-evento' ], '-' ) === FALSE ) {
				
				$_new[ 'data' ][ 'data-do-evento' ] = substr_replace( $_new[ 'data' ][ 'data-do-evento' ], '-', -4, 0);
				$_new[ 'data' ][ 'data-do-evento' ] = substr_replace( $_new[ 'data' ][ 'data-do-evento' ], '-', -7, 0);
				
				if ( strlen( $_new[ 'data' ][ 'data-do-evento' ] ) < 10 ) {
					
					$_new[ 'data' ][ 'data-do-evento' ] = '0' . $_new[ 'data' ][ 'data-do-evento' ];
					
				}
				
			}
			
			if ( strlen( $_new[ 'data' ][ 'data-do-evento' ] ) == 8 ) {
				
				$_new[ 'data' ][ 'data-do-evento' ] = substr_replace( $_new[ 'data' ][ 'data-do-evento' ], '19', -2, 0);
				
			}
			
			$_new[ 'data' ][ 'data-do-evento' ] = explode( '-', $_new[ 'data' ][ 'data-do-evento' ] );
			$_new[ 'data' ][ 'data-do-evento' ] = array_reverse( $_new[ 'data' ][ 'data-do-evento' ] );
			$_new[ 'data' ][ 'data-do-evento' ] = join( '-', $_new[ 'data' ][ 'data-do-evento' ] );
			
			$_new[ 'xml_data' ] = $this->sfcm->us_json_data_to_xml( $_new[ 'data' ] );
			
			$_new[ 'data' ] = json_encode( $_new[ 'data' ] );
			
		}
		
		//echo '<pre>' . print_r( $db_data, TRUE ) . '</pre>'; exit;
		
		foreach( $db_data as $key => $result ) {
			
			$data = elements( array(
				
				'id',
				'submit_form_id',
				'submit_datetime',
				'mod_datetime',
				'data',
				'xml_data',
				
			), $result );
			
			echo '<pre>' . print_r( $data[ 'data' ], TRUE ) . '</pre>';
			
			if ( $data != NULL ){
				
				if ( $this->db->update( 'tb_submit_forms_us', $data, array( 'id' => $data[ 'id' ], ) ) ){
					
					echo '<h5>inserindo:</h5> ' . '<pre>' . print_r( $data, TRUE ) . '</pre>';
					// confirm update for controller
					echo '[ok]';
					
				}
				else {
					
					// case update fails, return false
					echo '[erro]';;
					
				}
				
			}
			
		}
		
	}
	
	public function importar_biblioteca(){
		
		$this->load->model( 'common/submit_forms_common_model', 'sfcm' );
		
		$this->db->select( 'id, data' );
		$this->db->from( 'tb_submit_forms_us' );
		$users_submits = $this->db->get()->result_array();
		
		$db_data = array();
		
		
		$array_db1 = $array_db2 = array(
			
			'hostname' => 'localhost',
			'username' => 'root',
			'password' => '18372955',
			'database' => 'equoterapia_old3',
			'dbdriver' => 'mysql',
			'dbprefix' => '',
			'pconnect' => TRUE,
			'db_debug' => TRUE,
			'cache_on' => FALSE,
			'cachedir' => 'application/cache/database',
			'char_set' => 'utf8',
			'dbcollat' => 'utf8_general_ci',
			'swap_pre' => '',
			'autoinit' => TRUE,
			'stricton' => FALSE,
			
		);
		
		$array_db2[ 'database' ] = 'ande_final';
		
		
		$DB1 = $this->load->database( $array_db1, TRUE );
		
		$DB1->from( 'trabalhos' );
		$DB1->select( '*' );
		
		$result_db1 = $DB1->get()->result_array();
		
 		echo '<pre>' . print_r( $result_db1, TRUE ) . '</pre>';
		
// 		echo '<br/><br/><br/>---------------<br/><br/><br/>';
		
		$DB2 = $this->load->database( $array_db2, TRUE );
		
		$db_data = array();
		
		$DB2->from( 'tb_submit_forms_us' );
		$DB2->select('*');
// 		$DB2->where( 'submit_form_id', 20 );
		
// 		$result_db2 = $DB2->get()->result_array();
		
// 		echo '<pre>' . print_r( $f_params, TRUE ) . '</pre>'; exit;
		
		foreach( $result_db1 as $key => $result ) {
			
			$_new = & $db_data[];
			
 			echo '<pre>' . print_r( $result[ 'autor01' ], TRUE ) . '</pre>';
			
			$_new[ 'autores' ] = preg_split( "/(\\se\\s|,|\\/|;)/", $result[ 'autor01' ] );
			
			
			
			/*
			$_new = & $db_data[];
			
			$_new = $result;
			
			$_new[ 'data' ] = json_decode( $result[ 'data' ], TRUE );
			
			$_new[ 'data' ][ 'nome-completo' ] = isset( $_new[ 'data' ][ 'nome-completo' ] ) ? $this->str_utils->normalizarNome( $_new[ 'data' ][ 'nome-completo' ] ) : NULL;
			$_new[ 'data' ][ 'e-mail' ] = strtolower( $_new[ 'data' ][ 'e-mail' ] );
			
			$_new[ 'data' ][ 'data-do-evento' ] = str_replace( '/', '-',  $_new[ 'data' ][ 'data-do-evento' ] );
			
			if ( strpos( $_new[ 'data' ][ 'data-do-evento' ], '/' ) === FALSE AND strpos( $_new[ 'data' ][ 'data-do-evento' ], '-' ) === FALSE ) {
				
				$_new[ 'data' ][ 'data-do-evento' ] = substr_replace( $_new[ 'data' ][ 'data-do-evento' ], '-', -4, 0);
				$_new[ 'data' ][ 'data-do-evento' ] = substr_replace( $_new[ 'data' ][ 'data-do-evento' ], '-', -7, 0);
				
				if ( strlen( $_new[ 'data' ][ 'data-do-evento' ] ) < 10 ) {
					
					$_new[ 'data' ][ 'data-do-evento' ] = '0' . $_new[ 'data' ][ 'data-do-evento' ];
					
				}
				
			}
			
			if ( strlen( $_new[ 'data' ][ 'data-do-evento' ] ) == 8 ) {
				
				$_new[ 'data' ][ 'data-do-evento' ] = substr_replace( $_new[ 'data' ][ 'data-do-evento' ], '19', -2, 0);
				
			}
			
			$_new[ 'data' ][ 'data-do-evento' ] = explode( '-', $_new[ 'data' ][ 'data-do-evento' ] );
			$_new[ 'data' ][ 'data-do-evento' ] = array_reverse( $_new[ 'data' ][ 'data-do-evento' ] );
			$_new[ 'data' ][ 'data-do-evento' ] = join( '-', $_new[ 'data' ][ 'data-do-evento' ] );
			
			$_new[ 'xml_data' ] = $this->sfcm->us_json_data_to_xml( $_new[ 'data' ] );
			
			$_new[ 'data' ] = json_encode( $_new[ 'data' ] );
			*/
		}
		
 		echo '<pre>' . print_r( $db_data, TRUE ) . '</pre>'; exit;
		
		exit;
		//echo '<pre>' . print_r( $db_data, TRUE ) . '</pre>'; exit;
		
		foreach( $db_data as $key => $result ) {
			
			$data = elements( array(
				
				'id',
				'submit_form_id',
				'submit_datetime',
				'mod_datetime',
				'data',
				'xml_data',
				
			), $result );
			
			echo '<pre>' . print_r( $data[ 'data' ], TRUE ) . '</pre>';
			
			if ( $data != NULL ){
				
				if ( $this->db->update( 'tb_submit_forms_us', $data, array( 'id' => $data[ 'id' ], ) ) ){
					
					echo '<h5>inserindo:</h5> ' . '<pre>' . print_r( $data, TRUE ) . '</pre>';
					// confirm update for controller
					echo '[ok]';
					
				}
				else {
					
					// case update fails, return false
					echo '[erro]';;
					
				}
				
			}
			
		}
		
	}
	
	public function importar_autores(){
		
		$this->load->model( 'common/submit_forms_common_model', 'sfcm' );
		
		$this->db->select( 'id, data' );
		$this->db->from( 'tb_submit_forms_us' );
		$users_submits = $this->db->get()->result_array();
		
		$db_data = array();
		
		
		$array_db1 = $array_db2 = array(
			
			'hostname' => 'localhost',
			'username' => 'root',
			'password' => '18372955',
			'database' => 'equoterapia_old3',
			'dbdriver' => 'mysql',
			'dbprefix' => '',
			'pconnect' => TRUE,
			'db_debug' => TRUE,
			'cache_on' => FALSE,
			'cachedir' => 'application/cache/database',
			'char_set' => 'utf8',
			'dbcollat' => 'utf8_general_ci',
			'swap_pre' => '',
			'autoinit' => TRUE,
			'stricton' => FALSE,
			
		);
		
		$array_db2[ 'database' ] = 'ande_final';
		
		
		$DB1 = $this->load->database( $array_db1, TRUE );
		
		$DB1->from( 'trabalhos' );
		$DB1->select( '*' );
		
		$result_db1 = $DB1->get()->result_array();
		
//  		echo '<pre>' . print_r( $result_db1, TRUE ) . '</pre>';
		
// 		echo '<br/><br/><br/>---------------<br/><br/><br/>';
		
		$DB2 = $this->load->database( $array_db2, TRUE );
		
		$db_data = array();
		$autores = array();
		
// 		echo '<pre>' . print_r( $f_params, TRUE ) . '</pre>'; exit;
		
		// capturando os autores
		
		foreach( $result_db1 as $key => $result ) {
			
			$_new = & $autores[];
			
//  			echo '<pre>' . print_r( $result[ 'autor01' ], TRUE ) . '</pre>';
			
			$_new = preg_split( "/(\\se\\s|,|\\/|;)/", $result[ 'autor01' ] );
			$_new = array_merge( $_new, preg_split( "/(\\se\\s|,|\\/|;)/", $result[ 'autor02' ] ) );
			$_new = array_merge( $_new, preg_split( "/(\\se\\s|,|\\/|;)/", $result[ 'autor03' ] ) );
			$_new = array_merge( $_new, preg_split( "/(\\se\\s|,|\\/|;)/", $result[ 'autor04' ] ) );
			
			$_new = array_merge( $_new, preg_split( "/(\\se\\s|,|\\/|;)/", $result[ 'coautor01' ] ) );
			$_new = array_merge( $_new, preg_split( "/(\\se\\s|,|\\/|;)/", $result[ 'coautor02' ] ) );
			$_new = array_merge( $_new, preg_split( "/(\\se\\s|,|\\/|;)/", $result[ 'coautor03' ] ) );
			$_new = array_merge( $_new, preg_split( "/(\\se\\s|,|\\/|;)/", $result[ 'coautor04' ] ) );
			
			foreach( $_new as $key => & $autor ) {
				
				$find = array(
					
					'Teste',
					'Colaboradores: ',
					'COAUTOR 01',
					'COAUTOR 02',
					'COAUTOR 03',
					'COAUTOR 04',
					'AUTOR 01',
					'AUTOR 02',
					'AUTOR 03',
					'AUTOR 04',
					
				);
				$replace = '';
				
				$autor = $this->str_utils->normalizarNome( trim( str_replace( $find, $replace, $autor ) ) );
				
				$autor = $this->str_utils->normalizarNome( trim( str_replace( $find, $replace, $autor ) ) );
				
				if ( ! $autor ) unset( $_new[ $key ] );
				
			}
			
			if ( empty( $_new ) ) {
				
				unset( $_new );
				
				continue;
				
			}
			
		}
		
		$_autores = array();
		
		foreach( $autores as $key => $autor ) {
			
			foreach( $autor as $k => $v ) {
				
				$_autores[] = $v;
				
			}
			
		}
		
		$autores = $_autores;
		
		foreach( $autores as $autor ) {
			
			$_new = array();
			
			$_new[ 'submit_form_id' ] = '28';
			$_new[ 'submit_datetime' ] = '2015-12-10 12:02:38';
			$_new[ 'mod_datetime' ] = '2015-12-10 12:02:38';
			$_new[ 'data' ][ 'nome-completo' ] = $autor;
			
			$_new[ 'xml_data' ] = $this->sfcm->us_json_data_to_xml( $_new[ 'data' ] );
			
			$_new[ 'data' ] = json_encode( $_new[ 'data' ] );
			
			$DB2->from( 'tb_submit_forms_us' );
			$DB2->select('*');
			$DB2->where( 'submit_form_id', 28 );
			$DB2->where( 'LOWER( `data` ) LIKE \'%"nome-completo":"%' . strtolower( rtrim( ltrim( str_replace( '\\', '\\\\\\\\', json_encode( $autor ) ), '"' ), '"' ) ) . '%"%\' COLLATE \'utf8_general_ci\' ' );
			echo '<pre><b>' . '{"nome-completo":' . json_encode( $autor ) . '}' . '</b></pre>';
			
			$result_db2 = $DB2->get()->result_array();
			
			if ( ! empty( $result_db2 ) ){
				
				echo '<b>' . $autor . '</b><h5>já existe:</h5> ' . '<pre>' . print_r( $result_db2, TRUE ) . '</pre>';
				echo '[ok]<br/>';
				
			}
			else if ( $this->db->insert( 'tb_submit_forms_us', $_new ) ){
				
				echo '<h5>inserindo:</h5> ' . '<pre>' . print_r( $_new, TRUE ) . '</pre>';
				echo '[ok]<br/>';
				
			}
			else {
				
				echo '[erro]<br/>';;
				
			}
			
		}
		
 		echo '<pre>' . print_r( $db_data, TRUE ) . '</pre>';
		
		//echo '<pre>' . print_r( $db_data, TRUE ) . '</pre>'; exit;
		
 		$this->output->enable_profiler( TRUE );
	}
	
	public function importar_areas_de_atuacao(){
		
		$this->load->model( 'common/submit_forms_common_model', 'sfcm' );
		
		$this->db->select( 'id, data' );
		$this->db->from( 'tb_submit_forms_us' );
		$users_submits = $this->db->get()->result_array();
		
		$db_data = array();
		
		
		$array_db1 = $array_db2 = array(
			
			'hostname' => 'localhost',
			'username' => 'root',
			'password' => '18372955',
			'database' => 'equoterapia_old3',
			'dbdriver' => 'mysql',
			'dbprefix' => '',
			'pconnect' => TRUE,
			'db_debug' => TRUE,
			'cache_on' => FALSE,
			'cachedir' => 'application/cache/database',
			'char_set' => 'utf8',
			'dbcollat' => 'utf8_general_ci',
			'swap_pre' => '',
			'autoinit' => TRUE,
			'stricton' => FALSE,
			
		);
		
		$array_db2[ 'database' ] = 'ande_final';
		
		
		$DB1 = $this->load->database( $array_db1, TRUE );
		
		$DB1->from( 'trabalhos' );
		$DB1->select( '*' );
		
		$result_db1 = $DB1->get()->result_array();
		
//  		echo '<pre>' . print_r( $result_db1, TRUE ) . '</pre>';
		
// 		echo '<br/><br/><br/>---------------<br/><br/><br/>';
		
		$DB2 = $this->load->database( $array_db2, TRUE );
		
		$db_data = array();
		$areas = array();
		
// 		echo '<pre>' . print_r( $f_params, TRUE ) . '</pre>'; exit;
		
		// capturando os autores
		
		foreach( $result_db1 as $key => $result ) {
			
			$_new = & $areas[];
			
//  			echo '<pre>' . print_r( $result[ 'autor01' ], TRUE ) . '</pre>';
			
			$_new = preg_split( "/(\\se\\s|,|;)/", $result[ 'area' ] );
			
			foreach( $_new as $key => & $area ) {
				
				$find = array( 'Vídeo Documentario', 'Cavalo/' );
				$replace = '';
				
				$area = $this->str_utils->normalizarNome( trim( str_replace( $find, $replace, $area ) ) );
				
				$area = $this->str_utils->normalizarNome( trim( str_replace( $find, $replace, $area ) ) );
				
				if ( ! $area ) unset( $_new[ $key ] );
				
			}
			
			if ( empty( $_new ) ) {
				
				unset( $_new );
				
				continue;
				
			}
			
		}
		
		$_autores = array();
		
		foreach( $areas as $key => $area ) {
			
			foreach( $area as $k => $v ) {
				
				$_autores[] = $v;
				
			}
			
		}
		
		$areas = $_autores;
		
 		echo '<pre>' . print_r( $areas, TRUE ) . '</pre>';
		
		foreach( $areas as $area ) {
			
			$_new = array();
			
			$_new[ 'submit_form_id' ] = '15';
			$_new[ 'submit_datetime' ] = '2015-12-10 12:02:38';
			$_new[ 'mod_datetime' ] = '2015-12-10 12:02:38';
			$_new[ 'data' ][ 'area-de-atuacao' ] = $area;
			
			$_new[ 'xml_data' ] = $this->sfcm->us_json_data_to_xml( $_new[ 'data' ] );
			
			$_new[ 'data' ] = json_encode( $_new[ 'data' ] );
			
			$DB2->from( 'tb_submit_forms_us' );
			$DB2->select('*');
			$DB2->where( 'submit_form_id', 15 );
			$DB2->where( 'LOWER( `data` ) LIKE \'%"%":"%' . strtolower( rtrim( ltrim( str_replace( '\\', '\\\\\\\\', json_encode( $area ) ), '"' ), '"' ) ) . '%"%\' COLLATE \'utf8_general_ci\' ' );
			echo '<pre><b>' . '{"area-de-atuacao":' . json_encode( $area ) . '</b></pre>';
			
			$result_db2 = $DB2->get()->result_array();
			
			if ( ! empty( $result_db2 ) ){
				
				echo '<b>' . $area . '</b><h5>já existe:</h5> ' . '<pre>' . print_r( $result_db2, TRUE ) . '</pre>';
				echo '[ok]<br/>';
				
			}
			else if ( $this->db->insert( 'tb_submit_forms_us', $_new ) ){
				
				echo '<h5>inserindo:</h5> ' . '<pre>' . print_r( $_new, TRUE ) . '</pre>';
				echo '[ok]<br/>';
				
			}
			else {
				
				echo '<h5>inserindo:</h5> ' . '<pre>' . print_r( $_new, TRUE ) . '</pre>';
				echo '[ok]<br/>';
				
			}
			
		}
		
 		echo '<pre>' . print_r( $db_data, TRUE ) . '</pre>';
		
		//echo '<pre>' . print_r( $db_data, TRUE ) . '</pre>'; exit;
		
 		$this->output->enable_profiler( TRUE );
	}
	
	public function importar_artigos_academicos(){
		
		$this->load->model( 'common/submit_forms_common_model', 'sfcm' );
		
		$this->db->select( 'id, data' );
		$this->db->from( 'tb_submit_forms_us' );
		$users_submits = $this->db->get()->result_array();
		
		$db_data = array();
		
		
		$array_db1 = $array_db2 = array(
			
			'hostname' => 'localhost',
			'username' => 'root',
			'password' => '18372955',
			'database' => 'equoterapia_old3',
			'dbdriver' => 'mysql',
			'dbprefix' => '',
			'pconnect' => TRUE,
			'db_debug' => TRUE,
			'cache_on' => FALSE,
			'cachedir' => 'application/cache/database',
			'char_set' => 'utf8',
			'dbcollat' => 'utf8_general_ci',
			'swap_pre' => '',
			'autoinit' => TRUE,
			'stricton' => FALSE,
			
		);
		
		$array_db2[ 'database' ] = 'ande_final';
		
		
		$DB1 = $this->load->database( $array_db1, TRUE );
		
		$DB1->from( 'trabalhos' );
		$DB1->select( '*' );
		
		$result_db1 = $DB1->get()->result_array();
		
 		echo '<pre>' . print_r( $result_db1, TRUE ) . '</pre>';
		
// 		echo '<br/><br/><br/>---------------<br/><br/><br/>';
		
		$DB2 = $this->load->database( $array_db2, TRUE );
		
		$db_data = array();
		$artigos = array();
		$autores = array();
		
// 		echo '<pre>' . print_r( $f_params, TRUE ) . '</pre>'; exit;
		
		foreach( $result_db1 as $key => $artigo ) {
			
			$_new = & $artigos[];
			
			$_new[ 'submit_form_id' ] = '29';
			$_new[ 'submit_datetime' ] = $artigo[ 'data' ] . ' 12:02:38';
			$_new[ 'mod_datetime' ] = $artigo[ 'data' ] . ' 12:02:38';
			
			// imagem
			
			$_new[ 'data' ][ 'imagem' ] = '';
			
			// titulo
			
			$_new[ 'data' ][ 'titulo' ] = $this->str_utils->normalizarNome( $artigo[ 'titulo' ] );
			
			// ano-realizacao
			
			$_new[ 'data' ][ 'ano-realizacao' ] = ( ! empty( $artigo[ 'ano' ] ) ? $artigo[ 'ano' ] : '0000' ) . '-00-00';
			
			// autores
			
			$_autores = array();
			
			$_autores = preg_split( "/(\\se\\s|,|\\/|;)/", $artigo[ 'autor01' ] );
			$_autores = array_merge( $_autores, preg_split( "/(\\se\\s|,|\\/|;)/", $artigo[ 'autor02' ] ) );
			$_autores = array_merge( $_autores, preg_split( "/(\\se\\s|,|\\/|;)/", $artigo[ 'autor03' ] ) );
			$_autores = array_merge( $_autores, preg_split( "/(\\se\\s|,|\\/|;)/", $artigo[ 'autor04' ] ) );
			
			$autores = array();
			
			foreach( $_autores as $key => & $autor ) {
				
				$find = array(
					
					'Teste',
					'Colaboradores: ',
					'COAUTOR 01',
					'COAUTOR 02',
					'COAUTOR 03',
					'COAUTOR 04',
					'AUTOR 01',
					'AUTOR 02',
					'AUTOR 03',
					'AUTOR 04',
					
				);
				$replace = '';
				
				$autor = $this->str_utils->normalizarNome( trim( str_replace( $find, $replace, $autor ) ) );
				
				$autor = $this->str_utils->normalizarNome( trim( str_replace( $find, $replace, $autor ) ) );
				
				if ( ! $autor ) {
					
					continue;
					
				}
				
				$DB2->from( 'tb_submit_forms_us' );
				$DB2->select('*');
				$DB2->where( 'submit_form_id', 28 );
				$DB2->where( 'LOWER( `data` ) LIKE \'%"nome-completo":"%' . strtolower( rtrim( ltrim( str_replace( array( '\\', "'", ), array( '\\\\\\\\', "\'", ), json_encode( $autor ) ), '"' ), '"' ) ) . '%"%\' COLLATE \'utf8_general_ci\' ' );
				
				$autor = $DB2->get();
				
	 			if ( $autor->num_rows() > 0 ) {
					
					$autor = $autor->row_array();
					
					$autores[] = $autor[ 'id' ];
					
	 			}
				
			}
			
			$_new[ 'data' ][ 'autores' ] = $autores;
			
			// co-autores
			
			$_co_autores = array();
			
			$_co_autores = preg_split( "/(\\se\\s|,|\\/|;)/", $artigo[ 'coautor01' ] );
			$_co_autores = array_merge( $_co_autores, preg_split( "/(\\se\\s|,|\\/|;)/", $artigo[ 'coautor02' ] ) );
			$_co_autores = array_merge( $_co_autores, preg_split( "/(\\se\\s|,|\\/|;)/", $artigo[ 'coautor03' ] ) );
			$_co_autores = array_merge( $_co_autores, preg_split( "/(\\se\\s|,|\\/|;)/", $artigo[ 'coautor04' ] ) );
			
			$co_autores = array();
			
			foreach( $_co_autores as $key => & $co_autor ) {
				
				$find = array(
					
					'Teste',
					'Colaboradores: ',
					'COAUTOR 01',
					'COAUTOR 02',
					'COAUTOR 03',
					'COAUTOR 04',
					'AUTOR 01',
					'AUTOR 02',
					'AUTOR 03',
					'AUTOR 04',
					
				);
				$replace = '';
				
				$co_autor = $this->str_utils->normalizarNome( trim( str_replace( $find, $replace, $co_autor ) ) );
				
				$co_autor = $this->str_utils->normalizarNome( trim( str_replace( $find, $replace, $co_autor ) ) );
				
				if ( ! $co_autor ) {
					
					continue;
					
				}
				
				$DB2->from( 'tb_submit_forms_us' );
				$DB2->select('*');
				$DB2->where( 'submit_form_id', 28 );
				$DB2->where( 'LOWER( `data` ) LIKE \'%"nome-completo":"%' . strtolower( rtrim( ltrim( str_replace( '\\', '\\\\\\\\', json_encode( $co_autor ) ), '"' ), '"' ) ) . '%"%\' COLLATE \'utf8_general_ci\' ' );
				
				$_co_autor = $DB2->get();
				
	 			if ( $_co_autor->num_rows() > 0 ) {
					
					$_co_autor = $_co_autor->row_array();
					
					$co_autores[] = $_co_autor[ 'id' ];
					
	 			}
				
			}
			
			$_new[ 'data' ][ 'co-autores' ] = $co_autores;
			
// 			echo '<pre>' . print_r( $co_autores, TRUE ) . '</pre>';
			
			// resumo
			
			$artigo[ 'resumo' ] = preg_replace('/\n(\s*\n)+/', '</p><p>', $artigo[ 'resumo' ]);
			$artigo[ 'resumo' ] = preg_replace('/\n/', '<br>', $artigo[ 'resumo' ]);
			$artigo[ 'resumo' ] = '<p>'.$artigo[ 'resumo' ].'</p>';
			
			$find = array(
				
				'  ',
				'<br></p>',
				' </p>',
				'<p><br>',
				'<p>
<br>',
				"\t",
				
			);
			$replace = array(
				
				' ',
				'</p>',
				'</p>',
				'<p>',
				'<p>',
				"",
				
			);
			
			$artigo[ 'resumo' ] = trim( str_replace( $find, $replace, $artigo[ 'resumo' ] ) );
			$artigo[ 'resumo' ] = trim( str_replace( $find, $replace, $artigo[ 'resumo' ] ) );
			$artigo[ 'resumo' ] = trim( str_replace( $find, $replace, $artigo[ 'resumo' ] ) );
			$_new[ 'data' ][ 'resumo' ] = htmlspecialchars ( $artigo[ 'resumo' ] );
			
			// idiomas
			
			$idiomas = array();
			
			$_idiomas = $artigo[ 'idioma' ];
			
			$_idiomas = preg_split( "/(\\se\\s|,|;|\\s)/", $_idiomas );
			
			foreach( $_idiomas as $key => & $_idioma ) {
				
				$additional_replacements    = array 
					( "ǅ"    => "ǆ"        //   453 ->   454 
					, "ǈ"    => "ǉ"        //   456 ->   457 
					, "ǋ"    => "ǌ"        //   459 ->   460 
					, "ǲ"    => "ǳ"        //   498 ->   499 
					, "Ϸ"    => "ϸ"        //  1015 ->  1016 
					, "Ϲ"    => "ϲ"        //  1017 ->  1010 
					, "Ϻ"    => "ϻ"        //  1018 ->  1019 
					, "ᾈ"    => "ᾀ"        //  8072 ->  8064 
					, "ᾉ"    => "ᾁ"        //  8073 ->  8065 
					, "ᾊ"    => "ᾂ"        //  8074 ->  8066 
					, "ᾋ"    => "ᾃ"        //  8075 ->  8067 
					, "ᾌ"    => "ᾄ"        //  8076 ->  8068 
					, "ᾍ"    => "ᾅ"        //  8077 ->  8069 
					, "ᾎ"    => "ᾆ"        //  8078 ->  8070 
					, "ᾏ"    => "ᾇ"        //  8079 ->  8071 
					, "ᾘ"    => "ᾐ"        //  8088 ->  8080 
					, "ᾙ"    => "ᾑ"        //  8089 ->  8081 
					, "ᾚ"    => "ᾒ"        //  8090 ->  8082 
					, "ᾛ"    => "ᾓ"        //  8091 ->  8083 
					, "ᾜ"    => "ᾔ"        //  8092 ->  8084 
					, "ᾝ"    => "ᾕ"        //  8093 ->  8085 
					, "ᾞ"    => "ᾖ"        //  8094 ->  8086 
					, "ᾟ"    => "ᾗ"        //  8095 ->  8087 
					, "ᾨ"    => "ᾠ"        //  8104 ->  8096 
					, "ᾩ"    => "ᾡ"        //  8105 ->  8097 
					, "ᾪ"    => "ᾢ"        //  8106 ->  8098 
					, "ᾫ"    => "ᾣ"        //  8107 ->  8099 
					, "ᾬ"    => "ᾤ"        //  8108 ->  8100 
					, "ᾭ"    => "ᾥ"        //  8109 ->  8101 
					, "ᾮ"    => "ᾦ"        //  8110 ->  8102 
					, "ᾯ"    => "ᾧ"        //  8111 ->  8103 
					, "ᾼ"    => "ᾳ"        //  8124 ->  8115 
					, "ῌ"    => "ῃ"        //  8140 ->  8131 
					, "ῼ"    => "ῳ"        //  8188 ->  8179 
					, "Ⅰ"    => "ⅰ"        //  8544 ->  8560 
					, "Ⅱ"    => "ⅱ"        //  8545 ->  8561 
					, "Ⅲ"    => "ⅲ"        //  8546 ->  8562 
					, "Ⅳ"    => "ⅳ"        //  8547 ->  8563 
					, "Ⅴ"    => "ⅴ"        //  8548 ->  8564 
					, "Ⅵ"    => "ⅵ"        //  8549 ->  8565 
					, "Ⅶ"    => "ⅶ"        //  8550 ->  8566 
					, "Ⅷ"    => "ⅷ"        //  8551 ->  8567 
					, "Ⅸ"    => "ⅸ"        //  8552 ->  8568 
					, "Ⅹ"    => "ⅹ"        //  8553 ->  8569 
					, "Ⅺ"    => "ⅺ"        //  8554 ->  8570 
					, "Ⅻ"    => "ⅻ"        //  8555 ->  8571 
					, "Ⅼ"    => "ⅼ"        //  8556 ->  8572 
					, "Ⅽ"    => "ⅽ"        //  8557 ->  8573 
					, "Ⅾ"    => "ⅾ"        //  8558 ->  8574 
					, "Ⅿ"    => "ⅿ"        //  8559 ->  8575 
					, "Ⓐ"    => "ⓐ"        //  9398 ->  9424 
					, "Ⓑ"    => "ⓑ"        //  9399 ->  9425 
					, "Ⓒ"    => "ⓒ"        //  9400 ->  9426 
					, "Ⓓ"    => "ⓓ"        //  9401 ->  9427 
					, "Ⓔ"    => "ⓔ"        //  9402 ->  9428 
					, "Ⓕ"    => "ⓕ"        //  9403 ->  9429 
					, "Ⓖ"    => "ⓖ"        //  9404 ->  9430 
					, "Ⓗ"    => "ⓗ"        //  9405 ->  9431 
					, "Ⓘ"    => "ⓘ"        //  9406 ->  9432 
					, "Ⓙ"    => "ⓙ"        //  9407 ->  9433 
					, "Ⓚ"    => "ⓚ"        //  9408 ->  9434 
					, "Ⓛ"    => "ⓛ"        //  9409 ->  9435 
					, "Ⓜ"    => "ⓜ"        //  9410 ->  9436 
					, "Ⓝ"    => "ⓝ"        //  9411 ->  9437 
					, "Ⓞ"    => "ⓞ"        //  9412 ->  9438 
					, "Ⓟ"    => "ⓟ"        //  9413 ->  9439 
					, "Ⓠ"    => "ⓠ"        //  9414 ->  9440 
					, "Ⓡ"    => "ⓡ"        //  9415 ->  9441 
					, "Ⓢ"    => "ⓢ"        //  9416 ->  9442 
					, "Ⓣ"    => "ⓣ"        //  9417 ->  9443 
					, "Ⓤ"    => "ⓤ"        //  9418 ->  9444 
					, "Ⓥ"    => "ⓥ"        //  9419 ->  9445 
					, "Ⓦ"    => "ⓦ"        //  9420 ->  9446 
					, "Ⓧ"    => "ⓧ"        //  9421 ->  9447 
					, "Ⓨ"    => "ⓨ"        //  9422 ->  9448 
					, "Ⓩ"    => "ⓩ"        //  9423 ->  9449 
					, "𐐦"    => "𐑎"        // 66598 -> 66638 
					, "𐐧"    => "𐑏"        // 66599 -> 66639 
					); 
				
				$_idioma    = mb_strtolower( $_idioma, "UTF-8"); 
				
				$_idioma    = strtr( $_idioma, $additional_replacements );
				
				$_idioma = strtolower( $_idioma );
				
				$find = array(
					
					'  ',
					'-br',
					'gues',
					'inglês',
					'português',
					'Português Brasileiro',
					'port',
					'ing',
					'abstract',
					'em',
					'(resumo)',
					
				);
				$replace = array(
					
					' ',
					'',
					'guês',
					'ing',
					'Português Brasileiro',
					'port',
					'Português Brasileiro',
					'Inglês',
					'',
					'',
					'',
					
				);
				
				$_idioma = $this->str_utils->normalizarNome( trim( str_replace( $find, $replace, $_idioma ) ) );
				$_idioma = $this->str_utils->normalizarNome( trim( str_replace( $find, $replace, $_idioma ) ) );
				
				if ( $_idioma ) {
					
					$DB2->from( 'tb_submit_forms_us' );
					$DB2->select('*');
					$DB2->where( 'submit_form_id', 30 );
					$DB2->where( 'LOWER( `data` ) LIKE \'%"nome":"%' . strtolower( rtrim( ltrim( str_replace( '\\', '\\\\\\\\', json_encode( $_idioma ) ), '"' ), '"' ) ) . '%"%\' COLLATE \'utf8_general_ci\' ' );
					
					$_idioma = $DB2->get();
					
					if ( $_idioma->num_rows() > 0 ) {
						
						$_idioma = $_idioma->row_array();
						
						$idiomas[] = $_idioma[ 'id' ];
						
					}
					
				}
				
			}
			
			if ( ! empty( $idiomas ) ) {
				
				$_new[ 'data' ][ 'idiomas' ] = $idiomas;
				
			}
			
			// campos-pesquisa
			
			$campos = array();
			
			$_campos = $artigo[ 'area' ];
			
			$_campos = preg_split( "/(\\se\\s|,|;)/", $_campos );
			
			foreach( $_campos as $key => & $_campo ) {
				
				$find = array(
					
					'  ',
					'Vídeo Documentario',
					'Cavalo/',
					
				);
				$replace = array(
					
					' ',
					'',
					'',
					
				);
				
				$_campo = $this->str_utils->normalizarNome( trim( str_replace( $find, $replace, $_campo ) ) );
				
				$_campo = $this->str_utils->normalizarNome( trim( str_replace( $find, $replace, $_campo ) ) );
				
				if ( $_campo ) {
					
					$DB2->from( 'tb_submit_forms_us' );
					$DB2->select('*');
					$DB2->where( 'submit_form_id', 15 );
					$DB2->where( 'LOWER( `data` ) LIKE \'%"area-de-atuacao":"%' . strtolower( rtrim( ltrim( str_replace( '\\', '\\\\\\\\', json_encode( $_campo ) ), '"' ), '"' ) ) . '%"%\' COLLATE \'utf8_general_ci\' ' );
					
					$_campo = $DB2->get();
					
					if ( $_campo->num_rows() > 0 ) {
						
						$_campo = $_campo->row_array();
						
						$campos[] = $_campo[ 'id' ];
						
					}
					
				}
				
			}
			
			if ( ! empty( $campos ) ) {
				
				$_new[ 'data' ][ 'campos-pesquisa' ] = $campos;
				
			}
			
			// nivel-academico
			
			$nivel_academicos = array();
			
			$_nivel_academicos = $artigo[ 'escolaridade' ];
			
			$_nivel_academicos = preg_split( "/(\\se\\s|,|;)/", $_nivel_academicos );
			
			echo '<pre>' . print_r( $artigo[ 'escolaridade' ], TRUE ) . '</pre>';
			
			echo '<pre>' . print_r( $_nivel_academicos, TRUE ) . '</pre>';
			
			foreach( $_nivel_academicos as $key => & $_nivel_academico ) {
				
				$find = array(
					
					'  ',
					'Vídeo Documentario',
					'Cavalo/',
					
				);
				$replace = array(
					
					' ',
					'',
					'',
					
				);
				
				$_nivel_academico = $this->str_utils->normalizarNome( trim( str_replace( $find, $replace, $_nivel_academico ) ) );
				
				$_nivel_academico = $this->str_utils->normalizarNome( trim( str_replace( $find, $replace, $_nivel_academico ) ) );
				
				if ( $_nivel_academico ) {
					
					$DB2->from( 'tb_submit_forms_us' );
					$DB2->select('*');
					$DB2->where( 'submit_form_id', 33 );
					$DB2->where( 'LOWER( `data` ) LIKE \'%"titulo-escolaridade":"%' . strtolower( rtrim( ltrim( str_replace( '\\', '\\\\\\\\', json_encode( $_nivel_academico ) ), '"' ), '"' ) ) . '%"%\' COLLATE \'utf8_general_ci\' ' );
					
					$_nivel_academico = $DB2->get();
					
					if ( $_nivel_academico->num_rows() > 0 ) {
						
						$_nivel_academico = $_nivel_academico->row_array();
						
						$nivel_academicos[] = $_nivel_academico[ 'id' ];
						
					}
					
				}
				
			}
			
			if ( ! empty( $nivel_academicos ) ) {
				
				$_new[ 'data' ][ 'nivel-academico' ] = $nivel_academicos;
				
			}
			
			// paginas
			
			$_new[ 'data' ][ 'num-pages' ] = $this->str_utils->normalizarNome( trim( str_replace( $find, $replace, $artigo[ 'paginas' ] ) ) );
			
			// possui-ilustracao
			
			if ( strtolower( $artigo[ 'ilustracao' ] ) == 'sim' ) {
				
				$_new[ 'data' ][ 'possui-ilustracao' ] =  1;
				
			}
			
			// palavras-chave
			
			$additional_replacements    = array 
				( "ǅ"    => "ǆ"        //   453 ->   454 
				, "ǈ"    => "ǉ"        //   456 ->   457 
				, "ǋ"    => "ǌ"        //   459 ->   460 
				, "ǲ"    => "ǳ"        //   498 ->   499 
				, "Ϸ"    => "ϸ"        //  1015 ->  1016 
				, "Ϲ"    => "ϲ"        //  1017 ->  1010 
				, "Ϻ"    => "ϻ"        //  1018 ->  1019 
				, "ᾈ"    => "ᾀ"        //  8072 ->  8064 
				, "ᾉ"    => "ᾁ"        //  8073 ->  8065 
				, "ᾊ"    => "ᾂ"        //  8074 ->  8066 
				, "ᾋ"    => "ᾃ"        //  8075 ->  8067 
				, "ᾌ"    => "ᾄ"        //  8076 ->  8068 
				, "ᾍ"    => "ᾅ"        //  8077 ->  8069 
				, "ᾎ"    => "ᾆ"        //  8078 ->  8070 
				, "ᾏ"    => "ᾇ"        //  8079 ->  8071 
				, "ᾘ"    => "ᾐ"        //  8088 ->  8080 
				, "ᾙ"    => "ᾑ"        //  8089 ->  8081 
				, "ᾚ"    => "ᾒ"        //  8090 ->  8082 
				, "ᾛ"    => "ᾓ"        //  8091 ->  8083 
				, "ᾜ"    => "ᾔ"        //  8092 ->  8084 
				, "ᾝ"    => "ᾕ"        //  8093 ->  8085 
				, "ᾞ"    => "ᾖ"        //  8094 ->  8086 
				, "ᾟ"    => "ᾗ"        //  8095 ->  8087 
				, "ᾨ"    => "ᾠ"        //  8104 ->  8096 
				, "ᾩ"    => "ᾡ"        //  8105 ->  8097 
				, "ᾪ"    => "ᾢ"        //  8106 ->  8098 
				, "ᾫ"    => "ᾣ"        //  8107 ->  8099 
				, "ᾬ"    => "ᾤ"        //  8108 ->  8100 
				, "ᾭ"    => "ᾥ"        //  8109 ->  8101 
				, "ᾮ"    => "ᾦ"        //  8110 ->  8102 
				, "ᾯ"    => "ᾧ"        //  8111 ->  8103 
				, "ᾼ"    => "ᾳ"        //  8124 ->  8115 
				, "ῌ"    => "ῃ"        //  8140 ->  8131 
				, "ῼ"    => "ῳ"        //  8188 ->  8179 
				, "Ⅰ"    => "ⅰ"        //  8544 ->  8560 
				, "Ⅱ"    => "ⅱ"        //  8545 ->  8561 
				, "Ⅲ"    => "ⅲ"        //  8546 ->  8562 
				, "Ⅳ"    => "ⅳ"        //  8547 ->  8563 
				, "Ⅴ"    => "ⅴ"        //  8548 ->  8564 
				, "Ⅵ"    => "ⅵ"        //  8549 ->  8565 
				, "Ⅶ"    => "ⅶ"        //  8550 ->  8566 
				, "Ⅷ"    => "ⅷ"        //  8551 ->  8567 
				, "Ⅸ"    => "ⅸ"        //  8552 ->  8568 
				, "Ⅹ"    => "ⅹ"        //  8553 ->  8569 
				, "Ⅺ"    => "ⅺ"        //  8554 ->  8570 
				, "Ⅻ"    => "ⅻ"        //  8555 ->  8571 
				, "Ⅼ"    => "ⅼ"        //  8556 ->  8572 
				, "Ⅽ"    => "ⅽ"        //  8557 ->  8573 
				, "Ⅾ"    => "ⅾ"        //  8558 ->  8574 
				, "Ⅿ"    => "ⅿ"        //  8559 ->  8575 
				, "Ⓐ"    => "ⓐ"        //  9398 ->  9424 
				, "Ⓑ"    => "ⓑ"        //  9399 ->  9425 
				, "Ⓒ"    => "ⓒ"        //  9400 ->  9426 
				, "Ⓓ"    => "ⓓ"        //  9401 ->  9427 
				, "Ⓔ"    => "ⓔ"        //  9402 ->  9428 
				, "Ⓕ"    => "ⓕ"        //  9403 ->  9429 
				, "Ⓖ"    => "ⓖ"        //  9404 ->  9430 
				, "Ⓗ"    => "ⓗ"        //  9405 ->  9431 
				, "Ⓘ"    => "ⓘ"        //  9406 ->  9432 
				, "Ⓙ"    => "ⓙ"        //  9407 ->  9433 
				, "Ⓚ"    => "ⓚ"        //  9408 ->  9434 
				, "Ⓛ"    => "ⓛ"        //  9409 ->  9435 
				, "Ⓜ"    => "ⓜ"        //  9410 ->  9436 
				, "Ⓝ"    => "ⓝ"        //  9411 ->  9437 
				, "Ⓞ"    => "ⓞ"        //  9412 ->  9438 
				, "Ⓟ"    => "ⓟ"        //  9413 ->  9439 
				, "Ⓠ"    => "ⓠ"        //  9414 ->  9440 
				, "Ⓡ"    => "ⓡ"        //  9415 ->  9441 
				, "Ⓢ"    => "ⓢ"        //  9416 ->  9442 
				, "Ⓣ"    => "ⓣ"        //  9417 ->  9443 
				, "Ⓤ"    => "ⓤ"        //  9418 ->  9444 
				, "Ⓥ"    => "ⓥ"        //  9419 ->  9445 
				, "Ⓦ"    => "ⓦ"        //  9420 ->  9446 
				, "Ⓧ"    => "ⓧ"        //  9421 ->  9447 
				, "Ⓨ"    => "ⓨ"        //  9422 ->  9448 
				, "Ⓩ"    => "ⓩ"        //  9423 ->  9449 
				, "𐐦"    => "𐑎"        // 66598 -> 66638 
				, "𐐧"    => "𐑏"        // 66599 -> 66639 
				); 
			
			$artigo[ 'palavra' ]    = mb_strtolower( $artigo[ 'palavra' ], "UTF-8"); 
			
			$artigo[ 'palavra' ]    = strtr( $artigo[ 'palavra' ], $additional_replacements );
			
			$_new[ 'data' ][ 'palavras-chave' ] = trim( $artigo[ 'palavra' ] );
			
			// telefones
			
			$telefones = array();
			
			$find = array(
				
				'  ',
				'TEL 01',
				'TEL 02',
				
			);
			$replace = array(
				
				' ',
				'',
				'',
				
			);
			
			$artigo[ 'telefone01' ] = trim( str_replace( $find, $replace, $artigo[ 'telefone01' ] ) );
			$artigo[ 'telefone01' ] = trim( str_replace( $find, $replace, $artigo[ 'telefone01' ] ) );
			$artigo[ 'telefone02' ] = trim( str_replace( $find, $replace, $artigo[ 'telefone02' ] ) );
			$artigo[ 'telefone02' ] = trim( str_replace( $find, $replace, $artigo[ 'telefone02' ] ) );
			
			if ( strlen( $artigo[ 'telefone01' ] ) <= 3 ) {
				
				$artigo[ 'telefone01' ] = trim( $artigo[ 'telefone01' ] ) . ' ' . trim( $artigo[ 'telefone02' ] );
				
				unset( $artigo[ 'telefone02' ] );
				
			}
			
			if ( isset( $artigo[ 'telefone01' ] ) AND ! empty( $artigo[ 'telefone01' ] ) ) $telefones[] = $artigo[ 'telefone01' ];
			if ( isset( $artigo[ 'telefone02' ] ) AND ! empty( $artigo[ 'telefone02' ] ) ) $telefones[] = $artigo[ 'telefone02' ];
			
			$telefones = join( ' / ', $telefones );
			
			$_new[ 'data' ][ 'telefone' ] = $telefones;
			
// 			echo '<pre>' . print_r( $telefones, TRUE ) . '</pre>';
			
			// e-mails
			
			$emails = array();
			
			$artigo[ 'email01' ] = trim( $artigo[ 'email01' ] );
			$artigo[ 'email02' ] = trim( $artigo[ 'email02' ] );
			
			if ( strlen( $artigo[ 'email01' ] ) <= 3 ) {
				
				$artigo[ 'email01' ] = $artigo[ 'email01' ] . '  ' . $artigo[ 'email02' ];
				
				unset( $artigo[ 'email02' ] );
				
			}
			
			if ( isset( $artigo[ 'email01' ] ) AND ! empty( $artigo[ 'email01' ] ) ) $emails[] = $artigo[ 'email01' ];
			if ( isset( $artigo[ 'email02' ] ) AND ! empty( $artigo[ 'email02' ] ) ) $emails[] = $artigo[ 'email02' ];
			
			$emails = join( ', ', $emails );
			
			$emails = trim( str_replace( '  ', ' ', $emails ) );
			$emails = trim( str_replace( '  ', ' ', $emails ) );
			
			$_new[ 'data' ][ 'e-mail' ] = strtolower( $emails );
			
// 			echo '<pre>' . print_r( $emails, TRUE ) . '</pre>';
			
			// endereco
			
			$find = array(
				
				'  ',
				
			);
			$replace = array(
				
				' ',
				
			);
			
			$_new[ 'data' ][ 'endereco' ] = $this->str_utils->normalizarNome( trim( str_replace( $find, $replace, $artigo[ 'endereco' ] ) ) );
			
			// bairro
			
			$_new[ 'data' ][ 'bairro' ] = $this->str_utils->normalizarNome( trim( str_replace( $find, $replace, $artigo[ 'bairro' ] ) ) );
			
			// cidade
			
			$_new[ 'data' ][ 'cidade' ] = $this->str_utils->normalizarNome( trim( str_replace( $find, $replace, $artigo[ 'cidade' ] ) ) );
			
			// uf
			
			$DB2->from( 'tb_submit_forms_us' );
			$DB2->select('*');
			$DB2->where( 'submit_form_id', 11 );
			$DB2->where( 'LOWER( `data` ) LIKE \'%"sigla":"%' . strtolower( rtrim( ltrim( str_replace( '\\', '\\\\\\\\', json_encode( $artigo[ 'uf' ] ) ), '"' ), '"' ) ) . '%"%\' COLLATE \'utf8_general_ci\' ' );
			
			$estado = $DB2->get();
			
			if ( $estado->num_rows() > 0 ) {
				
				$estado = $estado->row_array();
				
				$_new[ 'data' ][ 'uf' ] = $estado[ 'id' ];
				
			}
			
			// cep
			
			$_new[ 'data' ][ 'postal-code' ] = $this->str_utils->normalizarNome( trim( str_replace( $find, $replace, $artigo[ 'cep' ] ) ) );
			
			// local
			
			$_new[ 'data' ][ 'local-realizacao' ] = $this->str_utils->normalizarNome( $artigo[ 'local' ] );
			
			// pais
			
			$DB2->from( 'tb_submit_forms_us' );
			$DB2->select('*');
			$DB2->where( 'submit_form_id', 31 );
			$DB2->where( 'LOWER( `data` ) LIKE \'%"nome":"%' . strtolower( rtrim( ltrim( str_replace( '\\', '\\\\\\\\', json_encode( $artigo[ 'pais' ] ) ), '"' ), '"' ) ) . '%"%\' COLLATE \'utf8_general_ci\' ' );
			
			$pais = $DB2->get();
			
			if ( $pais->num_rows() > 0 ) {
				
				$pais = $pais->row_array();
				
				$_new[ 'data' ][ 'pais' ] = $pais[ 'id' ];
				
			}
			
			// cidade-realizacao
			
			$_new[ 'data' ][ 'cidade-realizacao' ] = $this->str_utils->normalizarNome( trim( str_replace( $find, $replace, $artigo[ 'cidade_local' ] ) ) );
			
			// uf de realização
			
			$DB2->from( 'tb_submit_forms_us' );
			$DB2->select('*');
			$DB2->where( 'submit_form_id', 11 );
			$DB2->where( 'LOWER( `data` ) LIKE \'%"sigla":"%' . strtolower( rtrim( ltrim( str_replace( '\\', '\\\\\\\\', json_encode( $artigo[ 'uf_local' ] ) ), '"' ), '"' ) ) . '%"%\' COLLATE \'utf8_general_ci\' ' );
			
			$estado = $DB2->get();
			
			if ( $estado->num_rows() > 0 ) {
				
				$estado = $estado->row_array();
				
				$_new[ 'data' ][ 'uf-realizacao' ] = $estado[ 'id' ];
				
			}
			
			// pais-realizacao
			
			$DB2->from( 'tb_submit_forms_us' );
			$DB2->select('*');
			$DB2->where( 'submit_form_id', 31 );
			$DB2->where( 'LOWER( `data` ) LIKE \'%"nome":"%' . strtolower( rtrim( ltrim( str_replace( '\\', '\\\\\\\\', json_encode( $artigo[ 'pais_local' ] ) ), '"' ), '"' ) ) . '%"%\' COLLATE \'utf8_general_ci\' ' );
			
			$pais = $DB2->get();
			
			if ( $pais->num_rows() > 0 ) {
				
				$pais = $pais->row_array();
				
				$_new[ 'data' ][ 'pais-realizacao' ] = $pais[ 'id' ];
				
			}
			
// 			echo '<pre>' . print_r( $co_autores, TRUE ) . '</pre>';
			
			// status
			
			$_new[ 'data' ][ 'status' ] = ( strtolower( $artigo[ 'publicar' ] ) == 'sim' ) ? 7366 : 7367;
			
			// arquivo
			
			if ( trim( $artigo[ 'arquivo' ] ) != '' ) {
				
				$_new[ 'data' ][ 'arquivo' ] = 'media/artigos-academicos/documentos/' . $artigo[ 'arquivo' ];
				
			}
			
			$_new[ 'xml_data' ] = $this->sfcm->us_json_data_to_xml( $_new[ 'data' ] );
			
			$_new[ 'data' ] = json_encode( $_new[ 'data' ] );
			
			echo '<pre>' . print_r( $_new, TRUE ) . '</pre>';
			
			
			
			
			if ( $this->db->insert( 'tb_submit_forms_us', $_new ) ){
				
				echo '<h5>inserindo:</h5> ' . '<pre>' . print_r( $_new, TRUE ) . '</pre>';
				echo '[ok]<br/>';
				
			}
			else {
				
				echo '<h5>inserindo:</h5> ' . '<pre>' . print_r( $_new, TRUE ) . '</pre>';
				echo '[ok]<br/>';
				
			}
			
		}
		
//  		echo '<pre>' . print_r( $db_data, TRUE ) . '</pre>';
		
		//echo '<pre>' . print_r( $db_data, TRUE ) . '</pre>'; exit;
		
 		$this->output->enable_profiler( TRUE );
	}
	
}
