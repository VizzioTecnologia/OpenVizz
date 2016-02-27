<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Menu_items_search_plugin extends Plugins_mdl{
	
	public function run( &$data, $params = NULL ){
		
		$return = FALSE;
		
		if ( is_object( $this->search ) ) {
			
			log_message( 'debug', '[Plugins] Menu items search plugin initialized' );
			
			if ( ! $this->load->is_model_loaded( 'menus' ) ) {
				
				$this->load->model( 'menus_mdl', 'menus' );
				
			}
			
			// -------------------------------------------------
			// Parsing vars ------------------------------------
			
			$plugin_params = $this->search->config( 'plugins_params' );
			$plugin_params = isset( $plugin_params[ 'menu_items_search' ] ) ? $plugin_params[ 'menu_items_search' ] : FALSE;
			
			$menu_type_id =										( isset( $plugin_params[ 'menu_type_id' ] ) AND is_numeric( $plugin_params[ 'menu_type_id' ] ) ) ? $plugin_params[ 'menu_type_id' ] : NULL;
			
			// Parsing vars ------------------------------------
			// -------------------------------------------------
			
			// db search params
			$dsp = array(
				
				'plugin_name' => 'menu_items_search',
				'table_name' => 'tb_menus t1',
				'select_columns' => '
					
					t1.*,
					t2.title as parent_title,
					t2.alias as parent_alias,
					t3.title as menu_type_title
					
				',
				'tables_to_join' => array(
					
					array( 'tb_menus t2', 't1.parent = t2.id', 'left' ),
					array( 'tb_menu_types t3', 't1.menu_type_id = t3.id', 'left' ),
					
				),
				'search_columns' => 't1.title, t1.alias, t1.description',
				
			);
			//t1.parent DESC, t1.ordering DESC, t1.title DESC
			
			// -------------------------------------------------
			// Menu type filtering ------------------------------
			
			if ( is_numeric( $menu_type_id ) AND $menu_type_id != -1 ) {
				
				$dsp[ 'where_conditions' ] = '( t1.menu_type_id = "' . $menu_type_id . '" )';
				
			}
			
			// Menu type filtering ------------------------------
			// -------------------------------------------------
			
			$full_search_results = $this->search->db_search( $dsp );
			$full_search_results = $full_search_results ? $full_search_results->result_array() : $full_search_results;
			
			// -------------------------------------------------
			// Checking the get menu items tree function use ---
			
			/*
			 * We can't get a menu items tree when using pagination
			 * so, let's check this
			 */
			
			$get_tree = FALSE;
			
			$order_by = $this->search->config( 'order_by' );
			if (
				
				isset( $order_by[ 'menu_items_search' ] ) AND
				(
					
					trim( $order_by[ 'menu_items_search' ] ) === 't1.parent DESC, t1.ordering DESC, t1.title DESC' OR
					trim( $order_by[ 'menu_items_search' ] ) === 't1.parent ASC, t1.ordering ASC, t1.title ASC'
					
				) AND // if current order_by is ordering
				(
					
					! $this->search->config( 'ipp' ) OR
					! ( $this->search->count_all_results( 'menu_items_search' ) > $this->search->config( 'ipp' ) )
					
				)
				
			) {
				
				$get_tree = TRUE;
				
			}
			
			// Checking the get categories tree function use ---
			// -------------------------------------------------
			
			if ( $full_search_results ) {
				
				if ( $get_tree ) {
					
					$_tmp = $this->menus->get_menu_items_tree( array( 'array' => $full_search_results, ) );
					
					$full_search_results = check_var( $_tmp ) ? $_tmp : $full_search_results;
					
				}
				
				$default_search_results = array();
				
				foreach ( $full_search_results as $key => & $search_result ) {
					
					$this->menus->parse_menu_item( $search_result );
					
					$line = & $default_search_results[];
					
					$line[ 'id' ] = isset( $search_result[ 'id' ] ) ? $search_result[ 'id' ] : '';
					$line[ 'title' ] = isset( $search_result[ 'title' ] ) ? $search_result[ 'title' ] : '';
					$line[ 'description' ] = isset( $search_result[ 'description' ] ) ? $search_result[ 'description' ] : '';
					$line[ 'content' ] = $search_result[ 'description' ] ? word_limiter( strip_tags( html_entity_decode( $search_result[ 'description' ] ) ), 20, '...' ) : '';
					
				}
				
				// apply the string highlight to default results
				if ( $this->search->config( 'terms' ) ) {
					
					$keys = array(
						
						'title',
						'alias',
						'description',
						'content',
						
					);
					$default_search_results = $this->search->array_highlight( $default_search_results, $keys );
					
				}
				
				// apply the string highlight to full results
				if ( $this->search->config( 'terms' ) ) {
					
					$keys = array(
						
						'title',
						'alias',
						'description',
						'content',
						
					);
					$full_search_results = $this->search->array_highlight( $full_search_results, $keys );
					
				}
				
				$result = array(
					
					'results' => $default_search_results,
					'full_results' => $full_search_results,
					
				);
				
				$this->search->append_results( 'menu_items_search', $result );
				
				$return = TRUE;
				
			}
			
		}
		else {
			
			log_message( 'debug', '[Plugins] Menu items search plugin could not be executed! Search library object not initialized!' );
			
			$return = FALSE;
			
		}
		
		parent::_set_performed( 'menu_items_search' );
		
		return $return;
		
	}
	
	public function get_params_spec(){
		
	}
	
}
