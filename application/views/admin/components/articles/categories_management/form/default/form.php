<?php
	
	$this->plugins->load( array( 'names' => array( 'image_cropper', 'fancybox', 'modal_rf_file_picker', 'yetii' ), 'types' => array( 'js_text_editor', ) ) );
	
?>

<div id="category-form-wrapper" class="form-wrapper tabs-wrapper">
	
	<div class="form-wrapper-sub tabs-children">
		
		<?= form_open( get_url( 'admin' . $this->uri->ruri_string() ), array( 'id' => 'category-form', 'class' => ( ( $f_action == 'e' ) ? 'ajax' : '' ) ) ); ?>
			
			<div class="form-actions to-toolbar">
				
				<?= vui_el_button( array( 'text' => lang( 'action_save' ), 'icon' => 'save', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit', 'id' => 'submit', 'only_icon' => TRUE, 'form' => 'category-form', ) ); ?>
				
				<?= vui_el_button( array( 'text' => lang( 'action_apply' ), 'icon' => 'apply', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_apply', 'id' => 'submit-apply', 'only_icon' => TRUE, 'form' => 'category-form', ) ); ?>
				
				<?= vui_el_button( array( 'text' => lang( 'action_cancel' ), 'icon' => 'cancel', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_cancel', 'id' => 'submit-cancel', 'only_icon' => TRUE, 'form' => 'category-form', ) ); ?>
				
			</div>
			
			<header class="form-header tabs-header">
				
				<?php if ( $f_action == 'a' ) { ?>
				
				<h1><?= lang( 'new_category' ); ?></h1>
				
				<?php } else if ( $f_action == 'e' ) { ?>
				
				<h1><?= lang( 'edit_category' ); ?></h1>
				
				<?php } ?>
				
			</header>
			
			<div class="form-items tabs-items">
				
				<div class="form-item">
					
					<fieldset class="for-tab">
						
						<legend>
							
							<?= vui_el_button( array( 'text' => lang( 'basic_details' ), 'icon' => 'basic-details',  ) ); ?>
							
						</legend>
						
						<div class="category-info tabs-info">
							
							<div class="category-info-item category-info-title">
								
								<?= $category[ 'title' ]; ?>
								
							</div>
							
						</div>
						
						<div id="title" class="vui-field-wrapper-inline">
							
							<?php
								
								$field_name = 'title';
								$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
								$field_attr = 'autofocus id="' . $field_name . '" name="' . $field_name . '" class="' . $field_name . ' ' . ( $field_error ? 'field-error' : '' ) . '"' . ( $field_error ? element_title( $field_error ) : '' );
								
							?>
							
							<?= form_label( lang( $field_name ) ); ?>
							<?= form_input( $field_name, set_value( $field_name, $category[ $field_name ] ), $field_attr ); ?>
							
							<?php
								
								unset( $field_error );
								unset( $field_attr );
								
							?>
							
						</div>
						
						<div id="alias" class="vui-field-wrapper-inline">
							
							<?php
								
								$field_name = 'alias';
								$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
								$field_attr = ( $field_error ? 'autofocus' : '' ) . ' id="' . $field_name . '" name="' . $field_name . '" class="' . $field_name . ' ' . ( $field_error ? 'field-error' : '' ) . '"' . ( $field_error ? element_title( $field_error ) : '' );
								
							?>
							
							<?= form_label( lang( $field_name ) ); ?>
							<?= form_input( $field_name, set_value( $field_name, $category[ $field_name ] ), $field_attr ); ?>
							
							<?php
								
								unset( $field_error );
								unset( $field_attr );
								
							?>
							
						</div>
						
						<div id="category" class="vui-field-wrapper-inline">
							
							<?php
								$field_name = 'parent';
								$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
								$field_attr = ( $field_error ? 'autofocus' : '' ) . ' id="' . $field_name . '" name="' . $field_name . '" class="' . $field_name . ' ' . ( $field_error ? 'field-error' : '' ) . '"' . ( $field_error ? element_title( $field_error ) : '' );
								
								$field_options = array(
									
									0 => lang( 'root' ),
									
								);
								
								if ( $categories ){
									
									foreach( $categories as $cat_row ):
										
										$field_options[ $cat_row[ 'id' ] ] = $cat_row[ 'indented_title' ];
										
									endforeach;
									
								}
								
							?>
							
							<?= form_label( lang( 'parent_category' ) ); ?>
							<?= form_dropdown( $field_name, $field_options, set_value( $field_name, $category[ $field_name ] ), $field_attr ); ?>
							
							<?php
								
								unset( $field_error );
								unset( $field_attr );
								unset( $field_options );
								
							?>
							
						</div>
						
						<div id="status" class="vui-field-wrapper-inline">
							
							<?php
								
								$field_name = 'status';
								$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
								$field_attr = ( $field_error ? 'autofocus' : '' ) . ' id="' . $field_name . '" name="' . $field_name . '" class="' . $field_name . ' ' . ( $field_error ? 'field-error' : '' ) . '"' . ( $field_error ? element_title( $field_error ) : '' );
								
								$field_options = array(
									
									1 => lang( 'published' ),
									0 => lang( 'unpublished' ),
									
								);
								
							?>
							
							<?= form_label( lang( $field_name ) ); ?>
							<?= form_dropdown( $field_name, $field_options, set_value( $field_name, $category[ $field_name ] ), $field_attr ); ?>
							
							<?php
								
								unset( $field_error );
								unset( $field_attr );
								unset( $field_options );
								
							?>
							
						</div>
						
						<div id="image" class="vui-field-wrapper-inline">
							
							<?php
								
								$field_name = 'image';
								$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
								$field_attr = 'id="category-' . $field_name . '" name="' . $field_name . '" class="' . $field_name . ' ' . ( $field_error ? 'field-error' : '' ) . '"' . ( $field_error ? element_title( $field_error ) : '' );
								
							?>
							
							<?= form_label( lang( $field_name ) ); ?>
							<?= form_input( $field_name, set_value( $field_name, $category[ $field_name ] ), $field_attr ); ?>
							
							<?php
								
								unset( $field_error );
								unset( $field_attr );
								
							?>
							
						</div>
						
						<div id="description" class="vui-field-wrapper">
							
							<?php
								
								$field_name = 'description';
								$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
								$field_attr = ( $field_error ? 'autofocus' : '' ) . ' id="category-' . $field_name . '" name="' . $field_name . '" class="js-editor ' . $field_name . ' ' . ( $field_error ? 'field-error' : '' ) . '" ';
								
							?>
							
							<?= form_label( lang( $field_name ) ); ?>
							<textarea <?= $field_attr; ?>><?= ( $this->input->post( $field_name ) ? $this->input->post( $field_name, FALSE ) : ( check_var( $category[ $field_name ] ) ? $category[ $field_name ] : '' ) ); ?></textarea>
							
							<?php
								
								unset( $field_error );
								unset( $field_attr );
								
							?>
							
						</div>
						
						<?php if ( $f_action == 'e' ) { ?>
						
						<?= form_hidden( 'category_id', $category[ 'id' ] ); ?>
						
						<?php } ?>
						
					</fieldset>
					
				</div>
				
			</div>
			
			<div class="clear"></div>
			
		<?= form_close(); ?>
		
	</div>

</div>

<?php if ( $this->plugins->performed( 'yetii' ) ){ ?>

<script type="text/javascript" >
	
	$( document ).bind( 'keydown', function( e ){
		
		if ( pressed_key == '83 Ctrl' ){
			e.preventDefault();
			$( '#submit-apply' ).trigger( 'click' );
		}
		
	});
	
	$( document ).bind( 'ready', function(){
		
		/*************************************************/
		/**************** Criando as tabs ****************/
		
		callbackFunctionMakeTabs = function(){
			
			$( '.category-info-title' ).before( '<div class="category-info-item category-info-image"><?= ( isset( $category[ 'image' ] ) AND $category[ 'image' ] != '' ) ? '<a class="category-image-thumb-container" href="' . $category[ 'image' ] . '" target="_blank"><img class="category-image-thumb" src="' . $category[ 'image' ] . '" /></a>' : ''; ?></div>' );
			
		}
		
		makeTabs( $( '.tabs-wrapper' ), '.form-item fieldset.for-tab, .params-set-wrapper', 'legend, .params-set-title', null, callbackFunctionMakeTabs );
		
		/**************** Criando as tabs ****************/
		/*************************************************/
		
	});
	
</script>

<?php } ?>

<script type="text/javascript">
	
	var updateArticleTitle = function(){
		
		$( '.tab-item .category-info-title' ).html( '<span class="category-title-edited">' + window.categoryTitle + '</span>' );
		
	}
	
	$( '#category-title' ).bind( 'keyup', function() {
		
		window.categoryTitle = $( this ).val();
		updateArticleTitle();
		
	});
	
	<?php if ( $this->plugins->performed( 'modal_rf_file_picker' ) ) { ?>
	
	window.updateImage = function(){
		
		var url = $( '#category-image' ).val(),
			thumb_image = $( '.category-image-thumb' );
			
		var image_src = url + '?' + Math.floor( ( Math.random() * 100 ) + 1 );
		var thumb_image_src = 'thumbs/' + image_src;
		
		$( '.category-info-image' ).empty();
		
		if ( url != '' ){
			
			$( '.category-info-image' ).append( '<span class="category-image-thumb-container" href="' + url + '" target="_blank"><img class="category-image-thumb" src="' + image_src + '" /></span>' );
			
			$( '.category-image-thumb' ).attr( 'src', thumb_image_src );
			
		}
		
		$.fancybox.close();
		
	}
	window.onFileChooseFunction = function(){
		
		var url = $( '#category-image' ).val();
		
		if ( url != '' ){
			
			$.imageCrop.open({
				
				imgSrc: url,
				callback: updateImage
				
			});
			
		}
		
	}
	
	$( document ).bind( 'ready', function(){
		
		window.updateImage();
		
		$( '#category-image' ).after( '<?= vui_el_button( array( 'attr' => 'data-rffieldid="category-image" data-rftype="image"', 'url' => '#', 'text' => lang( 'select_image' ), 'get_url' => FALSE, 'id' => 'image-picker', 'icon' => 'more', 'only_icon' => TRUE, 'class' => 'modal-file-picker', ) ); ?>' );
		
		$('.category-image-thumb-container').fancybox();
		
	});
	
	<?php } ?>
	
</script>
