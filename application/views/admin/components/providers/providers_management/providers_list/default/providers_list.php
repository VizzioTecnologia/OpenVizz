
<div>
	
	<header class="component-head">
		
		<h1>
			
			<?= lang('providers'); ?>
			
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
			<th>
				<?php echo lang('company'); ?>
			</th>
			<th class="op-column">
				<?php echo lang('operations'); ?>
			</th>
		</tr>
		
		<?php foreach($providers as $provider): ?>
		<tr>
			<td class="company-thumb ta-center">
				<?php if ( $provider['logo_thumb'] ){ ?>
				
				<div class="company-thumb-wrapper">
				
				<?php if ( $provider['logo'] ){ ?>
				
				<?php echo anchor( $provider['logo'], img( array( 'src' => $provider['logo_thumb'], 'height' => 50 ) ),'target="_blank" class="company-logo-thumb" title="'.lang('action_view').'"'); ?>
				
				<?php } else { ?>
				
				<?php echo img( array( 'src' => $provider['logo_thumb'], 'height' => 50 ) ); ?>
				
				<?php } ?>
				
				</div>
				
				<?php } ?>
				
			</td>
			
			<td class="provider-title">
				<?php echo anchor('admin/'.$component_name.'/' . $component_function . '/edit_provider/'.$provider['id'],$provider['title'],'class="" title="'.lang('action_view').'"'); ?>
			</td>
			
			<td class="company-info">
				<?php if ( isset($provider['company_id']) AND $provider['company_id'] ){ ?>
				
				<a href="<?= get_url('admin/companies/companies_management/edit_company/'.$provider['company_id']); ?>" class="list-info-wrapper" data-companyid="<?= $provider['company_id']; ?>" target="_blank" >
					
					<span class="list-info-thumb-wrapper">
						<?php if ( $provider['logo_thumb'] ){ ?>
						
						<?php echo img( array( 'src' => $provider['logo_thumb'], 'width' => 24 ) ); ?>
						
						<?php } ?>
					</span>
					
					<?= $provider['trading_name']; ?>
					
				</a>
				
				<?php } ?>
			</td>
			
			<td class="operations">
				
				<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/' . $component_function . '/edit_provider/' . $provider['id'], 'text' => lang( 'action_edit' ), 'icon' => 'edit', 'only_icon' => TRUE, ) ); ?>
				
				<?= vui_el_button( array( 'url' => 'admin/' . $component_name . '/' . $component_function . '/remove_provider/' . $provider['id'], 'text' => lang( 'action_delete' ), 'icon' => 'remove', 'only_icon' => TRUE, ) ); ?>
				
			</td>
			
		</tr>
		<?php endforeach; ?>
	</table>
	
	<?php if ( $pagination ){ ?>
	<div class="pagination">
		<?php echo $pagination; ?>
	</div>
	<?php } ?>
	
	<script type="text/javascript" >
		
		$(document).ready(function(){
			
			findCompaniesElements();
			
		});
		
	</script>
	
</div>


<?php if ( $this->plugins->load( 'fancybox' ) ){ ?>

<script type="text/javascript" >
	
$( document ).ready( function(){
	
	$( ".company-logo-thumb" ).fancybox();
	
} );

</script>

<?php } ?>
