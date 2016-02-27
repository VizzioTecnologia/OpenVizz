<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class Users_model extends CI_Model{
		
		public function get_link_login_page( $menu_item_id = NULL ){
			
			return $this->users->get_link_login_page( $menu_item_id, $params );
			
		}
		
		public function get_link_logout_page( $menu_item_id = 0, $params = NULL ){
		
			return $this->users->get_link_logout_page( $menu_item_id, $params );
			
		}
		
	}
