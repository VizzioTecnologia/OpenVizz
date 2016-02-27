<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

set_include_path( get_include_path() . PATH_SEPARATOR . APPPATH . 'libraries' . DS . 'google-api-php-client/src' );

require_once 'Google/Client.php';
require_once 'Google/Auth/AssertionCredentials.php';

class Google extends Google_Client {
	
	function google(){
		
		log_message( 'Debug', 'Google API library is loaded.' );
		
	}
	
	function save_token_on_db( $token ){
		
		$CI = & get_instance();
		$data = array(
			
			'google_api_token' => $token,
			
		);
		
		$condition = array(
			
			'id' => $CI->users->user_data[ 'id' ],
			
		);
		
		if ( $CI->db->update( 'tb_users', $data, $condition ) ){
			return TRUE;
		}
		else {
			return FALSE;
		}
		
	}
	function get_token_from_db(){
		
		$CI = & get_instance();
		
		if ( check_var( $CI->users->user_data[ 'id' ] ) ){
			
			$CI->db->select( 'google_api_token' );
			$CI->db->from('tb_users');
			$CI->db->where( 'id', $CI->users->user_data[ 'id' ] );
			$CI->db->limit( 1 );
			
			$result = $CI->db->get()->row_array();
			
			return $result[ 'google_api_token' ];
			
		}
		else return FALSE;
		
	}
	
	function client(){
		
		$client = new Google_Client();
		return $client;
		
	}
	
}
