<?php
	
	foreach ( $data_scheme[ 'fields' ] as $key => $field ) {
		
		$alias = check_var( $field[ 'alias' ] ) ? $field[ 'alias' ] : FALSE;
		
		if ( $alias ) {
			
			$props_combobox_options[ $alias ] = $field[ 'label' ];
			
		}
		
	}
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'select',
		'name' => 'ud_d_detail_show_page_content_title',
		'label' => 'ud_d_detail_show_page_content_title',
		'tip' => 'tip_ud_d_detail_show_page_content_title',
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
	
	$params[ 'params_spec_values' ][ 'ud_d_detail_show_page_content_title' ] = '1';
	
	$new_params[] = $_tmp;
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'select',
		'name' => 'ud_d_detail_page_content_title_from_product_name',
		'label' => 'ud_d_detail_page_content_title_from_product_name',
		'tip' => 'tip_ud_d_detail_page_content_title_from_product_name',
		'default' => '1',
		'validation' => array(
			
			'rules' => 'trim|required',
			
		),
		'options' => array(
			
			'0' => 'no',
			'1' => 'yes',
			
		),
		
	);
	
	$params[ 'params_spec_values' ][ 'ud_d_detail_page_content_title_from_product_name' ] = '1';
	
	$new_params[] = $_tmp;
	
	//------------------------------------------------------
	
	$new_params[] = array(
		
		'type' => 'spacer',
		'label' => 'ud_d_detail_site_presentation_props',
		'tip' => 'tip_ud_title_prop',
		
	);
	
	//------------------------------------------------------
	
	$new_params[] = array(
		
		'type' => 'spacer',
		'label' => 'ud_title_prop',
		'tip' => 'tip_ud_title_prop',
		
	);
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'class' => 'ud-title-sortable',
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
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'class' => 'ud-title-sortable',
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
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'class' => 'ud-title-sortable',
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
	
	//------------------------------------------------------
	
	foreach ( $props_combobox_options as $alias => $label ) {
		
		$_tmp = array(
			
			'class' => 'ud-title-sortable',
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
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	//------------------------------------------------------
	
	$new_params[] = array(
		
		'type' => 'spacer',
		'label' => 'ud_content_prop',
		'tip' => 'tip_ud_content_prop',
		
	);
	
	//------------------------------------------------------
	
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
	
	//------------------------------------------------------
	
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
	
	//------------------------------------------------------
	
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
	
	//------------------------------------------------------
	
	foreach ( $data_scheme[ 'fields' ] as $key => $field ) {
		
		$alias = check_var( $field[ 'alias' ] ) ? $field[ 'alias' ] : FALSE;
		
		if ( $alias ) {
			
			$props_combobox_options[ $alias ] = $field[ 'label' ];
			
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
	
	
	
	
	
	
	
	
	
	
	
	
	//------------------------------------------------------
	
	$new_params[] = array(
		
		'type' => 'spacer',
		'label' => 'ud_other_info_prop',
		'tip' => 'tip_ud_other_info_prop',
		
	);

	//------------------------------------------------------
	
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
	
	//------------------------------------------------------
	
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
	
	//------------------------------------------------------
	
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
	
	//------------------------------------------------------
	
	foreach ( $data_scheme[ 'fields' ] as $key => $prop ) {
		
		$alias = check_var( $prop[ 'alias' ] ) ? $prop[ 'alias' ] : FALSE;
		
		if ( $alias AND ! in_array( $prop[ 'field_type' ], array( 'button', 'html', ) ) ) {
			
			$props_options[ $alias ] = $prop[ 'label' ];
			
			$_tmp = array(
				
				'type' => 'checkbox',
				'inline' => TRUE,
				'name' => 'ud_other_info_prop[' . $alias . ']',
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
	
		
		
		
		
		
		
		
	
	//------------------------------------------------------
	
	$new_params[] = array(
		
		'type' => 'spacer',
		'label' => 'ud_image_prop',
		'tip' => 'tip_ud_image_prop',
		
	);
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'select',
		'name' => 'ud_d_detail_main_image_on_title',
		'label' => 'ud_d_detail_main_image_on_title',
		'tip' => 'tip_ud_d_detail_main_image_on_title',
		'default' => '1',
		'validation' => array(
			
			'rules' => 'trim|required',
			
		),
		'options' => array(
			
			'0' => 'no',
			'1' => 'yes',
			
		),
		
	);
	
	$params[ 'params_spec_values' ][ 'ud_d_detail_main_image_on_title' ] = TRUE;
	
	$new_params[] = $_tmp;
	
	//------------------------------------------------------
	
	if ( check_var( $data_scheme[ 'params' ][ 'ud_d_detail_site_override_presentation_props' ] ) ) {
		
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
	
?>
