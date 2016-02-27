<?php
	
	$this->plugins->load( array( 'names' => array( 'image_cropper', 'fancybox', 'modal_rf_file_picker', 'yetii' ), 'types' => array( 'js_text_editor', ), ) );
	
?>


<?= form_open( get_url( 'admin' . $this->uri->ruri_string() ), array( 'id' => 'gallery-form', ) ); ?>

<div id="article-form-wrapper" class="form-wrapper tabs-wrapper">
	
	<div class="form-wrapper-sub tabs-children">
		
		<div class="form-actions to-toolbar">
			
			<?= vui_el_button( array( 'text' => lang( 'select' ), 'icon' => 'ok', 'class' => 'multi-selection-action-input', 'button_type' => 'button', 'type' => 'submit', 'value' => TRUE, 'name' => 'submit_ok', 'only_icon' => TRUE, 'form' => 'gallery-form', ) ); ?>
			
			<?= vui_el_button( array( 'text' => lang( 'action_cancel' ), 'icon' => 'cancel', 'button_type' => 'button', 'type' => 'submit', 'name' => 'submit_cancel', 'id' => 'submit-cancel', 'only_icon' => TRUE, 'form' => 'gallery-form', ) ); ?>
			
		</div>
		
		<header class="form-header tabs-header">
			
			<h1><?= lang( 'articles_gallery_wizard' ); ?></h1>
			
		</header>
		
		<div class="form-items tabs-items">
			
			<div class="form-item">
				
				<fieldset class="for-tab">
					
					<legend>
						
						<?= vui_el_button( array( 'text' => lang( 'basic_details' ), 'icon' => 'basic-details',  ) ); ?>
						
					</legend>
					
					<div id="title" class="vui-field-wrapper-inline">
						
						<?php
							
							$field_name = 'title';
							$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
							$field_attr = ( $field_error ? 'autofocus' : '' ) . ' id="gallery-' . $field_name . '" name="' . $field_name . '" class="' . $field_name . ' ' . ( $field_error ? 'field-error' : '' ) . '"' . ( $field_error ? element_title( $field_error ) : '' );
							
						?>
						
						<?= form_label( lang( $field_name ) ); ?>
						<?= form_input( $field_name, set_value( $field_name, $gallery[ $field_name ] ), $field_attr ); ?>
						
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
						<?= form_dropdown( $field_name, $field_options, set_value( $field_name, $gallery[ $field_name ] ), $field_attr ); ?>
						
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
							$field_attr = 'id="gallery-' . $field_name . '" name="' . $field_name . '" class="' . $field_name . ' ' . ( $field_error ? 'field-error' : '' ) . '"' . ( $field_error ? element_title( $field_error ) : '' );
							
						?>
						
						<?= form_label( lang( $field_name ) ); ?>
						<?= form_input( $field_name, set_value( $field_name, $gallery[ $field_name ] ), $field_attr ); ?>
						
						<?php
							
							unset( $field_error );
							unset( $field_attr );
							
						?>
						
					</div>
					
					<div class="divisor-h"></div>
					
					<div class="vui-field-wrapper">
						
						<?php
							
							$field_name = 'description';
							$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
							$field_attr = ( $field_error ? 'autofocus' : '' ) . ' id="category-' . $field_name . '" name="' . $field_name . '" class="js-editor ' . $field_name . ' ' . ( $field_error ? 'field-error' : '' ) . '" ';
							
						?>
						
						<?= form_label( lang( $field_name ) ); ?>
						<textarea <?= $field_attr; ?>><?= $gallery[ $field_name ]; ?></textarea>
						
						<?php
							
							unset( $field_error );
							unset( $field_attr );
							
						?>
						
					</div>
					
					<div id="category" class="vui-field-wrapper-inline">
						
						<?= vui_el_checkbox( array(
							
							'value' => TRUE,
							'text' => 'use_existing_category',
							'name' => 'use_existing_category',
							'form' => 'gallery-form',
							'class' => 'multi-selection-action',
							'checked' => $gallery[ 'use_existing_category' ],
							
						) ); ?>
						
						<?php
							$field_name = 'existing_category_id';
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
						
						<?= form_label( lang( 'existing_category_id' ) ); ?>
						<?= form_dropdown( $field_name, $field_options, set_value( $field_name, $gallery[ $field_name ] ), $field_attr ); ?>
						
						<?php
							
							unset( $field_error );
							unset( $field_attr );
							unset( $field_options );
							
						?>
						
					</div>
					
				</fieldset>
				
			</div>
			
			<div class="form-item">
				
				<fieldset class="for-tab">
					
					<legend>
						
						<?= vui_el_button( array( 'text' => lang( 'images' ), 'icon' => 'images',  ) ); ?>
						
					</legend>
					
					<div class="vui-field-wrapper-inline">
						
						<?php
							
							$field_name = 'dir';
							$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
							$field_attr = 'autofocus id="gallery-' . $field_name . '" name="' . $field_name . '" class="' . $field_name . ' ' . ( $field_error ? 'field-error' : '' ) . '"' . ( $field_error ? element_title( $field_error ) : '' );
							
						?>
						
						<?= form_label( lang( $field_name ) ); ?>
						<?= form_dropdown( $field_name, $dirs, set_value( $field_name, $gallery[ $field_name ] ), $field_attr ); ?>
						
						<?= vui_el_button( array(
							
							'text' => lang( 'action_apply_select_dir' ),
							'icon' => 'apply',
							'button_type' => 'button',
							'type' => 'submit',
							'name' => 'submit_select_dir',
							'value' => TRUE,
							'id' => 'submit-select-dir',
							'only_icon' => TRUE,
							'form' => 'gallery-form',
							
						) ); ?>
						
						<?php
							
							unset( $field_error );
							unset( $field_attr );
							
						?>
						
					</div>
					
					<div class="vui-field-wrapper-inline">
						
						<?php
						
						$field_name = 'allowed_ext[]';
						$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
						$label_attr = 'class="' . ( $field_error ? 'field-error' : '' ) . '"' . ( $field_error ? element_title( $field_error ) : '' );
						
						foreach( $extensions as $key => $extension ){
							
							$options = array(
								
								'wrapper-class' => 'checkbox-sub-item',
								'name' => $field_name,
								'id' => 'ext-' . $key,
								'value' => $key,
								'checked' => in_array( $key, $allowed_ext ) ? TRUE : FALSE,
								'text' => $extension,
								
							);
							
							echo vui_el_checkbox( $options );
							
						};
						
						unset( $field_error );
						unset( $field_attr );
						
						?>
						
					</div>
					
					<div class="divisor-h"></div>
					
					<table class="data-list responsive multi-selection-table">
						
						<tr>
							
							<th class="col-checkbox">
								
								<?= vui_el_checkbox( array(
									
									'title' => lang( 'select_all' ),
									'value' => 'select_all',
									'name' => 'select_all_images',
									'id' => 'select-all-items',
									'checked' => TRUE,
									
								) ); ?>
								
							</th>
							
							<?php $current_column = 'image'; ?>
							<th class="image-<?= $current_column; ?>">
								
								<?= lang( $current_column ); ?>
								
							</th>
							
							<?php $field_name = 'title'; ?>
							<th class="image-<?= $field_name; ?>">
								
								<?= lang( $field_name ); ?>
								
								<?php
									
									$field_name = 'images_title';
									$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
									$field_attr =
										
										( $field_error ? 'autofocus' : '' ) .
										' id="' . $field_name . '"' .
										' form="gallery-form' . '"' .
										' name="' . $field_name . '"' .
										' class="' . $field_name . ( $field_error ? ' field-error' : '' ) . '"' .
										( $field_error ? element_title( $field_error ) : '' );
									
								?>
								
								<?= form_input( $field_name, set_value( $field_name, '' ), $field_attr ); ?>
								
								<?php
									
									unset( $field_error );
									unset( $field_attr );
									
								?>
								
								<?= vui_el_button( array(
									
									'text' => lang( 'action_apply_images_title' ),
									'icon' => 'apply',
									'button_type' => 'button',
									'type' => 'submit',
									'name' => 'submit_images_title',
									'value' => TRUE,
									'id' => 'submit-images-title',
									'only_icon' => TRUE,
									'form' => 'gallery-form',
									
								) ); ?>
								
							</th>
							
						</tr>
						
						<?php if ( isset( $images ) AND is_array( $images ) AND ! empty( $images ) ){ ?>
						
						<?php foreach( $images as $key => $image ): ?>
						<tr>
							
							<td class="col-checkbox">
								
								<?= vui_el_checkbox( array(
									
									'value' => $image[ 'fullpath' ],
									'name' => 'selected_images[' . $image[ 'id' ] . ']',
									'form' => 'gallery-form',
									'class' => 'multi-selection-action',
									'checked' => key_exists( $image[ 'id' ], $selected_images ) ? TRUE : FALSE,
									
								) ); ?>
								
							</td>
							
							<?php $current_column = 'image'; ?>
							<td class="<?= $current_column; ?> col-<?= $current_column; ?>">
								
								<?php if ( $image[ 'url' ] ){ ?>
								
								<div class="thumb-image-wrapper">
									
									<?= anchor( $image[ 'url' ], img( array( 'src' => $image[ 'thumb_url' ], 'width' => 50 ) ),'rel="gallery-images" target="_blank" class="gallery-image-thumb" title="' . $image[ 'title' ] . '"' ); ?>
									
								</div>
								
								<?php } ?>
								
							</td>
							
							<?php $field_name = 'title'; ?>
							<td class="<?= $field_name; ?> col-<?= $field_name; ?>">
								
								<?php
									
									$field_name = 'title';
									$post_field_name = 'images[' . $image[ 'id' ] . '][' . $field_name . ']';
									$field_error = form_error( $field_name, '<div class="msg-inline-error">', '</div>' );
									$field_attr = ( $field_error ? 'autofocus' : '' ) . ' id="' . $field_name . '" form="' . 'gallery-form' . '" name="' . $post_field_name . '" class="' . $field_name . ' ' . ( $field_error ? 'field-error' : '' ) . '"' . ( $field_error ? element_title( $field_error ) : '' );
									
								?>
								
								<?= form_input( $post_field_name, $image[ 'title' ], $field_attr ); ?>
								
								<?php
									
									unset( $field_error );
									unset( $field_attr );
									
								?>
								
							</td>
							
						</tr>
						<?php endforeach; ?>
						
						<?php } else { ?>
						<tr class="no-results">
							
							<td colspan="3">
								
								<?= vui_el_button( array( 'text' => lang( 'msg_articles_gallery_error_no_images' ), 'icon' => 'error', ) ); ?>
								
							</td>
							
						</tr>
						<?php } ?>
						
					</table>
					
					<?php $this->plugins->load( 'jquery_checkboxes' ); ?>
					
				</fieldset>
				
			</div>
			
		</div>
		
	</div>
	
