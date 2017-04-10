<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' ); ?>

<section class="module-wrapper contact-module contact-module-wrapper <?= $module_data[ 'params' ][ 'module_class' ]; ?>">
	
	<?php if ( check_var( $module_data[ 'params' ][ 'show_title' ] ) ) { ?>
	<header class="module-title">
		
		<h3>
			
			<?= $module_data[ 'title' ]; ?>
			
		</h3>
		
	</header><?php
	
	}
	
	?><div class="module-content contact-module-layout-<?= $module_data[ 'params' ][ 'contact_module_layout' ]; ?> contact-module-theme-<?= $module_data[ 'params' ][ 'contact_module_theme' ]; ?>" itemscope itemtype="http://schema.org/Organization">

		<?php if ( check_var( $module_data[ 'params' ][ 'contact_module_show_name' ] ) ) { ?>

		<h4 itemprop="name" class="contact-name"><?= $contact[ 'name' ]; ?></h4>

		<?php } ?>

		<?php if ( check_var( $module_data[ 'params' ][ 'contact_module_show_phones' ] ) AND check_var( $contact[ 'phones' ] ) AND check_var( $module_data[ 'params' ][ 'contact_module_phones_to_show' ] ) ) { ?>
			
			<?php
			
			$phones = array();
			
			foreach ( $contact[ 'phones' ] as $key => $phone ) {
				
				if ( check_var( $phone[ 'publicly_visible' ] ) ){
					
					$phones[] = $phone;
					
				}
				
			} ?>
			
			<p class="contact-phones contact-info-item">
				
				<?php if ( check_var( $module_data[ 'params' ][ 'contact_module_show_phones_title' ] ) ) {?>
					
					<?php if ( count( $phones ) > 1 ) { ?>
						
						<span class="contact-info-title"><?= lang( 'phones' ); ?></span>
						
					<?php } else if ( count( $phones ) == 1 ) { ?>
						
						<span class="contact-info-title"><?= lang( 'phone' ); ?></span>
						
					<?php } ?>
					
				<?php } ?>
				
				<?php foreach ( $phones as $key => $phone ) { ?>
					
					<?php if ( in_array( $phone[ 'key' ], $module_data[ 'params' ][ 'contact_module_phones_to_show' ] ) ) { ?>
						
						<span class="contact-module-phone">
							
							<a href="tel://<?= str_replace( array( ' ', '-', '(', ')' ), '', ( check_var( $phone[ 'int_code' ] ) ? '+' . $phone[ 'int_code' ] : '' ) . ( check_var( $phone[ 'area_code' ] ) ? $phone[ 'area_code' ] : '' ) . $phone[ 'number' ] . ( check_var( $phone[ 'extension_number' ] ) ? $phone[ 'extension_number' ] : '' ) ); ?>">
								
								<span class="contact-module-phone-number" itemprop="telephone">
									
									<?= ( check_var( $phone[ 'int_code' ] ) ? '<span class="int-code">+' . $phone[ 'int_code' ] . '</span>' : '' ); ?> <?= ( check_var( $phone[ 'area_code' ] ) ? '<span class="area-code">(' . $phone[ 'area_code' ] . ')</span>' : '' ); ?> <span class="number"><?= $phone[ 'number' ]; ?></span> <span class="extension-number"><?= $phone[ 'extension_number' ]; ?></span>
									
								</span>
								
							</a>
							
							<?php if ( check_var( $module_data[ 'params' ][ 'contact_module_show_phones_titles' ] ) AND check_var( $phone[ 'phone_title_publicly_visible' ] ) AND check_var( $phone[ 'title' ] ) ){ ?>
								
								<span class="contact-item-title phone-title-<?= url_title( $phone[ 'title' ], '-', TRUE ); ?>">
									
									<?= $phone[ 'title' ]; ?>
									
								</span>
								
							<?php } ?>
							
						</span>
						
					<?php } ?>
					
				<?php } ?>
				
			</p>
			
		<?php } ?>
		
		
		
		<?php if ( check_var( $module_data[ 'params' ][ 'contact_module_show_emails' ] ) AND check_var( $contact[ 'emails' ] ) AND check_var( $module_data[ 'params' ][ 'contact_module_emails_to_show' ] ) ) { ?>
			
			<?php
			
			$emails = array();
			
			foreach ( $contact[ 'emails' ] as $key => $email ) {
				
				if ( check_var( $email[ 'publicly_visible' ] ) ){
					
					$emails[] = $email;
					
				}
				
			} ?>
			
			<?php if ( check_var( $emails ) ) { ?>
				
				<p class="contact-emails contact-info-item">
					
					<?php if ( check_var( $module_data[ 'params' ][ 'contact_module_show_emails_title' ] ) ) {?>
						
						<?php if ( count( $emails ) > 1 ) { ?>
							
							<span class="contact-info-title"><?= lang( 'emails' ); ?></span>
							
						<?php } else if ( count( $emails ) == 1 ) { ?>
							
							<span class="contact-info-title"><?= lang( 'email' ); ?></span>
							
						<?php } ?>
						
					<?php } ?>
					
					<?php foreach ( $emails as $key => $email ) { ?>
						
						<?php if ( in_array( $email[ 'key' ], $module_data[ 'params' ][ 'contact_module_emails_to_show' ] ) ) { ?>
							
							<?php $show_email_title = ( ( check_var( $module_data[ 'params' ][ 'contact_module_show_emails_titles' ] ) AND check_var( $email[ 'title' ] ) ) ? TRUE : FALSE ); ?>
							
							<span class="contact-module-email">
								
								<a href="mailto:<?= $contact[ 'name' ]; ?><<?= $email[ 'email' ]; ?>>" class="contact-module-email-value" itemprop="email">
									
									<?= $email[ 'email' ]; ?>
									
								</a>
								
								<?php if ( $show_email_title ){ ?>
									
									<span class="contact-item-title">
										
										<?= $email[ 'title' ]; ?>
										
									</span>
									
								<?php } ?>
								
							</span>
							
						<?php } ?>
						
					<?php } ?>
					
				</p>
				
			<?php } ?>
			
		<?php } ?>





		<?php

		$websites = array();

		foreach ( $contact[ 'websites' ] as $key => $website ) {

			if ( @$website[ 'publicly_visible' ] ){

				$websites[] = $website;

			}

		} ?>

		<?php if ( isset( $module_data[ 'params' ][ 'contact_module_show_websites' ] ) AND $module_data[ 'params' ][ 'contact_module_show_websites' ] AND $contact[ 'websites' ] AND ! empty( $websites ) AND isset( $module_data[ 'params' ][ 'contact_module_websites_to_show' ] ) AND ! empty( $module_data[ 'params' ][ 'contact_module_websites_to_show' ] ) ) { ?>

		<p class="contact-websites contact-info-item">

			<?php if ( @$module_data[ 'params' ][ 'contact_module_show_websites_title' ] ) {?>

				<?php if ( count( $websites ) > 1 ) { ?>

					<span class="contact-info-title"><?= lang( 'websites' ); ?></span>

				<?php } else if ( count( $websites ) == 1 ) { ?>

					<span class="contact-info-title"><?= lang( 'website' ); ?></span>

				<?php } ?>

			<?php } ?>

			<?php foreach ( $websites as $key => $website ) { ?>
				
				<?php if ( in_array( $website[ 'key' ], $module_data[ 'params' ][ 'contact_module_websites_to_show' ] ) ) { ?>
					
					<?php $website_title = ( ( @$module_data[ 'params' ][ 'contact_module_show_websites_titles' ] AND @$website[ 'website_title_publicly_visible' ] AND @$website[ 'title' ] ) ? TRUE : FALSE ); ?>

					<span class="contact-module-website">

						<?php if ( $website_title ){ ?>

							<span class="contact-item-title">

								<?= ( @$website[ 'title' ] ? $website[ 'title' ] . ':' : '' ); ?>

							</span>

						<?php } ?>

						<?php

							$website[ 'title' ] = parse_url( $website[ 'url' ] );
							$website[ 'title' ] = url_title( $website[ 'title' ][ 'host' ] ) ;

						?>

						<a href="<?= prep_url( $website[ 'url' ] ); ?>" class="contact-module-website-value <?= ( 'list-info-wrapper-website-' . $website[ 'title' ] ); ?>" target="_blank" itemprop="website">

							<?= $website[ 'url' ]; ?>

						</a>

					</span>
					
				<?php } ?>
				
			<?php } ?>
			
		</p>

		<?php } ?>



		<?php

		$addresses = array();

		foreach ( $contact[ 'addresses' ] as $key => $address ) {

			if ( @$address[ 'publicly_visible' ] ){

				$addresses[] = $address;

			}

		} ?>

		<?php if ( isset( $module_data[ 'params' ][ 'contact_module_show_addresses' ] ) AND $module_data[ 'params' ][ 'contact_module_show_addresses' ] AND $contact[ 'addresses' ] AND ! empty( $addresses ) ) { ?>

			<p class="contact-addresses contact-info-item" itemprop="address" itemscope itemtype="http://schema.org/PostalAddress">

				<?php if ( @$module_data[ 'params' ][ 'contact_module_show_addresses_title' ] ) {?>

					<?php if ( count( $addresses ) > 1 ) { ?>

						<span class="contact-info-title"><?= lang( 'addresses' ); ?></span>

						<?php } else if ( count( $addresses ) == 1 ) { ?>

						<span class="contact-info-title"><?= lang( 'address' ); ?></span>

					<?php } ?>

				<?php } ?>

				<?php foreach ( $contact[ 'addresses' ] as $key => $address ) { ?>

					<?php if ( @$module_data[ 'params' ][ 'contact_module_show_addresses_titles' ] AND @$address[ 'address_title_publicly_visible' ] AND @$address[ 'title' ] ){ ?>

						<span class="contact-item-title">

							<?= ( @$address[ 'title' ] ? $address[ 'title' ] : '' ); ?>

						</span>

					<?php } ?>



					<?php $pre_text = FALSE; ?>
					<?php $pre_text_line_1 = FALSE; ?>

					<?php if ( @$address[ 'public_area_title' ] OR @$address[ 'number' ] OR @$address[ 'complement' ] OR @$address[ 'neighborhood_title' ] ) { ?>

						<span class="contact-module-address-street" itemprop="streetAddress">

							<?php if ( @$module_data[ 'params' ][ 'contact_module_show_public_area' ] AND @$address[ 'public_area_title' ] ) { ?>

								<span class="contact-module-address-public-area">

									<?= @$address[ 'public_area_title' ]; ?>

									<?php $pre_text = TRUE; ?>
									<?php $pre_text_line_1 = TRUE; ?>

								</span>

							<?php } ?>

							<?php if ( @$module_data[ 'params' ][ 'contact_module_show_number' ] AND @$address[ 'number' ] ) { ?>

								<span class="contact-module-address-number">

									<?= ( $pre_text_line_1 ? ', ' : '' ); ?>

									<?= lang( 'abbr_number' ); ?>

									<?= @$address[ 'number' ]; ?>

									<?php $pre_text = TRUE; ?>
									<?php $pre_text_line_1 = TRUE; ?>

								</span>

							<?php } ?>

							<?php if ( @$module_data[ 'params' ][ 'contact_module_show_complement' ] AND @$address[ 'complement' ] ) { ?>

								<span class="contact-module-address-complement">

									<?= @$address[ 'complement' ]; ?>

									<?php $pre_text = TRUE; ?>
									<?php $pre_text_line_1 = TRUE; ?>

								</span>

							<?php } ?>

							<?php if ( @$module_data[ 'params' ][ 'contact_module_show_neighborhood' ] AND @$address[ 'neighborhood_title' ] ) { ?>

								<span class="contact-module-address-neighborhood">

									<?= @$address[ 'neighborhood_title' ]; ?>

									<?php $pre_text = TRUE; ?>
									<?php $pre_text_line_2 = TRUE; ?>

								</span>

							<?php } ?>

						</span>

					<?php } ?>



					<?php $pre_text_line_2 = FALSE; ?>

					<?php if ( @$address[ 'city_title' ] OR @$address[ 'state_acronym' ] ) { ?>

						<span class="contact-module-address-city-state">

							<?php if ( @$module_data[ 'params' ][ 'contact_module_show_city' ] AND @$address[ 'city_title' ] ) { ?>

								<span class="contact-module-address-city" itemprop="addressLocality">

									<?= @$address[ 'city_title' ]; ?>

									<?php $pre_text = TRUE; ?>
									<?php $pre_text_line_2 = TRUE; ?>

								</span>

							<?php } ?>

							<?php if ( @$module_data[ 'params' ][ 'contact_module_show_state' ] AND @$address[ 'state_acronym' ] ) { ?>

								<?= ( $pre_text_line_2 ? '-' : '' ); ?>

								<span class="contact-module-address-state" itemprop="addressRegion">

									<?= @$address[ 'state_acronym' ]; ?>

									<?php $pre_text = TRUE; ?>
									<?php $pre_text_line_2 = TRUE; ?>

								</span>

							<?php } ?>

						</span>

					<?php } ?>


					<?php $pre_text_line_3 = FALSE; ?>

					<?php if ( @$module_data[ 'params' ][ 'contact_module_show_postal_code' ] AND @$address[ 'postal_code' ] ) { ?>

						<span class="contact-module-address-postal-code" itemprop="postalCode">

							<span class="contact-module-address-postal-code-title">

								<?= lang( 'postal_code' ); ?>

							</span>

							<span itemprop="postalCode">

								<?= @$address[ 'postal_code' ]; ?>

							</span>

							<?php $pre_text = TRUE; ?>
							<?php $pre_text_line_3 = TRUE; ?>

						</span>

					<?php } ?>



					<?php $pre_text_line_4 = FALSE; ?>

					<?php if ( @$module_data[ 'params' ][ 'contact_module_show_country' ] AND @$address[ 'country_title' ] ) { ?>

						<span class="contact-module-address-country" itemprop="addressCountry">

							<?= @$address[ 'country_title' ]; ?>

							<?php $pre_text = TRUE; ?>
							<?php $pre_text_line_4 = TRUE; ?>

						</span>

					<?php } ?>

				<?php } ?>

			</p>
			
		<?php } ?>
		
		<div class="clear">&nbsp;</div>
		
	</div>
	
</section>



