<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Tests extends CI_controller {

	public function __construct(){

		parent::__construct();

	}

	public function index(){

		print_r( $this->session->userdata );

	}

	public function check_login(){

		$session_data = $this->session->userdata( 'user_data' );

		if ( isset( $session_data[ 'site' ][ 'login' ] ) AND $session_data[ 'site' ][ 'login' ] ) {

			echo 'usuário logado no site';

		}
		else {

			echo 'usuário não logado no site';

		}

	}

	public function login(){

		$session_data = $this->session->userdata( 'user_data' );

		$session_data[ 'site' ][ 'login' ] = TRUE;

		$this->session->set_userdata( 'user_data', $session_data );

	}

	public function logout(){

		$session_data = $this->session->userdata( 'user_data' );

		$session_data[ 'site' ][ 'login' ] = FALSE;

		$this->session->set_userdata( 'user_data', $session_data );

	}

}
