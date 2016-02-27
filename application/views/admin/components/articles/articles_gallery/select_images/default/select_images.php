
<div>
	
	<header class="component-head">
		
		<h1>
			
			<?= lang( 'Select images' ); ?>
			
		</h1>
		
	</header>
	
	<?php if ( isset( $images ) AND is_array( $images ) AND ! empty( $images ) ){ ?>
	
	<?= form_open( get_url( 'admin' . $this->uri->ruri_string() ), array( 'id' => 'gallery-form', ) ); ?><?= form_close(); ?>
	
	<div class="form-actions to-toolbar">
		
		<?= vui_el_button( array( 'text' => lang( 'select' ), 'icon' => 'ok', 'class' => 'multi-selection-action-input', 'button_type' => 'button', 'type' => 'submit', 'value' => 'submit_select', 'name' => 'submit_select', 'form' => 'gallery-form', ) ); ?>
		
		<?= vui_el_button( array( 'url' => $this->articles->get_ag_url( 'add' ), 'text' => lang( 'cancel' ), 'icon' => 'cancel', ) ); ?>
		
	</div>
	
	<table class="data-list responsive multi-selection-table">
		<tr>
			
			<th class="col-checkbox">
				
				<?= vui_el_checkbox( array( 'title' => lang( 'select_all' ), 'value' => 'select_all', 'name' => 'select_all_images', 'id' => 'select-all-items', ) ); ?>
				
			</th>
			
			<?php $current_column = 'image'; ?>
			<th class="image-<?= $current_column; ?>">
				
				<?= lang( $current_column ); ?>
				
			</th>
			
			<?php $current_column = 'title'; ?>
			<th class="image-<?= $current_column; ?>">
				
				<?= lang( $current_column ); ?>
				
			</th>
			
		</tr>
		
		<?php foreach( $images as $image ): ?>
		<tr>
			
			<td class="col-checkbox">
				
				<?= vui_el_checkbox( array( 'value' => $image[ 'fullpath' ], 'name' => 'selected_articles_ids[]', 'form' => 'gallery-form', 'class' => 'multi-selection-action', ) ); ?>
				
			</td>
			
			<?php $current_column = 'image'; ?>
			<td class="<?= $current_column; ?> col-<?= $current_column; ?>">
				
				<?php if ( $image[ 'url' ] ){ ?>
				
				<div class="thumb-image-wrapper">
					
					<?= anchor( $image[ 'url' ], img( array( 'src' => $image[ 'thumb_url' ], 'width' => 50 ) ),'rel="gallery-images" target="_blank" class="gallery-image-thumb" title="' . $image[ 'title' ] . '"' ); ?>
					
				</div>
				
				<?php } ?>
				
			</td>
			
			<?php $current_column = 'title'; ?>
			<td class="<?= $current_column; ?> col-<?= $current_column; ?>">
				
				<?= $image[ 'title' ]; ?>
				
			</td>
			
		</tr>
		<?php endforeach; ?>
	</table>
	
	<?php $this->plugins->load( 'jquery_checkboxes' ); ?>
	
	<?php if ( $this->plugins->load( 'fancybox' ) ) { ?>
	<script type="text/javascript" >
		
		$( document ).on( 'ready', function( e ){
			
			<?php if ( $this->plugins->load( 'fancybox' ) ){ ?>
			
			$( ".gallery-image-thumb" ).fancybox();
			
			//$.fancybox.showLoading()
			
			<?php } ?>
			
		});
		
	</script>
	<?php } ?>
	
	<?php } else { ?>
		
		<?= vui_el_button( array( 'text' => lang( 'msg_articles_gallery_error_no_images' ), 'icon' => 'error', ) ); ?>
		
		<?= vui_el_button( array( 'url' => $this->articles->get_ag_url( 'add' ), 'text' => lang( 'back' ), 'icon' => 'back', ) ); ?>
		
	<?php } ?>
	
</div>
