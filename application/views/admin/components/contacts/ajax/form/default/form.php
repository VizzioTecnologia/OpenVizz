<?php
	
	$created_date_time = ( check_var( $contact[ 'birthday_date' ] ) ) ? strtotime( $contact[ 'birthday_date' ] ) : gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
	$created_date = $this->input->post( 'created_date' ) ? $this->input->post( 'created_date' ) : strftime( '%Y-%m-%d', $created_date_time );
	$created_time = $this->input->post( 'created_time' ) ? $this->input->post( 'created_time' ) : strftime( '%T', $created_date_time );
	
	$contact[ 'birthday_date' ] = ( check_var( $contact[ 'birthday_date' ] ) ) ? strtotime( $contact[ 'birthday_date' ] ) : gmt_to_local( now(), $this->mcm->filtered_system_params[ 'time_zone' ], $this->mcm->filtered_system_params[ 'dst' ] );
	$contact[ 'birthday_date' ] = strftime( '%Y-%m-%d', $contact[ 'birthday_date' ] );
	
?>

<?php

$unique_hash_id = md5( rand( 100, 1000 ) ) . uniqid();

?>

<div id="<?= $unique_hash_id; ?>" class="ajax live-update-container">
	
	<div class="ajax-inner live-update-inner">
		
		<div class="dynamic-content"></div>
		
		<div class="contact-form">
			
			<div class="form-section">
				
				<div class="form-item vui-field-wrapper-inline contact-image">
					
					<?= form_hidden( 'lu[photo_local]', ( check_var( $contact[ 'photo_local' ] ) ? $contact[ 'photo_local' ] : '' ), FALSE, ' class="live-update" data-lu-field="photo_local" data-lu-url="' . $lu_url[ 'update' ] . '" id="lu-contact-image-' . $unique_hash_id . '"' ); ?>
					<?= form_hidden( 'lu[thumb_local]', ( check_var( $contact[ 'thumb_local' ] ) ? $contact[ 'thumb_local' ] : '' ), FALSE, ' class="live-update" data-lu-field="thumb_local" data-lu-url="' . $lu_url[ 'update' ] . '" id="lu-contact-image-thumb-' . $unique_hash_id . '"' ); ?>
					
					<?= img( array( 'class' => 'contact-image-thumb', 'src' => $contact[ 'thumb_local' ], 'height' => 96 ) ); ?>
					
				</div>
				
				<div class="form-item vui-field-wrapper-inline contact-name">
				
					<div data-lu-response="lu[name]" class="lu-result"></div>
					<?= form_label( lang( 'name' ) ); ?>
					
					<span class="item name inline-edit" data-inline-edit-type="text" data-inline-edit-field-id="<?= 'lu-contact-name-' . $unique_hash_id; ?>"><?= $contact[ 'name' ]; ?></span>
					
					<?= form_hidden( 'lu[name]', ( check_var( $contact[ 'name' ] ) ? $contact[ 'name' ] : '' ), FALSE, ' class="live-update" data-lu-field="name" data-lu-url="' . $lu_url[ 'update' ] . '" id="lu-contact-name-' . $unique_hash_id . '"' ); ?>
					
				</div>
				
				<div class="form-item vui-field-wrapper-inline contact-birthday_date">
					
					<div data-lu-response="lu[birthday_date]" class="lu-result"></div>
					<?= form_label( lang( 'birthday_date' ) ); ?>
					
					<span class="item name inline-edit" data-max-date="+0d" data-inline-edit-type="date" data-inline-edit-field-id="<?= 'lu-contact-birthday_date-' . $unique_hash_id; ?>"><?= $contact[ 'birthday_date' ]; ?></span>
					
					<?= form_hidden( 'lu[birthday_date]', ( check_var( $contact[ 'birthday_date' ] ) ? $contact[ 'birthday_date' ] : '' ), FALSE, ' class="live-update" data-lu-field="birthday_date" data-lu-url="' . $lu_url[ 'update' ] . '" id="lu-contact-birthday_date-' . $unique_hash_id . '"' ); ?>
					
				</div>
				
			</div>
			
			<div class="divisor-h"></div>
			
			<div class="form-section">
				
				<div class="sortable dynamic-fields-wrapper form-item vui-field-wrapper-inline contact-emails lu-contact-emails-<?= $unique_hash_id; ?>">
					
					<?= vui_el_button( array( 'text' => lang( 'emails' ), 'type' => 'text', 'icon' => 'emails', ) ); ?>
					
					<?= vui_el_button( array( 'text' => lang( 'add_email' ), 'icon' => 'add', 'url' => '#', 'class' => 'add-email sortable-disabled' ) ); ?>
					
					<div class="clear"></div>
					
					<?php if ( check_var( $contact[ 'emails' ] ) ){ ?>
					
					<?php foreach ( $contact[ 'emails' ] as $key => $email ) { ?>
					
					<div class="item sortable-item dynamic-field-item contact-email-wrapper">
						
						<div class="contact-email">
							
							<span class="item sortable-handle"></span><?php
							
							?><span class="item remove remove-email" title="<?= lang( 'remove_email' ); ?>"><?= lang( 'remove_email' ); ?></span><?php
							
							?><span class="item key"><?= $email[ 'key' ]; ?></span><?php
							
							?><span class="item title inline-edit" data-inline-edit-type="text" data-inline-edit-callback-function="saveEmails" title="<?= lang( 'title' ); ?>"><?= $email[ 'title' ] ? $email[ 'title' ] : ''; ?></span><?php
							
							?><span class="item email inline-edit" data-inline-edit-type="text" data-inline-edit-callback-function="saveEmails" title="<?= lang( 'email' ); ?>"><?= $email[ 'email' ]; ?></span>
							
						</div>
						
					</div>
					
					<?php } ?>
					
					<?php } ?>
					
					<?= form_hidden( 'lu[emails]', ( check_var( $contact[ 'emails' ] ) ? json_encode( $contact[ 'emails' ] ) : '' ), FALSE, ' class="live-update" data-lu-field="emails" data-lu-url="' . $lu_url[ 'update' ] . '" id="lu-contact-emails-' . $unique_hash_id . '"' ); ?>
					
				</div>
				
			</div>
			
			<div class="divisor-h"></div>
			
			<div class="form-section">
				
				<div class="sortable dynamic-fields-wrapper form-item vui-field-wrapper-inline contact-phones lu-contact-phones-<?= $unique_hash_id; ?>">
					
					<?= vui_el_button( array( 'text' => lang( 'phones' ), 'type' => 'text', 'icon' => 'phones', ) ); ?>
					
					<?= vui_el_button( array( 'text' => lang( 'add_phone' ), 'icon' => 'add', 'url' => '#', 'class' => 'add-phone sortable-disabled' ) ); ?>
					
					<div class="clear"></div>
					
					<?php if ( check_var( $contact[ 'phones' ] ) ){ ?>
					
					<?php foreach ( $contact[ 'phones' ] as $key => $phone ) { ?>
					
					<div class="item sortable-item dynamic-field-item contact-phone-wrapper">
						
						<div class="sortable-item-inner contact-phone">
							
							<span class="item sortable-handle"></span><?php
							
							?><span class="item remove remove-phone" title="<?= lang( 'remove_phone' ); ?>"><?= lang( 'remove_phone' ); ?></span><?php
							
							?><span class="item key"><?= $phone[ 'key' ]; ?></span><?php
							
							?><span class="item title inline-edit" data-inline-edit-type="text" data-inline-edit-callback-function="savePhones" title="<?= lang( 'title' ); ?>"><?= $phone[ 'title' ] ? $phone[ 'title' ] : ''; ?></span><?php
							
							?><span class="item area-code inline-edit" data-inline-edit-type="text" data-inline-edit-callback-function="savePhones" title="<?= lang( 'area_code' ); ?>"><?= $phone[ 'area_code' ]; ?></span><?php
							
							?><span class="item number inline-edit" data-inline-edit-type="text" data-inline-edit-callback-function="savePhones" title="<?= lang( 'number' ); ?>"><?= $phone[ 'number' ]; ?></span><?php
							
							?><span class="item extension-number inline-edit" data-inline-edit-type="text" data-inline-edit-callback-function="savePhones" title="<?= lang( 'extension_number' ); ?>"><?= $phone[ 'extension_number' ]; ?></span>
							
						</div>
						
					</div>
					
					<?php } ?>
					
					<?php } ?>
					
					<?= form_hidden( 'lu[phones]', ( check_var( $contact[ 'phones' ] ) ? json_encode( $contact[ 'phones' ] ) : '' ), FALSE, ' class="live-update" data-lu-field="phones" data-lu-url="' . $lu_url[ 'update' ] . '" id="lu-contact-phones-' . $unique_hash_id . '"' ); ?>
					
				</div>
				
			</div>
			
			<div class="divisor-h"></div>
			
			<div class="form-section">
				
				<div class="sortable dynamic-fields-wrapper form-item vui-field-wrapper-inline contact-addresses lu-contact-addresses-<?= $unique_hash_id; ?>">
					
					<?= vui_el_button( array( 'text' => lang( 'addresses' ), 'type' => 'text', 'icon' => 'addresses', ) ); ?>
					
					<?= vui_el_button( array( 'text' => lang( 'add_address' ), 'icon' => 'add', 'url' => '#', 'class' => 'add-address sortable-disabled' ) ); ?>
					
					<div class="clear"></div>
					
					<?php if ( check_var( $contact[ 'addresses' ] ) ){ ?>
					
					<?php foreach ( $contact[ 'addresses' ] as $key => $address ) { ?>
					
					<div class="item sortable-item dynamic-field-item contact-address-wrapper">
						
						<div class="sortable-item-inner contact-address">
							
							<span class="item sortable-handle"></span><?php
							
							?><span class="item remove remove-address" title="<?= lang( 'remove_address' ); ?>"><?= lang( 'remove_address' ); ?></span><?php
							
							?><span class="item key"><?= $address[ 'key' ]; ?></span><?php
							
							?><span class="item title inline-edit" data-inline-edit-type="text" data-inline-edit-callback-function="saveAddresses" title="<?= lang( 'title' ); ?>"><?= $address[ 'title' ] ? $address[ 'title' ] : ''; ?></span><?php
							
							?><span class="item neighborhood-title inline-edit" data-inline-edit-type="text" data-inline-edit-callback-function="saveAddresses" title="<?= lang( 'neighborhood_title' ); ?>"><?= lang( 'neighborhood_title' ); ?></span><?php
							
							?><span class="item public-area-title inline-edit" data-inline-edit-type="text" data-inline-edit-callback-function="saveAddresses" title="<?= lang( 'public_area_title' ); ?>"><?= $address[ 'public_area_title' ]; ?></span><?php
							
							?><span class="item number inline-edit" data-inline-edit-type="text" data-inline-edit-callback-function="saveAddresses" title="<?= lang( 'number' ); ?>"><?= $address[ 'number' ]; ?></span><?php
							
							?><span class="item complement inline-edit" data-inline-edit-type="text" data-inline-edit-callback-function="saveAddresses" title="<?= lang( 'complement' ); ?>"><?= $address[ 'complement' ]; ?></span><?php
							
							?><span class="item city-title inline-edit" data-inline-edit-type="text" data-inline-edit-callback-function="saveAddresses" title="<?= lang( 'city_title' ); ?>"><?= $address[ 'city_title' ]; ?></span><?php
							
							?><span class="item state-acronym inline-edit" data-inline-edit-type="text" data-inline-edit-callback-function="saveAddresses" title="<?= lang( 'state_acronym' ); ?>"><?= $address[ 'state_acronym' ]; ?></span><?php
							
							?><span class="item postal-code inline-edit" data-inline-edit-type="text" data-inline-edit-callback-function="saveAddresses" title="<?= lang( 'postal_code' ); ?>"><?= $address[ 'postal_code' ]; ?></span><?php
							
							?><span class="item country-title inline-edit" data-inline-edit-type="text" data-inline-edit-callback-function="saveAddresses" title="<?= lang( 'country_title' ); ?>"><?= $address[ 'country_title' ]; ?></span>
							
						</div>
						
					</div>
					
					<?php } ?>
					
					<?php } ?>
					
					<?= form_hidden( 'lu[addresses]', ( check_var( $contact[ 'addresses' ] ) ? json_encode( $contact[ 'addresses' ] ) : '' ), FALSE, ' class="live-update" data-lu-field="addresses" data-lu-url="' . $lu_url[ 'update' ] . '" id="lu-contact-addresses-' . $unique_hash_id . '"' ); ?>
					
				</div>
				
			</div>
			
		</div>
		
	</div>
	
