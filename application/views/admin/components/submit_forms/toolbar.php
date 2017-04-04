<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

$full_page_title_alias = $component_name . '_' . $this->component_function . '_' . $this->component_function_action;
echo vui_el_button( array( 'url' => $c_urls[ 'sf_list_link' ], 'text' => lang( $component_name ) . ' - ' . lang( $full_page_title_alias ), 'icon' => $component_name, 'class' => 'bread-crumb component-name', ) );

if ( $component_function_action != 'asf' ) {
	
	echo vui_el_button( array( 'url' => $c_urls[ 'sf_add_link' ], 'text' => lang( 'add_submit_form' ), 'icon' => 'add-submit-form', 'only_icon' => TRUE, ) );
	
}

if ( $component_function_action != 'sfl' ) {
	
	$menu_items = array(
		
		array(
			
			'id' => 'parent',
			'title' => isset( $submit_form[ 'title' ] ) ? $submit_form[ 'title' ] : lang( 'add_submit_form' ),
			'link' => $component_function_action == 'asf' ? $c_urls[ 'sf_add_link' ] : $submit_form[ 'edit_link' ],
			'icon' => $component_function_action == 'asf' ? 'add-submit-form' : 'submit-forms',
			
		),
		
	);
	
	$_menu_items_sf = array();

	if ( ! isset( $submit_forms ) ) {
		
		$submit_forms = $this->sfcm->get_submit_forms()->result_array();
		
		if ( $submit_forms ) {
			
			reset( $submit_forms );
			
			while ( list( $key, $_sf ) = each( $submit_forms ) ) {
				
				if ( ( isset( $submit_form[ 'id' ] ) AND $submit_form[ 'id' ] != $_sf[ 'id' ] ) OR ! isset( $submit_form[ 'id' ] ) ) {
					
					$this->sfcm->parse_sf( $_sf );
					
					$_menu_items_sf[ $key ] = array();
					
					$_menu_items_sf[ $key ][ 'id' ] = $_sf[ 'id' ];
					$_menu_items_sf[ $key ][ 'title' ] = $_sf[ 'title' ];
					$_menu_items_sf[ $key ][ 'icon' ] = 'submit-forms';
					$_menu_items_sf[ $key ][ 'parent_id' ] = 'parent';
					$_menu_items_sf[ $key ][ 'link' ] = $_sf[ 'edit_link' ];
					
					$menu_items[] = array(
						
						'id' => 'sf_us_' . $_sf[ 'id' ],
						'title' => lang( 'users_submits' ),
						'link' => $c_urls[ 'us_list_link' ] . '/' . $this->uri->assoc_to_uri( array( 'sfid' => $_sf[ 'id' ], ) ),
						'icon' => 'users-submits',
						'parent_id' => $_sf[ 'id' ],
						
					);
					
					//echo '<pre style="color:black">' . print_r( $_sf, TRUE ) . '</pre>';
					
					if ( isset( $_sf[ 'site_link' ] ) ) {
						
						$menu_items[] = array(
							
							'id' => 'sf_site_link_' . $_sf[ 'id' ],
							'title' => lang( 'view_submit_form_on_site' ),
							'link' => $_sf[ 'site_link' ],
							'icon' => 'view',
							'parent_id' => $_sf[ 'id' ],
							'target' => '_blank',
							
						);
						
					}
					
					if ( isset( $_sf[ 'data_list_site_link' ] ) ) {
						
						$menu_items[] = array(
							
							'id' => 'sf_users_submits_site_link_' . $_sf[ 'id' ],
							'title' => lang( 'view_users_submits_on_site' ),
							'link' => $_sf[ 'data_list_site_link' ],
							'icon' => 'view',
							'parent_id' => $_sf[ 'id' ],
							'target' => '_blank',
							
						);
						
					}
					
				}
				
			}

		}

	}

	$menu_items = array_merge_recursive( $menu_items, $_menu_items_sf );

	$menu_items = array_menu_to_array_tree( $menu_items );

	echo ul_menu( $menu_items );

}

if ( isset( $submit_form[ 'id' ] ) ) {
	
	if ( isset( $submit_form[ 'site_link' ] ) ) {
		
		echo vui_el_button(
			
			array(
				
				'id' => 'sf_site_link_' . $submit_form[ 'id' ],
				'title' => lang( 'view_submit_form_on_site' ),
				'url' => get_url( $submit_form[ 'site_link' ] ),
				'icon' => 'view',
				'target' => '_blank',
				'button_type' => 'anchor',
				'only_icon' => TRUE,
				
			)
			
		);
		
	}
	
	echo vui_el_button(
		
		array(
			
			'url' => $submit_form[ 'users_submits_link' ],
			'text' => lang( 'users_submits' ),
			'icon' => 'users_submits',
			'only_icon' => TRUE,
			
		)
		
	);
	
	echo vui_el_button( array( 'url' => $submit_form[ 'users_submits_add_link' ], 'text' => lang( 'add_user_submit' ), 'icon' => 'add', 'only_icon' => TRUE, ) );
	
}


?>