</div>

<?= form_close(); ?>


<script type="text/javascript">
	
	<?php if ( $this->plugins->performed( 'modal_rf_file_picker' ) ) { ?>
	window.updateImage = function(){
		
		var url = $( '#gallery-image' ).val(),
			thumb_image = $( '.gallery-image-thumb' );
			
		var image_src = url + '?' + Math.floor( ( Math.random() * 100 ) + 1 );
		var thumb_image_src = 'thumbs/' + image_src;
		
		<?php if ( $this->plugins->performed( 'fancybox' ) ) { ?>
		$.fancybox.close();
		<?php } ?>
		
	}
	window.onFileChooseFunction = function(){
		
		var url = $( '#gallery-image' ).val();
		
		if ( url != '' ){
			
			$.imageCrop.open({
				
				imgSrc: url,
				callback: updateImage
				
			});
			
		}
		
	}
	
	<?php } ?>
	
	$( document ).on( 'ready', function( e ){
		
		<?php if ( $this->plugins->performed( 'yetii' ) ){ ?>
		
		callbackFunctionMakeTabs = function(){
			
		}
		
		makeTabs( $( '.tabs-wrapper' ), '.form-item fieldset.for-tab, .params-set-wrapper', 'legend, .params-set-title', null, callbackFunctionMakeTabs );
		
		<?php } ?>
		
		<?php if ( $this->plugins->performed( 'fancybox' ) ){ ?>
		
		$( ".gallery-image-thumb" ).fancybox();
		
		<?php } ?>
		
		<?php if ( $this->plugins->performed( 'modal_rf_file_picker' ) ){ ?>
		
		$( '#gallery-image' ).after( '<?= vui_el_button( array( 'attr' => 'data-rffieldid="gallery-image" data-rfdir="' . str_replace( rtrim( MEDIA_PATH, DS ) , MEDIA_DIR_NAME, $gallery[ 'dir' ] ) . '" data-rftype="image"', 'url' => '#', 'text' => lang( 'select_image' ), 'get_url' => FALSE, 'id' => 'image-picker', 'icon' => 'more', 'only_icon' => TRUE, 'class' => 'modal-file-picker', ) ); ?>' );
		
		<?php } ?>
		
		$( '#submit-select-dir' ).hide();
		
		$( document ).on( 'change', '#gallery-dir', function( e ){
			
			$( '#submit-select-dir' ).click();
			
		});
		
	});
	
</script>
