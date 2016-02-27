
<section class="c-users users-global-config-form-wrapper form-wrapper tabs-wrapper">
	
	<div class="form-wrapper-sub tabs-children">
		
		<?php echo form_open( get_url( 'admin' . $this->uri->ruri_string() ), array( 'id' => 'articles-global-config-form' ) ); ?>
			
			<div class="form-actions to-toolbar to-main-toolbar">
				
				<?= vui_el_button( array( 'text' => lang( 'action_save' ), 'icon' => 'save', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit', 'id' => 'submit', 'only_icon' => TRUE, 'form' => 'articles-global-config-form', ) ); ?>
				
				<?= vui_el_button( array( 'text' => lang( 'action_apply' ), 'icon' => 'apply', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_apply', 'id' => 'submit-apply', 'only_icon' => TRUE, 'form' => 'articles-global-config-form', ) ); ?>
				
				<?= vui_el_button( array( 'text' => lang( 'action_cancel' ), 'icon' => 'cancel', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_cancel', 'id' => 'submit-cancel', 'only_icon' => TRUE, 'form' => 'articles-global-config-form', ) ); ?>
				
			</div>
			
			<header class="form-header tabs-header">
				
				<h1>
					
					<?= lang( 'users_component_config_edit_config' ); ?>
					
				</h1>
				
			</header>
			
			<div class="form-items tabs-items">
				
				<div class="form-item">
					
					<?php
					
					/*
						* gerando o html dos parâmetros, ele deve ser chamado na view, não no controller,
						* pois os erros de validação dos elementos dos parâmetros devem ser expostos
						* após a chamada da função $this->form_validation->run()
						*/
					
					echo params_to_html( $params_spec, $final_params_values );
					
					?>
					
				</div>
				
			</div>
			
		<?= form_close(); ?>
		
	</div>
	
</section>

<?php if ( $this->plugins->load( 'yetii' ) ){ ?>

<script type="text/javascript" >
	
$( document ).ready(function(){
	
	/*************************************************/
	/**************** Criando as tabs ****************/
	
	makeTabs( $( '.tabs-wrapper' ), '.params-set-wrapper', '.params-set-title' );
	
	/**************** Criando as tabs ****************/
	/*************************************************/
	
});

</script>

<?php } ?>
