<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Modules_common_model extends CI_Model{
	
	public function get_modules(){
		
		$mi_id = $this->mcm->current_menu_item[ 'id' ];
		
		$all_cond = 't1.mi_cond LIKE \'%"all"%\''; // módulos com condição de item de menu "todos"
		$all_except_cond = 't1.mi_cond NOT LIKE \'%"all_except"%"' . $mi_id . '"%\''; // módulos com condição de item de menu "todos, exceto"
		//$none_cond = 't1.mi_cond LIKE \'%"none"%\''; // módulos com condição de item de menu "nenhum"
		$none_except_cond = 't1.mi_cond LIKE \'%"none_except"%"' . $mi_id . '"%\''; // módulos com condição de item de menu "nenhum, exceto"
		$specific_cond = 't1.mi_cond LIKE \'%"specific"%"' . $mi_id . '"%\''; // módulos com condição de item de menu "específicos"
		
		$where =	'';
		$or_where_condition =	'';
		// -------------------------------------------------
		// montando as condições padrões -------------------

		$default_condition = '';
		
		$default_condition = ( $default_condition != '' ) ? $default_condition : NULL;
		
		$default_condition =	'( ';
		$default_condition .=	' `access_type` = "public"'; // public modules
		$default_condition .=	' AND ';
		$default_condition .=	't1.status = 1 '; // active modules
		$default_condition .=	' AND ';
		$default_condition .=	' t1.environment = \'' . environment() . '\' '; // módulos do ambiente atual
		$default_condition .=	' AND ( ( ' . $all_except_cond . ' AND ( ' . $all_cond . ' OR ' . $specific_cond . ' OR ' . $none_except_cond . ') )';
		$default_condition .=	' OR ( ' . $all_except_cond . ' AND t1.mi_cond LIKE \'%"all%\') )';
		$default_condition .=	' ) ';
		
		// montando as condições padrões -------------------
		// -------------------------------------------------
		
		// filtrando os módulos acessíveis ao usuário atual, caso esteja logado
		if ( $this->users->is_logged_in() ){

			if ( $this->users->check_privileges( 'modules_can_view_all' ) ){
				
				$where .= $default_condition . ' OR ( ';
				$where .=	't1.status = 1 '; // active modules
				$where .=	' AND ';
				$where .=	' t1.environment = \'' . environment() . '\' '; // módulos do ambiente atual
				$where .=	' AND ( ( ' . $all_except_cond . ' AND ( ' . $all_cond . ' OR ' . $specific_cond . ' OR ' . $none_except_cond . ') )';
				$where .=	' OR ( ' . $all_except_cond . ' AND t1.mi_cond LIKE \'%"all%\') )';
				$where .=	' ) ';
				
			}
			else if ( $this->users->check_privileges('modules_can_view_only_accessible') ){
				
				$where .= $default_condition . ' OR ( ';
				
				$where .= ' (`access_type` = \'users\' AND `access_ids` LIKE \'%>'.$this->users->user_data['id'].'<%\')';
				$where .= ' OR (`access_type` = \'users_groups\' AND `access_ids` LIKE \'%>'.$this->users->user_data['group_id'].'<%\')';
				
				$where .= ' ) ';
				
			}
			else{
				
				$where .= $default_condition . ' OR ( ';
				
				$where .= '( ';
				$where .= '( `access_type` = \'users\' AND `access_ids` LIKE \'%>'.$this->users->user_data['id'].'<%\')';
				$where .= ' OR (`access_type` = \'users_groups\' AND `access_ids` LIKE \'%>'.$this->users->user_data['group_id'].'<%\')';
				
				// obtendo os grupos de usuários acessíveis
				$accessible_users_groups = $this->view_modules_get_accessible_users_groups();
				
				if ( $accessible_users_groups ) {
					
					// obtendo a lista de usuários com base nos grupos obtidos
					
					// get users params
					$gup = array();
					
					$gup[ 'or_where_condition' ] = '';
					$i = 1;
					foreach ( $accessible_users_groups as $key => $accessible_users_group ) {
						
						$gup[ 'or_where_condition' ][ 'fake_index_' . $i ] = '`group_id` = "' . $accessible_users_group[ 'id' ] . '"';
						
						$i++;
						
					}
					
					$users = $this->users->get_users( $gup )->result_array();
					
					$users_ids = '';
					
					foreach ( $users as $key => $user) {
						
						$users_ids .= '(.*)>' . $user['id'] . '<(.*)|';
						
					}
					
					$users_ids = rtrim( $users_ids, '|' );
					
					
					$where .= ' ( `access_type` = \'users\' AND `access_ids` REGEXP "' . $users_ids . '" ) ';
					
					end( $accessible_users_groups );
					$lugk = key( $accessible_users_groups );
					
					$where .= ' OR ( `access_type` = \'users_groups\' AND `access_ids` REGEXP "';
					foreach ( $accessible_users_groups as $key => $users_group ) {

						$where .= '(.*)>' . $users_group['id'] . '<(.*)';

						if ( ! ( $key == $lugk ) ){

							$where .= '|';

						}

					}
					$where .= '" )';
					
				}
				
				$where .= ')';
				
				$where .= ' ) ';
				
			}
			
		}
		else {
			
			$where .=	$default_condition;
			
		}
		
		$this->db->select('
			
			t1.*,
			
		');
		
		$this->db->from( 'tb_modules t1' );
		
		$this->db->order_by( 't1.ordering asc, t1.position asc, t1.id asc', '', TRUE );
		
		if ( $where ) {
			
			$this->db->where( $where );
			
		}
		
		if ( $or_where_condition ) {
			
			if( is_array( $or_where_condition ) ){
				
				foreach ( $or_where_condition as $key => $value ) {
					
					if ( gettype( $or_where_condition) === 'array' AND ( strpos( $key,'fake_index_' ) !== FALSE ) ){
						
						$this->db->or_where( $value );
						
					}
					else $this->db->or_where( $key, $value );
					
				}
				
			}
			else $this->db->or_where( $or_where_condition );
			
		}
		
		$modules = $this->db->get()->result_array();
		
		$modules_result = array();
		foreach ( $modules as $key => & $module ) {
			
			$module_model_name = $module[ 'type' ] . '_module';
			
			$this->load->model( 'modules/' . $module_model_name );
			
			$module[ 'params' ] = get_params( $module[ 'params' ] );
			
			$modules_result[ $module[ 'position' ] ][ $module[ 'ordering' ] . '-' . $module[ 'alias' ] ] = $this->{ $module_model_name }->run( $module );
			
			//$data[ 'content' ] = &$modules_result[ $module[ 'position' ] ][ $module[ 'ordering' ] . '-' . $module[ 'alias' ] ];
			
			// -------------------------------------------------
			// loading content plugins -------------------------
			
			$this->plugins->load( NULL, 'content' );
			
			// loading content plugins -------------------------
			// -------------------------------------------------
			
			
			
			
		}
		
		$this->mcm->loaded_modules = $modules_result;
		
	}
	
	// --------------------------------------------------------------------

	public function view_modules_get_accessible_users_groups( $user_id = NULL ) {

		$user_id = $this->users->user_data['id'];
		$accessible_groups = FALSE;

		$this->users->get_users_groups_query();

		// grupos de usuários de mesmo nível e abaixo
		if ($this->users->check_privileges('modules_can_view_only_same_and_low_group_level')){

			$accessible_groups = $this->users->get_users_groups_same_and_low_group_level();

		}
		// grupos de usuários de mesmo nível
		else if ($this->users->check_privileges('modules_can_view_only_same_group_level')){

			$accessible_groups = $this->users->get_users_groups_same_group_level();

		}
		else if ($this->users->check_privileges('modules_can_view_only_same_group_and_below')){

			$accessible_groups = $this->users->get_users_groups_same_group_and_below();

		}
		else if ($this->users->check_privileges('modules_can_view_only_same_group')){

			$accessible_groups = $this->users->get_users_groups_same_group();

		}
		else if ($this->users->check_privileges('modules_can_view_only_low_groups')){

			$accessible_groups = $this->users->get_users_groups_low_groups();

		}

		return $accessible_groups;

	}

	// --------------------------------------------------------------------
	
	public function get_module( $id = NULL ){
		
		if ( $id != NULL ){
			
			$this->db->select('
				
				t1.*,
				
			');
			
			$this->db->from('tb_modules t1');
			$this->db->where( 't1.id', $id );
			// limitando o resultando em apenas 1
			$this->db->limit(1);
			return $this->db->get();
			
		}
		else {
			
			return FALSE;
			
		}
		
	}
	
	public function get_modules_types(){
		
		$modules_types = file_list_to_array( MODULES_PATH, '*_module.php' );
		
		foreach ( $modules_types as $key => &$module_type ) {
			
			$module_type = array( 
				
				'title' => 'module_type_' . basename( $module_type, '_module.php' ),
				'alias' => basename( $module_type, '_module.php' ),
				
			 );
			
		}
		
		$this->mcm->modules_types = &$modules_types;
		
	}
	
	public function insert( $data = NULL ){
		
		if ( $data != NULL ){
			
			if ( $this->db->insert( 'tb_modules', $data ) ){
				
				// confirm the insertion for controller
				return $this->db->insert_id();
				
			}
			else {
				
				// case the insertion fails, return false
				return FALSE;
				
			}
		}
		else {
			
			redirect( 'admin/modules/a/ml' );
			
		}
		
	}
	
	public function update( $data = NULL, $condition = NULL ){
		
		if ($data != NULL && $condition != NULL){
			if ($this->db->update('tb_modules', $data, $condition)){
				// confirm update for controller
				return TRUE;
			}
			else {
				// case update fails, return false
				return FALSE;
			}
		}
		redirect( 'admin/modules/a/ml' );
		
	}
	
	public function delete($condition = NULL){
		if ($condition != null){
			if ($this->db->delete('tb_modules',$condition)){
				// confirm delete for controller
				return TRUE;
			}
			else {
				// case delete fails, return false
				return FALSE;
			}
		}
		redirect();
	}
	
}
