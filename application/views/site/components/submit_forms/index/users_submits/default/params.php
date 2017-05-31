<?php
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'select',
		'name' => 'ud_d_list_show_page_content_title',
		'label' => 'ud_d_list_show_page_content_title',
		'tip' => 'tip_ud_d_list_show_page_content_title',
		'default' => '1',
		'validation' => array(
			
			'rules' => 'trim',
			
		),
		'options' => array(
			
			'global' => 'global',
			'0' => 'no',
			'1' => 'yes',
			
		),
		
	);
	
	$params[ 'params_spec_values' ][ 'ud_d_list_show_page_content_title' ] = '1';
	
	$new_params[] = $_tmp;
	
	//------------------------------------------------------
	
	$new_params[] = array(
		
		'type' => 'spacer',
		'label' => 'ud_d_list_site_props_to_show',
		'tip' => 'tip_ud_d_list_site_props_to_show',
		
	);
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'select',
		'name' => 'ud_d_list_site_override_visible_props',
		'label' => 'ud_d_list_site_override_visible_props',
		'tip' => 'tip_ud_d_list_site_override_visible_props',
		'validation' => array(
			
			'rules' => 'trim|required',
			
		),
		'options' => array(
			
			'1' => 'yes',
			'0' => 'no',
			
		),
		
	);
	
	$params[ 'params_spec_values' ][ 'ud_d_list_site_override_visible_props' ] = 0;
	
	$new_params[] = $_tmp;
	
	//------------------------------------------------------
	
	foreach ( $data_scheme[ 'fields' ] as $key => $field ) {
		
		$alias = check_var( $field[ 'alias' ] ) ? $field[ 'alias' ] : FALSE;
		
		if ( $alias ) {
			
			$fields_options[ $alias ] = $field[ 'label' ];
			
		}
		
	}
	
	if ( check_var( $current_params_values[ 'ud_d_list_site_override_visible_props' ] ) ) {
		
		//------------------------------------------------------
		
		$new_params[] = array(
			
			'type' => 'spacer',
			
		);
		
		//------------------------------------------------------
		
		$_tmp = array(
			
			'type' => 'checkbox',
			'inline' => TRUE,
			'name' => 'ud_d_list_site_props_to_show[id]',
			'label' => 'id',
			'value' => 'id',
			'validation' => array(
				
				'rules' => 'trim',
				
			),
			
		);

		if ( $new_flag ) {
			
			// $params[ 'params_spec_values' ][ 'ud_title_prop[id]' ] = 'id';
			
		}
		
		$new_params[] = $_tmp;
		
		/*
		* ------------------------------------
		*/

		$_tmp = array(
			
			'type' => 'checkbox',
			'inline' => TRUE,
			'name' => 'ud_d_list_site_props_to_show[submit_datetime]',
			'label' => 'submit_datetime',
			'value' => 'submit_datetime',
			'validation' => array(
				
				'rules' => 'trim',
				
			),
			
		);

		if ( $new_flag ) {
			
			// $params[ 'params_spec_values' ][ 'ud_title_prop[submit_datetime]' ] = 'submit_datetime';
			
		}
		
		$new_params[] = $_tmp;
		
		//------------------------------------------------------
		
		$_tmp = array(
			
			'type' => 'checkbox',
			'inline' => TRUE,
			'name' => 'ud_d_list_site_props_to_show[mod_datetime]',
			'label' => 'mod_datetime',
			'value' => 'mod_datetime',
			'validation' => array(
				
				'rules' => 'trim',
				
			),
			
		);

		if ( $new_flag ) {
			
			// $params[ 'params_spec_values' ][ 'ud_title_prop[mod_datetime]' ] = 'mod_datetime';
			
		}
		
		$new_params[] = $_tmp;
		
		//------------------------------------------------------
		
		foreach ( $fields_options as $alias => $label ) {
			
			$_tmp = array(
				
				'type' => 'checkbox',
				'inline' => TRUE,
				'name' => 'ud_d_list_site_props_to_show[' . $alias . ']',
				'label' => $label,
				'value' => $alias,
				'validation' => array(
					
					'rules' => 'trim',
					
				),
				
			);
			
			if ( ! in_array( $field[ 'field_type' ], array( 'button', 'html', ) ) ) {
				
				if ( $new_flag ) {
					
					// $params[ 'params_spec_values' ][ 'ud_title_prop[' . $alias . ']' ] = $alias;
					
				}
				
			}
			
			if ( $field[ 'field_type' ] == 'combo_box' ) {
				
				$select_fields[] = $field;
				
			}
			
			$new_params[] = $_tmp;
			
		}
		
	}
	
	//------------------------------------------------------
	
	$new_params[] = array(
		
		'type' => 'spacer',
		'label' => 'ud_d_list_site_presentation_props',
		'tip' => 'tip_ud_title_prop',
		'level' => '4',
		
	);
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'select',
		'name' => 'ud_d_list_site_override_presentation_props',
		'label' => 'ud_d_list_site_override_presentation_props',
		'tip' => 'tip_ud_d_list_site_override_presentation_props',
		'validation' => array(
			
			'rules' => 'trim|required',
			
		),
		'options' => array(
			
			'1' => 'yes',
			'0' => 'no',
			
		),
		
	);
	
	$params[ 'params_spec_values' ][ 'ud_d_list_site_override_presentation_props' ] = 0;
	
	$new_params[] = $_tmp;
	
	//------------------------------------------------------
	
	$new_params[] = array(
		
		'type' => 'spacer',
		'label' => 'ud_title_prop',
		'tip' => 'tip_ud_title_prop',
		
	);
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'checkbox',
		'inline' => TRUE,
		'name' => 'ud_data_list_d_titles_as_link',
		'label' => 'ud_data_list_d_titles_as_link',
		'tip' => 'tip_ud_data_list_d_titles_as_link',
		'validation' => array(
			
			'rules' => 'trim',
			
		),
		
	);
	
	$params[ 'params_spec_values' ][ 'ud_data_list_d_titles_as_link' ] = 1;
	
	$new_params[] = $_tmp;
	
	
	
	if ( check_var( $current_params_values[ 'ud_d_list_site_override_presentation_props' ] ) ) {
		
		//------------------------------------------------------
		
		$new_params[] = array(
			
			'type' => 'spacer',
			
		);
		
		//------------------------------------------------------
		
		$_tmp = array(
			
			'type' => 'checkbox',
			'inline' => TRUE,
			'name' => 'ud_title_prop[id]',
			'label' => 'id',
			'value' => 'id',
			'validation' => array(
				
				'rules' => 'trim',
				
			),
			
		);

		if ( $new_flag ) {
			
			// $params[ 'params_spec_values' ][ 'ud_title_prop[id]' ] = 'id';
			
		}
		
		$new_params[] = $_tmp;
		
		/*
		* ------------------------------------
		*/

		$_tmp = array(
			
			'type' => 'checkbox',
			'inline' => TRUE,
			'name' => 'ud_title_prop[submit_datetime]',
			'label' => 'submit_datetime',
			'value' => 'submit_datetime',
			'validation' => array(
				
				'rules' => 'trim',
				
			),
			
		);

		if ( $new_flag ) {
			
			// $params[ 'params_spec_values' ][ 'ud_title_prop[submit_datetime]' ] = 'submit_datetime';
			
		}

		$new_params[] = $_tmp;

		/*
		* ------------------------------------
		*/

		$_tmp = array(
			
			'type' => 'checkbox',
			'inline' => TRUE,
			'name' => 'ud_title_prop[mod_datetime]',
			'label' => 'mod_datetime',
			'value' => 'mod_datetime',
			'validation' => array(
				
				'rules' => 'trim',
				
			),
			
		);

		if ( $new_flag ) {
			
			// $params[ 'params_spec_values' ][ 'ud_title_prop[mod_datetime]' ] = 'mod_datetime';
			
		}

		$new_params[] = $_tmp;

		/*
		* ------------------------------------
		*/

		foreach ( $fields_options as $alias => $label ) {
			
			$_tmp = array(
				
				'type' => 'checkbox',
				'inline' => TRUE,
				'name' => 'ud_title_prop[' . $alias . ']',
				'label' => $label,
				'value' => $alias,
				'validation' => array(
					
					'rules' => 'trim',
					
				),
				
			);
			
			if ( ! in_array( $field[ 'field_type' ], array( 'button', 'html', ) ) ) {
				
				if ( $new_flag ) {
					
					// $params[ 'params_spec_values' ][ 'ud_title_prop[' . $alias . ']' ] = $alias;
					
				}
				
			}
			
			if ( $field[ 'field_type' ] == 'combo_box' ) {
				
				$select_fields[] = $field;
				
			}
			
			$new_params[] = $_tmp;
			
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		/*
		* ------------------------------------
		*/

		$new_params[] = array(
			
			'type' => 'spacer',
			'label' => 'ud_content_prop',
			'tip' => 'tip_ud_content_prop',
			
		);

		/*
		* ------------------------------------
		*/

		$_tmp = array(
			
			'type' => 'checkbox',
			'inline' => TRUE,
			'name' => 'ud_content_prop[id]',
			'label' => 'id',
			'value' => 'id',
			'validation' => array(
				
				'rules' => 'trim',
				
			),
			
		);

		if ( $new_flag ) {
			
			// $params[ 'params_spec_values' ][ 'ud_content_prop[id]' ] = 'id';
			
		}

		$new_params[] = $_tmp;

		/*
		* ------------------------------------
		*/

		$_tmp = array(
			
			'type' => 'checkbox',
			'inline' => TRUE,
			'name' => 'ud_content_prop[submit_datetime]',
			'label' => 'submit_datetime',
			'value' => 'submit_datetime',
			'validation' => array(
				
				'rules' => 'trim',
				
			),
			
		);

		if ( $new_flag ) {
			
			// $params[ 'params_spec_values' ][ 'ud_content_prop[submit_datetime]' ] = 'submit_datetime';
			
		}

		$new_params[] = $_tmp;

		/*
		* ------------------------------------
		*/

		$_tmp = array(
			
			'type' => 'checkbox',
			'inline' => TRUE,
			'name' => 'ud_content_prop[mod_datetime]',
			'label' => 'mod_datetime',
			'value' => 'mod_datetime',
			'validation' => array(
				
				'rules' => 'trim',
				
			),
			
		);
		
		if ( $new_flag ) {
			
			// $params[ 'params_spec_values' ][ 'ud_content_prop[mod_datetime]' ] = 'mod_datetime';
			
		}
		
		$new_params[] = $_tmp;
		
		/*
		* ------------------------------------
		*/
		
		foreach ( $data_scheme[ 'fields' ] as $key => $field ) {
			
			$alias = check_var( $field[ 'alias' ] ) ? $field[ 'alias' ] : FALSE;
			
			if ( $alias ) {
				
				$fields_options[ $alias ] = $field[ 'label' ];
				
				$_tmp = array(
					
					'type' => 'checkbox',
					'inline' => TRUE,
					'name' => 'ud_content_prop[' . $alias . ']',
					'label' => $field[ 'label' ],
					'value' => $alias,
					'validation' => array(
						
						'rules' => 'trim',
						
					),
					
				);
				
				if ( ! in_array( $field[ 'field_type' ], array( 'button', 'html', ) ) ) {
					
					if ( $new_flag ) {
						
						// $params[ 'params_spec_values' ][ 'ud_content_prop[' . $alias . ']' ] = $alias;
						
					}
					
				}
				
				if ( $field[ 'field_type' ] == 'combo_box' ) {
					
					$select_fields[] = $field;
					
				}
				
				$new_params[] = $_tmp;
				
			}
			
		}
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		/*
		* ------------------------------------
		*/

		$new_params[] = array(
			
			'type' => 'spacer',
			'label' => 'ud_other_info_prop',
			'tip' => 'tip_ud_other_info_prop',
			
		);

		/*
		* ------------------------------------
		*/

		$_tmp = array(
			
			'type' => 'checkbox',
			'inline' => TRUE,
			'name' => 'ud_other_info_prop[id]',
			'label' => 'id',
			'value' => 'id',
			'validation' => array(
				
				'rules' => 'trim',
				
			),
			
		);

		if ( $new_flag ) {
			
			// $params[ 'params_spec_values' ][ 'ud_other_info_prop[id]' ] = 'id';
			
		}

		$new_params[] = $_tmp;

		/*
		* ------------------------------------
		*/

		$_tmp = array(
			
			'type' => 'checkbox',
			'inline' => TRUE,
			'name' => 'ud_other_info_prop[submit_datetime]',
			'label' => 'submit_datetime',
			'value' => 'submit_datetime',
			'validation' => array(
				
				'rules' => 'trim',
				
			),
			
		);

		if ( $new_flag ) {
			
			// $params[ 'params_spec_values' ][ 'ud_other_info_prop[submit_datetime]' ] = 'submit_datetime';
			
		}

		$new_params[] = $_tmp;

		/*
		* ------------------------------------
		*/

		$_tmp = array(
			
			'type' => 'checkbox',
			'inline' => TRUE,
			'name' => 'ud_other_info_prop[mod_datetime]',
			'label' => 'mod_datetime',
			'value' => 'mod_datetime',
			'validation' => array(
				
				'rules' => 'trim',
				
			),
			
		);

		if ( $new_flag ) {
			
			// $params[ 'params_spec_values' ][ 'ud_other_info_prop[mod_datetime]' ] = 'mod_datetime';
			
		}

		$new_params[] = $_tmp;

		/*
		* ------------------------------------
		*/

		foreach ( $data_scheme[ 'fields' ] as $key => $field ) {
			
			$alias = check_var( $field[ 'alias' ] ) ? $field[ 'alias' ] : FALSE;
			
			if ( $alias ) {
				
				$fields_options[ $alias ] = $field[ 'label' ];
				
				$_tmp = array(
					
					'type' => 'checkbox',
					'inline' => TRUE,
					'name' => 'ud_other_info_prop[' . $alias . ']',
					'label' => $field[ 'label' ],
					'value' => $alias,
					'validation' => array(
						
						'rules' => 'trim',
						
					),
					
				);
				
				if ( ! in_array( $field[ 'field_type' ], array( 'button', 'html', ) ) ) {
					
					if ( $new_flag ) {
						
						// $params[ 'params_spec_values' ][ 'ud_other_info_prop[' . $alias . ']' ] = $alias;
						
					}
					
				}
				
				if ( $field[ 'field_type' ] == 'combo_box' ) {
					
					$select_fields[] = $field;
					
				}
				
				$new_params[] = $_tmp;
				
			}
			
		}
		
	}
	
	
	
		//------------------------------------------------------
		
		$new_params[] = array(
			
			'type' => 'spacer',
			'label' => 'ud_image_prop',
			'tip' => 'tip_ud_image_prop',
			
		);
		
		//------------------------------------------------------
		
		$_tmp = array(
			
			'type' => 'select',
			'name' => 'ud_d_list_main_image_on_title',
			'label' => 'ud_d_list_main_image_on_title',
			'tip' => 'tip_ud_d_list_main_image_on_title',
			'default' => '1',
			'validation' => array(
				
				'rules' => 'trim|required',
				
			),
			'options' => array(
				
				'0' => 'no',
				'1' => 'yes',
				
			),
			
		);
		
		$params[ 'params_spec_values' ][ 'ud_d_list_main_image_on_title' ] = TRUE;
		
		$new_params[] = $_tmp;
		
		//------------------------------------------------------
		
		if ( check_var( $menu_item[ 'params' ][ 'ud_d_list_site_override_presentation_props' ] ) ) {
			
			foreach ( $data_scheme[ 'fields' ] as $key => $prop ) {
				
				$alias = check_var( $prop[ 'alias' ] ) ? $prop[ 'alias' ] : FALSE;
				
				if ( $alias AND ! in_array( $prop[ 'field_type' ], array( 'button', 'html', ) ) ) {
					
					$fieldss_options[ $alias ] = $prop[ 'label' ];
					
					$_tmp = array(
						
						'type' => 'checkbox',
						'inline' => TRUE,
						'name' => 'ud_image_prop[' . $alias . ']',
						'label' => $prop[ 'label' ],
						'value' => $alias,
						'validation' => array(
							
							'rules' => 'trim',
							
						),
						
					);
					
					if ( $prop[ 'field_type' ] == 'combo_box' ) {
						
						$select_fields[] = $prop;
						
					}
					
					$new_params[] = $_tmp;
					
				}
				
			}
			
		}

	
	
	
	
	
	
	
	//------------------------------------------------------
	
	$new_params[] = array(
		
		'type' => 'spacer',
		'label' => 'ud_data_list_search',
		'name' => 'ud_data_list_search',
		'level' => '4',
		
	);
	
	/*
	* ------------------------------------
	*/
	
	$_tmp = array(
		
		'type' => 'select',
		'name' => 'use_search',
		'label' => 'use_search',
		'tip' => 'tip_use_search',
		'validation' => array(
			
			'rules' => 'trim|required',
			
		),
		'options' => array(
			
			'global' => 'global',
			'1' => 'yes',
			'0' => 'no',
			
		),
		
	);
	
	$params[ 'params_spec_values' ][ 'use_search' ] = 0;
	
	$new_params[] = $_tmp;
	
	/*
	* ------------------------------------
	*/
	
	$_tmp = array(
		
		'type' => 'select',
		'name' => 'ud_d_list_site_search_box_positioning',
		'label' => 'ud_d_list_site_search_box_positioning',
		'tip' => 'tip_ud_d_list_site_search_box_positioning',
		'validation' => array(
			
			'rules' => 'trim|required',
			
		),
		'options' => array(
			
			'global' => 'global',
			'l' => 'left',
			'r' => 'right',
			't' => 'top',
			
		),
		
	);
	
	$params[ 'params_spec_values' ][ 'ud_d_list_site_search_box_positioning' ] = 0;
	
	$new_params[] = $_tmp;
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'select',
		'name' => 'ud_data_availability_site_search[__terms]',
		'label' => 'ud_data_list_prop_search_field_terms',
		'validation' => array(
			
			'rules' => 'trim',
			
		),
		'options' => array(
			
			'global' => 'global',
			'__terms' => 'yes',
			'0' => 'no',
			
		),
		
	);
	
	$params[ 'params_spec_values' ][ 'ud_data_availability_site_search[__terms]' ] = '__terms';
	
	$select_fields[] = $field;
	
	$new_params[] = $_tmp;
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'text',
		'inline' => TRUE,
		'name' => 'search_terms_string',
		'label' => 'search_terms_string',
		'tip' => 'tip_search_terms_string',
		'validation' => array(
			
			'rules' => 'trim',
			
		),
		
	);
	
	$params[ 'params_spec_values' ][ 'search_terms_string' ] = lang( 'search_terms' );
	
	$new_params[] = $_tmp;
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//------------------------------------------------------
	
	$new_params[] = array(
		
		'type' => 'spacer',
		'label' => 'ud_data_availability_site_search_lbl',
		'name' => 'ud_data_availability_site_search_lbl',
		
	);
	
	//------------------------------------------------------
	
	foreach ( $data_scheme[ 'fields' ] as $key => $field ) {
		
		$alias = check_var( $field[ 'alias' ] ) ? $field[ 'alias' ] : FALSE;
		
		if ( $alias ) {
			
			if ( in_array( $field[ 'field_type' ], array( 'combo_box', ) ) ) {
				
				$fields_options[ $alias ] = $field[ 'label' ];
				
				$_tmp = array(
					
					'type' => 'checkbox',
					'inline' => TRUE,
					'name' => 'ud_data_availability_site_search[' . $alias . ']',
					'label' => $field[ 'label' ],
					'value' => $alias,
					'validation' => array(
						
						'rules' => 'trim',
						
					),
					
				);
				
				$_tmp = array(
					
					'type' => 'select',
					'name' => 'ud_data_availability_site_search[' . $alias . ']',
					'label' => $field[ 'label' ],
					'validation' => array(
						
						'rules' => 'trim',
						
					),
					'options' => array(
						
						'global' => 'global',
						$alias => 'yes',
						'0' => 'no',
						
					),
					
				);
				
				$params[ 'params_spec_values' ][ 'ud_data_availability_site_search[' . $alias . ']' ] = $alias;
				
				$select_fields[] = $field;
				
				$new_params[] = $_tmp;
				
				//------------------------------------------------------
				
				$_tmp = array(
					
					'type' => 'select',
					'name' => 'ud_d_list_search_options_list_style[' . $alias . ']',
					'label' => 'ud_d_list_search_options_list_style',
					'validation' => array(
						
						'rules' => 'trim',
						
					),
					'options' => array(
						
						'default' => 'default',
						'list' => 'list',
						
					),
					
				);
				
				$params[ 'params_spec_values' ][ 'ud_data_availability_site_search[' . $alias . ']' ] = $alias;
				
				$new_params[] = $_tmp;
				
			}
			
		}
		
	}
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'spacer',
		'label' => 'ud_data_list_results',
		'name' => 'ud_data_list_results',
		
	);
	
	$new_params[] = $_tmp;
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'select',
		'inline' => TRUE,
		'name' => 'show_default_results',
		'label' => 'show_default_results',
		'tip' => 'tip_show_default_results',
		'validation' => array(
			
			'rules' => 'trim|required',
			
		),
		'options' => array(
			
			'1' => 'yes',
			'0' => 'no',
			
		),
		
	);
	
	$params[ 'params_spec_values' ][ 'show_default_results' ] = 1;
	
	$new_params[] = $_tmp;
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'select',
		'inline' => TRUE,
		'name' => 'show_results_count',
		'label' => 'show_results_count',
		'tip' => 'tip_show_results_count',
		'validation' => array(
			
			'rules' => 'trim|required',
			
		),
		'options' => array(
			
			'1' => 'yes',
			'0' => 'no',
			
		),
		
	);
	
	$params[ 'params_spec_values' ][ 'show_results_count' ] = 1;
	
	$new_params[] = $_tmp;
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'select',
		'inline' => TRUE,
		'name' => 'show_search_results_count',
		'label' => 'show_search_results_count',
		'tip' => 'tip_show_search_results_count',
		'validation' => array(
			
			'rules' => 'trim|required',
			
		),
		'options' => array(
			
			'1' => 'yes',
			'0' => 'no',
			
		),
		
	);
	
	$params[ 'params_spec_values' ][ 'show_search_results_count' ] = 1;
	
	$new_params[] = $_tmp;
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'text',
		'inline' => TRUE,
		'name' => 'ud_d_list_results_string',
		'label' => 'ud_d_list_results_string_config_label',
		'tip' => 'tip_ud_d_list_results_string_config_label',
		'validation' => array(
			
			'rules' => 'trim',
			
		),
		
	);
	
	$params[ 'params_spec_values' ][ 'ud_d_list_results_string' ] = lang( 'ud_d_list_results_string' );
	
	$new_params[] = $_tmp;
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'text',
		'inline' => TRUE,
		'name' => 'ud_d_list_search_results_string',
		'label' => 'ud_d_list_search_results_string_config_label',
		'tip' => 'tip_ud_d_list_search_results_string_config_label',
		'validation' => array(
			
			'rules' => 'trim',
			
		),
		
	);
	
	$params[ 'params_spec_values' ][ 'ud_d_list_search_results_string' ] = lang( 'ud_d_list_search_results_string' );
	
	$new_params[] = $_tmp;
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'text',
		'inline' => TRUE,
		'name' => 'ud_d_list_single_result_string',
		'label' => 'ud_d_list_single_result_string_config_label',
		'tip' => 'tip_ud_d_list_single_result_string_config_label',
		'validation' => array(
			
			'rules' => 'trim',
			
		),
		
	);
	
	$params[ 'params_spec_values' ][ 'ud_d_list_single_result_string' ] = lang( 'ud_d_list_single_result_string' );
	
	$new_params[] = $_tmp;
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'text',
		'inline' => TRUE,
		'name' => 'ud_d_list_search_single_result_string',
		'label' => 'ud_d_list_search_single_result_string_config_label',
		'tip' => 'tip_ud_d_list_search_single_result_string_config_label',
		'validation' => array(
			
			'rules' => 'trim',
			
		),
		
	);
	
	$params[ 'params_spec_values' ][ 'ud_d_list_search_single_result_string' ] = lang( 'ud_d_list_search_single_result_string' );
	
	$new_params[] = $_tmp;
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'text',
		'inline' => TRUE,
		'name' => 'ud_data_no_search_result_str',
		'label' => 'ud_data_no_search_result_str',
		'tip' => 'tip_ud_data_no_search_result_str',
		'validation' => array(
			
			'rules' => 'trim',
			
		),
		
	);
	
	$params[ 'params_spec_values' ][ 'ud_data_no_search_result_str' ] = lang( 'ud_data_no_search_result_str_value' );
	
	$new_params[] = $_tmp;
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'text',
		'inline' => TRUE,
		'name' => 'ud_data_no_result_str',
		'label' => 'ud_data_no_result_str',
		'tip' => 'tip_ud_data_no_result_str',
		'validation' => array(
			
			'rules' => 'trim',
			
		),
		
	);
	
	$params[ 'params_spec_values' ][ 'ud_data_no_result_str' ] = lang( 'ud_data_no_result_str_value' );
	
	$new_params[] = $_tmp;
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'textarea',
		'inline' => TRUE,
		'name' => 'us_default_results_filters',
		'label' => 'us_default_results_filters',
		'default' => '',
		'tip' => 'tip_us_default_results_filters',
		'validation' => array(
			
			'rules' => 'trim',
			
		),
		
	);
	
	$new_params[] = $_tmp;
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'number',
		'inline' => TRUE,
		'name' => 'ud_data_list_max_columns',
		'label' => 'ud_data_list_max_columns',
		'tip' => 'tip_ud_data_list_max_columns',
		'validation' => array(
			
			'rules' => 'trim',
			
		),
		
	);
	
	$params[ 'params_spec_values' ][ 'ud_data_list_max_columns' ] = 3;
	
	$new_params[] = $_tmp;
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'select',
		'inline' => TRUE,
		'name' => 'users_submits_items_per_page',
		'label' => 'users_submits_items_per_page',
		'tip' => 'tip_users_submits_items_per_page',
		'validation' => array(
			
			'rules' => 'trim|required',
			
		),
		'options' => array(
			
			'global' => 'global',
			'1' => 1,
			'2' => 2,
			'3' => 3,
			'4' => 4,
			'5' => 5,
			'6' => 6,
			'8' => 8,
			'9' => 9,
			'10' => 10,
			'12' => 12,
			'15' => 15,
			'20' => 20,
			'25' => 25,
			'30' => 30,
			'35' => 35,
			'40' => 40,
			'45' => 45,
			'50' => 50,
			'100' => 100,
			
		),
		
	);
	
	$params[ 'params_spec_values' ][ 'users_submits_items_per_page' ] = '12';
	
	$new_params[] = $_tmp;
	
	//------------------------------------------------------
	
	$new_params[] = array(
		
		'type' => 'spacer',
		'label' => 'ud_d_list_order_by',
		'level' => '4',
		
	);
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'select',
		'inline' => TRUE,
		'name' => 'ud_d_list_show_order_by_controls',
		'label' => 'ud_d_list_show_order_by_controls',
		'validation' => array(
			
			'rules' => 'trim',
			
		),
		'options' => array(
			
			'1' => 'yes',
			'0' => 'no',
			
		),
		
	);
	
	$params[ 'params_spec_values' ][ 'ud_d_list_show_order_by_controls' ] = 0;
	
	$new_params[] = $_tmp;
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'select',
		'inline' => TRUE,
		'multiple' => TRUE,
		'name' => 'users_submits_order_by_field',
		'label' => 'users_submits_order_by_field',
		'tip' => 'tip_users_submits_order_by_field',
		'validation' => array(
			
			'rules' => 'trim',
			
		),
		'options' => array(
			
			'' => 'combobox_select',
			'random' => 'random',
			'id' => 'id',
			'submit_datetime' => 'submit_datetime',
			'mod_datetime' => 'mod_datetime',
			
		),
		
	);
	
	$_tmp[ 'options' ] = array_merge( $_tmp[ 'options' ], $fields_options );
	
	$params[ 'params_spec_values' ][ 'users_submits_order_by_field' ] = 'submit_datetime';
	
	$new_params[] = $_tmp;
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'select',
		'inline' => TRUE,
		'name' => 'users_submits_order_by_direction',
		'label' => 'users_submits_order_by_direction',
		'tip' => 'tip_users_submits_order_by_direction',
		'validation' => array(
			
			'rules' => 'trim|required',
			
		),
		'options' => array(
			
			'ASC' => 'ascendant',
			'DESC' => 'decrescent',
			
		),
		
	);
	
	$params[ 'params_spec_values' ][ 'users_submits_order_by_direction' ] = 'DESC';
	
	$new_params[] = $_tmp;
	
	//------------------------------------------------------
	
	$new_params[] = array(
		
		'type' => 'spacer',
		'label' => 'ud_d_list_order_by_visible_props',
		
	);
	
	//------------------------------------------------------
	
	$ud_d_list_order_by_options = array(
		
		'random' => 'random',
		'id' => 'id',
		'submit_datetime' => 'submit_datetime',
		'mod_datetime' => 'mod_datetime',
		
	);
	
	$ud_d_list_order_by_options = array_merge( $ud_d_list_order_by_options, $fields_options );
	
	foreach ( $ud_d_list_order_by_options as $alias => $label ) {
		
		$_tmp = array(
			
			'type' => 'checkbox',
			'inline' => TRUE,
			'name' => 'ud_d_list_order_by_visible_props[' . $alias . ']',
			'label' => $label,
			'value' => $alias,
			'validation' => array(
				
				'rules' => 'trim',
				
			),
			
		);
		
		if ( ! in_array( $field[ 'field_type' ], array( 'button', 'html', ) ) ) {
			
			if ( $new_flag ) {
				
				// $params[ 'params_spec_values' ][ 'ud_title_prop[' . $alias . ']' ] = $alias;
				
			}
			
		}
		
		if ( $field[ 'field_type' ] == 'combo_box' ) {
			
			$select_fields[] = $field;
			
		}
		
		$new_params[] = $_tmp;
		
	}
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'spacer',
		'label' => 'readmore',
		'name' => 'spacer_readmore',
		
	);
	
	$new_params[] = $_tmp;
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'checkbox',
		'inline' => TRUE,
		'name' => 'ud_data_list_d_readmore_link',
		'label' => 'ud_data_list_d_readmore_link',
		'tip' => 'tip_ud_data_list_d_readmore_link',
		'validation' => array(
			
			'rules' => 'trim',
			
		),
		'value' => 1,
		
	);
	
	$params[ 'params_spec_values' ][ 'ud_data_list_d_readmore_link' ] = 1;
	
	$new_params[] = $_tmp;
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'text',
		'inline' => TRUE,
		'name' => 'ud_data_list_d_readmore_link_custom_str',
		'label' => 'ud_data_list_d_readmore_link_custom_str',
		'tip' => 'tip_ud_data_list_d_readmore_link_custom_str',
		'validation' => array(
			
			'rules' => 'trim',
			
		),
		
	);
	
	$params[ 'params_spec_values' ][ 'ud_data_list_d_readmore_link_custom_str' ] = 'readmore';
	
	$new_params[] = $_tmp;
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'text',
		'inline' => TRUE,
		'name' => 'ud_data_list_d_readmore_link_url',
		'label' => 'ud_data_list_d_readmore_link_url',
		'tip' => 'tip_ud_data_list_d_readmore_link_url',
		'validation' => array(
			
			'rules' => 'trim',
			
		),
		
	);
	
	$params[ 'params_spec_values' ][ 'ud_data_list_d_readmore_link_url' ] = '';
	
	$new_params[] = $_tmp;
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'select',
		'inline' => TRUE,
		'name' => 'ud_data_list_d_readmore_link_target',
		'label' => 'ud_data_list_d_readmore_link_target',
		'validation' => array(
			
			'rules' => 'trim',
			
		),
		'options' => array(
			
			'' => 'combobox_select',
			'_self' => 'url_target_self',
			'_blank' => 'url_target_blank',
			
		),
		
	);
	
	$params[ 'params_spec_values' ][ 'ud_data_list_d_readmore_link_target' ] = '';
	
	$new_params[] = $_tmp;
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'select',
		'inline' => TRUE,
		'name' => 'ud_data_list_d_use_modal',
		'label' => 'ud_data_list_d_use_modal',
		'validation' => array(
			
			'rules' => 'trim',
			
		),
		'options' => array(
			
			'1' => 'yes',
			'0' => 'no',
			
		),
		
	);
	
	$params[ 'params_spec_values' ][ 'ud_data_list_d_use_modal' ] = 0;
	
	$new_params[] = $_tmp;
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'select',
		'name' => 'ud_d_list_page_content_title_image',
		'label' => 'ud_d_list_page_content_title_image',
		'tip' => 'tip_ud_d_list_page_content_title_image',
		'default' => '1',
		'validation' => array(
			
			'rules' => 'trim',
			
		),
		'options' => array(
			
			'0' => 'no',
			'1' => 'yes',
			
		),
		
	);
	
	$params[ 'params_spec_values' ][ 'ud_d_list_page_content_title_image' ] = '1';
	
	$params[ 'params_spec' ][ 'menu_item' ][ 'ud_d_list_page_content_title_image' ] = $_tmp;
	
	//------------------------------------------------------
	
// 	array_push_pos( $params[ 'params_spec' ][ 'ud_params_section_d_list_params' ], $new_params, 1  );
	
?>
