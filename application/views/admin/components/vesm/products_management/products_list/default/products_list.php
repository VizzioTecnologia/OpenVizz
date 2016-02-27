
<div>
	
	<header class="component-head">
		
		<h1>
			
			<?= lang( 'products' ); ?>
			
		</h1>
		
	</header>
	
	<?php if ( $pagination ){ ?>
	<div class="pagination">
		<?php echo $pagination; ?>
	</div>
	<?php } ?>
	
	<table>
		<tr>
			<th class="product-code">
				<?php echo lang('product_code'); ?>
			</th>
			<th class="product-title">
				<?php echo lang('title'); ?>
			</th>
			<th class="provider">
				<?php echo lang('provider'); ?>
			</th>
			<th class="cost_price">
				<?php echo lang('cost_price'); ?>
			</th>
			<th class="warranty">
				<?php echo lang('warranty'); ?>
			</th>
			
			<th class="op-column">
				<?php echo lang('operations'); ?>
			</th>
		</tr>
		
		<?php foreach($products as $product): ?>
		<tr>
			<td class="product-code ta-center">
				<?php echo anchor('https://www.google.com/search?q='.urlencode( strip_tags($product->code) ),$product->code,'target="_blank" class="" title="'.lang('action_search').'"'); ?>
			</td>
			<td class="product-title">
				<?php if ( $product->external_url ) { ?>
				<?php echo anchor($product->external_url,$product->title,'target="_blank" class="" title="'.lang('action_view').'"'); ?>
				<?php } else {  ?>
				<?php echo $product->title; ?>
				<?php }  ?>
			</td>
			<td class="provider ta-center">
				
				<a href="<?= get_url('admin/companies/companies_management/edit_company/'.$product->company_id); ?>" class="list-info-wrapper" data-companyid="<?= $product->company_id; ?>" target="_blank" >
					
					<span class="list-info-thumb-wrapper">
						<?php if ( $product->logo_thumb ){ ?>
						
						<?php echo img( array( 'src' => $product->logo_thumb, 'width' => 24 ) ); ?>
						
						<?php } ?>
					</span>
					
					<?= $product->provider_title; ?>
					
				</a>
				
			</td>
			<td class="cost_price ta-center">
				
				<?php if ( $product->cost_price == 0 ){ ?>
				<span class="text-warning">
					<?php echo number_format($product->cost_price, 2, ',', '.'); ?>
				</span>
				<?php } else { ?>
				<?php echo number_format($product->cost_price, 2, ',', '.'); ?>
				<?php } ?>
				
			</td>
			<td class="warranty ta-center">
				<?php echo $product->warranty; ?>
			</td>
			
			<td class="operations">
				<?php echo anchor('admin/'.$component_name.'/' . $component_function . '/edit_product/'.$product->id,lang('action_edit'),'class="btn btn-edit" title="'.lang('action_edit').'"'); ?>
				<?php echo anchor('admin/'.$component_name.'/' . $component_function . '/remove_product/'.$product->id,lang('action_delete'),'class="btn btn-delete" title="'.lang('action_delete').'"'); ?>
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
