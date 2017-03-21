<?php if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' ); ?>

<section id="component-content" class="contact-details <?= @$params['page_class']; ?>">
	
	<?php if ( @$params['show_page_content_title'] ) { ?>
	<header class="component-heading">
		<h1>
			
			<?php if ( @$params['show_title_as_link_on_detail_view'] AND $contact['name'] == @$this->mcm->html_data['content']['title'] ) { ?>
			<span class="heading-content"><a href="<?= $contact[ 'url' ]; ?>"><?= @$this->mcm->html_data['content']['title']; ?></a></span>
			<?php } else { ?>
			<span class="heading-content"><?= @$this->mcm->html_data['content']['title']; ?></span>
			<?php } ?>
			
		</h1>
	</header>
	<?php } ?>
	
	<div class="contact-wrapper">
		
		<?php if ( check_var( $params[ 'contact_details_show_gmaps' ] ) AND check_var( $params[ 'contact_details_map_gmaps_query' ] ) ) { ?>
			
			<div class="gmaps">
				
				<div class="inner">
					
					<?= $params[ 'contact_details_map_gmaps_query' ]; ?>
					
				</div>
				
			</div><?php
			
		}
		
		 ?><div class="contact-info">
			
			 <div class="inner">
				
				<?php if ( isset( $params[ 'contact_details_show_image' ] ) AND $params[ 'contact_details_show_image' ] AND $contact[ 'photo_local' ] ) { ?>
				
				<div class="thumb">
					
					<div class="s1 inner">
						
						<div class="s2">
							
							<a href="<?= get_url( $contact[ 'photo_local' ] ); ?>">
								
								<?= img( array( 'src' => $contact[ 'thumb_local' ], 'width' => 96 ) ); ?>
								
							</a>
							
						</div>
						
					</div>
					
				</div>
				
				<?php } ?>
				
				<?php if ( isset( $params[ 'contact_details_show_name' ] ) AND $params[ 'contact_details_show_name' ] ) { ?>
				
				<div id="contact-name" class="contact-info-item">
					
					<?= lang( 'name' ); ?>: <?= $contact[ 'name' ]; ?>
					
				</div>
				
				<?php } ?>
				
				<?php if ( check_var( $params[ 'contact_details_show_phones' ] ) AND check_var( $contact[ 'phones' ] ) ) { ?>
					
					<div id="contact-phones" class="contact-info-item">
						
						<?php
						
						$phones = array();
						
						foreach ( $contact[ 'phones' ] as $key => $phone ) {
							
							if ( check_var( $phone[ 'publicly_visible' ] ) ){
								
								$phones[] = $phone;
								
							}
							
						} ?>
						
						<?php if ( count( $phones ) > 1 ) { ?>
							
							<h4 class="contact-info-title"><?= lang( 'phones' ); ?></h4>
							
						<?php } else if ( count( $phones ) == 1 ) { ?>
							
							<h4 class="contact-info-title"><?= lang( 'phone' ); ?></h4>
							
						<?php } ?>
						
						<?php foreach ( $phones as $key => $phone ) { ?>
							
							<?php if ( check_var( $phone[ 'publicly_visible' ] ) ) { ?>
								
								<span class="contact-phone">
									
									<a href="tel://<?= str_replace( array( ' ', '-', '(', ')' ), '', ( check_var( $phone[ 'int_code' ] ) ? '+' . $phone[ 'int_code' ] : '' ) . ( check_var( $phone[ 'area_code' ] ) ? $phone[ 'area_code' ] : '' ) . $phone[ 'number' ] . ( check_var( $phone[ 'extension_number' ] ) ? $phone[ 'extension_number' ] : '' ) ); ?>">
										
										<span class="contact-phone-number" itemprop="telephone">
											
											<?= ( check_var( $phone[ 'int_code' ] ) ? '<span class="int-code">+' . $phone[ 'int_code' ] . '</span>' : '' ); ?> <?= ( check_var( $phone[ 'area_code' ] ) ? '<span class="area-code">(' . $phone[ 'area_code' ] . ')</span>' : '' ); ?> <span class="number"><?= $phone[ 'number' ]; ?></span> <span class="extension-number"><?= $phone[ 'extension_number' ]; ?></span>
											
										</span>
										
									</a>
									
									<?php if ( check_var( $phone[ 'phone_title_publicly_visible' ] ) AND check_var( $phone[ 'title' ] ) ) { ?>
										
										<span class="contact-item-title">
											
											<?= $phone[ 'title' ]; ?>
											
										</span>
										
									<?php } ?>
									
								</span>
								
							<?php } ?>
							
						<?php } ?>
						
					</div>
					
				<?php } ?>
				
				<?php if ( check_var( $params[ 'contact_details_show_emails' ] ) AND check_var( $contact[ 'emails' ] ) ) { ?>
					
					<div id="contact-emails" class="contact-info-item">
						
						<?php
						
						$emails = array();
						
						foreach ( $contact[ 'emails' ] as $key => $email ) {
							
							if ( check_var( $email[ 'publicly_visible' ] ) ){
								
								$emails[] = $email;
								
							}
							
						} ?>
						
						<?php if ( count( $emails ) > 1 ) { ?>
							
							<h4 class="contact-info-title"><?= lang( 'emails' ); ?></h4>
							
						<?php } else if ( count( $emails ) == 1 ) { ?>
							
							<h4 class="contact-info-title"><?= lang( 'email' ); ?></h4>
							
						<?php } ?>
						
						<?php foreach ( $emails as $key => $email ) { ?>
							
							<?php if ( check_var( $email[ 'publicly_visible' ] ) ) { ?>
								
								<?php $show_email_title = ( ( check_var( $email[ 'email_title_publicly_visible' ] ) AND check_var( $email[ 'title' ] ) ) ? TRUE : FALSE ); ?>
								
								<span class="contact-module-email">
									
									<a href="mailto:<?= $contact[ 'name' ]; ?><<?= $email[ 'email' ]; ?>>" class="contact-email-value" itemprop="email">
										
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
						
					</div>
					
				<?php } ?>
				
				<?php if ( isset( $params[ 'contact_details_show_addresses' ] ) AND $params[ 'contact_details_show_addresses' ] AND $contact[ 'addresses' ] ) { ?>
				
				<div id="contact-addresses" class="contact-info-item">
					
					<?php if ( count( $contact[ 'addresses' ] ) > 1 ) { ?>
					
					<h4 class="contact-info-title"><?= lang( 'addresses' ); ?></h4>
					
					<?php foreach ( $contact[ 'addresses' ] as $key => $address ) { ?>
						
						( <?= $address[ 'title' ]; ?> )<br /><br />
						<?= $address[ 'email' ]; ?>
						
					<?php } ?>
					
					<?php } else if ( count( $contact[ 'addresses' ] ) == 1 ) { ?>
					
					<h4 class="contact-info-title"><?= lang( 'address' ); ?></h4>
					
					<?php foreach ( $contact[ 'addresses' ] as $key => $address ) { ?>
						
						<?= $address[ 'public_area_title' ]; ?>
						<?php if ( $address[ 'number' ] ) { ?> <?= ( ( $address[ 'public_area_title' ] ) ? '-' : '' ); ?> <?= lang( 'number' ); ?> <?= $address[ 'number' ]; ?><?php } ?>
						<?php if ( $address[ 'complement' ] ) { ?> <?= ( ( $address[ 'public_area_title' ] OR $address[ 'number' ] ) ? '-' : '' ); ?><?= $address[ 'complement' ]; ?><?php } ?>
						
						<?= ( ( $address[ 'public_area_title' ] OR $address[ 'number' ] OR $address[ 'complement' ] ) ? '<br />' : '' ); ?>
						
						<?= ( ( $address[ 'neighborhood_title' ] ) ? $address[ 'neighborhood_title' ] . '<br />' : '' ); ?>
						
						<?= ( ( $address[ 'postal_code' ] ) ? $address[ 'postal_code' ] . '<br />' : '' ); ?>
						
						<?= $address[ 'city_title' ]; ?> - <?= $address[ 'state_acronym' ]; ?><br />
						<?= $address[ 'country_title' ]; ?><br />
						
					<?php } ?>
					
					<?php } ?>
					
				</div>
				
				<?php } ?>
				
			</div>
			
		</div><?php 
		
		 ?><div class="contact-form">
			
			 <div class="inner">
				
				<?php if ( isset( $params[ 'contact_form_title_text' ] ) AND $params[ 'contact_form_title_text' ] ) { ?>
				
				<h3><?= $params[ 'contact_form_title_text' ]; ?></h3>
				
				<?php } ?>
				
				<?= form_open( get_url( $this->uri->ruri_string() ), array( 'id' => 'menu-item-form', ) ); ?>
					
					<?php if ( isset( $params[ 'contact_form_show_field_name' ] ) AND $params[ 'contact_form_show_field_name' ] ) { ?>
					
					<div id="name-container" class="contact-form-item">
						
						<?= form_label( lang( 'name' ) . ( ( isset( $params[ 'contact_form_field_name_is_required' ] ) AND $params[ 'contact_form_field_name_is_required' ] ) ? ' ' . lang( 'form_required_string' ) : '' ), 'name' ); ?>
						
						<?= form_error( 'name', '<div class="msg-inline-error">', '</div>' ); ?>
						
						<?= vui_el_input_text(
								
							array(
								
								'text' => lang( 'name' ),
								'name' => 'name',
								'value' => set_value( 'name', @$visitor[ 'name' ] ),
								'class' => 'form-element',
								'id' => 'name',
								'autofocus' => TRUE,
								
							)
							
						); ?>
						
					</div>
					
					<?php } ?>
					
					<?php if ( isset( $params[ 'contact_form_show_field_email' ] ) AND $params[ 'contact_form_show_field_email' ] ) { ?>
					
					<div id="email-container" class="contact-form-item">
						
						<?= form_label( lang( 'email' ) . ( ( isset( $params[ 'contact_form_field_email_is_required' ] ) AND $params[ 'contact_form_field_email_is_required' ] ) ? ' ' . lang( 'form_required_string' ) : '' ), 'email' ); ?>
						
						<?= form_error( 'email', '<div class="msg-inline-error">', '</div>' ); ?>
						
						<?= vui_el_input_text(
								
							array(
								
								'text' => lang( 'email' ),
								'name' => 'email',
								'value' => set_value( 'email', @$visitor[ 'email' ] ),
								'class' => 'form-element',
								'id' => 'email',
								
							)
							
						); ?>
						
					</div>
					
					<?php } ?>
					
					<?php if ( isset( $params[ 'contact_form_show_field_phone_1' ] ) AND $params[ 'contact_form_show_field_phone_1' ] ) { ?>
					
					<div id="phone-1-container" class="contact-form-item">
						
						<?= form_label( ( ( isset( $params[ 'contact_form_show_field_phone_2' ] ) AND $params[ 'contact_form_show_field_phone_2' ] ) ? lang( 'phone_1' ) : lang( 'phone' ) ) . ( ( isset( $params[ 'contact_form_field_phone_1_is_required' ] ) AND $params[ 'contact_form_field_phone_1_is_required' ] ) ? ' ' . lang( 'form_required_string' ) : '' ), 'phone-1' ); ?>
						
						<?= form_error( 'phone_1', '<div class="msg-inline-error">', '</div>' ); ?>
						
						<?= vui_el_input_text(
								
							array(
								
								'text' => lang( 'phone_1' ),
								'name' => 'phone_1',
								'value' => set_value( 'phone_1', @$visitor[ 'phone_1' ] ),
								'class' => 'form-element',
								'id' => 'phone-1',
								
							)
							
						); ?>
						
					</div>
					
					<?php } ?>
					
					<?php if ( isset( $params[ 'contact_form_show_field_phone_2' ] ) AND $params[ 'contact_form_show_field_phone_2' ] ) { ?>
					
					<div id="phone-2-container" class="contact-form-item">
						
						<?= form_label( ( ( isset( $params[ 'contact_form_show_field_phone_1' ] ) AND $params[ 'contact_form_show_field_phone_1' ] ) ? lang( 'phone_2' ) : lang( 'phone' ) ) . ( ( isset( $params[ 'contact_form_field_phone_2_is_required' ] ) AND $params[ 'contact_form_field_phone_2_is_required' ] ) ? ' ' . lang( 'form_required_string' ) : '' ), 'phone-2' ); ?>
						
						<?= form_error( 'phone_2', '<div class="msg-inline-error">', '</div>' ); ?>
						
						<?= vui_el_input_text(
								
							array(
								
								'text' => lang( 'phone_2' ),
								'name' => 'phone_2',
								'value' => set_value( 'phone_2', @$visitor[ 'phone_2' ] ),
								'class' => 'form-element',
								'id' => 'phone-2',
								
							)
							
						); ?>
						
					</div>
					
					<?php } ?>
					
					<?php if ( isset( $params[ 'contact_form_show_field_company' ] ) AND $params[ 'contact_form_show_field_company' ] ) { ?>
					
					<div id="company-container" class="contact-form-item">
						
						<?= form_label( lang( 'company' ) . ( ( isset( $params[ 'contact_form_field_company_is_required' ] ) AND $params[ 'contact_form_field_company_is_required' ] ) ? ' ' . lang( 'form_required_string' ) : '' ), 'company' ); ?>
						
						<?= form_error( 'company', '<div class="msg-inline-error">', '</div>' ); ?>
						
						<?= vui_el_input_text(
								
							array(
								
								'text' => lang( 'company' ),
								'name' => 'company',
								'value' => set_value( 'company', @$visitor[ 'company' ] ),
								'class' => 'form-element',
								'id' => 'company',
								
							)
							
						); ?>
						
					</div>
					
					<?php } ?>
					
					
					
					<?php if ( isset( $params[ 'contact_form_show_field_addresses' ] ) AND $params[ 'contact_form_show_field_addresses' ] ) { ?>
					
					<?php if ( isset( $params[ 'contact_form_show_field_address_country' ] ) AND $params[ 'contact_form_show_field_address_country' ] ) { ?>
					
					<div id="country-container" class="contact-form-item">
						
						<?= form_label( lang( 'country' ) . ( ( isset( $params[ 'contact_form_field_address_country_is_required' ] ) AND $params[ 'contact_form_field_address_country_is_required' ] ) ? ' ' . lang( 'form_required_string' ) : '' ), 'country' ); ?>
						
						<?= form_error( 'country', '<div class="msg-inline-error">', '</div>' ); ?>
						
						<?= vui_el_input_text(
								
							array(
								
								'text' => lang( 'country' ),
								'name' => 'country',
								'value' => set_value( 'country', @$visitor[ 'country' ] ),
								'class' => 'form-element',
								'id' => 'country',
								
							)
							
						); ?>
						
					</div>
					
					<?php } ?>
					
					<?php if ( isset( $params[ 'contact_form_show_field_address_state' ] ) AND $params[ 'contact_form_show_field_address_state' ] ) { ?>
					
					<div id="state-container" class="contact-form-item">
						
						<?= form_label( lang( 'state' ) . ( ( isset( $params[ 'contact_form_field_address_state_is_required' ] ) AND $params[ 'contact_form_field_address_state_is_required' ] ) ? ' ' . lang( 'form_required_string' ) : '' ), 'state' ); ?>
						
						<?= form_error( 'state', '<div class="msg-inline-error">', '</div>' ); ?>
						
						<?= vui_el_input_text(
								
							array(
								
								'text' => lang( 'state' ),
								'name' => 'state',
								'value' => set_value( 'state', @$visitor[ 'state' ] ),
								'class' => 'form-element',
								'id' => 'state',
								
							)
							
						); ?>
						
					</div>
					
					<?php } ?>
					
					<?php if ( isset( $params[ 'contact_form_show_field_address_city' ] ) AND $params[ 'contact_form_show_field_address_city' ] ) { ?>
					
					<div id="city-container" class="contact-form-item">
						
						<?= form_label( lang( 'city' ) . ( ( isset( $params[ 'contact_form_field_address_city_is_required' ] ) AND $params[ 'contact_form_field_address_city_is_required' ] ) ? ' ' . lang( 'form_required_string' ) : '' ), 'city' ); ?>
						
						<?= form_error( 'city', '<div class="msg-inline-error">', '</div>' ); ?>
						
						<?= vui_el_input_text(
								
							array(
								
								'text' => lang( 'city' ),
								'name' => 'city',
								'value' => set_value( 'city', @$visitor[ 'city' ] ),
								'class' => 'form-element',
								'id' => 'city',
								
							)
							
						); ?>
						
					</div>
					
					<?php } ?>
					
					<?php if ( isset( $params[ 'contact_form_show_field_address_neighborhood' ] ) AND $params[ 'contact_form_show_field_address_neighborhood' ] ) { ?>
					
					<div id="neighborhood-container" class="contact-form-item">
						
						<?= form_label( lang( 'neighborhood' ) . ( ( isset( $params[ 'contact_form_field_address_neighborhood_is_required' ] ) AND $params[ 'contact_form_field_address_neighborhood_is_required' ] ) ? ' ' . lang( 'form_required_string' ) : '' ), 'neighborhood' ); ?>
						
						<?= form_error( 'neighborhood', '<div class="msg-inline-error">', '</div>' ); ?>
						
						<?= vui_el_input_text(
								
							array(
								
								'text' => lang( 'neighborhood' ),
								'name' => 'neighborhood',
								'value' => set_value( 'neighborhood', @$visitor[ 'neighborhood' ] ),
								'class' => 'form-element',
								'id' => 'neighborhood',
								
							)
							
						); ?>
						
					</div>
					
					<?php } ?>
					
					<?php if ( isset( $params[ 'contact_form_show_field_address_public_area' ] ) AND $params[ 'contact_form_show_field_address_public_area' ] ) { ?>
					
					<div id="public-area-container" class="contact-form-item">
						
						<?= form_label( lang( 'public_area' ) . ( ( isset( $params[ 'contact_form_field_address_public_area_is_required' ] ) AND $params[ 'contact_form_field_address_public_area_is_required' ] ) ? ' ' . lang( 'form_required_string' ) : '' ), 'public-area' ); ?>
						
						<?= form_error( 'public_area', '<div class="msg-inline-error">', '</div>' ); ?>
						
						<?= vui_el_input_text(
								
							array(
								
								'text' => lang( 'public-area' ),
								'name' => 'public-area',
								'value' => set_value( 'public-area', @$visitor[ 'public-area' ] ),
								'class' => 'form-element',
								'id' => 'public-area',
								
							)
							
						); ?>
						
					</div>
					
					<?php } ?>
					
					<?php if ( isset( $params[ 'contact_form_show_field_address_number' ] ) AND $params[ 'contact_form_show_field_address_number' ] ) { ?>
					
					<div id="number-container" class="contact-form-item">
						
						<?= form_label( lang( 'number' ) . ( ( isset( $params[ 'contact_form_field_address_number_is_required' ] ) AND $params[ 'contact_form_field_address_number_is_required' ] ) ? ' ' . lang( 'form_required_string' ) : '' ), 'number' ); ?>
						
						<?= form_error( 'number', '<div class="msg-inline-error">', '</div>' ); ?>
						
						<?= vui_el_input_text(
							
							array(
								
								'text' => lang( 'number' ),
								'name' => 'number',
								'value' => set_value( 'number', @$visitor[ 'number' ] ),
								'class' => 'form-element',
								'id' => 'number',
								
							)
							
						); ?>
						
					</div>
					
					<?php } ?>
					
					<?php if ( isset( $params[ 'contact_form_show_field_address_postal_code' ] ) AND $params[ 'contact_form_show_field_address_postal_code' ] ) { ?>
					
					<div id="complement-container" class="contact-form-item">
						
						<?= form_label( lang( 'complement' ) . ( ( isset( $params[ 'contact_form_field_address_complement_is_required' ] ) AND $params[ 'contact_form_field_address_complement_is_required' ] ) ? ' ' . lang( 'form_required_string' ) : '' ), 'complement' ); ?>
						
						<?= form_error( 'complement', '<div class="msg-inline-error">', '</div>' ); ?>
						
						<?= vui_el_input_text(
							
							array(
								
								'text' => lang( 'complement' ),
								'name' => 'complement',
								'value' => set_value( 'complement', @$visitor[ 'complement' ] ),
								'class' => 'form-element',
								'id' => 'complement',
								
							)
							
						); ?>
						
					</div>
					
					<?php } ?>
					
					<?php if ( isset( $params[ 'contact_form_show_field_address_postal_code' ] ) AND $params[ 'contact_form_show_field_address_postal_code' ] ) { ?>
					
					<div id="postal-code-container" class="contact-form-item">
						
						<?= form_label( lang( 'postal_code' ) . ( ( isset( $params[ 'contact_form_field_address_postal_code_is_required' ] ) AND $params[ 'contact_form_field_address_postal_code_is_required' ] ) ? ' ' . lang( 'form_required_string' ) : '' ), 'postal-code' ); ?>
						
						<?= form_error( 'postal_code', '<div class="msg-inline-error">', '</div>' ); ?>
						
						<?= vui_el_input_text(
							
							array(
								
								'text' => lang( 'postal_code' ),
								'name' => 'postal_code',
								'value' => set_value( 'postal_code', @$visitor[ 'postal_code' ] ),
								'class' => 'form-element',
								'id' => 'postal-code',
								
							)
							
						); ?>
						
					</div>
					
					<?php } ?>
					
					<?php } ?>
					
					
					
					<?php if ( isset( $params[ 'contact_form_show_field_extra_combobox_1' ] ) AND $params[ 'contact_form_show_field_extra_combobox_1' ] AND $params[ 'contact_form_options_field_extra_combobox_1' ] ) { ?>
					
					<div id="extra-combobox-1-container" class="contact-form-item">
						
						<?= form_label( lang( $params[ 'contact_form_title_field_extra_combobox_1' ] ) . ( ( isset( $params[ 'contact_form_field_extra_combobox_1_is_required' ] ) AND $params[ 'contact_form_field_extra_combobox_1_is_required' ] ) ? ' ' . lang( 'form_required_string' ) : '' ), 'extra-combobox-1' ); ?>
						<?php
							$options = array(
								
								'' => lang( 'combobox_select' ),
								
							);
							
							$params[ 'contact_form_options_field_extra_combobox_1' ] = explode( "\n" , $params[ 'contact_form_options_field_extra_combobox_1' ] );
							
							foreach( $params[ 'contact_form_options_field_extra_combobox_1' ] as $option ) {
								
								$options[ $option ] = $option;
								
							};
						?>
						
						<?= form_error( 'extra_combobox_1', '<div class="msg-inline-error">', '</div>' ); ?>
						
						<?= vui_el_dropdown(
								
							array(
								
								'name' => 'extra_combobox_1',
								'options' => $options,
								'value' => set_value( 'extra_combobox_1', 0 ),
								'class' => 'form-element',
								'id' => 'extra-combobox-1',
								
							)
							
						); ?>
						
					</div>
					
					<?php } ?>
					
					<?php if ( isset( $params[ 'contact_form_show_field_extra_combobox_2' ] ) AND $params[ 'contact_form_show_field_extra_combobox_2' ] AND $params[ 'contact_form_options_field_extra_combobox_1' ] ) { ?>
					
					<div id="extra-combobox-2-container" class="contact-form-item">
						
						<?= form_label( lang( $params[ 'contact_form_title_field_extra_combobox_2' ] ) . ( ( isset( $params[ 'contact_form_field_extra_combobox_2_is_required' ] ) AND $params[ 'contact_form_field_extra_combobox_2_is_required' ] ) ? ' ' . lang( 'form_required_string' ) : '' ), 'extra-combobox-2' ); ?>
						
						<?= form_error( 'extra_combobox_2', '<div class="msg-inline-error">', '</div>' ); ?>
						
						<?php
							$options = array(
								
								'' => lang( 'combobox_select' ),
								
							);
							
							$params[ 'contact_form_options_field_extra_combobox_2' ] = explode( "\n" , $params[ 'contact_form_options_field_extra_combobox_2' ] );
							
							foreach( $params[ 'contact_form_options_field_extra_combobox_2' ] as $option ) {
								
								$options[ $option ] = $option;
								
							};
						?>
						
						<?= vui_el_dropdown(
								
							array(
								
								'name' => 'extra_combobox_2',
								'options' => $options,
								'value' => set_value( 'extra_combobox_2', 0 ),
								'class' => 'form-element',
								'id' => 'extra-combobox-2',
								
							)
							
						); ?>
						
					</div>
					
					<?php } ?>
					
					<?php if ( isset( $params[ 'contact_form_show_field_extra_combobox_3' ] ) AND $params[ 'contact_form_show_field_extra_combobox_3' ] AND $params[ 'contact_form_options_field_extra_combobox_1' ] ) { ?>
					
					<div id="extra-combobox-3-container" class="contact-form-item">
						
						<?= form_label( lang( $params[ 'contact_form_title_field_extra_combobox_3' ] ) . ( ( isset( $params[ 'contact_form_field_extra_combobox_3_is_required' ] ) AND $params[ 'contact_form_field_extra_combobox_3_is_required' ] ) ? ' ' . lang( 'form_required_string' ) : '' ), 'extra-combobox-3' ); ?>
						
						<?= form_error( 'extra_combobox_3', '<div class="msg-inline-error">', '</div>' ); ?>
						
						<?php
							$options = array(
								
								'' => lang( 'combobox_select' ),
								
							);
							
							$params[ 'contact_form_options_field_extra_combobox_3' ] = explode( "\n" , $params[ 'contact_form_options_field_extra_combobox_3' ] );
							
							foreach( $params[ 'contact_form_options_field_extra_combobox_3' ] as $option ) {
								
								$options[ $option ] = $option;
								
							};
						?>
						
						<?= vui_el_dropdown(
								
							array(
								
								'name' => 'extra_combobox_3',
								'options' => $options,
								'value' => set_value( 'extra_combobox_3', 0 ),
								'class' => 'form-element',
								'id' => 'extra-combobox-3',
								
							)
							
						); ?>
						
					</div>
					
					<?php } ?>
					
					
					
					<?php if ( isset( $params[ 'contact_form_show_field_subject' ] ) AND $params[ 'contact_form_show_field_subject' ] ) { ?>
					
					<div id="subject-container" class="contact-form-item">
						
						<?php if ( isset( $params[ 'contact_form_field_subject_is_hidden' ] ) AND $params[ 'contact_form_field_subject_is_hidden' ] ) { ?>
							
							<?= form_hidden( 'subject', ( ( isset( $params[ 'contact_form_field_subject_default_value' ] ) AND $params[ 'contact_form_field_subject_default_value' ] ) ? $params[ 'contact_form_field_subject_default_value' ] : $contact[ 'name' ] ) ); ?>
							
						<?php } else { ?>
							
							<?= form_label( lang( 'subject' ) . ( ( isset( $params[ 'contact_form_field_subject_is_required' ] ) AND $params[ 'contact_form_field_subject_is_required' ] ) ? ' ' . lang( 'form_required_string' ) : '' ), 'subject' ); ?>
							
							<?= form_error( 'subject', '<div class="msg-inline-error">', '</div>' ); ?>
							
							<?= vui_el_input_text(
								
								array(
									
									'text' => lang( 'subject' ),
									'name' => 'subject',
									'value' => set_value( 'subject', @$visitor[ 'subject' ] ),
									'class' => 'form-element',
									'id' => 'subject',
									
								)
								
							); ?>
							
						<?php } ?>
						
					</div>
					
					<?php } ?>
					
					<?php if ( isset( $params[ 'contact_form_show_field_message' ] ) AND $params[ 'contact_form_show_field_message' ] ) { ?>
					
					<div id="message-container" class="contact-form-item">
						
						<?php if ( isset( $params[ 'contact_form_field_message_custom_title' ] ) AND $params[ 'contact_form_field_message_custom_title' ] ) { ?>
							
							<?= form_label( $params[ 'contact_form_field_message_custom_title' ], 'message' ); ?>
							
						<?php } else { ?>
							
							<?= form_label( lang( 'message' ) . ( ( isset( $params[ 'contact_form_field_message_is_required' ] ) AND $params[ 'contact_form_field_message_is_required' ] ) ? ' ' . lang( 'form_required_string' ) : '' ), 'message' ); ?>
							
						<?php } ?>
						
						<?= form_error( 'message', '<div class="msg-inline-error">', '</div>' ); ?>
						
						<?= vui_el_textarea(
								
							array(
								
								'text' => lang( 'message' ),
								'value' => set_value( 'message', @$visitor[ 'message' ] ),
								'id' => 'message',
								'name' => 'message',
								'class' => 'form-element',
								
							)
							
						); ?>
						
					</div>
					
					<?php } ?>
					
					<div class="clear"></div>
					
					<?= vui_el_button(
							
						array(
							
							'text' => lang( 'send' ),
							'value' => lang( 'send' ),
							'id' => 'submit',
							'name' => 'submit',
							'class' => 'form-element',
							'button_type' => 'button',
							
						)
						
					); ?>
					
				<?= form_close(); ?>
				
			</div>
			
		</div>
		
	</div>
	
</section>


<script type="text/javascript">
	
	<?php if ( $this->plugins->load( 'fancybox' ) ) { ?>
	$( document ).on( 'ready', function(){
		
		$( '.thumb a' ).fancybox();
		
	} );
	<?php } ?>
	
</script>

