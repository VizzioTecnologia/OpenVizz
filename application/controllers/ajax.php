<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

require( APPPATH . 'controllers/main.php' );

/**
 * Classe que recebera chamadas Ajax vindas do submit_form
 */
class Ajax extends Main {
	public $params;
	
	public function __construct()
	{
		
		parent::__construct();
		
		$this->load->language( 'ajax' );
		
		$this->params = $_POST;
	}
	
	/*
	 * Procura por usuarios pelo cpf informado
	 */
	public function check_cpf() {
		
		$user[ 'user_id' ] = FALSE;
		
		if ( $this->form_validation->check_cpf('04497467473'))
		{
			
			// -------------------------------------------------			
			// Procurar por usuarios
			$params['where_condition'] = 't1.params LIKE "%'.$this->params['value'].'%"';
			$result = $this->users->get_users($params);
			$rows = $result->result_array();
			foreach($rows as $row)
			{
				$row['params'] = json_decode($row['params']);
				if(isset($row['params']->user_fields->cpf) AND $row['params']->user_fields->cpf==$this->params['value'])
				{
					$user_fields = $row['params']->user_fields;
					
					$user['user_id'] = $row['id'];
					$user['login'] = $row['username'];
					$user['name'] = $row['name'];
					$user['email'] = $row['email'];
					$user['badge_name'] = 		(isset($user_fields->badge_name))? $user_fields->badge_name : '';
					$user['identity_number'] = 	(isset($user_fields->identity_number))? $user_fields->identity_number : '';
					$user['issuer'] = 			(isset($user_fields->issuer))? $user_fields->issuer : '';
					$user['issuer_uf'] = 		(isset($user_fields->issuer_uf))? $user_fields->issuer_uf : '';
					$user['cpf'] = 				(isset($user_fields->cpf))? $user_fields->cpf : '';
					$user['birthday'] = 		(isset($user_fields->birthday))? $user_fields->birthday : '';
					$user['genre'] = 			(isset($user_fields->genre))? $user_fields->genre : '';
					$user['place_of_birth'] = 	(isset($user_fields->place_of_birth))? $user_fields->place_of_birth : '';
					
					break;
				}
			}
		}
		
		echo json_encode( $user );
	}
	
}
?>
