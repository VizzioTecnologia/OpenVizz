<?php

	$this->plugins->load( NULL, 'js_text_editor' );
	$this->plugins->load( NULL, 'js_time_picker' );
	$this->plugins->load( 'image_cropper' );
	$this->plugins->load( 'fancybox' );
	$this->plugins->load( 'modal_rf_file_picker' );
	
	$fields = array();
	
	foreach ( $submit_form[ 'fields' ] as $key => $field ) {
		
		$fields[ $field[ 'alias' ] ] = $field;
		
	}
	
?>


<div id="global-config-form-wrapper" class="form-wrapper tabs-wrapper">
	
	<div class="form-actions to-toolbar to-main-toolbar">
		
		<?= vui_el_button( array( 'text' => lang( 'action_save' ), 'icon' => 'save', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit', 'id' => 'submit', 'only_icon' => TRUE, 'form' => 'submit-form-form', ) ); ?>
		
		<?= vui_el_button( array( 'text' => lang( 'action_apply' ), 'icon' => 'apply', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_apply', 'id' => 'submit-apply', 'only_icon' => TRUE, 'form' => 'submit-form-form', ) ); ?>
		
		<?= vui_el_button( array( 'text' => lang( 'action_cancel' ), 'icon' => 'cancel', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_cancel', 'id' => 'submit-cancel', 'only_icon' => TRUE, 'form' => 'submit-form-form', ) ); ?>
		
	</div>
	
	<header class="form-header tabs-header">
		
		<h1>
			
			<?php if ( $component_function_action == 'asf' ) { ?>
			
			<?= lang( 'new_submit_form' ); ?>
			
			<?php } else if ( $component_function_action == 'esf' ) { ?>
			
			<?= lang( 'edit_submit_form' ); ?>
			
			<?php } ?>
			
		</h1>
		
	</header>
	
	<div class="form-wrapper-sub tabs-children">
		
		<div class="form-items tabs-items">
			
			<div class="form-item">
				
				<fieldset id="submit-form-user_submit">
					
					<legend>
						
						<?= vui_el_button( array( 'text' => lang( 'user_submit' ), 'icon' => 'user_submit',  ) ); ?>
						
					</legend>
					
					<?php
						
						require( 'form.php' );
						
					?>
					
				</fieldset>
				
			</div>
			
		</div>
		
	</div>
	
</div>

<script type="text/javascript" >

$( document ).ready( function(){

	<?php if ( $this->plugins->load( 'yetii' ) ){ ?>

	/*************************************************/
	/**************** Criando as tabs ****************/

	makeTabs( $( '.tabs-wrapper' ), '.tabs-wrapper fieldset', 'legend' );

	/**************** Criando as tabs ****************/
	/*************************************************/

	<?php } ?>

});

</script>
