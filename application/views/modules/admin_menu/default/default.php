<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

//echo print_r( $module_data, true ) . '<br/><br/><br/>-----------<br/><br/><br/>';

$_path = VIEWS_PATH . SITE_COMPONENTS_VIEWS_PATH . 'submit_forms' . DS . 'index' . DS . 'users_submits' . DS . 'default' . DS;

$filter_fields_input_name = 'users_submits_search[dinamic_filter_fields]';

$params = $module_data[ 'params' ];

$unique_hash = md5( uniqid( rand(), true ) );

echo vui_el_button( array( 'wrapper_class' => 'admin-menu-switch', 'url' => 'admin', 'get_url' => FALSE, 'text' => lang( 'admin_menu' ), 'icon' => 'start', 'only_icon' => TRUE, ) );

?>

<section id="admin-menu-module-<?= $unique_hash; ?>" class="module-wrapper admin-menu-module admin-menu-module-wrapper direction-<?= $params[ 'admin_menu_direction' ]; ?> <?= $params[ 'module_class' ]; ?>">
	
	<?php if ( check_var( $params[ 'show_title' ] ) ) { ?>
	<header class="module-title">
		
		<h1>
			
			<?= $module_data[ 'title' ]; ?>
			
		</h1>
		
	</header><?php
	
	}
	
	?><div class="module-content admin-menu-module-content layout-<?= url_title( $params[ 'admin_menu_layout' ] ); ?>">
		
		<ul class="menu shortcuts total-6 total-even"><?php
			
			?><li><?php
				
				// echo vui_el_button( array( 'url' => 'admin/responsive_file_manager/index/a/rfm', 'text' => lang( 'file_manager' ), 'icon' => 'browse', 'only_icon' => TRUE, ) );
				
				echo vui_el_button( array( 'attr' => 'data-rftype="all"', 'url' => '#', 'text' => lang( 'select_image' ), 'get_url' => FALSE, 'icon' => 'browse', 'only_icon' => TRUE, 'class' => 'modal-file-picker', ) );
				
			?></li><?php
			
			?><li><?php
				
				echo vui_el_button( array( 'url' => BASE_URL, 'get_url' => FALSE, 'check_current_url' => FALSE, 'target' => '_blank', 'text' => lang( 'go_to_site' ), 'icon' => 'web', 'only_icon' => TRUE, ) );
				
			?></li><?php
			
			?><li><?php
				
				echo vui_el_button( array( 'url' => 'admin/main/config_management/global_config', 'text' => lang( 'global_config' ), 'icon' => 'config', 'only_icon' => TRUE, ) );
				
			?></li><?php
			
			?><li><?php
				
				echo vui_el_button( array( 'url' => 'admin/main/switch_profiler', 'text' => lang( 'switch_profiler' ), 'icon' => 'profiler', 'only_icon' => TRUE, 'class' => ( check_var( $CI->session->userdata[ 'user_data' ][ $CI->mcm->environment ][ 'profiler' ] ) ? 'active profiler-on' : 'profiler-off' ), ) );
				
			?></li><?php
			
			?><li><?php
				
				echo vui_el_button( array( 'url' => 'admin/users/users_management/edit_user/'.base64_encode(base64_encode(base64_encode(base64_encode($CI->users->user_data['id'])))), 'text' => lang('logged_as').' '.$CI->users->user_data['name'], 'icon' => 'user', 'only_icon' => TRUE, ) );
				
			?></li><?php
			
			?><li><?php
				
				echo vui_el_button( array( 'url' => 'admin/main/index/logout', 'text' => lang( 'action_logout' ), 'icon' => 'logout', 'only_icon' => TRUE, ) );
				
			?></li><?php
			
		?></ul><?php
		
		
		?><ul class="menu">
			
			<li class="logo">
				
				<?= vui_el_button( array( 'url' => 'admin/main/index/dashboard', 'text' => lang( 'Via CMS' ), 'icon' => 'viacms', 'class' => 'logo' ) ); ?>
				
			</li><?php
			
			?><li><?php
				
				echo vui_el_button( array( 'url' => 'admin/main/components_management/components_list', 'text' => lang( 'components' ), 'icon' => 'components', ) );
				
				?><ul class="components"><?php
					
					$_components = array();
					
					foreach ( $CI->mcm->components as $key => $component ) {
						
						if ( $component[ 'status' ] == 1 AND $component[ 'unique_name' ] != 'main' ) {
							
							$_components[ lang( $component[ 'title' ] ) ] = $component;
							
						}
						
					}
					
					ksort( $_components );
					
					foreach ( $_components as $key => $component ) {
						
						if ( $component[ 'status' ] == 1 ) {
							
							echo '<li>';
							echo vui_el_button( array( 'url' => $component[ 'admin_url' ], 'text' => lang( $component[ 'title' ] ), 'icon' => $component[ 'unique_name' ], ) );
							
							if ( $component[ 'unique_name' ] == 'menus' ) {
								
								$_menu_types = array();
								
								$CI->load->model( 'menus_mdl' );
								$menu_types = $CI->menus_mdl->get_menu_types();
								
								if ( $menu_types ) {
									
									foreach ( $menu_types as $key => $menu_type ) {
										
										$_menu_types[ lang( $menu_type[ 'title' ] ) ] = $menu_type;
										
									}
									
									ksort( $_menu_types );
									
									echo '<ul>';
									
									foreach ( $_menu_types as $key => $menu_type ) {
										
										echo '<li>';
										echo vui_el_button( array( 'url' => $CI->menus_mdl->get_mi_url( 'list', array( 'menu_type_id' => $menu_type[ 'id' ], ) ), 'text' => lang( $menu_type[ 'title' ] ), 'icon' => 'menus', ) );
										echo '</li>';
										
									}
									
									echo '</ul>';
									
								}
								
							}
							
							echo '</li>';
							
						}
						
					}
					
				?></ul>
				
			</li>
			
		</ul>
		
	</div>

</section>

<?php if ( $CI->plugins->load( 'fancybox' ) ){ ?>

<script type="text/javascript" >
	
	$( document ).on( 'ready', function( e ){
		
		var fbContent;
		
		$( "#users-submits-module-<?= $unique_hash; ?> .modal" ).fancybox({
			
			wrapCSS: 'testimonials',
			maxWidth: 1280,
			content: fbContent,
			beforeShow: function(){
				
				fbContent = $( this ).html();
				$(".fancybox-overlay").addClass("user-submit-detail users-submits-layout-testimonials testimonials");
				
			},
			afterClose: function(){
				fbContent = '';
			},
			onComplete: function(){
			}
			
		});
		
	});
	
</script>

<?php } ?>
