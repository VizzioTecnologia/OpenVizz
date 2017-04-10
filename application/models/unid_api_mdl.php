<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Unid_api_mdl extends CI_Model{
	
	private $_internal_mode = FALSE;
	private $_skip_access_check = FALSE;
	private $_translate_return_codes = 1;
	
	// --------------------------------------------------------------------
	
	public function __invoke( $config, $internal_mode = NULL, $_skip_access_check = NULL ) {
		
		return $this->api( $config, $internal_mode, $_skip_access_check );
		
    }
    
	// --------------------------------------------------------------------
	
	public function __construct(){
		
		$this->load->language( 'unid_api' );
		
		if ( ! $this->load->is_model_loaded( 'unid' ) ) {
			
			$this->load->model( 'unid_mdl', 'unid' );
			
		}
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Return the message status, in code or human friendly format
	 * Maybe be good in some situations to obfuscate the return status code description.
	 *
	 * @access public
	 * @return array
	 */
	
	public function get_api_return_status( $return_code, $arg1 = NULL, $arg2 = NULL ) {
		
		$return_codes = array(
			
			// general
			
			'e_0' => lang( 'err_udapi_ani', NULL, $arg1, $arg2 ), // action not informed
			'e_1' => lang( 'err_udapi_iinai', NULL, $arg1, $arg2 ), // informed id(s) is not a integer
			'e_2' => lang( 'err_udapi_ua', NULL, $arg1, $arg2 ), // unknow action
			'e_3' => lang( 'err_udapi_lwp', NULL, $arg1, $arg2 ), // login wrong password
			'e_4' => lang( 'err_udapi_lunf', NULL, $arg1, $arg2 ), // login user not found
			'e_5' => lang( 'err_udapi_ipfto', NULL, $arg1, $arg2 ), // insufficient privileges for this operation
			'e_6' => lang( 'err_udapi_acd', NULL, $arg1, $arg2 ), // access denied
			'e_7' => lang( 'err_udapi_tarl', NULL, $arg1, $arg2 ), // this action require login
			'e_8' => lang( 'err_udapi_anf', NULL, $arg1, $arg2 ), // article not found
			'e_9' => lang( 'err_udapi_iuptaa', NULL, $arg1, $arg2 ), // insufficient user privileges to access UniD's API
			'e_10' => lang( 'err_udapi_lr', NULL, $arg1, $arg2 ), // login required
			'e_11' => lang( 'err_udapi_luni', NULL, $arg1, $arg2 ), // login - username not informed
			'e_12' => lang( 'err_udapi_lpni', NULL, $arg1, $arg2 ), // login - password not informed
			'e_13' => lang( 'err_udapi_lunf', NULL, $arg1, $arg2 ), // login - user not found
			'e_14' => lang( 'err_udapi_lwp', NULL, $arg1, $arg2 ), // login - wrong password
			
			// get data scheme
			
			'e_10000' => lang( 'err_udapi_dsini', NULL, $arg1, $arg2 ), // data scheme id not informed
			'e_10001' => lang( 'err_udapi_dsnf', NULL, $arg1, $arg2 ), //  data scheme not found
			'e_10002' => lang( 'err_udapi_ad', NULL, $arg1, $arg2 ), // api disabled
			'e_10003' => lang( 'err_udapi_dslwp', NULL, $arg1, $arg2 ), // ds login - wrong password
			'e_10004' => lang( 'err_udapi_ip', NULL, $arg1, $arg2 ), // insufficient privileges
			'e_10005' => lang( 'err_udapi_adftsu', NULL, $arg1, $arg2 ), // access denied for the specified user
			'e_10006' => lang( 'err_udapi_dslunf', NULL, $arg1, $arg2 ), // ds login - user not found
			'e_10007' => lang( 'err_udapi_dsarl', NULL, $arg1, $arg2 ), // data scheme access require login
			'e_10008' => lang( 'err_udapi_dsad', NULL, $arg1, $arg2 ), // data scheme access denied
			'e_10009' => lang( 'err_udapi_ndsf', NULL, $arg1, $arg2 ), // no data scheme found
			
			// get data property
			
			'e_20000' => lang( 'err_udapi_dini', NULL, $arg1, $arg2 ), // data id not informed
			'e_20001' => lang( 'err_udapi_pani', NULL, $arg1, $arg2 ), // property alias not informed
			'e_20002' => lang( 'err_udapi_pnitds', NULL, $arg1, $arg2 ), // property not in the data schema
			'e_20003' => lang( 'err_udapi_dnf', NULL, $arg1, $arg2 ), //  data not found
			
			// set data property
			
			'e_30000' => lang( 'err_udapi_nvni', NULL, $arg1, $arg2 ), //  new value not informed
			'e_30001' => lang( 'err_udapi_cud', NULL, $arg1, $arg2 ), //  can't update data
			'e_30002' => lang( 'err_udapi_ndni', NULL, $arg1, $arg2 ), //  new data not informed
			'e_30003' => lang( 'err_udapi_ind', NULL, $arg1, $arg2 ), //  invalid new data
			
			's_30000' => lang( 'suc_udapi_dus', NULL, $arg1, $arg2 ), // data updated successfuly
			
			// get data
			
			'e_40000' => lang( 'err_udapi_if', NULL, $arg1, $arg2 ), //  invalid filters
			'e_40001' => lang( 'err_udapi_ndf', NULL, $arg1, $arg2 ), //  No UniD Data found
			
			// db
			
			'e_5000' => lang( 'err_udapi_dbdsnf', NULL, $arg1, $arg2 ), // DB - data scheme not found
			'e_5001' => lang( 'err_udapi_dbdini', NULL, $arg1, $arg2 ), // DB - data and id not informed
			
			
		);
		
		if ( $this->_translate_return_codes ) {
			
			return array(
				
				$return_code => $return_codes[ $return_code ],
				
			);
			
		}
		
		return $return_code;
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Get the current url params and return the params allowed
	 *
	 * @access private
	 * @return array
	 */
	
	public function api( $config, $internal_mode = NULL, $_skip_access_check = NULL ) {
		
		$post = $this->input->post( NULL, TRUE );
		
		if ( $internal_mode ) {
			
			$this->_internal_mode = TRUE;
			
			if ( isset( $post[ 'u' ] ) ) unset( $post[ 'u' ] );
			if ( isset( $post[ 'p' ] ) ) unset( $post[ 'p' ] );
			
		}
		
		if ( $_skip_access_check ) {
			
			$this->_skip_access_check = TRUE;
			
		}
		
		$act =												isset( $post[ 'a' ] ) ?  urldecode( $post[ 'a' ] ) : ( isset( $config[ 'a' ] ) ? urldecode( $config[ 'a' ] ) : NULL ); // action
		$username =											isset( $post[ 'u' ] ) ?  urldecode( $post[ 'u' ] ) : ( isset( $config[ 'u' ] ) ? urldecode( $config[ 'u' ] ) : NULL ); // user
		$password =											isset( $post[ 'p' ] ) ?  urldecode( $post[ 'p' ] ) : ( isset( $config[ 'p' ] ) ? urldecode( $config[ 'p' ] ) : NULL ); // password
		$rt =												isset( $post[ 'rt' ] ) ?  urldecode( $post[ 'rt' ] ) : ( isset( $config[ 'rt' ] ) ? urldecode( $config[ 'rt' ] ) : 'json' ); // Return type: json, php_print_r
		
		if ( ! isset( $act ) ) {
			
			$out[ 'errors' ][] = $this->get_api_return_status( 'e_0' );
			
		}
		else {
			
			set_current_component( 'submit_forms' );
			
			// -------------------------------------
			// Params filtering
			
			// obtendo os parâmetros globais do componente
			$component_params = $this->current_component[ 'params' ];
			
			$filtered_params = $component_params;
			
			// -------------------------------------
			// Get UniD Data Scheme
			// Restriction checking by Data Scheme
			// URL / POST
			
			if ( $act == 'gds' ) {
				
				$dsids =										isset( $post[ 'dsi' ] ) ? urldecode( $post[ 'dsi' ] ) : ( isset( $config[ 'dsi' ] ) ? urldecode( $config[ 'dsi' ] ) : NULL ); // Data scheme ID (Submit form id). Cold be multiple ID's
				
				$ctl =										isset( $post[ 'ctl' ] ) ? urldecode( $post[ 'ctl' ] ) : ( isset( $config[ 'ctl' ] ) ? urldecode( $config[ 'ctl' ] ) : '*' ); // columns to load
				
					if ( strpos( $dsids, '|' ) ) {
						
						$dsids = explode( '|', $dsids );
						
					}
					else {
						
						$dsids = array( $dsids );
						
					}
					
					if ( strpos( $ctl, '|' ) ) {
						
						$ctl = explode( '|', $ctl );
						
					}
					
				if ( check_var( $dsids ) ) {
					
					foreach( $dsids as $k => $dsid ) {
						
						if ( ! $this->_validate_int_id( $dsid ) ) {
							
							$out[ 'errors' ][] = $this->get_api_return_status( 'e_1', $dsid );
							
						}
						else {
							
							// get data scheme params
							$gdsp = array(
								
								'where_condition' => 't1.id = ' . $dsid,
								'cols' => $ctl,
								'limit' => 1,
								
							);
							
							if ( ! ( $data_scheme = $this->get_data_schemes( $gdsp )->row_array() ) ) {
								
								$out[ 'errors' ][] = $this->get_api_return_status( 'e_10001', $dsid );
								
							}
							else {
								
								$this->parse_ds( $data_scheme );
								
								// -------------------------------------------------
								// Params filtering
								
								$data_scheme[ 'params' ] = filter_params( $filtered_params, $data_scheme[ 'params' ] );
								
								// -------------------------------------------------
								// Check if UniD Data Scheme's API is enabled
								
								$api_status = $this->_check_ds_api_status( $data_scheme );
								
								if ( isset( $api_status[ 'errors' ] ) ) {
									
									$out[ 'errors' ] = isset( $out[ 'errors' ] ) ? array_merge( $out[ 'errors' ], $api_status[ 'errors' ] ) : $api_status[ 'errors' ];
									
								}
								else {
									
									// -------------------------------------------------
									// Check UniD Data Scheme access restritions
									
									$api_access = $this->_check_ds_api_access( $data_scheme, $username, $password );
									
									if ( isset( $api_access[ 'errors' ] ) ) {
										
										$out[ 'errors' ] = isset( $out[ 'errors' ] ) ? array_merge( $out[ 'errors' ], $api_access[ 'errors' ] ) : $api_access[ 'errors' ];
										
									}
									else {
										
										$out[ 'out' ][ 'data_schemes' ][ $dsid ] = $data_scheme;
										
									}
									
								}
								
							}
							
						}
						
					}
					
				}
				else {
					
					// get data scheme params
					$gdsp = array(
						
						'cols' => $ctl,
						
					);
					
					if ( ! ( $data_schemes = $this->get_data_schemes( $gdsp )->result_array() ) ) {
						
						$out[ 'errors' ][] = $this->get_api_return_status( 'e_10009' );
						
					}
					else {
						
						reset( $data_schemes );
						
						while ( list( $k, $data_scheme ) = each( $data_schemes ) ) {
							
							$this->parse_ds( $data_scheme );
							
							// -------------------------------------------------
							// Params filtering
							
							$data_scheme[ 'params' ] = filter_params( $filtered_params, $data_scheme[ 'params' ] );
							
							// -------------------------------------------------
							// Check if UniD Data Scheme's API is enabled
							
							$api_status = $this->_check_ds_api_status( $data_scheme );
							
							if ( isset( $api_status[ 'errors' ] ) ) {
								
								$out[ 'errors' ] = isset( $out[ 'errors' ] ) ? array_merge( $out[ 'errors' ], $api_status[ 'errors' ] ) : $api_status[ 'errors' ];
								
							}
							else {
								
								// -------------------------------------------------
								// Check UniD Data Scheme access restritions
								
								$api_access = $this->_check_ds_api_access( $data_scheme, $username, $password );
								
								if ( isset( $api_access[ 'errors' ] ) ) {
									
									$out[ 'errors' ] = isset( $out[ 'errors' ] ) ? array_merge( $out[ 'errors' ], $api_access[ 'errors' ] ) : $api_access[ 'errors' ];
									
								}
								else {
									
									$_out_ds = array();
									
									reset( $data_scheme );
									
									while ( list( $col, $col_value ) = each( $data_scheme ) ) {
										
										if ( ( is_array( $ctl ) AND in_array( $col, $ctl ) ) OR $ctl == $col ) {
											
											$_out_ds[ $col ] = $data_scheme[ $col ];
											
										}
										
									}
									
									$out[ 'out' ][ 'data_schemes' ][ $data_scheme[ 'id' ] ] = $_out_ds;
									
								}
								
							}
							
						}
						
					}
					
				}
				
			}
			
			// -------------------------------------
			// Get UniD Data Scheme Property
			// Restriction checking by Data Scheme
			// URL / POST
			
			else if ( $act == 'gdsp' ) {
				
				$dsid =										isset( $post[ 'dsi' ] ) ?  urldecode( $post[ 'dsi' ] ) : ( isset( $config[ 'dsi' ] ) ? urldecode( $config[ 'dsi' ] ) : NULL ); // Data Scheme id (submit form id)
				$pa =										isset( $post[ 'pa' ] ) ?  urldecode( $post[ 'pa' ] ) : ( isset( $config[ 'pa' ] ) ? urldecode( $config[ 'pa' ] ) : NULL ); // Property alias
				
				if ( ! check_var( $dsid ) ) {
					
					$out[ 'errors' ][] = $this->get_api_return_status( 'e_10000' );
					
				}
				else {
					
					if ( ! isset( $pa ) ) {
						
						$out[ 'errors' ][] = $this->get_api_return_status( 'e_20001' );
						
					}
					else {
						
						if ( ! $this->_validate_int_id( $dsid ) ) {
							
							$out[ 'errors' ][] = $this->get_api_return_status( 'e_1', $dsid );
							
						}
						else {
							
							// get submit form params
							$gdsp = array(
								
								'where_condition' => 't1.id = ' . $dsid,
								'limit' => 1,
								
							);
							
							$data_scheme = $this->get_data_schemes( $gdsp )->row_array();
							
							if ( ! $data_scheme ) {
								
								$out[ 'errors' ][] = $this->get_api_return_status( 'e_10001', $dsid );
								
							}
							else {
								
								$this->parse_ds( $data_scheme );
								
								// -------------------------------------------------
								// Params filtering
								
								$data_scheme[ 'params' ] = filter_params( $filtered_params, $data_scheme[ 'params' ] );
								
								// -------------------------------------------------
								// Check if UniD Data Scheme's API is enabled
								
								$api_status = $this->_check_ds_api_status( $data_scheme );
								
								if ( isset( $api_status[ 'errors' ] ) ) {
									
									$out[ 'errors' ] = isset( $out[ 'errors' ] ) ? array_merge( $out[ 'errors' ], $api_status[ 'errors' ] ) : $api_status[ 'errors' ];
									
								}
								else {
									
									// -------------------------------------------------
									// Check UniD Data Scheme access restritions
									
									$api_access = $this->_check_ds_api_access( $data_scheme, $username, $password );
									
									if ( isset( $api_access[ 'errors' ] ) ) {
										
										$out[ 'errors' ] = isset( $out[ 'errors' ] ) ? array_merge( $out[ 'errors' ], $api_access[ 'errors' ] ) : $api_access[ 'errors' ];
										
									}
									else {
										
										if ( ! isset( $data_scheme[ 'fields' ][ $pa ] ) ) {
											
											$out[ 'errors' ][] = $this->get_api_return_status( 'e_20002', $dsid, $pa );
											
										}
										else {
											
											$out[ 'out' ] = $data_scheme[ 'fields' ][ $pa ];
											
										}
										
									}
									
								}
								
							}
							
						}
						
					}
					
				}
				
			}
			
			// -------------------------------------
			// Get UniD Data Scheme Property Options
			// Restriction checking by Data Scheme
			// URL / POST
			
			else if ( $act == 'gdspo' ) {
				
				$dsid =										isset( $post[ 'dsi' ] ) ?  urldecode( $post[ 'dsi' ] ) : ( isset( $config[ 'dsi' ] ) ? urldecode( $config[ 'dsi' ] ) : NULL ); // Data Scheme id (submit form id)
				$pa =										isset( $post[ 'pa' ] ) ?  urldecode( $post[ 'pa' ] ) : ( isset( $config[ 'pa' ] ) ? urldecode( $config[ 'pa' ] ) : NULL ); // Property alias
				
				if ( ! check_var( $dsid ) ) {
					
					$out[ 'errors' ][] = $this->get_api_return_status( 'e_10000' );
					
				}
				else {
					
					if ( ! isset( $pa ) ) {
						
						$out[ 'errors' ][] = $this->get_api_return_status( 'e_20001' );
						
					}
					else {
						
						if ( ! $this->_validate_int_id( $dsid ) ) {
							
							$out[ 'errors' ][] = $this->get_api_return_status( 'e_1', $dsid );
							
						}
						else {
							
							// get submit form params
							$gdsp = array(
								
								'where_condition' => 't1.id = ' . $dsid,
								'limit' => 1,
								
							);
							
							$data_scheme = $this->get_data_schemes( $gdsp )->row_array();
							
							if ( ! $data_scheme ) {
								
								$out[ 'errors' ][] = $this->get_api_return_status( 'e_10001', $dsid );
								
							}
							else {
								
								$this->parse_ds( $data_scheme );
								
								// -------------------------------------------------
								// Params filtering
								
								$data_scheme[ 'params' ] = filter_params( $filtered_params, $data_scheme[ 'params' ] );
								
								// -------------------------------------------------
								// Check if UniD Data Scheme's API is enabled
								
								$api_status = $this->_check_ds_api_status( $data_scheme );
								
								if ( isset( $api_status[ 'errors' ] ) ) {
									
									$out[ 'errors' ] = isset( $out[ 'errors' ] ) ? array_merge( $out[ 'errors' ], $api_status[ 'errors' ] ) : $api_status[ 'errors' ];
									
								}
								else {
									
									// -------------------------------------------------
									// Check UniD Data Scheme access restritions
									
									$api_access = $this->_check_ds_api_access( $data_scheme, $username, $password );
									
									if ( isset( $api_access[ 'errors' ] ) ) {
										
										$out[ 'errors' ] = isset( $out[ 'errors' ] ) ? array_merge( $out[ 'errors' ], $api_access[ 'errors' ] ) : $api_access[ 'errors' ];
										
									}
									else {
										
										if ( ! isset( $data_scheme[ 'fields' ][ $pa ] ) ) {
											
											$out[ 'errors' ][] = $this->get_api_return_status( 'e_20002', $dsid, $pa );
											
										}
										else {
											
											if ( check_var( $data_scheme[ 'fields' ][ $pa ][ 'options_from_users_submits' ] ) AND check_var( $data_scheme[ 'fields' ][ $pa ][ 'options_title_field' ] ) ) {
												
												$_POST = array(
													
													'a' => 'gd',
													'u' => urlencode( $username ),
													'p' => urlencode( $password ),
													'rt' => 'json',
													'f' => urlencode(
														
														json_encode(
															
															array(
																
																array(
																	
																	'alias' => 'submit_form_id',
																	'comp_op' => '=',
																	'value' => $data_scheme[ 'fields' ][ $pa ][ 'options_from_users_submits' ],
																	'value_type' => 'num',
																	
																),
																
															)
															
														)
														
													)
													
												);
												
												$_tmp = $this->api( $_POST );
												
												if ( ! is_array( $_tmp ) ) $_tmp = json_decode( $_tmp, TRUE );
												
												if ( check_var( $_tmp[ 'out' ] ) ) {
													
													while ( list( $key, $ud_data ) = each( $_tmp[ 'out' ] ) ) {
														
														$out[ 'out' ][ $ud_data[ 'id' ] ] = $ud_data[ 'data' ][ $data_scheme[ 'fields' ][ $pa ][ 'options_title_field' ] ];
														
													}
													
												}
												
												$_tmp = NULL;
												unset( $_tmp );
												
											}
											else if ( check_var( $data_scheme[ 'fields' ][ $pa ][ 'options' ] ) ) {
												
												$options = explode( "\n", $data_scheme[ 'fields' ][ $pa ][ 'options' ] );
												
												while ( list( $key, $option ) = each( $options ) ) {
													
													$out[ 'out' ][ $option ] = $option;
													
												}
												
											}
											else {
												
												$out[ 'out' ] = array(
													
													1 => $data_scheme[ 'fields' ][ $pa ][ 'label' ],
													
												);
												
											}
											
										}
										
									}
									
								}
								
							}
							
						}
						
					}
					
				}
				
			}
			
			// -------------------------------------
			// Get UniD Data
			// Restriction checking by Data Scheme
			// URL / POST
			
			else if ( $act == 'gd' ) {
				
				$f =										isset( $post[ 'f' ] ) ?  urldecode( $post[ 'f' ] ) : ( isset( $config[ 'f' ] ) ? urldecode( $config[ 'f' ] ) : NULL ); // Filters
				
				$f = get_params( $f );
				
				if ( ! ( is_array( $f ) ) ) {
					
					$out[ 'errors' ][] = $this->get_api_return_status( 'e_40000' );
					
				}
				else {
					
					$search_config = array(
						
						'plugins' => 'sf_us_search',
						'ignore_cache' => TRUE,
						'allow_empty_terms' => TRUE,
						'plugins_params' => array(
							
							'sf_us_search' => array(
								
								'filters' => $f,
								
							),
							
						),
						
					);
					
					$this->load->library( 'search' );
					$this->search->config( $search_config );
					
					$ud_data = $this->search->get_full_results( 'sf_us_search', TRUE );
					
					if ( check_var( $ud_data ) ) {
						
						$out[ 'out' ] = $ud_data;
						
					}
					else {
						
						$out[ 'errors' ][] = $this->get_api_return_status( 'e_40001' );
						
					}
					
				}
				
			}
			
			// -------------------------------------
			// Get UniD Data Property
			// Restriction checking by Data Scheme
			// URL / POST
			
			else if ( $act == 'gdp' ) {
				
				$dtid =										isset( $post[ 'di' ] ) ?  urldecode( $post[ 'di' ] ) : ( isset( $config[ 'di' ] ) ? urldecode( $config[ 'di' ] ) : NULL ); // UniD Data id (user submit id)
				$pa =										isset( $post[ 'pa' ] ) ?  urldecode( $post[ 'pa' ] ) : ( isset( $config[ 'pa' ] ) ? urldecode( $config[ 'pa' ] ) : NULL ); // UniD Data Property alias
				$pp =										isset( $post[ 'pp' ] ) ?  ( bool ) ( int ) urldecode( $post[ 'pp' ] ) : ( isset( $config[ 'pp' ] ) ? ( bool ) ( int ) urldecode( $config[ 'pp' ] ) : FALSE ); // Parse UniD Data Property
				$hv =										isset( $post[ 'hv' ] ) ?  ( bool ) ( int ) urldecode( $post[ 'hv' ] ) : ( isset( $config[ 'hv' ] ) ? ( bool ) ( int ) urldecode( $config[ 'hv' ] ) : FALSE ); // Html value
				
				if ( ! check_var( $dtid ) ) {
					
					$out[ 'errors' ][] = $this->get_api_return_status( 'e_20000' );
					
				}
				else {
					
					if ( ! $this->_validate_int_id( $dtid ) ) {
						
						$out[ 'errors' ][] = $this->get_api_return_status( 'e_1', $dtid );
						
					}
					else {
						
						if ( ! isset( $pa ) ) {
							
							$out[ 'errors' ][] = $this->get_api_return_status( 'e_20001' );
							
						}
						else {
							
							$data[ 'ud_data' ] = array();
							
							$this->load->library( 'search' );
							
							$search_config = array(
								
								'plugins' => 'sf_us_search',
								'allow_empty_terms' => TRUE,
								'plugins_params' => array(
									
									'sf_us_search' => array(
										
										'us_id' => $dtid,
										
									),
									
								),
								
							);
							
							$this->search->config( $search_config );
							
							if ( ! ( $ud_data =  $this->search->get_full_results( 'sf_us_search', TRUE ) ) ) {
								
								$out[ 'errors' ][] = $this->get_api_return_status( 'e_20003', $dtid );
								
							}
							else {
								
								$ud_data = reset( $ud_data );
								
								// Getting the UniD Data's Data Scheme
								
								$dsid = $ud_data[ 'submit_form_id' ];
								
								if ( ! $this->_validate_int_id( $dsid ) ) {
									
									$out[ 'errors' ][] = $this->get_api_return_status( 'e_1', $dsid );
									
								}
								else {
									
									// get data scheme function params
									$gdsp = array(
										
										'where_condition' => 't1.id = ' . $dsid,
										'limit' => 1,
										
									);
									
									if ( ! ( $data_scheme = $this->get_data_schemes( $gdsp )->row_array() ) ) {
										
										$out[ 'errors' ][] = $this->get_api_return_status( 'e_10001', $dsid );
										
									}
									else {
										
										$this->parse_ds( $data_scheme );
										
										// -------------------------------------------------
										// Params filtering
										
										$data_scheme[ 'params' ] = filter_params( $filtered_params, $data_scheme[ 'params' ] );
										
										// -------------------------------------------------
										// Check if UniD Data Scheme's API is enabled
										
										$api_status = $this->_check_ds_api_status( $data_scheme );
										
										if ( isset( $api_status[ 'errors' ] ) ) {
											
											$out[ 'errors' ] = isset( $out[ 'errors' ] ) ? array_merge( $out[ 'errors' ], $api_status[ 'errors' ] ) : $api_status[ 'errors' ];
											
										}
										else {
											
											// -------------------------------------------------
											// Check UniD Data Scheme access restritions
											
											$api_access = $this->_check_ds_api_access( $data_scheme, $username, $password );
											
											if ( isset( $api_access[ 'errors' ] ) ) {
												
												$out[ 'errors' ] = isset( $out[ 'errors' ] ) ? array_merge( $out[ 'errors' ], $api_access[ 'errors' ] ) : $api_access[ 'errors' ];
												
											}
											else {
												
												if ( ! isset( $ud_data[ 'data' ][ $pa ] ) ) {
													
													if ( ! check_var( $data_scheme ) ) {
														
														$out[ 'warnings' ][] = $this->get_api_return_status( 'e_10001', $ud_data[ 'submit_form_id' ] );
														
													}
													else {
														
														if ( ! check_var( $data_scheme[ 'fields' ][ $pa ] ) ) {
															
															$out[ 'errors' ][] = $this->get_api_return_status( 'e_20002', $dsid, $pa );
															
														}
														else {
															
															$out[ 'out' ] = "";
															
														}
														
													}
													
												}
												else {
													
													if ( ( int ) $pp === 1 ) {
														
														$out[ 'out' ] = $this->parse_ud_data( $ud_data, NULL, $hv );
														$out[ 'out' ] = $out[ 'out' ][ 'parsed_data' ][ 'full' ][ $pa ][ 'value' ];
														
													}
													else {
														
														$out[ 'out' ] = $ud_data[ 'data' ][ $pa ];
														
													}
													
												}
												
											}
											
										}
										
									}
									
								}
								
							}
							
						}
						
					}
					
				}
				
			}
			
			// -------------------------------------
			// Update UniD Data Property
			// Require login
			// Restriction checking by Data Scheme
			// POST
			
			else if ( $act == 'udp' ) {
				
				if ( ! isset( $im ) ) {
					
					$config = $this->input->post( NULL, TRUE );
					
				}
				
				$dtid =										isset( $config[ 'di' ] ) ? urldecode( $config[ 'di' ] ) : NULL; // UniD Data id (user submit id)
				$pa =										isset( $config[ 'pa' ] ) ? urldecode( $config[ 'pa' ] ) : NULL; // Property alias
				$nv =										isset( $config[ 'nv' ] ) ? $config[ 'nv' ] : NULL; // New value
				
				$user = $this->_check_user_access( $username, $password );
				
				if ( isset( $user[ 'errors' ] ) ) {
					
					$out[ 'errors' ] = isset( $out[ 'errors' ] ) ? array_merge( $out[ 'errors' ], $user[ 'errors' ] ) : $user[ 'errors' ];
					
				}
				else {
					
					if ( ! check_var( $dtid ) ) {
						
						$out[ 'errors' ][] = $this->get_api_return_status( 'e_20000' );
						
					}
					else if ( ! $this->_validate_int_id( $dtid ) ) {
						
						$out[ 'errors' ][] = $this->get_api_return_status( 'e_1', $dtid );
						
					}
					else {
						
						if ( ! isset( $pa ) ) {
							
							$out[ 'errors' ][] = $this->get_api_return_status( 'e_20001' );
							
						}
						else {
							
							if ( ! isset( $nv ) ) {
								
								$out[ 'errors' ][] = $this->get_api_return_status( 'e_30000' );
								
							}
							else {
							
								$data[ 'ud_data' ] = array();
								
								$this->load->library( 'search' );
								
								$search_config = array(
									
									'plugins' => 'sf_us_search',
									'allow_empty_terms' => TRUE,
									'plugins_params' => array(
										
										'sf_us_search' => array(
											
											'us_id' => $dtid,
											
										),
										
									),
									
								);
								
								$this->search->config( $search_config );
								
								if ( ! ( $ud_data =  $this->search->get_full_results( 'sf_us_search', TRUE ) ) ) {
									
									$out[ 'errors' ][] = $this->get_api_return_status( 'e_20003', $dtid );
									
								}
								else {
									
									$ud_data = reset( $ud_data );
									
									$current_prop_value = isset( $ud_data[ 'data' ][ $pa ] ) ? $ud_data[ 'data' ][ $pa ] : '';
									
									$data_scheme = FALSE;
									$dsid = $ud_data[ 'submit_form_id' ];
									
									$out[ 'out' ] = '';
									
									if ( ! $this->_validate_int_id( $dsid ) ) {
										
										$out[ 'errors' ][] = $this->get_api_return_status( 'e_1', $dsid );
										
									}
									else {
										
										// get data scheme function params
										$gdsp = array(
											
											'where_condition' => 't1.id = ' . $dsid,
											'limit' => 1,
											
										);
										
										if ( ! ( $data_scheme = $this->get_data_schemes( $gdsp )->row_array() ) ){
											
											$out[ 'errors' ][] = $this->get_api_return_status( 'e_10001', $dsid );
											
										}
										else {
											
											$this->parse_ds( $data_scheme );
											
											// -------------------------------------------------
											// Params filtering
											
											$data_scheme[ 'params' ] = filter_params( $filtered_params, $data_scheme[ 'params' ] );
											
											// -------------------------------------------------
											// Check if UniD Data Scheme's API is enabled
											
											$api_status = $this->_check_ds_api_status( $data_scheme );
											
											if ( isset( $api_status[ 'errors' ] ) ) {
												
												$out[ 'errors' ] = isset( $out[ 'errors' ] ) ? array_merge( $out[ 'errors' ], $api_status[ 'errors' ] ) : $api_status[ 'errors' ];
												
											}
											else {
												
												// -------------------------------------------------
												// Check UniD Data Scheme access restritions
												
												$api_access = $this->_check_ds_api_access( $data_scheme, $username, $password );
												
												if ( isset( $api_access[ 'errors' ] ) ) {
													
													$out[ 'errors' ] = isset( $out[ 'errors' ] ) ? array_merge( $out[ 'errors' ], $api_access[ 'errors' ] ) : $api_access[ 'errors' ];
													
												}
												else {
													
													if ( ! check_var( $data_scheme[ 'fields' ][ $pa ] ) ) {
														
														$out[ 'errors' ][] = $this->get_api_return_status( 'e_20002', $dsid, $pa );
														
													}
													else {
														
														$prop = $data_scheme[ 'fields' ][ $pa ];
														
														$xss_filtering = TRUE;
														
														if ( isset( $data_scheme[ 'params' ][ 'ud_data_props_enable_xss_filtering_site' ] ) AND ! check_var( $data_scheme[ 'params' ][ 'ud_data_props_enable_xss_filtering_site' ] ) ) {
															
															$xss_filtering = FALSE;
															
														}
														
														$post[ 'nv' ] = $this->input->post( 'nv', $xss_filtering );
														
														$nv = ! is_array( $post[ 'nv' ] ) ? urldecode( $post[ 'nv' ] ) : $post[ 'nv' ];
														
														// -------------------------------------
														// Validating received data
														
														$validation_result = $this->_validate_ud_data_property( $data_scheme, $pa, $nv );
														
														if ( isset( $validation_result[ 'errors' ] ) ){
															
															$out[ 'errors' ] = $validation_result[ 'errors' ];
															
														}
														else {
															
															// We need set this variable again, because some validation rules may change its values on $this->input->post()
															$nv = $this->input->post( 'nv', $xss_filtering );
															
															if ( $prop[ 'field_type' ] == 'textarea' AND ! is_array( $nv ) ) {
																
																$nv = htmlspecialchars( $nv );
																
															}
															
															$ud_data[ 'data' ][ $pa ] = $nv;
															
															$mod_datetime = gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
															$mod_datetime = strftime( '%Y-%m-%d %T', $mod_datetime );
															
															$db_data[ 'mod_datetime' ] = $mod_datetime;
															$ud_data[ 'params' ][ 'modified_by_user_id' ] = $user[ 'id' ];
															
															// -----------------------------------------------
															// Inserting data into DB
															
															$db_data[ 'id' ] = $dtid;
															$db_data[ 'params' ] = json_encode( $ud_data[ 'params' ] );
															$db_data[ 'data' ] = json_encode( $ud_data[ 'data' ] );
															
															if ( ! $this->update_ud_data( $db_data, $dtid ) ){
																
																$out[ 'errors' ][] = $this->get_api_return_status( 'e_30001' );
																
															}
															else {
																
																$out[ 'out' ] = $this->get_api_return_status( 's_30000' );
																
															}
															
														}
														
													}
													
												}
												
											}
											
										}
										
									}
									
								}
								
							}
							
						}
						
					}
					
				}
				
			}
			
			// -------------------------------------
			// Add / Edit UniD Data
			// Require login
			// Restriction checking by Data Scheme
			// POST
			
			else if ( $act == 'ad' OR $act == 'ed' ) {
				
				if ( ! isset( $im ) ) {
					
					$config = $this->input->post( NULL, TRUE );
					
				}
				
				$dsid =										isset( $config[ 'dsi' ] ) ? urldecode( $config[ 'dsi' ] ) : NULL; // UniD Data Scheme id (if ad)
				$dtid =										isset( $config[ 'di' ] ) ? urldecode( $config[ 'di' ] ) : NULL; // UniD Data id (if ed)
				$nd =										isset( $config[ 'nd' ] ) ? urldecode( $config[ 'nd' ] ) : NULL; // New data (NOT UniD Data, this refer to 'data' db collumn, wich contains all UniD Data Properties)
				
				$user = $this->_check_user_access( $username, $password );
				
				if ( isset( $user[ 'errors' ] ) ) {
					
					$out[ 'errors' ][] = isset( $out[ 'errors' ] ) ? array_merge( $out[ 'errors' ], $user[ 'errors' ] ) : $user[ 'errors' ];
					
				}
				else {
					
					if ( ! isset( $nd ) ) {
						
						$out[ 'errors' ][] = $this->get_api_return_status( 'e_30002' );
						
					}
					
					if ( ! isset( $out[ 'errors' ] ) ) {
						
						if ( $act == 'ad' ) {
							
							if ( ! check_var( $dsid ) ) {
								
								$out[ 'errors' ][] = $this->get_api_return_status( 'e_10000' );
								
							}
							else if ( ! $this->_validate_int_id( $dsid ) ) {
								
								$out[ 'errors' ][] = $this->get_api_return_status( 'e_1', $dsid );
								
							}
							else {
								
								// get data scheme function params
								$gdsp = array(
									
									'where_condition' => 't1.id = ' . $dsid,
									'limit' => 1,
									
								);
								
								if ( ! ( $data_scheme = $this->get_data_schemes( $gdsp )->row_array() ) ){
									
									$out[ 'errors' ][] = $this->get_api_return_status( 'e_10001', $dsid );
									
								}
								else {
									
									$this->parse_ds( $data_scheme );
									
									// -------------------------------------------------
									// Params filtering
									
									$data_scheme[ 'params' ] = filter_params( $filtered_params, $data_scheme[ 'params' ] );
									
									// -------------------------------------------------
									// Check if the API of the UniD Data Scheme is enabled
									
									$api_status = $this->_check_ds_api_status( $data_scheme );
									
									if ( isset( $api_status[ 'errors' ] ) ) {
										
										$out[ 'errors' ] = isset( $out[ 'errors' ] ) ? array_merge( $out[ 'errors' ], $api_status[ 'errors' ] ) : $api_status[ 'errors' ];
										
									}
									else {
										
										// -------------------------------------------------
										// Check access restritions of the UniD Data Scheme
										
										$api_access = $this->_check_ds_api_access( $data_scheme, $username, $password );
										
										if ( isset( $api_access[ 'errors' ] ) ) {
											
											$out[ 'errors' ] = isset( $out[ 'errors' ] ) ? array_merge( $out[ 'errors' ], $api_access[ 'errors' ] ) : $api_access[ 'errors' ];
											
										}
										else {
											
											$xss_filtering = TRUE;
											
											if ( isset( $data_scheme[ 'params' ][ 'ud_data_props_enable_xss_filtering_site' ] ) AND ! check_var( $data_scheme[ 'params' ][ 'ud_data_props_enable_xss_filtering_site' ] ) ) {
												
												$xss_filtering = FALSE;
												
											}
											
											$nd = $this->input->post( 'nd', $xss_filtering );
											
											$nd = get_params( $nd );
											
											if ( ! ( check_var( $nd ) AND is_array( $nd ) ) ) {
												
												$out[ 'errors' ][] = $this->get_api_return_status( 'e_30003' );
												
											}
											else {
												
												reset( $nd );
												
												while ( list( $pa, $nv ) = each( $nd ) ) {
													
													if ( ! check_var( $data_scheme[ 'fields' ][ $pa ] ) ) {
														
														$out[ 'warnings' ][] = $this->get_api_return_status( 'e_20002', $dsid, $pa );
														
														unset( $nd[ $pa ] );
														continue;
														
													}
													else {
														
														$prop = $data_scheme[ 'fields' ][ $pa ];
														
														// -------------------------------------
														// Validating received data
														
														$validation_result = $this->_validate_ud_data_property( $data_scheme, $pa, $nv );
														
														if ( isset( $validation_result[ 'errors' ] ) ){
															
															$out[ 'errors' ] = array_merge( $out[ 'errors' ], $validation_result[ 'errors' ] );
															
														}
														else {
															
															if ( $prop[ 'field_type' ] == 'textarea' AND ! is_array( $nv ) ) {
																
																$nv = htmlspecialchars( $nv );
																
															}
															
															$ud_data[ 'data' ][ $pa ] = $nv;
															
														}
														
													}
													
												}
												
												if ( ! isset( $out[ 'errors' ] ) ) {
													
													$mod_datetime = gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
													$mod_datetime = strftime( '%Y-%m-%d %T', $mod_datetime );
													
													if ( $user[ 'id' ] ) {
													
														$ud_data[ 'params' ][ 'modified_by_user_id' ] = $user[ 'id' ];
														
													}
													else if ( $this->_internal_mode AND $this->_skip_access_check AND $this->users->is_logged_in() ) {
														
														$ud_data[ 'params' ][ 'modified_by_user_id' ] = $this->users->user_data[ 'id' ];
														
													}
													
													// -----------------------------------------------
													// Inserting data into DB
													
													$db_data[ 'submit_form_id' ] = $dsid;
													$db_data[ 'submit_datetime' ] = $mod_datetime;
													$db_data[ 'mod_datetime' ] = $mod_datetime;
													$db_data[ 'params' ] = json_encode( $ud_data[ 'params' ] );
													$db_data[ 'data' ] = json_encode( $ud_data[ 'data' ] );
													
													$return_id = $this->insert_ud_data( $db_data );
													
													if ( ! $return_id ){
														
														$out[ 'errors' ][] = $this->get_api_return_status( 'e_30001' );
														
													}
													else {
														
														$out[ 'out' ] = $this->get_api_return_status( 's_30000' );
														
													}
													
												}
												
											}
											
										}
										
									}
									
								}
								
							}
							
						}
						
						else if ( $act == 'ed' ) {
							
							if ( ! check_var( $dtid ) ) {
								
								$out[ 'errors' ][] = $this->get_api_return_status( 'e_20000' );
								
							}
							else if ( ! $this->_validate_int_id( $dtid ) ) {
								
								$out[ 'errors' ][] = $this->get_api_return_status( 'e_1', $dtid );
								
							}
							else {
								
								$data[ 'ud_data' ] = array();
								
								$this->load->library( 'search' );
								
								$search_config = array(
									
									'plugins' => 'sf_us_search',
									'allow_empty_terms' => TRUE,
									'plugins_params' => array(
										
										'sf_us_search' => array(
											
											'us_id' => $dtid,
											
										),
										
									),
									
								);
								
								$this->search->config( $search_config );
								
								if ( ! ( $ud_data =  $this->search->get_full_results( 'sf_us_search', TRUE ) ) ) {
									
									$out[ 'errors' ][] = $this->get_api_return_status( 'e_20003', $dtid );
									
								}
								else {
									
									$ud_data = reset( $ud_data );
									
									$data_scheme = FALSE;
									$dsid = $ud_data[ 'submit_form_id' ];
									
									if ( ! $this->_validate_int_id( $dsid ) ) {
										
										$out[ 'errors' ][] = $this->get_api_return_status( 'e_1', $dsid );
										
									}
									else {
										
										// get data scheme function params
										$gdsp = array(
											
											'where_condition' => 't1.id = ' . $dsid,
											'limit' => 1,
											
										);
										
										if ( ! ( $data_scheme = $this->get_data_schemes( $gdsp )->row_array() ) ){
											
											$out[ 'errors' ][] = $this->get_api_return_status( 'e_10001', $dsid );
											
										}
										else {
											
											$this->parse_ds( $data_scheme );
											
											// -------------------------------------------------
											// Params filtering
											
											$data_scheme[ 'params' ] = filter_params( $filtered_params, $data_scheme[ 'params' ] );
											
											// -------------------------------------------------
											// Check if the API of the UniD Data Scheme is enabled
											
											$api_status = $this->_check_ds_api_status( $data_scheme );
											
											if ( isset( $api_status[ 'errors' ] ) ) {
												
												$out[ 'errors' ] = isset( $out[ 'errors' ] ) ? array_merge( $out[ 'errors' ], $api_status[ 'errors' ] ) : $api_status[ 'errors' ];
												
											}
											else {
												
												// -------------------------------------------------
												// Check access restritions of the UniD Data Scheme
												
												$api_access = $this->_check_ds_api_access( $data_scheme, $username, $password );
												
												if ( isset( $api_access[ 'errors' ] ) ) {
													
													$out[ 'errors' ] = isset( $out[ 'errors' ] ) ? array_merge( $out[ 'errors' ], $api_access[ 'errors' ] ) : $api_access[ 'errors' ];
													
												}
												else {
													
													$xss_filtering = TRUE;
													
													if ( isset( $data_scheme[ 'params' ][ 'ud_data_props_enable_xss_filtering_site' ] ) AND ! check_var( $data_scheme[ 'params' ][ 'ud_data_props_enable_xss_filtering_site' ] ) ) {
														
														$xss_filtering = FALSE;
														
													}
													
													$nd = $this->input->post( 'nd', $xss_filtering );
													
													$nd = get_params( $nd );
													
													if ( ! ( check_var( $nd ) AND is_array( $nd ) ) ) {
														
														$out[ 'errors' ][] = $this->get_api_return_status( 'e_30003' );
														
													}
													else {
														
														reset( $nd );
														
														while ( list( $pa, $nv ) = each( $nd ) ) {
															
															if ( ! check_var( $data_scheme[ 'fields' ][ $pa ] ) ) {
																
																$out[ 'warnings' ][] = $this->get_api_return_status( 'e_20002', $dsid, $pa );
																
																unset( $nd[ $pa ] );
																
															}
															
														}
														
														reset( $data_scheme[ 'fields' ] );
														
														
														while ( list( $pa, $prop ) = each( $data_scheme[ 'fields' ] ) ) {
															
															$nv = isset( $nd[ $pa ] ) ? $nd[ $pa ] : NULL;
															
															// -------------------------------------
															// Validating received data
															
															$validation_result = $this->_validate_ud_data_property( $data_scheme, $pa, $nv );
															
															if ( isset( $validation_result[ 'errors' ] ) ){
																
																$out[ 'errors' ] = isset( $out[ 'errors' ] ) ? array_merge( $out[ 'errors' ], $validation_result[ 'errors' ] ) : $validation_result[ 'errors' ];
																
															}
															else {
																
																if ( $prop[ 'field_type' ] == 'textarea' AND ! is_array( $nv ) ) {
																	
																	$nv = htmlspecialchars( $nv );
																	
																}
																
																$ud_data[ 'data' ][ $pa ] = $nv;
																
															}
															
														}
														
														if ( ! isset( $out[ 'errors' ] ) ) {
															
															$mod_datetime = gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
															$mod_datetime = strftime( '%Y-%m-%d %T', $mod_datetime );
															
															if ( $user[ 'id' ] ) {
															
																$ud_data[ 'params' ][ 'modified_by_user_id' ] = $user[ 'id' ];
																
															}
															else if ( $this->_internal_mode AND $this->_skip_access_check AND $this->users->is_logged_in() ) {
																
																$ud_data[ 'params' ][ 'modified_by_user_id' ] = $this->users->user_data[ 'id' ];
																
															}
															
															// -----------------------------------------------
															// Inserting data into DB
															
															$db_data[ 'mod_datetime' ] = $mod_datetime;
															$db_data[ 'params' ] = json_encode( $ud_data[ 'params' ] );
															$db_data[ 'data' ] = json_encode( $ud_data[ 'data' ] );
															
															$update_ud_data = $this->update_ud_data( $db_data, $ud_data[ 'id' ] );
															
															if ( isset( $update_ud_data[ 'errors' ] ) ) {
																
																$out[ 'errors' ] = isset( $out[ 'errors' ] ) ? array_merge( $out[ 'errors' ], $update_ud_data[ 'errors' ] ) : $update_ud_data[ 'errors' ];
																
															}
															else {
															
																$out[ 'out' ] = $this->get_api_return_status( 's_30000' );
																
															}
															
														}
														
													}
													
												}
												
											}
									
										}
										
									}
									
								}
								
							}
							
						}
						
					}
					
				}
				
			}
			
			// -------------------------------------
			// Unknow action
			
			else {
			
				$out = $this->get_api_return_status( 'e_2' );
				
			}
			
		}
		
		if ( $this->_internal_mode AND $this->_skip_access_check ) {
			
			return $out;
			
		}
		
		if ( in_array( $rt, array( 'json', '1' ) ) ) {
			
			header( 'Content-Type: application/json' );
			$out = @json_encode( $out );
			
		}
		else if ( in_array( $rt, array( 'php_print_r', '2' ) ) ) {
			
			$out = @print_r( $out, TRUE );
			
		}
		
		//$out = mb_convert_encoding( $out,'UTF-8','UTF-8' );
		//echo '<pre>' . print_r( $out, TRUE ) . '</pre>'; exit;
		return $out . "\n";
		
	}
	
	// --------------------------------------------------------------------
	
	private function _validate_int_id( $id ){
		
		if ( ! ( $id AND is_numeric( $id ) AND is_int( $id + 0 ) ) ) {
			
			return FALSE;
			
		}
		
		return TRUE;
		
	}
	
	// --------------------------------------------------------------------
	
	private function _validate_ud_data_property( & $data_scheme, $pa, $nv = "" ){
		
		$orig_post = $this->input->post( NULL, TRUE );
		$out = NULL;
		
		$prop = $data_scheme[ 'fields' ][ $pa ];
		
		if ( is_array( $nv ) ) {
			
			$formatted_field_name = $pa . 'vuddp_nv[]';
			
		}
		else {
			
			$formatted_field_name = $pa . 'vuddp_nv';
			
		}
		
		$_POST = array( $formatted_field_name => $nv );
		
		$rules = array( 'trim' );
		
		if ( check_var( $prop[ 'field_is_required' ] ) ){
			
			$rules[] = 'required';
			
		}
		
		if ( isset( $prop[ 'validation_rule' ] ) AND is_array( $prop[ 'validation_rule' ] ) ){
			
			foreach ( $prop[ 'validation_rule' ] as $key => $rule ) {
				
				$comp = '';
				$skip = FALSE;
				
				switch ( $rule ) {
					/*
					case 'matches':
						
						$comp .= '[form[' . $prop[ 'validation_rule_parameter_matches'] . ']]';
						break;
						*/
					case 'min_length':
						
						$comp .= '[' . $prop[ 'validation_rule_parameter_min_length'] . ']';
						break;
						
					case 'max_length':
						
						$comp .= '[' . $prop[ 'validation_rule_parameter_max_length'] . ']';
						break;
						
					case 'exact_length':
						
						$comp .= '[' . $prop[ 'validation_rule_parameter_exact_length'] . ']';
						break;
						
					case 'less_than':
						
						$comp .= '[' . $prop[ 'validation_rule_parameter_less_than'] . ']';
						break;
						
					case 'mask':
						
						if ( isset( $prop[ 'ud_validation_rule_parameter_mask_type' ] ) ) {
							
							$orig_post[ 'nv' ] = $_POST[ $formatted_field_name ] = $nv = unmask( $nv, $prop[ 'ud_validation_rule_parameter_mask_type' ], isset( $prop[ 'ud_validation_rule_parameter_mask_custom_mask' ] ) ? $prop[ 'ud_validation_rule_parameter_mask_custom_mask' ] : '' );
							
						}
						
						$skip = TRUE;
						unset( $prop[ 'validation_rule' ][ $key ] );
						break;
					
				}
				
				if ( ! $skip ) {
					
					$rules[] = $rule . $comp;
					
				}
				
			}
			
		}
		
		// xss filtering
		if ( check_var( $data_scheme[ 'params' ][ 'ud_data_props_enable_xss_filtering_admin' ] ) ) {
			
			$rules[] = 'xss';
			
		}
		
		// articles
		if ( $prop[ 'field_type' ] === 'articles' AND isset( $nv ) ) {
			
			$search_config = array(
				
				'plugins' => 'articles_search',
				'ipp' => 0,
				'allow_empty_terms' => TRUE,
				'plugins_params' => array(
					
					'articles_search' => array(
						
						'article_id' => $nv,
						
					),
					
				),
				
			);
			
			$this->load->library( 'search' );
			$this->search->config( $search_config );
			
			$article = $this->search->get_full_results( 'articles_search', TRUE );
			
			if ( ! $article ) {
				
				$out[ 'errors' ][] = $this->get_api_return_status( 'e_8' );
				
			}
			
		}
		
		// date
		else if ( $prop[ 'field_type' ] === 'date' ) {
			
			if ( isset( $nv ) AND is_array( $nv ) ) {
				
				$d = ( isset( $nv[ 'd' ] ) AND $nv[ 'd' ] != '' ) ? $nv[ 'd' ] : '00';
				$m = ( isset( $nv[ 'm' ] ) AND $nv[ 'm' ] != '' ) ? $nv[ 'm' ] : '00';
				$y = ( isset( $nv[ 'y' ] ) AND $nv[ 'y' ] != '' ) ? $nv[ 'y' ] : '0000';
				
				$nv = $y . '-' . $m . '-' . $d;
				
			}
			
		}
		
		$rules = join( '|', $rules );
		
		$this->form_validation->set_rules( $formatted_field_name, lang( $prop[ 'label' ] ), $rules );
		
		if ( ! isset( $out[ 'errors' ] ) AND $this->form_validation->run() ){
			
			$out = $nv;
			
		}
		else if ( isset( $out[ 'errors' ] ) ){
			
		}
		else {
			
			$out = array( 'errors' => array( form_error( $formatted_field_name, '', '' ) ) );
			
		}
		
		$this->form_validation->clear();
		
		$_POST = $orig_post;
		
		return $out;
		
	}
	
	// --------------------------------------------------------------------
	
	private function _check_user_access( $username = NULL, $password = NULL ){
		
		if ( $this->_internal_mode ) {
			
			if ( ! $this->_skip_access_check ) {
				
				if ( $this->users->is_logged_in() AND ! check_var( $username ) AND ! check_var( $password ) ) {
					
					return $this->users->user_data;
					
				}
				else if ( check_var( $username ) AND check_var( $password ) ) {
					
					if ( $user = $this->users->get_user( array( 't1.username' => $username ) )->row_array() ) {
						
						$user[ 'params' ] = get_params( $user[ 'params' ] );
						$user[ 'privileges' ] = get_params( $user[ 'privileges' ] );
						
						return $user;
						
					}
					
				}
				
			}
			else {
				
				return TRUE;
				
			}
			
		}
		
		if ( ! check_var( $username ) AND ! check_var( $password ) ) {
			
			$out[ 'errors' ][] = $this->get_api_return_status( 'e_10' );
			
		}
		else if ( ! check_var( $username ) ) {
			
			$out[ 'errors' ][] = $this->get_api_return_status( 'e_11' );
			
		}
		else if ( ! check_var( $password ) ) {
			
			$out[ 'errors' ][] = $this->get_api_return_status( 'e_12' );
			
		}
		else {
			
			if ( ! ( $user = $this->users->get_user( array( 't1.username' => $username ) )->row_array() ) ) {
				
				$out[ 'errors' ][] = $this->get_api_return_status( 'e_13' );
				
			}
			else {
				
				if ( $user[ 'password' ] != $this->users->encode_password( $password ) ) {
					
					$out[ 'errors' ][] = $this->get_api_return_status( 'e_14' );
					
				}
				else {
					
					$user[ 'params' ] = get_params( $user[ 'params' ] );
					$user[ 'privileges' ] = get_params( $user[ 'privileges' ] );
					
					$out = $user;
					$user = NULL;
					unset( $user );
					
				}
				
			}
			
		}
		
		return $out;
		
	}
	
	// --------------------------------------------------------------------
	
	private function _check_user_api_access( $user ){
		
		if ( $this->_internal_mode AND $this->_skip_access_check ) {
			
			return TRUE;
			
		}
		
		if ( ! $this->users->_check_privileges( 'priv_ds_api', $user ) ){
			
			$out[ 'errors' ][] = $this->get_api_return_status( 'e_9' );
			
		}
		else {
			
			$out = TRUE;
			
		}
		
		return $out;
		
	}
	
	// --------------------------------------------------------------------
	
	private function _check_ds_api_status( $data_scheme ){
		
		if ( $this->_internal_mode ) {
			
			return TRUE;
			
		}
		
		if ( ! check_var( $data_scheme[ 'params' ][ 'ud_ds_api_access_active' ] ) ) {
			
			$out[ 'errors' ][] = $this->get_api_return_status( 'e_10002', $data_scheme[ 'id' ] );
			
		}
		else {
			
			$out = TRUE;
			
		}
		
		return $out;
		
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Checks the UniD Data Scheme's access restrictions to the specified user
	 * Returns TRUE if the user has the necessary permissions to access the UniD Data Scheme
	 * Otherwise returns an array with errors 
	 *
	 * @access private
	 * @param array | Data Scheme array
	 * @param string | Username
	 * @param string | Password
	 * @return mixed
	 */
	
	private function _check_ds_api_access( $data_scheme, $username = NULL, $password = NULL ){
		
		if ( $this->_internal_mode AND $this->_skip_access_check ) {
			
			return TRUE;
			
		}
		
		$out = FALSE;
		
		if ( isset( $data_scheme[ 'params' ][ 'ud_ds_api_access_type' ] ) AND in_array( $data_scheme[ 'params' ][ 'ud_ds_api_access_type' ], array( 1, 2 ) ) ) {
			
			$out = array();
			
			$user = $this->_check_user_access( $username, $password );
			
			if ( isset( $user[ 'errors' ] ) ) {
				
				$out[ 'errors' ] = isset( $out[ 'errors' ] ) ? array_merge( $out[ 'errors' ], $user[ 'errors' ] ) : $user[ 'errors' ];
				
			}
			else {
				
				$user_api_access = $this->_check_user_api_access( $user );
				
				if ( isset( $user_api_access[ 'errors' ] ) ) {
					
					$out[ 'errors' ] = isset( $out[ 'errors' ] ) ? array_merge( $out[ 'errors' ], $user_api_access[ 'errors' ] ) : $user_api_access[ 'errors' ];
					
				}
				else if ( ( $data_scheme[ 'params' ][ 'ud_ds_api_access_type' ] == 1 AND ( ! check_var( $data_scheme[ 'params' ][ 'ud_ds_api_access_type_user_group' ] ) OR ! in_array( $user[ 'group_id' ], $data_scheme[ 'params' ][ 'ud_ds_api_access_type_user_group' ] ) ) ) OR ( $data_scheme[ 'params' ][ 'ud_ds_api_access_type' ] == 2 AND ( ! check_var( $data_scheme[ 'params' ][ 'ud_ds_api_access_type_users' ] ) OR ! in_array( $user[ 'id' ], $data_scheme[ 'params' ][ 'ud_ds_api_access_type_users' ] ) ) ) ) {
					
					$out[ 'errors' ][] = $this->get_api_return_status( 'e_10005', $data_scheme[ 'id' ] );
					
				}
				else {
					
					$out = TRUE;
					
				}
				
			}
			
		}
		// Public
		else if ( $data_scheme[ 'params' ][ 'ud_ds_api_access_type' ] == 3 ) {
			
			$out = TRUE;
			
		}
		
		return $out;
		
	}
	
	// --------------------------------------------------------------------
	
	public function update_ud_data( $data = NULL, $id = NULL ){
		
		if ( ! isset( $data ) AND ! isset( $id ) ){
			
			$out[ 'errors' ][] = $this->get_api_return_status( 'e_5001' );
			
		}
		else {
			
			if ( ! isset( $data[ 'id' ] ) ) {
				
				$data[ 'id' ] = $id;
				
			}
			
			if ( isset( $data[ 'data' ] ) ) {
				
				$data[ 'xml_data' ] = $this->us_json_data_to_xml( $data );
				
			}
			
			// detecting values differences
			
			if ( isset( $data[ 'id' ] ) ) {
				
				$_d_id = $data[ 'id' ];
				
				unset( $data[ 'id' ] );
				
				$current_data = $this->get_ud_data( $_d_id, NULL, TRUE );
				
				$d_data = get_params( $data[ 'data' ] );
				
				foreach( $d_data as $alias => $value ) {
					
					if ( isset( $current_data[ 'data' ][ $alias ] ) AND $current_data[ 'data' ][ $alias ] !== $d_data[ $alias ] ) {
						
						$diff[] = $alias;
						
					}
					
				}
				
			}
			
			if ( $this->db->update( 'tb_submit_forms_us', $data, array( 'id' => $id ) ) ){
				
				if ( isset( $diff ) ) {
					
					$data[ 'id' ] = $_d_id;
					$data[ 'submit_form_id' ] = $current_data[ 'submit_form_id' ];
					
					foreach( $diff as $changed_prop_alias ) {
						
						$this->adjust_referenced_data( $data, $changed_prop_alias );
						
					}
					
				}
				
			}
			
		}
		
		if ( isset( $out[ 'errors' ] ) ){
			
			return $out[ 'errors' ];
			
		}
		else return TRUE;
		
	}
	
	// --------------------------------------------------------------------
	
	public function insert_ud_data( $data = NULL ){
		
		if ( isset( $data[ 'data' ] ) ) {
			
			$data[ 'xml_data' ] = $this->us_json_data_to_xml( $data );
			
		}
		
		if ( $data != NULL AND is_array( $data ) ){
			
			if ( $this->db->insert( 'tb_submit_forms_us', $data ) ){
				
				$return_id = $this->db->insert_id();
				
				return $return_id;
				
			}
			
		}
		
		log_message( 'error', '[Submit forms] Error attempting to insert submit record!' );
		
		return FALSE;
		
	}
	
	// --------------------------------------------------------------------
	
	public function us_json_data_to_xml( & $us ){
		
		$us_data = $us[ 'data' ];
		
		if ( is_string( $us_data ) ) {
			
			$us_data = get_params( $us_data );
			
		}
		
		//echo '<pre>' . print_r( $us_data, TRUE ) . '</pre>';
		
		if ( ! is_array( $us_data ) ) {
			
		}
		else {
		
			$xml = '<?xml version="1.0" encoding="UTF-8" ?>';
			
			if ( ! isset( $us[ 'submit_form_id' ] ) ) {
				
				$this->db->select( 'submit_form_id' );
				$this->db->from('tb_submit_forms_us t1');
				$this->db->where( 'id', $us[ 'id' ] );
				// limitando o resultando em apenas 1
				$this->db->limit( 1 );
				
				$us[ 'submit_form_id' ] = $this->db->get()->row_array();
				$us[ 'submit_form_id' ] = $us[ 'submit_form_id' ][ 'submit_form_id' ];
				
			}
			
			// get data scheme params
			$gdsp = array(
				
				'where_condition' => 't1.id = ' . $us[ 'submit_form_id' ],
				'limit' => 1,
				
			);
			
			if ( ! ( $data_scheme = $this->get_data_schemes( $gdsp )->row_array() ) ){
				
				return $this->get_api_return_status( 'e_5000', $us[ 'submit_form_id' ] );
				
			}
			
			$this->parse_ds( $data_scheme, TRUE );
			
			$ds_props = $data_scheme[ 'fields' ];
			
			reset( $us_data );
			
			while ( list( $alias, $value ) = each( $us_data ) ) {
				
				reset( $ds_props );
				
				while ( list( $k, $ds_prop ) = each( $ds_props ) ) {
				
					if ( ! isset( $ds_prop[ 'field_type' ] ) ) { continue; }
					
					if ( $ds_prop[ 'alias' ] == $alias  ) {
						
						if ( $ds_prop[ 'field_type' ] == 'date' ){
							
							$___date = explode( '-', $value );
							
							$format = '';
							
							$use_y = check_var( $ds_prop[ 'sf_date_field_use_year' ] ) ? TRUE : FALSE;
							$use_m = check_var( $ds_prop[ 'sf_date_field_use_month' ] ) ? TRUE : FALSE;
							$use_d = check_var( $ds_prop[ 'sf_date_field_use_day' ] ) ? TRUE : FALSE;
							
							$format .= ( $use_y AND isset( $___date[ 0 ] ) AND ( int ) $___date[ 0 ] > 0 ) ? 'y' : '';
							$format .= ( $use_m AND isset( $___date[ 1 ] ) AND ( int ) $___date[ 1 ] > 0 ) ? 'm' : '';
							$format .= ( $use_d AND isset( $___date[ 2 ] ) AND ( int ) $___date[ 2 ] > 0 ) ? 'd' : '';
							
							$___date[ 0 ] = ( int ) $___date[ 0 ] > 0 ? $___date[ 0 ] : '2000';
							$___date[ 1 ] = ( int ) $___date[ 1 ] > 1 ? $___date[ 1 ] : '01';
							$___date[ 2 ] = ( int ) $___date[ 2 ] > 2 ? $___date[ 2 ] : '01';
							
							$value = $___date[ 0 ] . '-' . $___date[ 1 ] . '-' . $___date[ 2 ];
							
							if ( ! empty( $format ) ) {
								
								$format = 'sf_us_dt_ft_pt_' . $format . '_' . $ds_prop[ 'sf_date_field_presentation_format' ];
								
								$value =  strftime( lang( $format ), strtotime( $value ) );
								
							}
							else {
								
								$value =  '';
								
							}
							
						}
						else if ( in_array( $ds_prop[ 'field_type' ], array( 'combo_box', 'radiobox', 'checkbox', ) ) ) {
							
							if ( ! is_array( $value ) ) {
								
								$__tmp = @json_decode( $value, TRUE );
								
								if ( is_array( $__tmp ) ) {
									
									$value = $__tmp;
									
								}
								
							}
							
							if ( is_array( $value ) ) {
								
								if ( count( $value ) == 1 ) {
									
									$value = $value[ 0 ];
									
									if ( check_var( $ds_props[ $alias ][ 'options_from_users_submits' ] ) AND ( check_var( $ds_props[ $alias ][ 'options_title_field' ] ) OR check_var( $ds_props[ $alias ][ 'options_title_field_custom' ] ) ) AND is_numeric( $value ) AND $_user_submit = $this->get_ud_data( $value ) ) {
										
										$value = isset( $_user_submit[ 'data' ][ $ds_props[ $alias ][ 'options_title_field' ] ] ) ? $_user_submit[ 'data' ][ $ds_props[ $alias ][ 'options_title_field' ] ] : $_user_submit[ 'id' ];
										
									}
									else {
										
										if ( in_array( $ds_props[ $alias ][ 'field_type' ], array( 'checkbox', 'radiobox' ) ) AND ! check_var( $ds_props[ $alias ][ 'options' ] ) AND ( $value == '1' OR $value == '' ) ) {
											
											if ( check_var( $value ) ) {
												
												$value = lang( 'yes' );
												
											}
											else {
												
												$value = lang( 'no' );
												
											}
											
										}
										
									}
									
								}
								else {
									
									$_field_value = array();
									
									foreach ( $value as $k => $v ) {
										
										if ( is_string( $v ) ) {
											
											if ( check_var( $ds_props[ $alias ][ 'options_from_users_submits' ] ) AND ( check_var( $ds_props[ $alias ][ 'options_title_field' ] ) OR check_var( $ds_props[ $alias ][ 'options_title_field_custom' ] ) ) AND is_numeric( $v ) AND $_user_submit = $this->get_ud_data( $v ) ) {
												
												$v = isset( $_user_submit[ 'data' ][ $ds_props[ $alias ][ 'options_title_field' ] ] ) ? $_user_submit[ 'data' ][ $ds_props[ $alias ][ 'options_title_field' ] ] : $_user_submit[ 'id' ];
												
											}
											
											$_field_value[] = $v;
											
										}
										
									}
									
									$value = join( ', ', $_field_value );
									
								}
								
							}
							else {
								
								if ( check_var( $ds_props[ $alias ][ 'options_from_users_submits' ] ) AND ( check_var( $ds_props[ $alias ][ 'options_title_field' ] ) OR check_var( $ds_props[ $alias ][ 'options_title_field_custom' ] ) ) AND is_numeric( $value ) AND $_user_submit = $this->get_ud_data( $value, NULL, TRUE ) ) {
									
									$value = isset( $_user_submit[ 'data' ][ $ds_props[ $alias ][ 'options_title_field' ] ] ) ? $_user_submit[ 'data' ][ $ds_props[ $alias ][ 'options_title_field' ] ] : $_user_submit[ 'id' ];
									
								}
								
							}
							
						}
						
					}
					
				}
				
				$xml .= '<' . $alias . '>';
				$xml .= strip_tags( html_entity_decode( $value ) );
				$xml .= '</' . $alias . '>' . "\n";
				
			}
			
// 			echo '<pre>' . htmlspecialchars( print_r( $xml, TRUE ) ) . '</pre>'; exit;
			
			return $xml;
			
		}
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Parse a unid data
	 *
	 * @access public
	 * @param array | should be the us array, not only data
	 * @param bool | determines whether the parameters should be filtered
	 * @return void
	 */
	
	public function parse_ud_data( & $ud_data = NULL, $ds_props_to_show = NULL, $value_html = FALSE ){
		
		if ( is_array( $ud_data ) ) {
			
			$data_scheme = FALSE;
			$dsid = $ud_data[ 'submit_form_id' ];
			
			if ( is_string( $ud_data[ 'data' ] ) ) {
				
				$ud_data[ 'data' ] = get_params( $ud_data[ 'data' ] );
				
			}
			
			if ( is_int( $dsid ) OR ( is_string( $dsid ) AND ctype_digit( $dsid ) ) ) {
				
				// get data scheme function params
				$gdsp = array(
					
					'where_condition' => 't1.id = ' . $dsid,
					'limit' => 1,
					
				);
				
				if ( $data_scheme = $this->get_data_schemes( $gdsp )->row_array() ){
					
					$this->parse_ds( $data_scheme );
					
				}
				
			}
			
			if ( isset( $ud_data[ 'params' ][ 'created_by_user_id' ] ) AND ( is_int( $ud_data[ 'params' ][ 'created_by_user_id' ] ) OR ( is_string( $ud_data[ 'params' ][ 'created_by_user_id' ] ) AND ctype_digit( $ud_data[ 'params' ][ 'created_by_user_id' ] ) ) ) ) {
				
				$cbu_id = $ud_data[ 'params' ][ 'created_by_user_id' ];
				
				$ud_data[ 'created_by_user' ] = array();
				
				$ud_data[ 'created_by_user' ] = $this->users->get_user( array( 't1.id' => $cbu_id ) )->row_array();
				
				$ud_data[ 'created_by_user' ][ 'edit_link' ] = $this->users->admin_get_link_edit( $cbu_id );
				$ud_data[ 'created_by_user' ][ 'remove_link' ] = $this->users->admin_get_link_remove( $cbu_id );
				$ud_data[ 'created_by_user' ][ 'enable_link' ] = $this->users->admin_get_link_enable( $cbu_id );
				$ud_data[ 'created_by_user' ][ 'disable_link' ] = $this->users->admin_get_link_disable( $cbu_id );
				
			}
			
			if ( isset( $ud_data[ 'params' ][ 'modified_by_user_id' ] ) AND ( is_int( $ud_data[ 'params' ][ 'modified_by_user_id' ] ) OR ( is_string( $ud_data[ 'params' ][ 'modified_by_user_id' ] ) AND ctype_digit( $ud_data[ 'params' ][ 'modified_by_user_id' ] ) ) ) ) {
				
				$mbu_id = $ud_data[ 'params' ][ 'modified_by_user_id' ];
				
				if ( isset( $cbu_id ) AND $mbu_id == $cbu_id ) {
					
					$ud_data[ 'modified_by_user' ] = $ud_data[ 'created_by_user' ];
					
				}
				else {
					
					$ud_data[ 'modified_by_user' ] = array();
					
					$ud_data[ 'modified_by_user' ] = $this->users->get_user( array( 't1.id' => $mbu_id ) )->row_array();
					
					$ud_data[ 'modified_by_user' ][ 'edit_link' ] = $this->users->admin_get_link_edit( $mbu_id );
					$ud_data[ 'modified_by_user' ][ 'remove_link' ] = $this->users->admin_get_link_remove( $mbu_id );
					$ud_data[ 'modified_by_user' ][ 'enable_link' ] = $this->users->admin_get_link_enable( $mbu_id );
					$ud_data[ 'modified_by_user' ][ 'disable_link' ] = $this->users->admin_get_link_disable( $mbu_id );
					
				}
				
			}
			
			if ( $data_scheme ) {
				
				$_fields = $data_scheme[ 'fields' ];
				$ds_props = array();
				
				foreach ( $_fields as $key => $ds_prop ) {
					
					$ds_props[ $ds_prop[ 'alias' ] ] = $ds_prop;
					
				}
				
				if ( ! $ds_props_to_show AND isset( $data_scheme[ 'params' ][ 'props_to_show_site' ] ) ) {
					
					$ds_props_to_show = & $data_scheme[ 'params' ][ 'props_to_show_site' ];
					
				}
				
				$us_fields = $_titles_fields = $_contents_fields = array();
				
				$us_fields[ 'id' ][ 'label' ] = ( isset( $data_scheme[ 'params' ][ 'ud_ds_default_data_id_pres_title' ] ) ? lang( $data_scheme[ 'params' ][ 'ud_ds_default_data_id_pres_title' ] ) : lang( 'id' ) );
				$us_fields[ 'id' ][ 'value' ] = $ud_data[ 'id' ];
				$us_fields[ 'id' ][ 'visible' ] = ( ! check_var( $ds_props_to_show ) OR in_array( 'id', $ds_props_to_show ) ) ? TRUE : FALSE;
				
				$us_fields[ 'submit_datetime' ][ 'label' ] = ( isset( $data_scheme[ 'params' ][ 'ud_ds_default_data_sdt_pres_title' ] ) ? lang( $data_scheme[ 'params' ][ 'ud_ds_default_data_sdt_pres_title' ] ) : lang( 'submit_datetime' ) );
				$us_fields[ 'submit_datetime' ][ 'value' ] = strftime( ( isset( $data_scheme[ 'params' ][ 'ud_ds_default_data_sdt_dt_format' ] ) ? $data_scheme[ 'params' ][ 'ud_ds_default_data_sdt_dt_format' ] : lang( 'ud_data_datetime' ) ), strtotime( $ud_data[ 'submit_datetime' ] ) );
				$us_fields[ 'submit_datetime' ][ 'visible' ] = ( ! check_var( $ds_props_to_show ) OR in_array( 'submit_datetime', $ds_props_to_show ) ) ? TRUE : FALSE;
				
				$us_fields[ 'mod_datetime' ][ 'label' ] = ( isset( $data_scheme[ 'params' ][ 'ud_ds_default_data_mdt_pres_title' ] ) ? lang( $data_scheme[ 'params' ][ 'ud_ds_default_data_mdt_pres_title' ] ) : lang( 'mod_datetime' ) );
				$us_fields[ 'mod_datetime' ][ 'value' ] = strftime( ( isset( $data_scheme[ 'params' ][ 'ud_ds_default_data_mdt_dt_format' ] ) ? $data_scheme[ 'params' ][ 'ud_ds_default_data_mdt_dt_format' ] : lang( 'ud_data_datetime' ) ), strtotime( $ud_data[ 'mod_datetime' ] ) );
				$us_fields[ 'mod_datetime' ][ 'visible' ] = ( ! check_var( $ds_props_to_show ) OR in_array( 'mod_datetime', $ds_props_to_show ) ) ? TRUE : FALSE;
				
				foreach ( $ud_data[ 'data' ] as $key_2 => $ds_prop_value ) {
					
					//echo '<pre>' . $key_2 . ': ' . print_r( $ds_prop_value, TRUE ) . '</pre><br/>'; 
					
					if ( isset( $ds_props[ $key_2 ] ) ) {
						
						//echo $key_2 . ': <br/><pre>' . print_r( $ds_props[ $key_2 ], TRUE ) . '</pre><br/>'; 
						
						if ( ! is_numeric( $key_2 ) ) {
							
							if ( $ds_props[ $key_2 ][ 'field_type' ] == 'date' ){
								
								$ds_prop_value = htmlspecialchars_decode( $ds_prop_value );
								
								$prop_y = date( 'Y', strtotime( $ds_prop_value ) );
								$prop_m = date( 'm', strtotime( $ds_prop_value ) );
								$prop_d = date( 'd', strtotime( $ds_prop_value ) );
								
								$current_date[ 'y' ] = date( 'Y', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) );
								$current_date[ 'm' ] = date( 'm', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) );
								$current_date[ 'd' ] = date( 'd', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) );
								
								$format = '';
								
								$format .= ( check_var( $ds_props[ $key_2 ][ 'sf_date_field_use_year' ] ) AND ! ( $prop_y == $current_date[ 'y' ] AND check_var( $ds_props[ $key_2 ][ 'sf_date_field_hide_current_year' ] ) ) ) ? 'y' : '';
								$format .= ( check_var( $ds_props[ $key_2 ][ 'sf_date_field_use_month' ] ) AND ! ( $prop_m == $current_date[ 'm' ] AND $prop_y == $current_date[ 'y' ] AND check_var( $ds_props[ $key_2 ][ 'sf_date_field_hide_current_month' ] ) ) ) ? 'm' : '';
								$format .= ( check_var( $ds_props[ $key_2 ][ 'sf_date_field_use_day' ] ) AND ! ( $prop_d == $current_date[ 'd' ] AND $prop_m == $current_date[ 'm' ] AND $prop_y == $current_date[ 'y' ] AND check_var( $ds_props[ $key_2 ][ 'sf_date_field_hide_current_day' ] ) ) ) ? 'd' : '';
								
								if ( $format != '' ) {
									
									$format = 'sf_us_dt_ft_pt_' . $format . '_' . ( check_var( $ds_props[ $key_2 ][ 'sf_date_field_presentation_format' ] ) ? $ds_props[ $key_2 ][ 'sf_date_field_presentation_format' ] : 'ymd' );
									
								}
								
								$relative_datetime = '';
								
								$is_today = FALSE;
								$is_yesterday = FALSE;
								$is_tomorrow = FALSE;
								
								if ( check_var( $ds_props[ $key_2 ][ 'sf_date_field_relative_datetime' ] ) AND date_is_valid( $ds_prop_value ) ) {
									
									$date = new DateTime( $ds_prop_value );
									$now = new DateTime( date( 'Y-m-d', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) ) );
									$yesterday = new DateTime( date( 'Y-m-d', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) ) );
									$yesterday->sub( date_interval_create_from_date_string( '1 day' ) );
									$tomorrow = new DateTime( date( 'Y-m-d', gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] ) ) );
									$tomorrow->add( date_interval_create_from_date_string( '1 day' ) );
									
									$difference = $date->diff( $now );
									
									$_y = $difference->y;
									$_m = $difference->m;
									$_d = $difference->d;
									$_h = $difference->h;
									$_i = $difference->i;
									$_s = $difference->s;
									
									$relative_datetime_str_prefix_fw = 'sf_us_dt_relative_fw_';
									$relative_datetime_str_prefix_bw = 'sf_us_dt_relative_bw_';
									
									if ( $date == $now ) {
										
										$is_today = TRUE;
										
										$relative_datetime = lang( 'sf_us_dt_relative_today' );
										
									}
									else if ( $date == $yesterday ) {
										
										$is_yesterday = TRUE;
										
										$relative_datetime = lang( 'sf_us_dt_relative_yesterday' );
										
									}
									else if ( $date == $tomorrow ) {
										
										$is_tomorrow = TRUE;
										
										$relative_datetime = lang( 'sf_us_dt_relative_tomorrow' );
										
									}
									else if ( $_y != 0 ) {
										
										if ( $_y == 1 AND $date > $now ) {
											
											$relative_datetime = sprintf( lang( $relative_datetime_str_prefix_fw . 'y' ), $difference->y );
											
										}
										else if ( $_y == 1 AND $date < $now ) {
											
											$relative_datetime = sprintf( lang( $relative_datetime_str_prefix_bw . 'y' ), $difference->y );
											
										}
										else if ( $date < $now ) {
											
											$relative_datetime = sprintf( lang( $relative_datetime_str_prefix_bw . 'plural_' . 'y' ), $difference->y );
											
										}
										else if ( $date > $now ) {
											
											$relative_datetime = sprintf( lang( $relative_datetime_str_prefix_fw . 'plural_' . 'y' ), $difference->y );
											
										}
										
									}
									else if ( $_m != 0 ) {
										
										if ( $_m == 1 AND $date > $now ) {
											
											$relative_datetime = sprintf( lang( $relative_datetime_str_prefix_fw . 'm' ), $difference->m );
											
										}
										else if ( $_m == 1 AND $date < $now ) {
											
											$relative_datetime = sprintf( lang( $relative_datetime_str_prefix_bw . 'm' ), $difference->m );
											
										}
										else if ( $date < $now ) {
											
											$relative_datetime = sprintf( lang( $relative_datetime_str_prefix_bw . 'plural_' . 'm' ), $difference->m );
											
										}
										else if ( $date > $now ) {
											
											$relative_datetime = sprintf( lang( $relative_datetime_str_prefix_fw . 'plural_' . 'm' ), $difference->m );
											
										}
										
									}
									else if ( $_d != 0 ) {
										
										if ( $date < $now ) {
											
											$relative_datetime = sprintf( lang( $relative_datetime_str_prefix_bw . 'plural_' . 'd' ), $difference->d );
											
										}
										else if ( $date > $now ) {
											
											$relative_datetime = sprintf( lang( $relative_datetime_str_prefix_fw . 'plural_' . 'd' ), $difference->d );
											
										}
										
									}
									
								}
								
								$_prop_value = $ds_prop_value;
								
								$ds_prop_value = '';
									
								if ( $format != '' ) {
									
									$ds_prop_value .= '<span class="normal-datetime">' . strftime( lang( $format ), strtotime( $_prop_value ) ) . '</span>';
									
								}
								
								$ds_prop_value .=
									
									( $relative_datetime ? '<span class="relative-datetime' . ( ( $is_today ) ? ' is-today' : ( ( $is_yesterday ) ? ' is-yesterday' : ( ( $is_tomorrow ) ? ' is-tomorrow' : '' ) ) ) . '">'
									. $relative_datetime
									. '</span> ' : '' );
									
							}
							else if ( in_array( $ds_props[ $key_2 ][ 'field_type' ], array( 'checkbox', 'radiobox', 'combo_box', ) ) ){
								
								if ( check_var( $ud_data[ $key_2 ], TRUE ) ) {
									
									$ds_prop_value = $ud_data[ $key_2 ];
									
								}
								else {
									
									$__tmp = @json_decode( $ds_prop_value, TRUE );
									
									if ( is_array( $__tmp ) OR is_array( $ds_prop_value ) ) {
										
										if ( is_array( $__tmp ) ) {
											
											$ds_prop_value = $__tmp;
											
										}
										
										if ( count( $ds_prop_value ) == 1 ) {
											
											$ds_prop_value = $ds_prop_value[ 0 ];
											
											if ( check_var( $ds_props[ $key_2 ][ 'options_from_users_submits' ] ) AND ( check_var( $ds_props[ $key_2 ][ 'options_title_field' ] ) OR check_var( $ds_props[ $key_2 ][ 'options_title_field_custom' ] ) ) AND is_numeric( $ds_prop_value ) AND $_user_submit = $this->get_ud_data( $ds_prop_value ) ) {
												
												$ds_prop_value = isset( $_user_submit[ 'data' ][ $ds_props[ $key_2 ][ 'options_title_field' ] ] ) ? $_user_submit[ 'data' ][ $ds_props[ $key_2 ][ 'options_title_field' ] ] : $_user_submit[ 'id' ];
												
											}
											else {
												
												if ( in_array( $ds_props[ $key_2 ][ 'field_type' ], array( 'checkbox', 'radiobox' ) ) AND ! check_var( $ds_props[ $key_2 ][ 'options' ] ) AND ( $ds_prop_value == '1' OR $ds_prop_value == '' ) ) {
													
													if ( check_var( $ds_prop_value ) ) {
														
														$ds_prop_value = lang( 'yes' );
														
													}
													else {
														
														$ds_prop_value = lang( 'no' );
														
													}
													
												}
												
											}
											
										}
										else {
											
											$_field_value = array();
											
											foreach ( $ds_prop_value as $k => $value ) {
												
												if ( is_string( $value ) ) {
													
													$value = htmlspecialchars_decode( $value );
													
													if ( check_var( $ds_props[ $key_2 ][ 'options_from_users_submits' ] ) AND ( check_var( $ds_props[ $key_2 ][ 'options_title_field' ] ) OR check_var( $ds_props[ $key_2 ][ 'options_title_field_custom' ] ) ) AND is_numeric( $value ) AND $_user_submit = $this->get_ud_data( $value ) ) {
														
														$value = isset( $_user_submit[ 'data' ][ $ds_props[ $key_2 ][ 'options_title_field' ] ] ) ? $_user_submit[ 'data' ][ $ds_props[ $key_2 ][ 'options_title_field' ] ] : $_user_submit[ 'id' ];
														
													}
													
													$_field_value[] = $value;
													
												}
												
											}
											
											$ds_prop_value = join( ', ', $_field_value );
											
										}
										
									}
									else {
										
										if ( check_var( $ud_data[ $key_2 ], TRUE ) ) {
											
											$ds_prop_value = $ud_data[ $key_2 ];
											
										}
										else if ( check_var( $ds_props[ $key_2 ][ 'options_from_users_submits' ] ) AND ( check_var( $ds_props[ $key_2 ][ 'options_title_field' ] ) OR check_var( $ds_props[ $key_2 ][ 'options_title_field_custom' ] ) ) AND is_numeric( $ds_prop_value ) AND $_user_submit = $this->get_ud_data( $ds_prop_value ) ) {
											
											$ds_prop_value = isset( $_user_submit[ 'data' ][ $ds_props[ $key_2 ][ 'options_title_field' ] ] ) ? $_user_submit[ 'data' ][ $ds_props[ $key_2 ][ 'options_title_field' ] ] : $_user_submit[ 'id' ];
											
										}
										
									}
									
								}
								
							}
							else if ( in_array( $ds_props[ $key_2 ][ 'field_type' ], array( 'input_text', 'textarea' ) ) ) {
								
								if ( is_array( $ds_prop_value ) ) {
									
									$_field_value = array();
									
									foreach ( $ds_prop_value as $k => $value ) {
										
										if ( is_string( $value ) ) {
											
											if ( check_var( $ds_props[ $key_2 ][ 'options_from_users_submits' ] ) AND ( check_var( $ds_props[ $key_2 ][ 'options_title_field' ] ) OR check_var( $ds_props[ $key_2 ][ 'options_title_field_custom' ] ) ) AND is_numeric( $value ) AND $_user_submit = $this->get_ud_data( $value ) ) {
												
												$value = isset( $_user_submit[ 'data' ][ $ds_props[ $key_2 ][ 'options_title_field' ] ] ) ? $_user_submit[ 'data' ][ $ds_props[ $key_2 ][ 'options_title_field' ] ] : $_user_submit[ 'id' ];
												
											}
											
											$_field_value[] = $value;
											
										}
										
									}
									
									$ds_prop_value = join( ', ', $_field_value );
									
								}
								
								$ds_prop_value = htmlspecialchars_decode( $ds_prop_value );
								
								if ( in_array( $ds_props[ $key_2 ][ 'field_type' ], array( 'input_text' ) ) AND check_var( $ds_props[ $key_2 ][ 'validation_rule' ] ) AND in_array( 'mask', $ds_props[ $key_2 ][ 'validation_rule' ] ) AND check_var( $ds_props[ $key_2 ][ 'ud_validation_rule_parameter_mask_type' ] ) ) {
									
									$ds_prop_value = mask( $ds_prop_value,
										
										array(
											
											'mask_type' => isset( $ds_props[ $key_2 ][ 'ud_validation_rule_parameter_mask_type' ] ) ? $ds_props[ $key_2 ][ 'ud_validation_rule_parameter_mask_type' ] : NULL,
											'custom_mask' => isset( $ds_props[ $key_2 ][ 'ud_validation_rule_parameter_mask_custom_mask' ] ) ? $ds_props[ $key_2 ][ 'ud_validation_rule_parameter_mask_custom_mask' ] : NULL,
											'currency_symbol' => isset( $ds_props[ $key_2 ][ 'ud_validation_rule_parameter_mask_money_currency_symbol' ] ) ? $ds_props[ $key_2 ][ 'ud_validation_rule_parameter_mask_money_currency_symbol' ] : NULL,
											'decimal_point' => isset( $ds_props[ $key_2 ][ 'ud_validation_rule_parameter_mask_money_dec_point' ] ) ? $ds_props[ $key_2 ][ 'ud_validation_rule_parameter_mask_money_dec_point' ] : NULL,
											'thousands_separator' => isset( $ds_props[ $key_2 ][ 'ud_validation_rule_parameter_mask_money_thous_sep' ] ) ? $ds_props[ $key_2 ][ 'ud_validation_rule_parameter_mask_money_thous_sep' ] : NULL,
											
										)
										
									);
									
								}
								
							}
							
							// image prop type out
							
							if ( check_var( $ds_prop_value ) AND check_var( $ds_props[ $key_2 ][ 'advanced_options' ][ 'prop_is_ud_image' ] ) ) {
								
								$us_fields[ $key_2 ][ 'thumb_url' ] = get_url( $ds_prop_value );
								
								if ( ! url_is_absolute( $ds_prop_value ) ) {
									
									$us_fields[ $key_2 ][ 'thumb_url' ] = get_url( THUMBS_DIR_NAME . '/' . $ds_prop_value );
									
								}
								
								$ds_prop_value = get_url( $ds_prop_value );
								
								if ( $value_html ) {
									
									$thumb_params = array(
										
										'wrapper_class' => 'us-image-wrapper',
										'src' => url_is_absolute( $ds_prop_value ) ? $ds_prop_value : get_url( 'thumbs/' . $ds_prop_value ),
										'href' => get_url( $ds_prop_value ),
										'rel' => 'us-thumb',
										'title' => $ds_prop_value,
										'modal' => TRUE,
										'prevent_cache' => check_var( $advanced_options[ 'prop_is_ud_image_thumb_prevent_cache_admin' ] ) ? TRUE : FALSE,
										
									);
									
									$ds_prop_value = vui_el_thumb( $thumb_params );
									
								}
								
							}
							
							// url prop type out
							
							if ( check_var( $ds_prop_value ) AND check_var( $ds_props[ $key_2 ][ 'advanced_options' ][ 'prop_is_ud_url' ] ) ) {
								
								$_tmp = preg_split( "/(;| |,)/", $ds_prop_value );
								$_tmp_2 = array();
								
								if ( is_array( $_tmp ) ) {
									
									foreach( $_tmp as $k => $v ) {
										
										if ( trim( $v ) != '' ) {
											
											$v = get_url( $v );
											$_tmp_2[] = $value_html ? ( '<a href="' . $v . '" target="_blank">' . $v . '</a>' ) : $v;
											
										}
										
									}
									
									$ds_prop_value = join( ( $value_html ? ',<br/> ' : ', ' ), $_tmp_2 );
									
								}
								else {
									
									$___url = get_url( $ds_prop_value );
									$ds_prop_value = $value_html ? ( '<a href="' . $___url . '" target="_blank"' . '>' . $___url . '</a>' ) : $___url;
									
								}
								
							}
							
							// email prop type out
							
							if ( check_var( $ds_prop_value ) AND check_var( $ds_props[ $key_2 ][ 'advanced_options' ][ 'prop_is_ud_email' ] ) ) {
								
								$_tmp = preg_split( "/(;| |,|\/)/", $ds_prop_value );
								$_tmp_2 = array();
								
								if ( is_array( $_tmp ) ) {
									
									foreach( $_tmp as $k => $v ) {
										
										if ( trim( $v ) != '' ) {
											
											$_tmp_2[] = $value_html ? ( '<a href="mailto:' . $v . '" target="_blank">' . $v . '</a>' ) : $v;
											
										}
										
									}
									
									$ds_prop_value = join( ( $value_html ? ',<br/> ' : ', ' ), $_tmp_2 );
									
								}
								else {
									
									$ds_prop_value = '<a href="mailto:' . $ds_prop_value . '">' . $ds_prop_value . '</a>';
									
								}
								
							}
							
							$us_fields[ $key_2 ][ 'label' ] = isset( $ds_props[ $key_2 ][ 'presentation_label' ] ) ? $ds_props[ $key_2 ][ 'presentation_label' ] : $ds_props[ $key_2 ][ 'label' ];
							$us_fields[ $key_2 ][ 'value' ] = $ds_prop_value;
							$us_fields[ $key_2 ][ 'visible' ] = ( ! check_var( $ds_props_to_show ) OR in_array( $key_2, $ds_props_to_show ) ) ? TRUE : FALSE;
							
						}
						
					}
					else {
						
						$us_fields[ $key_2 ][ 'label' ] = '[' . $key_2 . ']';
						$us_fields[ $key_2 ][ 'value' ] = $ds_prop_value;
						$us_fields[ $key_2 ][ 'visible' ] = ( ! check_var( $ds_props_to_show ) OR in_array( $key_2, $ds_props_to_show ) ) ? TRUE : FALSE;
						
					}
					
					if ( check_var( $ds_props[ $key_2 ][ 'update_ud_data_out_format' ] ) ) {
						
						$regex = '/\{(.*?):value\}/i';
						
						$content = $ds_props[ $key_2 ][ 'update_ud_data_out_format' ];
						
						preg_match_all( $regex, $content, $matches );
						
						if ( count( $matches[ 1 ] ) ){
							
							$find = array(
								
								$key_2 => '{' . $key_2 . ':value}',
								
							);
							$replace = array(
								
								$key_2 => $ds_prop_value,
								
							);
							
							foreach( $matches[ 1 ] as $match ) {
								
								if ( $match != $key_2 AND check_var( $ud_data[ $match ], TRUE ) ) {
									
									$find[ $match ] = '{' . $match . ':value}';
									$replace[ $match ] = $ud_data[ $match ];
									
								}
								
							}
							
							$us_fields[ $key_2 ][ 'value' ] = str_replace( $find, $replace, $ds_props[ $key_2 ][ 'update_ud_data_out_format' ] );
							
						}
			
					}
					
				}
				
				$ud_data[ 'parsed_data' ][ 'full' ] = $us_fields;
				
				return $ud_data;
				
			}
			
		}
		
	}
	
	// --------------------------------------------------------------------
	
	public function get_ud_data( $id, $ds_id = NULL, $ignore_cache = FALSE ) {
		
		$search_config = array(
			
			'plugins' => 'sf_us_search',
			'ignore_cache' => $ignore_cache,
			'allow_empty_terms' => TRUE,
			'ipp' => 1,
			'cp' => 1,
			'plugins_params' => array(
				
				'sf_us_search' => array(
					
					'us_id' => $id,
					'sf_id' => $ds_id,
					
				),
				
			),
			
		);
		
		$this->load->library( 'search' );
		$this->search->reset_config();
		$this->search->config( $search_config );
		
		$ud_data = $this->search->get_full_results( 'sf_us_search', TRUE );
		
		if ( isset( $ud_data[ 0 ] ) ) {
			
			return $ud_data[ 0 ];
			
		}
		else {
			
			return FALSE;
			
		}
		
	}
	
	// --------------------------------------------------------------------
	
	public function get_data_schemes( $f_params = NULL ){
		
		// inicializando as variáveis
		$where_condition =						isset( $f_params['where_condition'] ) ? $f_params['where_condition'] : NULL;
		$or_where_condition =					isset( $f_params['or_where_condition'] ) ? $f_params['or_where_condition'] : NULL;
		$limit =								isset( $f_params['limit'] ) ? $f_params['limit'] : NULL;
		$offset =								isset( $f_params['offset'] ) ? $f_params['offset'] : NULL;
		$order_by =								isset( $f_params['order_by'] ) ? $f_params['order_by'] : 't1.title asc, t1.id asc';
		$order_by_escape =						isset( $f_params['order_by_escape'] ) ? $f_params['order_by_escape'] : TRUE;
		$return_type =							isset( $f_params['return_type'] ) ? $f_params['return_type'] : 'get';
		$cols =									isset( $f_params['cols'] ) ? $f_params['cols'] : '*';
		
		$allowed_cols = array(
			
			'id',
			'alias',
			'title',
			'create_datetime',
			'mod_datetime',
			'ordering',
			'status',
			'fields',
			'params',
			
		);
		
		if ( is_array( $cols ) ) {
			
			$_cols = array(
				
				'`t1`.`id`',
				'`t1`.`params`',
				
			);
			
			foreach( $cols as $k => $col ) {
				
				if ( ! in_array( $col, $allowed_cols ) ) {
					
					unset( $cols[ $k ] );
					continue;
					
				}
				
				if ( $col != 'id' AND $col != 'params' ) {
					
					$_cols[] = '`t1`.`' . $col . '`';
					
				}
				
			}
			
			$cols = join( ',', $_cols );
			
		}
		else if ( $cols != '*' ) {
			
			if ( ! in_array( $cols, $allowed_cols ) ) {
				
				$cols = '`t1`.`id`, `t1`.`params`';
				
			}
			else {
				
				$cols = '`t1`.`id`, `t1`.`params`, ' . $cols;
				
			}
			
		}
		
		$this->db->select( $cols );
		
		$this->db->from('tb_submit_forms t1');
		
		$this->db->order_by( $order_by, '', $order_by_escape );
		
		if ( $where_condition ){
			
			if( is_array( $where_condition ) ){

				foreach ( $where_condition as $key => $value ) {

					if( gettype( $where_condition ) === 'array' AND ( strpos( $key, 'fake_index_' ) !== FALSE ) ){

						$this->db->where( $value );
					}
					else $this->db->where( $key, $value );
					
				}
			}
			else $this->db->where( $where_condition );
		}
		if ( $or_where_condition ){
			if( is_array( $or_where_condition ) ){
				foreach ( $or_where_condition as $key => $value ) {

					if( gettype( $or_where_condition ) === 'array' AND ( strpos( $key, 'fake_index_' ) !== FALSE ) ){

						$this->db->or_where( $value );

					}
					else $this->db->or_where( $key, $value );

				}
			}
			else $this->db->or_where( $or_where_condition );

		}
		if ( $return_type === 'count_all_results' ){

			return $this->db->count_all_results();

		}
		if ( $limit ){

			$this->db->limit( $limit, $offset ? $offset : NULL );

		}
		
		$_ds_query_id = url_title( $this->db->_compile_select() );
		
		$data_scheme = FALSE;
		
		if ( $this->cache->cache( $_ds_query_id ) ) {
			
			$data_scheme = $this->cache->cache( $_ds_query_id );
			
			$this->db->_reset_select();
			
		}
		else {
			
			$data_scheme = $this->db->get();
			
			$this->cache->cache( $_ds_query_id, $data_scheme );
			
		}
		
		return $data_scheme;
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Parse a unid data scheme
	 *
	 * @access public
	 * @param array
	 * @param bool | determines whether the parameters should be filtered
	 * @return mixed
	 */
	
	public function parse_ds( & $data_scheme = NULL, $filter_params = FALSE, $props_to_show_site_list = NULL, $props_to_show_site_detail = NULL ){
		
		$props_to_show_site_list = check_var( $props_to_show_site_list ) ? $props_to_show_site_list : FALSE;
		$props_to_show_site_detail = check_var( $props_to_show_site_detail ) ? $props_to_show_site_detail : FALSE;
		
		$errors = FALSE;
		
		reset( $data_scheme );
		if ( is_array( $data_scheme ) AND is_numeric( key( $data_scheme ) ) ) {
			
			foreach ( $data_scheme as $key => $item ) {
				
				if ( key_exists( 'id', $item ) ) {
					
					$this->parse_ds( $item );
					
				}
				
			}
			
		}
		
		if ( is_array( $data_scheme ) AND key_exists( 'id', $data_scheme ) ){
			
			$data_scheme[ 'site_link' ] = $this->unid->get_link(
				
				array (
					
					'url_alias' => 'site_add_data',
					'ds' => $data_scheme,
					
				)
				
			);
			
			// Data submit menu item
			// -------------------------------------------------
			
			// -------------------------------------------------
			// Data list menu item
			
			$data_scheme[ 'data_list_site_link' ] = $this->unid->get_link(
				
				array (
					
					'url_alias' => 'site_data_list',
					'ds' => $data_scheme,
					
				)
				
			);
			
			// Data list menu item
			// -------------------------------------------------
			
			
			
			
			
			
			if ( isset( $data_scheme[ 'params' ] ) ) {
				
				$data_scheme[ 'params' ] = get_params( $data_scheme[ 'params' ] );
				
				if ( $filter_params ) {
					
					// obtendo os parâmetros globais do componente
					$component_params = $this->mcm->get_component( 'submit_forms' );
					$component_params = $component_params[ 'params' ];
					
					$params = array();
					
					$data_scheme[ 'params' ] = filter_params( $component_params, $data_scheme[ 'params' ] );
					
				}
				
			}
			
			$data_scheme[ 'edit_link' ] = ADMIN_ALIAS . '/' . $this->component_name . '/sfm/' . $this->uri->assoc_to_uri(
				
				array(
					
					'a' => 'esf',
					'sfid' => $data_scheme[ 'id' ],
					
				)
				
			);
			
			$data_scheme[ 'users_submits_link' ] = ADMIN_ALIAS . '/' . $this->component_name . '/usm/' . $this->uri->assoc_to_uri(
				
				array(
					
					'a' => 'usl',
					'sfid' => $data_scheme[ 'id' ],
					
				)
				
			);
			
			$data_scheme[ 'remove_link' ] = ADMIN_ALIAS . '/' . $this->component_name . '/sfm/' . $this->uri->assoc_to_uri(
				
				array(
					
					'a' => 'rds',
					'sfid' => $data_scheme[ 'id' ],
					
				)
				
			);
			
			if ( isset( $data_scheme[ 'fields' ] ) ) {
				
				$data_scheme[ 'fields' ] = get_params( $data_scheme[ 'fields' ], TRUE );
				
				$_fields = array();
				
				$data_scheme[ 'ud_image_prop' ] = NULL;
				
				$data_scheme[ 'params' ][ 'props_to_show_site_list' ] = array();
				
				if ( ( $props_to_show_site_list AND in_array( 'id', $props_to_show_site_list ) ) OR ( ! $props_to_show_site_list AND check_var( $data_scheme[ 'params' ][ 'ud_ds_default_data_id_site_list_show' ] ) ) ) {
					
					$new_column = & $data_scheme[ 'params' ][ 'props_to_show_site_list' ][ 'id' ];
					
					$new_column = 'id';
					
				}
				
				if ( ( $props_to_show_site_list AND in_array( 'submit_datetime', $props_to_show_site_list ) ) OR ( ! $props_to_show_site_list AND check_var( $data_scheme[ 'params' ][ 'ud_ds_default_data_sdt_site_list_show' ] ) ) ) {
					
					$new_column = & $data_scheme[ 'params' ][ 'props_to_show_site_list' ][ 'submit_datetime' ];
					
					$new_column = 'submit_datetime';
					
				}
				
				if ( ( $props_to_show_site_list AND in_array( 'mod_datetime', $props_to_show_site_list ) ) OR ( ! $props_to_show_site_list AND check_var( $data_scheme[ 'params' ][ 'ud_ds_default_data_mdt_site_list_show' ] ) ) ) {
					
					$new_column = & $data_scheme[ 'params' ][ 'props_to_show_site_list' ][ 'mod_datetime' ];
					
					$new_column = 'mod_datetime';
					
				}
				
				if ( ( $props_to_show_site_detail AND in_array( 'id', $props_to_show_site_detail ) ) OR ( ! $props_to_show_site_detail AND check_var( $data_scheme[ 'params' ][ 'ud_ds_default_data_id_site_detail_show' ] ) ) ) {
					
					$new_column = & $data_scheme[ 'params' ][ 'props_to_show_site_detail' ][ 'id' ];
					
					$new_column = 'id';
					
				}
				
				if ( ( $props_to_show_site_detail AND in_array( 'submit_datetime', $props_to_show_site_detail ) ) OR ( ! $props_to_show_site_detail AND check_var( $data_scheme[ 'params' ][ 'ud_ds_default_data_sdt_site_detail_show' ] ) ) ) {
					
					$new_column = & $data_scheme[ 'params' ][ 'props_to_show_site_detail' ][ 'submit_datetime' ];
					
					$new_column = 'submit_datetime';
					
				}
				
				if ( ( $props_to_show_site_detail AND in_array( 'mod_datetime', $props_to_show_site_detail ) ) OR ( ! $props_to_show_site_detail AND check_var( $data_scheme[ 'params' ][ 'ud_ds_default_data_mdt_site_detail_show' ] ) ) ) {
					
					$new_column = & $data_scheme[ 'params' ][ 'props_to_show_site_detail' ][ 'mod_datetime' ];
					
					$new_column = 'mod_datetime';
					
				}
				
				foreach( $data_scheme[ 'fields' ] as $k => & $prop ) {
					
					if ( check_var( $prop ) ) {
						
						if ( ! in_array( $prop[ 'field_type' ], array( 'html', 'button' ) ) ){
							
							if ( check_var( $prop[ 'availability' ][ 'site' ] ) ) {
								
								$data_scheme[ 'params' ][ 'ud_data_availability_site_search' ][ $prop[ 'alias' ] ] = $prop[ 'alias' ];
								
							}
							else {
								
								$data_scheme[ 'params' ][ 'ud_data_availability_site_search' ][ $prop[ 'alias' ] ] = 0;
								
							}
							
							if ( ( $props_to_show_site_list AND in_array( $prop[ 'alias' ], $props_to_show_site_list ) ) OR ( ! $props_to_show_site_list AND check_var( $prop[ 'visibility' ][ 'site' ][ 'list' ] ) ) ) {
								
								$new_column = & $data_scheme[ 'params' ][ 'props_to_show_site_list' ][ $prop[ 'alias' ] ];
								
								$new_column = $prop[ 'alias' ];
								
							}
							
							if ( ( $props_to_show_site_detail AND in_array( $prop[ 'alias' ], $props_to_show_site_detail ) ) OR ( ! $props_to_show_site_detail AND check_var( $prop[ 'visibility' ][ 'site' ][ 'detail' ] ) ) ) {
								
								$new_column_d = & $data_scheme[ 'params' ][ 'props_to_show_site_detail' ][ $prop[ 'alias' ] ];
								
								$new_column_d = $prop[ 'alias' ];
								
							}
							
						}
						
						if ( ! check_var( $prop[ 'key' ] ) ) {
							
							unset( $data_scheme[ 'fields' ][ $k ] );
							//$prop[ 'alias' ] = isset( $prop[ 'alias' ] ) ? $prop[ 'alias' ] : $this->make_field_name( $prop[ 'label' ] );
							
						}
						else {
							
							if ( empty( $prop[ 'label' ] ) ) {
								
								if ( isset( $prop[ 'presentation_label' ] ) ) {
									
									$prop[ 'label' ] = $prop[ 'presentation_label' ];
									
								}
								else {
									
									$prop[ 'label' ] = lang( 'field' ) . ' ' . $prop[ 'key' ];
									
								}
								
							}
							
							if ( empty( $prop[ 'presentation_label' ] ) ) {
								
								if ( isset( $prop[ 'label' ] ) ) {
									
									$prop[ 'presentation_label' ] = $prop[ 'label' ];
									
								}
								else {
									
									$prop[ 'presentation_label' ] = lang( 'field' ) . ' ' . $prop[ 'key' ];
									
								}
								
							}
							
							if ( empty( $prop[ 'alias' ] ) ) {
								
								if ( isset( $prop[ 'label' ] ) ) {
									
									$prop[ 'alias' ] = $this->make_field_name( $prop[ 'label' ] );
									
								}
								else {
									
									$prop[ 'alias' ] = $this->make_field_name( lang( 'field' ) . ' ' . $prop[ 'key' ] );
									
								}
								
							}
							
							// -------------------------------------------------
							// Properties types
							
							if ( isset( $prop[ 'advanced_options' ][ 'prop_is_ud_image' ] ) ) {
								
								$data_scheme[ 'params' ][ 'ud_image_prop' ][ $prop[ 'alias' ] ] = 1;
								
							}
							
							if ( isset( $prop[ 'advanced_options' ][ 'prop_is_ud_title' ] ) ) {
								
								$data_scheme[ 'params' ][ 'ud_title_prop' ][ $prop[ 'alias' ] ] = 1;
								
							}
							
							if ( isset( $prop[ 'advanced_options' ][ 'prop_is_ud_content' ] ) ) {
								
								$data_scheme[ 'params' ][ 'ud_content_prop' ][ $prop[ 'alias' ] ] = 1;
								
							}
							
							if ( isset( $prop[ 'advanced_options' ][ 'prop_is_ud_other_info' ] ) ) {
								
								$data_scheme[ 'params' ][ 'ud_other_info_prop' ][ $prop[ 'alias' ] ] = 1;
								
							}
							
							if ( isset( $prop[ 'advanced_options' ][ 'prop_is_ud_status' ] ) ) {
								
								$data_scheme[ 'params' ][ 'ud_status_prop' ][ $prop[ 'alias' ] ] = 1;
								
							}
							
							if ( isset( $prop[ 'advanced_options' ][ 'prop_is_ud_event_datetime' ] ) ) {
								
								$data_scheme[ 'params' ][ 'ud_event_datetime_prop' ][ $prop[ 'alias' ] ] = 1;
								
							}
							
							// Properties types
							// -------------------------------------------------
							
							$_fields[ $prop[ 'alias' ] ] = $prop;
							
						}
						
					}
					
				}
				
				// temporary
				$data_scheme[ 'ud_image_prop' ] = & $data_scheme[ 'params' ][ 'ud_image_prop' ];
				$data_scheme[ 'ud_title_prop' ] = & $data_scheme[ 'params' ][ 'ud_title_prop' ];
				$data_scheme[ 'ud_content_prop' ] = & $data_scheme[ 'params' ][ 'ud_content_prop' ];
				$data_scheme[ 'ud_other_info_prop' ] = & $data_scheme[ 'params' ][ 'ud_other_info_prop' ];
				$data_scheme[ 'ud_status_prop' ] = & $data_scheme[ 'params' ][ 'ud_status_prop' ];
				$data_scheme[ 'ud_event_datetime_prop' ] = & $data_scheme[ 'params' ][ 'ud_event_datetime_prop' ];
				
// 				echo '<pre>' . print_r( $data_scheme, TRUE ) . '</pre>';exit;
				
				$data_scheme[ 'fields' ] = $_fields;
				
// 				echo '<pre>' . print_r( $data_scheme[ 'params' ][ 'props_to_show_site_list' ], TRUE ) . '</pre>';
// 				echo '<pre>' . print_r( $data_scheme[ 'params' ][ 'props_to_show_site_detail' ], TRUE ) . '</pre>';
				
				array_unshift( $data_scheme[ 'fields' ], NULL );
				
				unset( $data_scheme[ 'fields' ][ 0 ] );
				unset( $_fields );
				
// 				echo '<pre>' . print_r( $data_scheme[ 'ud_status_prop' ], TRUE ) . '</pre>';
				
			}
			
		}
		
		if ( ! $errors ) {
			
			return TRUE;
			
		}
		else if ( $errors ) {
			
			return $errors;
			
		}
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Scan all data seeking referenced properties and adjusts its values
	 *
	 * @access public
	 * @return array
	 */
	
	public function adjust_referenced_data( $data, $changed_prop_alias ) {
		
		// We select data schemes that use the property of this data as reference
		
		$this->db->select( 'id' );
		$this->db->from( 'tb_submit_forms' );
		$this->db->where( '`fields` LIKE \'%"options_from_users_submits":"' . $data[ 'submit_form_id' ] . '"%\' COLLATE \'utf8_general_ci\'' );
		
		$where = '(';
		$where .= '`fields` LIKE \'%"options_title_field":"' . $changed_prop_alias . '"%\' COLLATE \'utf8_general_ci\' OR ';
		$where .= '`fields` LIKE \'%"options_filter_order_by":"' . $changed_prop_alias . '"%\' COLLATE \'utf8_general_ci\'';
		$where .= ')';
		
		$this->db->where( $where );
		
		$ds_ids = $this->db->get()->result_array();
		
		if ( $ds_ids ) {
			
			$this->db->select( '
				
				id,
				submit_form_id,
				data
				
			' );
			$this->db->from( 'tb_submit_forms_us' );
			
			$where_1 = array();
			
			foreach( $ds_ids as $value ) {
				
				$id = $value[ 'id' ];
				
				$where_1[] = '`submit_form_id` = ' . $id;
				
			}
			
			$where_1 = '(' . join( ' OR ', $where_1 ) . ')';
			
			// ----------------
			
			$where_2 = array();
			
			$where_2[] = '`data` LIKE \'%"%":["' . $data[ 'id' ] . '"]%\' COLLATE \'utf8_general_ci\'';
			$where_2[] = '`data` LIKE \'%"%":["' . $data[ 'id' ] . '","%]%\' COLLATE \'utf8_general_ci\'';
			$where_2[] = '`data` LIKE \'%"%":[%","' . $data[ 'id' ] . '"]%\' COLLATE \'utf8_general_ci\'';
			$where_2[] = '`data` LIKE \'%"%":[%","' . $data[ 'id' ] . '","%]%\' COLLATE \'utf8_general_ci\'';
			$where_2[] = '`data` LIKE \'%"%":"' . $data[ 'id' ] . '"%\' COLLATE \'utf8_general_ci\'';
			
			$where_2 = '(' . join( ' OR ', $where_2 ) . ')';
			
			$where = $where_1 . ' AND ' . $where_2;
			
			$this->db->where( $where );
			
			$data_results = $this->db->get()->result_array();
			
			if ( $data_results ) {
				
				foreach( $data_results as $_data ) {
					
					$_data[ 'xml_data' ] = $this->us_json_data_to_xml( $_data );
					
					$_d_id = $_data[ 'id' ];
					
					unset( $_data[ 'id' ] );
					
					if ( ! $this->db->update( 'tb_submit_forms_us', $_data, array( 'id' => $_d_id ) ) ){
						
						msg( lang( $data[ 'params' ][ 'ud_data_update_fail_referenced_data' ], NULL, $data[ 'id' ] ), 'error' );
						
					}
					
				}
				
			}
			
		}
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Get formated fied name
	 *
	 * @access public
	 * @param string
	 * @return string
	 */
	
	public function make_field_name( $label ){
		
		return url_title( $label, '-', TRUE );
		
	}
	
}
