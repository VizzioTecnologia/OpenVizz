<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Unid_d_search_plugin extends Plugins_mdl{
	
	public function run( &$data, $params = NULL ){
		
		$return = FALSE;
		
		if ( is_object( $this->search ) ) {
			
			log_message( 'debug', '[Plugins] Unified data schemas search plugin initialized' );
			
			// -------------------------------------------------
			// Parsing vars ------------------------------------
			
			$plugin_params =									$this->search->config( 'plugins_params' );
			$plugin_params =									isset( $plugin_params[ 'unid_ds_search' ] ) ? $plugin_params[ 'unid_ds_search' ] : array();
			
			// -------------
			
			$filters =											( isset( $plugin_params[ 'filters' ] ) ) ? $plugin_params[ 'filters' ] : NULL; // array
			
			// -------------
			
			$where_conditions =									( isset( $plugin_params[ 'where_conditions' ] ) ) ? $plugin_params[ 'where_conditions' ] : NULL;
			
			// -------------
			
			$or_where_conditions =								( isset( $plugin_params[ 'or_where_conditions' ] ) ) ? $plugin_params[ 'or_where_conditions' ] : NULL;
			
			// -------------
			
			$us_id =											( isset( $plugin_params[ 'us_id' ] ) AND ( is_numeric( $plugin_params[ 'us_id' ] ) OR is_array( $plugin_params[ 'us_id' ] ) ) ) ? $plugin_params[ 'us_id' ] : NULL;
			
			// -------------
			
			$sf_id =											( isset( $plugin_params[ 'sf_id' ] ) AND is_numeric( $plugin_params[ 'sf_id' ] ) ) ? $plugin_params[ 'sf_id' ] : NULL;
			
			// -------------
			
			$random =											( isset( $plugin_params[ 'random' ] ) ) ? ( bool ) $plugin_params[ 'random' ] : FALSE;
			
			// -------------
			
			$filter_params =									( isset( $plugin_params[ 'filter_params' ] ) ) ? ( bool ) $plugin_params[ 'filter_params' ] : FALSE;
			$menu_item_id =										( isset( $plugin_params[ 'menu_item_id' ] ) AND is_numeric( $plugin_params[ 'menu_item_id' ] ) ) ? $plugin_params[ 'menu_item_id' ] : NULL;
			
			// -------------
			
			$order_by =											$this->search->config( 'order_by' );
			$order_by =											( isset( $order_by[ 'unid_ds_search' ] ) ) ? $order_by[ 'unid_ds_search' ] : 'submit_datetime';
			$order_by =											( isset( $plugin_params[ 'order_by' ] ) ) ? $plugin_params[ 'order_by' ] : $order_by;
			
			$order_by_direction =								$this->search->config( 'order_by_direction' );
			$order_by_direction =								( isset( $order_by_direction[ 'unid_ds_search' ] ) ) ? $order_by_direction[ 'unid_ds_search' ] : 'DESC';
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
			
			// Parsing vars ------------------------------------
			// -------------------------------------------------
			
			// db search params
			$dsp = array(
				
				'plugin_name' => 'unid_ds_search',
				'table_name' => 'tb_submit_forms_us t1',
				'random' => $random,
				'select_columns' => '
					
					t1.*,
					t2.title as submit_form_title,
					
				',
				'tables_to_join' => array(
					
					array( 'tb_submit_forms t2', 't1.submit_form_id = t2.id', 'left' ),
					
				),
				'search_columns' => '`t1`.`id`, `t1`.`submit_form_id`, `t1`.`submit_datetime`, `t1`.`mod_datetime`, `t1`.`data`',
				
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
			// Filters -----------------------------------------
			
			$filters_condition = NULL;
			$default_condition_array[ 'filters_condition' ] = NULL;
			
			if ( $filters ) {
				
				if ( is_array( $filters ) ) {
					
					$default_condition_array[ 'filters_condition' ] = $this->resolve_filters( $filters );
					
				}
				
			}
			
			//print_r( $default_condition_array ); exit;
			
			// Filters -----------------------------------------
			// -------------------------------------------------
			
			// -------------------------------------------------
			// User submit filtering ---------------------------
			
			$us_condition = NULL;
			$default_condition_array[ 'us_condition' ] = NULL;
			
			if ( $us_id ) {
				
				if ( is_array( $us_id ) ) {
					
					foreach ( $us_id as $key => $id ) {
						
						$us_condition[] = '`t1`.`id` = \'' . $id . '\' ';
						
					}
					
					$us_condition = ' ( ' . join( ' OR ', $us_condition ) . ' ) ';
					
				}
				else {
					
					$us_condition = '`t1`.`id` = ' . $us_id;
					
				}
				
				$default_condition_array[ 'us_condition' ] = $us_condition;
				
			}
			
			// User submit filtering ---------------------------
			// -------------------------------------------------
			
			// -------------------------------------------------
			// Submit form filtering ---------------------------
			
			$sf_condition = NULL;
			$default_condition_array[ 'sf_condition' ] = NULL;
			
			if ( $sf_id ) {
				
				$sf_condition = '`t1`.`submit_form_id` = ' . $sf_id;
				$default_condition_array[ 'sf_condition' ] = $sf_condition;
				
			}
			
			// Submit form filtering ---------------------------
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
				$search_conditions .= '`t1`.`submit_datetime` LIKE \'%' . strtolower( $terms ) . '%\' ';
				$search_conditions .= 'OR LOWER( `t1`.`output` ) LIKE \'%' . strtolower( $terms ) . '%\' ';
				$search_conditions .= 'OR LOWER( `t1`.`output` ) LIKE \'%' . strtolower( rtrim( ltrim( json_encode( $terms ), '"' ), '"' ) ) . '%\' ';
				$search_conditions .= 'OR LOWER( `t1`.`data` ) LIKE \'%"%":"%' . strtolower( $terms ) . '%"%\' COLLATE \'utf8_general_ci\' ';
				$search_conditions .= 'OR LOWER( `t1`.`data` ) LIKE \'%"%":"%' . strtolower( rtrim( ltrim( str_replace( '\\', '\\\\\\\\', json_encode( $terms ) ), '"' ), '"' ) ) . '%"%\' COLLATE \'utf8_general_ci\' ';
				$search_conditions .= 'OR LOWER( `t1`.`xml_data` ) LIKE \'%>%' . strtolower( $terms ) . '%<%\' COLLATE \'utf8_general_ci\' ';
				$search_conditions .= ')';
				
				$dsp[ 'where_conditions' ][] = $search_conditions;
				
			}
			
			// Setting terms conditions ------------------------
			// -------------------------------------------------
			
			$order_by_allowed = FALSE;
			$order_by_is_int = FALSE;
			$order_by_is_date = FALSE;
			
			if ( $sf_id ) {
				
				// get submit form params
				$gsfp = array(
					
					'where_condition' => 't1.id = ' . $sf_id,
					'limit' => 1,
					
				);
				
				$submit_form = $this->sfcm->get_submit_forms( $gsfp )->row_array();
				
				$this->sfcm->parse_sf( $submit_form );
				
				$i = 0;
				
				foreach( $submit_form[ 'fields' ] as $k => & $field ) {
					
					if ( $order_by AND ! in_array( $order_by, array( 'id', 'submit_datetime', 'mod_datetime', ) ) AND $order_by == $field[ 'alias' ] ) {
						
						$order_by_allowed = TRUE;
						
					}
					
					if ( $order_by == $field[ 'alias' ] ) {
						
						if ( isset( $field[ 'validation_rule' ] ) ) {
							
							foreach( $field[ 'validation_rule' ] as $rule ) {
								
								if ( $rule == 'integer' ) {
									
									$order_by_is_int = TRUE;
									
								}
								
							}
							
						}
						if ( in_array( $field[ 'field_type' ], array( 'date' ) ) ) {
							
							$order_by_is_date = TRUE;
							
						}
						
					}
					
					if ( ! in_array( $field[ 'field_type' ], array( 'button', 'html' ) ) AND check_var( $field[ 'alias' ] ) ) {
						
						if ( in_array( $field[ 'field_type' ], array( 'combo_box', 'radiobox', 'checkbox', ) ) AND check_var( $field[ 'options_from_users_submits' ] ) ) {
							
							$dsp[ 'select_columns' ] .= ', EXTRACTVALUE( `__us' . $i . 'sf' . $field[ 'options_from_users_submits' ] . '__`.`xml_data`,  \'//' . $field[ 'options_title_field' ] . '\' ) AS `' . $field[ 'alias' ] . '`';
							$dsp[ 'tables_to_join' ][] = array( 'tb_submit_forms_us `__us' . $i . 'sf' . $field[ 'options_from_users_submits' ] . '__`', 'EXTRACTVALUE( `t1`.`xml_data`, \'//' . $field[ 'alias' ] . '\' ) = `__us' . $i . 'sf' . $field[ 'options_from_users_submits' ] . '__`.`id`', 'left', FALSE );
							
							$i++;
							
						}
						else {
							
							$dsp[ 'select_columns' ] .= ', EXTRACTVALUE( `t1`.`xml_data`,  \'//' . $field[ 'alias' ] . '\' ) AS `' . $field[ 'alias' ] . '`';
							
						}
						
					}
					
				}
				
				$dsp[ 'select_escape' ] = FALSE;
				
			}
			
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
				
				else if ( ! in_array( $order_by, array( 'id', 'submit_datetime', 'mod_datetime',  ) ) AND $sf_id ) {
					
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
					
					$this->sfcm->parse_us( $search_result, $filter_params, $menu_item_id );
					
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
	
	protected function resolve_filters( $filters ){
		
		if ( is_array( $filters ) ) {
			
			$out = '';
			
			$out .= '( ';
			
			$i = 0;
			
			//print_r( $filters ); exit;
			
			foreach( $filters as $k => $filter ) {
				
				if ( is_array( $filter ) AND isset( $filter[ 'alias' ] ) AND isset( $filter[ 'comp_op' ] ) AND isset( $filter[ 'value' ] ) ) {
					
					if ( $i > 0) {
						
						if ( isset( $filter[ 'log_op' ] ) ) {
							
							$out .= ' ' . strtoupper( $filter[ 'log_op' ] ) . ' ';
							
						}
						else {
							
							$out .= ' AND ';
							
						}
						
					}
					
					if ( ! in_array( $filter[ 'alias' ], array( 'id', 'submit_datetime', 'mod_datetime' ) ) ) {
						
						$_column = 'EXTRACTVALUE( `t1`.`xml_data`, \'//' . trim( $filter[ 'alias' ] ) . '\' )';
						
					}
					else {
						
						$_column = '`t1`.`' . $filter[ 'alias' ] . '`';
						
					}
					
					$value_type = 'str';
					
					if ( isset( $filter[ 'value_type' ] ) ) {
						
						switch ( $filter[ 'value_type' ] ) {
							
							case 'num':
								
								$value_type = 'num';
								
							case 'func':
								
								$value_type = 'func';
								
						}
						
					}
					
					if ( $value_type == 'str' ) {
						
						$out .= $_column . ' ' . $filter[ 'comp_op' ] . ' \'' . $filter[ 'value' ] . '\'';
						
					}
					else if ( $value_type == 'num' ) {
						
						$out .= $_column . ' ' . $filter[ 'comp_op' ] . ' ' . $filter[ 'value' ];
						
					}
					else if ( $value_type == 'func' ) {
						
						$out .= $_column . ' ' . $filter[ 'comp_op' ] . ' ' . $filter[ 'value' ];
						
					}
					
				}
				else if ( is_array( $filter ) ) {
					
					if ( $i > 0) {
						
						if ( isset( $filter[ 'log_op' ] ) ) {
							
							$out .= ' ' . strtoupper( $filter[ 'log_op' ] ) . ' ';
							
						}
						else {
							
							$out .= ' AND ';
							
						}
						
					}
					
					$out .= $this->resolve_filters( $filter );
					
				}
				
				$i++;
				
			}
			
			$out .= ' )';
			
			return $out;
			
		}
		
	}
	
	public function get_params_spec(){
	
	}
	
}
