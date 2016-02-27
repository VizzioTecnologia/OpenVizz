<?php if ( ! defined( 'BASEPATH' ) ) exit('No direct script access allowed' );
	
	$_url = get_url( $this->uri->ruri_string() );
	
?>

<section class="c-users login-wrapper logout-wrapper <?= @$params['page_class']; ?>">
	
	<?php if ( check_var( $params[ 'show_page_content_title' ] ) ) { ?>
	
	<header class="component-heading">
		
		<h1>
			
			<?php if ( check_var( $html_data['content']['title'] ) ) { ?>
				
				<?= $html_data['content']['title']; ?>
				
			<?php } ?>
			
		</h1>
		
		<p>
			
			<?= lang( 'you_are_already_logged' ); ?>
			
		</p>
		
	</header>
	
	<?php } ?>
	
	<?php echo form_open( $_url ); ?>
		
		<div class="button-logout-wrapper vui-field-wrapper">
			
			<?php 
			
				echo vui_el_button(
					
					array(
						
						'text' => lang( 'action_logout' ),
						'icon' => 'logout',
						'button_type' => 'button',
						'name' => 'submit_logout',
						'id' => 'submit-logout',
						
					)
					
				);
				
			?>
			
		</div>
		
	<?= form_close(); ?>
	
</section>
