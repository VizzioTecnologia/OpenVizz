		
		
		<header>
			<h1><?php echo lang('select_a_product'); ?></h1>
		</header>
		
		<?php if ( isset($products) AND $products ){ ?>
		<div>
			
			<?php if ( $pagination ){ ?>
			<div class="pagination">
				<?php echo $pagination; ?>
			</div>
			<?php } ?>
			
			<?php echo form_open(get_url('admin'.$this->uri->ruri_string())); ?>
			
			<div class="form-actions">
				
				<?php echo form_submit(array('id'=>'submit-ok','class'=>'button button-ok','name'=>'submit_ok'),lang('action_ok')); ?>
				<?php echo form_submit(array('id'=>'submit-cancel','class'=>'button button-cancel','name'=>'submit_cancel'),lang('action_cancel')); ?>
				
			</div>
			
			<?php echo form_close(); ?>
			
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
						<?php echo anchor($product->external_url,$product->title,'target="_blank" class="" title="'.strip_tags($product->title).'"'); ?>
						<?php } else {  ?>
						<?php echo $product->title; ?>
						<?php }  ?>
					</td>
					<td class="provider ta-center">
						
						<a href="<?= get_url('admin/companies/companies_management/edit_company/'.$product->company_id); ?>" class="list-info-wrapper" data-companyid="<?= $product->company_id; ?>" target="_blank" >
							
							<span class="list-info-thumb-wrapper">
								<?php if ( $product->logo_thumb ){ ?>
								
								<?php echo img( array( 'src' => base_url().'assets/images/components/companies/'.$product->company_id . '/' . $product->logo_thumb, 'width' => 24 ) ); ?>
								
								<?php } ?>
							</span>
							
							<?= $product->provider_title; ?>
							
						</a>
						
					</td>
					<td class="cost_price ta-center">
						<?php echo number_format($product->cost_price, 2, ',', '.'); ?>
					</td>
					<td class="warranty ta-center">
						<?php echo $product->warranty; ?>
					</td>
					
					<td class="operations">
						<?php echo form_open(get_url('admin'.$this->uri->ruri_string())); ?>
						<?php echo form_hidden('product_id',$product->id); ?>
						<?php echo form_submit(array('id'=>'submit-select','name'=>'submit_select','class'=>'button button-select'),lang('select')); ?>
						<?php echo form_close(); ?>
					</td>
					
				</tr>
				<?php endforeach; ?>
			</table>
			
			<?php if ( $pagination ){ ?>
			<div class="pagination">
				<?php echo $pagination; ?>
			</div>
			<?php } ?>
			
			<?php } else if ( ( ! isset($products) OR ! $products ) AND $this->input->post() ){ ?>
			
			<?php echo lang('no_results'); ?>
			
			<?php } else { ?>
			
			<?php echo lang('perform_search'); ?>
			
			<?php }  ?>
			
			<?php echo form_open(get_url('admin'.$this->uri->ruri_string())); ?>
			
			<div class="form-actions">
				
				<?php echo form_submit(array('id'=>'submit-ok','class'=>'button button-ok','name'=>'submit_ok'),lang('action_ok')); ?>
				<?php echo form_submit(array('id'=>'submit-cancel','class'=>'button button-cancel','name'=>'submit_cancel'),lang('action_cancel')); ?>
				
			</div>
			
			<?php echo form_close(); ?>
			
		</div>
		<script type="text/javascript" >
			
			$(document).ready(function(){
				
				findCompaniesElements();
				
			});
			
		</script>
		