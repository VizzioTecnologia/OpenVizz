<?php
	
	$this->plugins->load( 'jquery_ui' );
	
	$head_script_declaration = "
		
		$( document ).on( 'ready', function(){
			
			// --------------------------------
			// Drag and drop sortable
			
			$( '.ud-title-sortable:first' ).parent().addClass( 'ud-title-sortable-wrapper' );
			
			$( '.ud-title-sortable-wrapper' ).sortable({
				
				//containment: 'parent', // descomente para limitar o movimento ao container pai
				items: '.ud-title-sortable',
				placeholder: 'field-wrapper sortable-item sortable-placeholder',
				start: function(event, ui) {
					
					$( this ).css( 'height', $( this ).outerHeight() );
	
					ui.item.addClass( 'sorting' );
					
				},
				stop: function(event, ui) {
					
					ui.item.removeClass( 'sorting' );
					
					ui.item.css( 'position', '' );
					
					// console.log( 'New position: ' + ( ui.item.index() + 1 ) )
					
				},
				
			});
			
			$( document ).on( 'sortout', '.sortable', function( event, ui ){
				
				$( this ).css( 'height', '' );
				
			});
			
		});
		
	";
	
	$this->voutput->append_head_script_declaration( 'ud_sortable_props', $head_script_declaration );
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'select',
		'name' => 'ud_d_detail_page_content_title_from_metadata',
		'label' => 'ud_d_detail_page_content_title_from_metadata',
		'tip' => 'tip_ud_d_detail_page_content_title_from_metadata',
		'default' => '1',
		'validation' => array(
			
			'rules' => 'trim|required',
			
		),
		'options' => array(
			
			'0' => 'no',
			'1' => 'yes',
			
		),
		
	);
	
	$params[ 'params_spec_values' ][ 'ud_d_detail_page_content_title_from_metadata' ] = '1';
	
	$params[ 'params_spec' ][ 'menu_item' ][ 'ud_d_detail_page_content_title_from_metadata' ] = $_tmp;
	
	//------------------------------------------------------
	
	$new_params[] = array(
		
		'type' => 'spacer',
		'label' => 'ud_d_detail_site_presentation_props',
		'tip' => 'tip_ud_title_prop',
		
	);
	
	//------------------------------------------------------
	
	$_tmp = array(
		
		'type' => 'select',
		'name' => 'ud_d_detail_site_override_presentation_props',
		'label' => 'ud_d_detail_site_override_presentation_props',
		'tip' => 'tip_ud_d_detail_site_override_presentation_props',
		'default' => '0',
		'validation' => array(
			
			'rules' => 'trim|required',
			
		),
		'options' => array(
			
			'0' => 'no',
			'1' => 'yes',
			
		),
		
	);
	
	$params[ 'params_spec_values' ][ 'ud_d_detail_site_override_presentation_props' ] = 0;
	
	$new_params[] = $_tmp;
	
	//------------------------------------------------------
	
	if ( check_var( $menu_item[ 'params' ][ 'ud_d_detail_site_override_presentation_props' ] ) ) {
		
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
		
		foreach ( $fields_options as $alias => $label ) {
			
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
		'name' => 'ud_d_main_image_on_title',
		'label' => 'ud_d_main_image_on_title',
		'tip' => 'tip_ud_d_main_image_on_title',
		'default' => '1',
		'validation' => array(
			
			'rules' => 'trim|required',
			
		),
		'options' => array(
			
			'0' => 'no',
			'1' => 'yes',
			
		),
		
	);
	
	$params[ 'params_spec_values' ][ 'ud_d_main_image_on_title' ] = TRUE;
	
	$new_params[] = $_tmp;
	
	//------------------------------------------------------
	
	if ( check_var( $menu_item[ 'params' ][ 'ud_d_detail_site_override_presentation_props' ] ) ) {
		
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