</div>

<script type="text/javascript">
	
	/*************************************************/
	/************* Reordenação dos campos ************/
	
	$( ".sortable" ).sortable({
		
		//containment: "parent", // descomente para bloquear o movimento no container pai
		items: '.sortable-item',
		start: function(event, ui) {
			
			ui.item.addClass( 'sorting' );
			
		},
		stop: function(event, ui) {
			
			ui.item.removeClass( 'sorting' );
			
		},
		handle: ".sortable-handle"
		
	});
	$( ".sortable" ).disableSelection();
	
	function reorderItems( sortableContainer, orderElementSelector ){
		
		sortableContainer.children().each(function( index ) {
			
			$( this ).find( orderElementSelector ).text( index );
			
		});
		
	}
	
	/************* Reordenação dos campos ************/
	/*************************************************/
	
	
	
	/*************************************************/
	/********************* Emails ********************/
	
	function addEmail(){
		
		var count = $( '#<?= $unique_hash_id; ?> .contact-email' ).length;
		
		var newEmail = $( '<div class="item sortable-item dynamic-field-item contact-email-wrapper"></div>' ).appendTo( '#<?= $unique_hash_id; ?> .contact-emails' );
		newEmail.append('<div class="contact-email"></div>');
		newEmail.children().append('<span class="item sortable-handle"></span>');
		newEmail.children().append('<span class="item remove remove-email" title="<?= lang( 'remove_email' ); ?>"><?= lang( 'remove_email' ); ?></span>');
		newEmail.children().append('<span class="item key">' + ( count + 1 ) + '</span>');
		newEmail.children().append('<span class="item title inline-edit" data-inline-edit-callback-function="saveEmails" title="<?= lang( 'title' ); ?>"><?= lang( 'new_email' ); ?></span>');
		newEmail.children().append('<span class="item email inline-edit" data-inline-edit-callback-function="saveEmails" title="<?= lang( 'email' ); ?>"><?= lang( 'email' ); ?></span>');
		
		$.fancybox.update();
		$.fancybox.reposition();
		
	}
	
	$( '#<?= $unique_hash_id; ?> .contact-emails .add-email' ).on( 'click', function( e ){
		
		e.preventDefault();
		addEmail();
		
	});
	
	$( document ).on( 'click', '#<?= $unique_hash_id; ?> .contact-emails .remove-email', function( e ){
		
		var jthis = $( this );
		
		e.preventDefault();
		jthis.parent().remove();
		saveEmails();
		
	});
	
	function saveEmails(){
		
		var emails = {};
		
		$( '#<?= $unique_hash_id; ?> .contact-email' ).each( function( index ) {
			
			var jthis = $( this );
			
			emails[ index + 1 ] = {
				
				key: index + 1,
				title: jthis.find( '.title' ).text(),
				email: jthis.find( '.email' ).text()
				
			}
			
		});
		
		$( '#lu-contact-emails-<?= $unique_hash_id; ?>' ).val( JSON.stringify( emails ) ).trigger( 'change' );
		console.log( JSON.stringify( emails ) )
		
	}
	
	$( ".contact-emails" ).on( "sortout", function( event, ui ){
		
		reorderItems( $(this), '.key' );
		saveEmails();
		
	});
	
	/********************* Emails ********************/
	/*************************************************/
	
	
	
	/*************************************************/
	/********************* Phones ********************/
	
	function addPhone(){
		
		var count = $( '#<?= $unique_hash_id; ?> .contact-phone' ).length;
		
		var newPhone = $( '<div class="item sortable-item dynamic-field-item contact-phone-wrapper"></div>' ).appendTo( '#<?= $unique_hash_id; ?> .contact-phones' );
		newPhone.append('<div class="contact-phone"></div>');
		newPhone.children().append('<span class="item sortable-handle"></span>');
		newPhone.children().append('<span class="item remove remove-phone" title="<?= lang( 'remove_phone' ); ?>"><?= lang( 'remove_phone' ); ?></span>');
		newPhone.children().append('<span class="item key">' + ( count + 1 ) + '</span>');
		newPhone.children().append('<span class="item title inline-edit" data-inline-edit-callback-function="savePhones" title="<?= lang( 'title' ); ?>"><?= lang( 'new_phone' ); ?></span>');
		newPhone.children().append('<span class="item area-code inline-edit" data-inline-edit-callback-function="savePhones" title="<?= lang( 'area_code' ); ?>"><?= lang( 'area_code' ); ?></span>');
		newPhone.children().append('<span class="item number inline-edit" data-inline-edit-callback-function="savePhones" title="<?= lang( 'number' ); ?>"><?= lang( 'number' ); ?></span>');
		newPhone.children().append('<span class="item extension-number inline-edit" data-inline-edit-callback-function="savePhones" title="<?= lang( 'extension_number' ); ?>"><?= lang( 'extension_number' ); ?></span>');
		
		$.fancybox.update();
		$.fancybox.reposition();
		
	}
	
	$( '#<?= $unique_hash_id; ?> .contact-phones .add-phone' ).on( 'click', function( e ){
		
		e.preventDefault();
		addPhone();
		
	});
	
	$( document ).on( 'click', '#<?= $unique_hash_id; ?> .contact-phones .remove-phone', function( e ){
		
		var jthis = $( this );
		
		e.preventDefault();
		jthis.parent().remove();
		savePhones();
		
	});
	
	function savePhones(){
		
		var phones = {};
		
		$( '#<?= $unique_hash_id; ?> .contact-phone' ).each( function( index ) {
			
			var jthis = $( this );
			
			phones[ index + 1 ] = {
				
				key: index + 1,
				title: jthis.find( '.title' ).text(),
				area_code: jthis.find( '.area-code' ).text(),
				number: jthis.find( '.number' ).text(),
				extension_number: jthis.find( '.extension-number' ).text(),
				
			}
			
		});
		
		$( '#lu-contact-phones-<?= $unique_hash_id; ?>' ).val( JSON.stringify( phones ) ).trigger( 'change' );
		console.log( JSON.stringify( phones ) )
		
	}
	
	$( ".contact-phones" ).on( "sortout", function( event, ui ){
		
		reorderItems( $(this), '.key' );
		savePhones();
		
	});
	
	$( ".contact-phones" ).on( "sortout", function( event, ui ){
		
		reorderItems( $(this), '.key' );
		savePhones();
		
	});
	
	/********************* Phones ********************/
	/*************************************************/
	
	/*************************************************/
	/******************** Addresses ******************/
	
	function addAddress(){
		
		var count = $( '#<?= $unique_hash_id; ?> .contact-address' ).length;
		
		var newAddress = $( '<div class="item sortable-item dynamic-field-item contact-address-wrapper"></div>' ).appendTo( '#<?= $unique_hash_id; ?> .contact-addresses' );
		newAddress.append('<div class="contact-address"></div>');
		newAddress.children().append('<span class="item sortable-handle"></span>');
		newAddress.children().append('<span class="item remove remove-address" title="<?= lang( 'remove_address' ); ?>"><?= lang( 'remove_address' ); ?></span>');
		newAddress.children().append('<span class="item key">' + ( count + 1 ) + '</span>');
		newAddress.children().append('<span class="item title inline-edit" data-inline-edit-callback-function="saveAddresses" title="<?= lang( 'new_address' ); ?>"><?= lang( 'new_address' ); ?></span>');
		newAddress.children().append('<span class="item neighborhood-title inline-edit" data-inline-edit-callback-function="saveAddresses" title="<?= lang( 'neighborhood_title' ); ?>"><?= lang( 'neighborhood_title' ); ?></span>');
		newAddress.children().append('<span class="item public-area-title inline-edit" data-inline-edit-callback-function="saveAddresses" title="<?= lang( 'public_area_title' ); ?>"><?= lang( 'public_area_title' ); ?></span>');
		newAddress.children().append('<span class="item number inline-edit" data-inline-edit-callback-function="saveAddresses" title="<?= lang( 'number' ); ?>"><?= lang( 'number' ); ?></span>');
		newAddress.children().append('<span class="item complement inline-edit" data-inline-edit-callback-function="saveAddresses" title="<?= lang( 'complement' ); ?>"><?= lang( 'complement' ); ?></span>');
		newAddress.children().append('<span class="item city-title inline-edit" data-inline-edit-callback-function="saveAddresses" title="<?= lang( 'city_title' ); ?>"><?= lang( 'city_title' ); ?></span>');
		newAddress.children().append('<span class="item state-acronym inline-edit" data-inline-edit-callback-function="saveAddresses" title="<?= lang( 'state_acronym' ); ?>"><?= lang( 'state_acronym' ); ?></span>');
		newAddress.children().append('<span class="item postal-code inline-edit" data-inline-edit-callback-function="saveAddresses" title="<?= lang( 'postal_code' ); ?>"><?= lang( 'postal_code' ); ?></span>');
		newAddress.children().append('<span class="item country-title inline-edit" data-inline-edit-callback-function="saveAddresses" title="<?= lang( 'country_title' ); ?>"><?= lang( 'country_title' ); ?></span>');
		
		$.fancybox.update();
		$.fancybox.reposition();
		
	}
	
	$( '#<?= $unique_hash_id; ?> .contact-addresses .add-address' ).on( 'click', function( e ){
		
		e.preventDefault();
		addAddress();
		
	});
	
	$( document ).on( 'click', '#<?= $unique_hash_id; ?> .contact-addresses .remove-address', function( e ){
		
		var jthis = $( this );
		
		e.preventDefault();
		jthis.parent().remove();
		saveAddresses();
		
	});
	
	function saveAddresses(){
		
		var addresses = {};
		
		$( '#<?= $unique_hash_id; ?> .contact-address' ).each( function( index ) {
			
			var jthis = $( this );
			
			addresses[ index + 1 ] = {
				
				key: index + 1,
				title: jthis.find( '.title' ).text(),
				neighborhood_title: jthis.find( '.neighborhood-title' ).text(),
				public_area_title: jthis.find( '.public-area-title' ).text(),
				number: jthis.find( '.number' ).text(),
				complement: jthis.find( '.complement' ).text(),
				city_title: jthis.find( '.city-title' ).text(),
				state_acronym: jthis.find( '.state-acronym' ).text(),
				postal_code: jthis.find( '.postal-code' ).text(),
				country_title: jthis.find( '.country-title' ).text(),
				
			}
			
		});
		
		$( '#lu-contact-addresses-<?= $unique_hash_id; ?>' ).val( JSON.stringify( addresses ) ).trigger( 'change' );
		console.log( JSON.stringify( addresses ) )
		
	}
	
	$( ".contact-addresses" ).on( "sortout", function( event, ui ){
		
		reorderItems( $(this), '.key' );
		saveAddresses();
		
	});
	
	$( ".contact-addresses" ).on( "sortout", function( event, ui ){
		
		reorderItems( $(this), '.key' );
		saveAddresses();
		
	});
	
	/******************** Addresses ******************/
	/*************************************************/
	
	if ( typeof window.unique_hash_id == 'undefined' ){
		
		window.unique_hash_id = '<?= $unique_hash_id; ?>';
		
	}
	
	$( '#<?= $unique_hash_id; ?> .contact-image-thumb' ).after( '<?= vui_el_button( array( 'attr' => 'data-rf-relative-url="1" data-rf-callback-function-on-choose="rfCallbackFunctionOnChoose" data-rf-callback-function-on-click="rfCallbackFunctionOnClick" data-rfdir="' . trim( $contact_image_path, DS ) . '" data-rf-container="#' . $unique_hash_id . ' .dynamic-content" data-rffieldid="lu-contact-image-' . $unique_hash_id . '" data-rftype="image"', 'url' => '#', 'text' => lang( 'select_image' ), 'get_url' => FALSE, 'id' => 'image-picker', 'icon' => 'more', 'only_icon' => TRUE, 'class' => 'modal-file-picker', ) ); ?>' );
	
	runRFFilePicker();
	
	function rfCallbackFunctionOnClick(){
		
		$( '#<?= $unique_hash_id; ?> .contact-form' ).hide();
		
	}
	
	function rfCallbackFunctionOnChoose(){
		
		window.modalFancybox.height = '100%';
		window.modalFancybox.autoHeight = false;
		window.modalFancybox.autoResize = false;
		window.modalFancybox.autoSize = false;
		$.fancybox.update();
		$.fancybox.reposition();
		console.log(window.modalFancybox)
		console.log($.fancybox)
		$.imageCrop.open({
			
			imgSrc: $( '#lu-contact-image-<?= $unique_hash_id; ?>' ).val(),
			callback: updateImage
			
		});
		
	}
	window.updateImage = function(){
		
		updateContactImage();
		
		$( '#<?= $unique_hash_id; ?> .contact-form' ).show();
		$( '#<?= $unique_hash_id; ?> .dynamic-content' ).empty();
		
		$.fancybox.update();
		$.fancybox.reposition();
		
	}
	
	function updateContactImage(){
		
		var url = $( '#lu-contact-image-<?= $unique_hash_id; ?>' ).val();
		var thumbUrl = 'thumbs/' + url + '?' + Math.floor( ( Math.random() * 100 ) + 1 );
		
		$( '#lu-contact-image-thumb-<?= $unique_hash_id; ?>' ).val( thumbUrl );
		$( '#<?= $unique_hash_id; ?> .contact-image-thumb' ).attr( 'src', thumbUrl );
		
		$( '#lu-contact-image-<?= $unique_hash_id; ?>' ).trigger( 'change' );
		$( '#lu-contact-image-thumb-<?= $unique_hash_id; ?>' ).trigger( 'change' );
		
	}
	
	function saveContactImage(){
		
		var luUri = jthis.data( 'lu-url' );
		var url = $( '#lu-contact-image-<?= $unique_hash_id; ?>' ).val();
		var thumbUrl = 'thumbs/' + url;
		var luField = jthis.data( 'lu-field' );
		var luValue = jthis.val();
		
		var data = {
			
			condition:{
				
				id: '<?= $contact[ 'id' ]; ?>'
				
			},
			update_data:{},
			ajax: true
			
		}
		
		data[ 'update_data' ][ luField ] = luValue;
		
		luSave( luUri, data );
		
	}
	
	function luSave( luUri, data ){
		
		$.ajax({
			
			type: "POST",
			cache: false,
			url: luUri,
			data: {
				
				lu: data,
				ajax: true
				
			},
			success: function ( data ) {
				
				console.log( data );
				
				$.fancybox.update();
				$.fancybox.reposition();
				
			},
			dataType: "json"
			
		});
		
	}
	
	$( '.live-update', '#<?= $unique_hash_id; ?>' ).on( "change", function ( e ) {
		
		jthis = $( this );
		
		jthis.trigger( 'blur' );
		e.preventDefault();
		
		var luUri = jthis.data( 'lu-url' );
		var luField = jthis.data( 'lu-field' );
		var luValue = jthis.val();
		
		var data = {
			
			condition:{
				
				id: '<?= $contact[ 'id' ]; ?>'
				
			},
			update_data:{},
			ajax: true
			
		}
		
		data[ 'update_data' ][ luField ] = luValue;
		
		luSave( luUri, data );
		
	});
	
	$( '.live-update', '#<?= $unique_hash_id; ?>' ).on( "keyup", function ( e ) {
		
		jthis = $( this );
		
		// prevenindo que o formulário seja submetido
		if ( e.keyCode == 13 ) {
			
			jthis.trigger( 'blur' );
			e.preventDefault();
			
			jthis.trigger( 'change' );
			
		}
		
	});
	
</script>
