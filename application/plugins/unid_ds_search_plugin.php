<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Unid_ds_search_plugin extends Plugins_mdl{
	
	public function run( &$data, $params = NULL ){
		
		$return = FALSE;
		
		if ( is_object( $this->search ) ) {
			
			log_message( 'debug', '[Plugins] Unified data schemas search plugin initialized' );
			
			// -------------------------------------------------
			// Parsing vars
			
			$plugin_params =									$this->search->config( 'plugins_params' );
			$plugin_params =									isset( $plugin_params[ 'unid_ds_search' ] ) ? $plugin_params[ 'unid_ds_search' ] : array();
			
			// -------------
			
			$where_conditions =									( isset( $plugin_params[ 'where_conditions' ] ) ) ? $plugin_params[ 'where_conditions' ] : NULL;
			
			// -------------
			
			$or_where_conditions =								( isset( $plugin_params[ 'or_where_conditions' ] ) ) ? $plugin_params[ 'or_where_conditions' ] : NULL;
			
			// -------------
			
			$dsid =												( isset( $plugin_params[ 'dsid' ] ) AND ( is_numeric( $plugin_params[ 'dsid' ] ) OR is_array( $plugin_params[ 'dsid' ] ) ) ) ? $plugin_params[ 'dsid' ] : NULL;
			
			// -------------
			
			$random =											( isset( $plugin_params[ 'random' ] ) ) ? ( bool ) $plugin_params[ 'random' ] : FALSE;
			
			// -------------
			
			$select_columns =									( isset( $plugin_params[ 'select_columns' ] ) ) ? ( bool ) $plugin_params[ 'select_columns' ] : array(
				
				'id',
				'create_datetime',
				'mod_datetime',
				'alias',
				'title',
				'properties',
				'params',
				
			);
			
			// -------------
			
			$filter_params =									( isset( $plugin_params[ 'filter_params' ] ) ) ? ( bool ) $plugin_params[ 'filter_params' ] : FALSE;
			$menu_item_id =										( isset( $plugin_params[ 'menu_item_id' ] ) AND is_numeric( $plugin_params[ 'menu_item_id' ] ) ) ? $plugin_params[ 'menu_item_id' ] : NULL;
			
			// -------------
			
			$order_by =											$this->search->config( 'order_by' );
			$order_by =											( isset( $order_by[ 'unid_ds_search' ] ) ) ? $order_by[ 'unid_ds_search' ] : 'create_datetime';
			$order_by =											( isset( $plugin_params[ 'order_by' ] ) ) ? $plugin_params[ 'order_by' ] : $order_by;
			
			// -------------
			
			$order_by_direction =								$this->search->config( 'order_by_direction' );
			$order_by_direction =								( isset( $order_by_direction[ 'unid_ds_search' ] ) ) ? $order_by_direction[ 'unid_ds_search' ] : 'ASC';
			$order_by_direction =								strtoupper( ( isset( $plugin_params[ 'order_by_direction' ] ) ) ? $plugin_params[ 'order_by_direction' ] : $order_by_direction );
			
			if ( in_array( $order_by_direction, array( 'A', 'ASC', 'D', 'DESC', 'R', 'RANDOM', ) ) ) {
				
				if ( $order_by_direction === 'A' ) {
					
					$order_by_direction = 'ASC';
					
				}
				else if ( $order_by_direction === 'D' ) {
					
					$order_by_direction = 'DESC';
					
				}
				else if ( $order_by_direction === 'R' OR $order_by_direction === 'RANDOM' ) {
					
					$order_by_direction = 'RANDOM';
					$random = TRUE;
					
				}
				
			}
			
			// Parsing vars
			// -------------------------------------------------
			
			$select_columns = join( ',', preg_filter( '/^/', 't1.', $select_columns ) );
			
			// db search params
			$dsp = array(
				
				'plugin_name' => 'unid_ds_search',
				'table_name' => 'tb_unid_data_schemas t1',
				'random' => $random,
				'select_columns' => $select_columns,
				'search_columns' => '`t1`.`id`, `t1`.`title`, `t1`.`create_datetime`, `t1`.`mod_datetime`, `t1`.`params`',
				
			);
			
			if ( $where_conditions ) {
				
				$dsp[ 'where_conditions' ][] = $where_conditions;
				
			}
			
			$default_condition = array();
			$default_condition_array = array();
			
			// -------------------------------------------------
			// Preserving original searh config ----------------
			
			$search_config = $this->search->config();
			
			// Preserving original searh config ----------------
			// -------------------------------------------------
			
			// -------------------------------------------------
			// Data schema filtering ---------------------------
			
			$ds_condition = NULL;
			$default_condition_array[ 'ds_condition' ] = NULL;
			
			if ( $dsid ) {
				
				if ( is_array( $dsid ) ) {
					
					if ( count( $dsid ) == 1 ) {
						
						$ds_condition = '`t1`.`id` = ' . $dsid[ 0 ];
						
					}
					else {
						
						$search_config[ 'ipp' ] = 0;
						$ds_condition = '`t1`.`id` IN (' . join( ',', $dsid ) . ')';
						
					}
					
				}
				else {
					
					$ds_condition = '`t1`.`id` = ' . $dsid;
					
				}
				
				
				$default_condition_array[ 'ds_condition' ] = $ds_condition;
				
			}
			
			// Data schema filtering ---------------------------
			// -------------------------------------------------
			
			$default_condition_array = array_filter( $default_condition_array, 'strlen' );
			
			$default_condition = check_var( $default_condition_array ) ? '( ' . join( ' AND ' , $default_condition_array ) . ' )' : NULL;
			$dsp[ 'where_conditions' ][] = $default_condition;
			
			// -------------------------------------------------
			// Setting terms conditions ------------------------
			
			if ( $terms = $this->search->config( 'terms' ) ) {
				
				$dsp[ 'terms' ] = '';
				
				$search_conditions = '';
				$search_conditions .= '(';
				
				if ( is_numeric( $terms ) AND ( int ) $terms > 0 ){
					
					$search_conditions .= '`t1`.`id` LIKE \'%' . strtolower( $terms ) . '%\' OR ';
					
				}
				$search_conditions .= '`t1`.`create_datetime` LIKE \'%' . strtolower( $terms ) . '%\' ';
				$search_conditions .= 'OR `t1`.`mod_datetime` LIKE \'%' . strtolower( $terms ) . '%\' ';
				$search_conditions .= 'OR LOWER( `t1`.`alias` ) LIKE \'%' . strtolower( $terms ) . '%\' ';
				$search_conditions .= 'OR LOWER( `t1`.`title` ) LIKE \'%' . strtolower( $terms ) . '%\' ';
				$search_conditions .= 'OR LOWER( `t1`.`data` ) LIKE \'%"%":"%' . strtolower( $terms ) . '%"%\' COLLATE \'utf8_general_ci\' ';
				$search_conditions .= 'OR LOWER( `t1`.`data` ) LIKE \'%"%":"%' . strtolower( rtrim( ltrim( str_replace( '\\', '\\\\\\\\', json_encode( $terms ) ), '"' ), '"' ) ) . '%"%\' COLLATE \'utf8_general_ci\' ';
				$search_conditions .= ')';
				
				$dsp[ 'where_conditions' ][] = $search_conditions;
				
			}
			
			// Setting terms conditions ------------------------
			// -------------------------------------------------
			
			$order_by_allowed = FALSE;
			$order_by_is_int = FALSE;
			$order_by_is_date = FALSE;
			
			// -------------------------------------------------
			// Order by ----------------------------------------
			
			if ( $order_by_allowed AND $order_by ) {
				
				// order by complement
				$comp_ob = '';
				
				if ( in_array( $order_by, array( 'id', 'submit_datetime', 'mod_datetime',  ) ) ) {
					
					switch ( $order_by ) {
						
						case 'id':
							
							$order_by = '`t1`.`id`';
							break;
							
						case 'submit_datetime':
							
							$order_by = '`t1`.`mod_datetime`';
							break;
							
						case 'mod_datetime':
							
							$order_by = '`t1`.`mod_datetime`';
							break;
							
					}
					
					$search_config[ 'order_by' ][ 'unid_ds_search' ] = $order_by;
					$search_config[ 'order_by_direction' ][ 'unid_ds_search' ] = $order_by_direction;
					
				}
				
				else if ( ! in_array( $order_by, array( 'id', 'submit_datetime', 'mod_datetime',  ) ) AND $dsid ) {
					
					//echo '<pre>' . print_r( $submit_form[ 'fields' ], TRUE ) . '</pre>'; exit;
					
					if ( $order_by_is_int ) {
						
						$order_by = 'CAST( `' . $order_by . '` as SIGNED INTEGER )';
						$search_config[ 'order_by' ][ 'unid_ds_search' ] = array( $order_by, $order_by_direction, FALSE );
						
					}
					else if ( $order_by_is_date ) {
						
						$order_by = 'STR_TO_DATE(`' . $order_by . '`,\'%Y-%m-%d\')';
						$search_config[ 'order_by' ][ 'unid_ds_search' ] = array( $order_by, $order_by_direction, FALSE );
						
					}
					else {
						
						$search_config[ 'order_by' ][ 'unid_ds_search' ] = array( $order_by, $order_by_direction, TRUE );
						
					}
					
				}
				
			}
			else {
				
				$order_by = '`t1`.`id`';
				
				$search_config[ 'order_by' ][ 'unid_ds_search' ] = $order_by;
				$search_config[ 'order_by_direction' ][ 'unid_ds_search' ] = $order_by_direction;
				
				
			}
			
			$search_config[ 'random' ] = $random;
			
			// Order by ----------------------------------------
			// -------------------------------------------------
			
			$this->search->config( $search_config );
			
			$full_search_results = $this->search->db_search( $dsp );
			$full_search_results = $full_search_results ? $full_search_results->result_array() : $full_search_results;
			
			if ( $full_search_results ) {
				
				$default_search_results = array();
				
				foreach ( $full_search_results as $key => & $search_result ) {
					
					$this->unid->parse_ds( $search_result, $filter_params, $menu_item_id );
					
					// -------------------------------------------------
					// Ordering by dinamic fields ----------------------
					
					$search_result[ 'order_by' ] = isset( $search_result[ 'data' ][ $order_by ] ) ? $search_result[ 'data' ][ $order_by ] : '';
					
					// Ordering by dinamic fields ----------------------
					// -------------------------------------------------
					
					$line = & $default_search_results[];
					
					$line[ 'id' ] = isset( $search_result[ 'id' ] ) ? $search_result[ 'id' ] : '';
					
				}
				
				// -------------------------------------------------
				// Ordering by dinamic fields ----------------------
				
				// $full_search_results = multi_sort( $full_search_results, 'order_by' );
				/*
				if ( strtolower( $order_by_direction ) == 'desc' ){
					
					rsort( $full_search_results );
					
				}
				*/
				// Ordering by dinamic fields ----------------------
				// -------------------------------------------------
				
				$result = array(
					
					'results' => $default_search_results,
					'full_results' => $full_search_results,
					
				);
				
				$this->search->append_results( 'unid_ds_search', $result );
				
				$return = TRUE;
				
			}
			
		}
		else {
			
			log_message( 'debug', '[Plugins] Submit forms Users submits search plugin could not be executed! Search library object not initialized!' );
			
			$return = FALSE;
			
		}
		
		//parent::_set_performed( 'articles_search' );
		
		return $return;
		
	}
	
	public function get_params_spec(){
	
	}
	
}
