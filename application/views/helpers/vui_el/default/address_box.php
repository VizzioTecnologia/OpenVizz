
<?php if ( $address ) { ?>

<span class="vui-address-box<?= $wrapper_class ? " $wrapper_class" : ''; ?>">
	
	<span class="vui-address-box-inner" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">
		
		<?php if ( $address[ 'title' ] ) { ?>
			
			<span class="title"><?= $address[ 'title' ]; ?></span>
			
		<?php } ?>
		
		<?php $pre_text = FALSE; ?>
		<?php $pre_text_line_1 = FALSE; ?>
		
		<span class="content">
			
			<?php if ( @$address[ 'public_area_title' ] OR @$address[ 'number' ] OR @$address[ 'complement' ] OR @$address[ 'neighborhood_title' ] ) { ?>
				
				<span class="street" itemprop="streetAddress">
				
					<?php if ( @$address[ 'public_area_title' ] ) { ?>
						
						<span class="public-area">
							
							<?= @$address[ 'public_area_title' ]; ?>
							
							<?php $pre_text = TRUE; ?>
							<?php $pre_text_line_1 = TRUE; ?>
							
						</span> 
						
					<?php } ?>
					
					<?php if ( @$address[ 'number' ] ) { ?>
						
						<span class="number">
							
							<?= ( $pre_text_line_1 ? '' : '' ); ?>
							
							<?= lang( 'abbr_number' ); ?>
							
							<?= @$address[ 'number' ]; ?>
							
							<?php $pre_text = TRUE; ?>
							<?php $pre_text_line_2 = TRUE; ?>
							
						</span> 
						
					<?php } ?>
					
					<?php if ( @$address[ 'complement' ] ) { ?>
						
						<?= ( $pre_text_line_2 ? ', ' : '' ); ?>
						
						<span class="complement">
							
							<?= @$address[ 'complement' ]; ?>
							
							<?php $pre_text = TRUE; ?>
							<?php $pre_text_line_2 = TRUE; ?>
							
						</span> 
						
					<?php } ?>
					
					<?php if ( @$address[ 'neighborhood_title' ] ) { ?>
						
						<span class="neighborhood">
							
							<?= @$address[ 'neighborhood_title' ]; ?>
							
							<?php $pre_text = TRUE; ?>
							<?php $pre_text_line_3 = TRUE; ?>
							
						</span> 
						
					<?php } ?>
					
				</span>
				
			<?php } ?>
			
			
			
			<?php $pre_text_line_2 = FALSE; ?>
			
			<?php if ( @$address[ 'city_title' ] OR @$address[ 'state_acronym' ] ) { ?>
				
				<span class="city-state">
					
					<?php if ( @@$address[ 'city_title' ] ) { ?>
						
						<span class="city" itemprop="addressLocality">
							
							<?= @$address[ 'city_title' ]; ?>
							
							<?php $pre_text = TRUE; ?>
							<?php $pre_text_line_2 = TRUE; ?>
							
						</span> 
						
					<?php } ?>
					
					<?php if ( @$address[ 'state_acronym' ] ) { ?>
						
						<?= ( $pre_text_line_2 ? ' - ' : '' ); ?>
						
						<span class="state" itemprop="addressRegion">
						
							<?= @$address[ 'state_acronym' ]; ?>
							
							<?php $pre_text = TRUE; ?>
							<?php $pre_text_line_2 = TRUE; ?>
							
						</span> 
						
					<?php } ?>
					
				</span>
				
			<?php } ?>
			
			
			
			<?php $pre_text_line_3 = FALSE; ?>
		
			<?php if ( @$address[ 'postal_code' ] ) { ?>
				
				<span class="postal-code" itemprop="postalCode">
					
					<span class="title">
						
						<?= lang( 'postal_code' ); ?>
						
					</span> 
					
					<span class="content" itemprop="postalCode">
						
						<?= @$address[ 'postal_code' ]; ?>
						
					</span> 
					
					<?php $pre_text = TRUE; ?>
					<?php $pre_text_line_3 = TRUE; ?>
					
				</span>
				
			<?php } ?>
			
			
			
			<?php $pre_text_line_4 = FALSE; ?>
			
			<?php if ( @$address[ 'country_title' ] ) { ?>
				
				<span class="country" itemprop="addressCountry">
					
					<?= @$address[ 'country_title' ]; ?>
					
					<?php $pre_text = TRUE; ?>
					<?php $pre_text_line_4 = TRUE; ?>
					
				</span> 
				
			<?php } ?>
			
		</span>
		
	</span>
		
</span>

<?php } ?>