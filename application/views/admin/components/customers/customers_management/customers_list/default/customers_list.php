
<div>
	
	<header class="component-head">
		
		<h1>
			
			<?= lang( 'customers' ); ?>
			
		</h1>
		
	</header>
	
	<?php if ( $pagination ){ ?>
	<div class="pagination">
		<?php echo $pagination; ?>
	</div>
	<?php } ?>
	
	<table>
		<tr>
			<th class="company-thumb">
				<?php echo lang('logo'); ?>
			</th>
			<th>
				<?php echo lang('title'); ?>
			</th>
			<th class="op-column">
				<?php echo lang('operations'); ?>
			</th>
		</tr>
		
		<?php foreach($customers as $customer): ?>
		<tr>
			<td class="company-thumb ta-center">
				
				<?php if ( $customer->contact_id AND $customer->contact_photo_thumb ){ ?>
					
					<div class="company-thumb-wrapper">
						
						<?php if ( $customer->contact_photo ){ ?>
						
						<?php echo anchor( $customer->contact_photo, img( array( 'src' => $customer->contact_photo_thumb, 'height' => 50 ) ),'target="_blank" class="company-logo-thumb" title="'.lang('action_view').'"'); ?>
						
						<?php } else { ?>
						
						<?php echo img( array( 'src' => $customer->contact_photo_thumb, 'height' => 50 ) ); ?>
						
						<?php } ?>
						
					</div>
					
				<?php } else if ( $customer->logo_thumb ){ ?>
					
					<div class="company-thumb-wrapper">
						
						<?php if ( $customer->logo ){ ?>
						
						<?php echo anchor( $customer->logo, img( array( 'src' => $customer->logo_thumb, 'height' => 50 ) ),'target="_blank" class="company-logo-thumb" title="'.lang('action_view').'"'); ?>
						
						<?php } else { ?>
						
						<?php echo img( array( 'src' => $customer->logo_thumb, 'height' => 50 ) ); ?>
						
						<?php } ?>
						
					</div>
					
				<?php } ?>
				
			</td>
			
			<td class="company-trading-name">
				<?php echo anchor('admin/'.$component_name.'/' . $component_function . '/edit_customer/'.$customer->id,$customer->title,'class="" title="'.lang('action_view').'"'); ?>
			</td>
			<td class="operations">
				
				<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/' . $component_function . '/edit_customer/' . $customer->id, 'text' => lang( 'action_edit' ), 'icon' => 'edit', 'only_icon' => TRUE, ) ); ?>
				
				<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/' . $component_function . '/remove_customer/' . $customer->id, 'text' => lang( 'action_delete' ), 'icon' => 'remove', 'only_icon' => TRUE, ) ); ?>
				
			</td>
			
		</tr>
		<?php endforeach; ?>
	</table>
	
	<?php if ( $pagination ){ ?>
	<div class="pagination">
		<?php echo $pagination; ?>
	</div>
	<?php } ?>
	
</div>


<?php if ( $this->plugins->load( 'fancybox' ) ){ ?>

<script type="text/javascript" >
	
$( document ).ready( function(){
	
	$( ".company-logo-thumb" ).fancybox();
	
} );

</script>

<?php } ?>
