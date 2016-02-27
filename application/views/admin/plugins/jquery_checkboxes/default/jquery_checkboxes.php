<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<script type="text/javascript">
	
	//<![CDATA[
	
	$( document ).on( 'click', '#select-all-items', function( e ){
		
		var jthis = $( this );
		var table = $( e.target ).closest( '.multi-selection-table' );
		
		$( 'td input:checkbox', table ).prop( 'checked', this.checked );
		
		
	});
	$( document ).on( 'click', 'input:checkbox', function( e ){
		
		var jthis = $( this );
		var table = $( e.target ).closest( '.multi-selection-table' );
		var row = $( e.target ).closest( 'tr' );
		
		checkMultiSelectionActionInputs();
		
		$( '.last-checked' ).removeClass( 'last-checked' );
		
		if ( this.checked ){
			
			jthis.addClass( 'last-checked' );
			
		}
		else {
			
		}
		
	});
	
	function checkMultiSelectionActionInputs(){
		
		var inputsCheckedCount = $( '.multi-selection-action:checked' ).length;
		
		if ( inputsCheckedCount > 0 ){
			
			$( '.multi-selection-action-input' ).removeClass( 'no-multi-selection-action' );
			$( '.multi-selection-action-input' ).addClass( 'has-multi-selection-action' );
			$( '.multi-selection-action-input' ).attr( 'disabled', false );
			
		}
		else {
			
			$( '.multi-selection-action-input' ).removeClass( 'has-multi-selection-action' );
			$( '.multi-selection-action-input' ).addClass( 'no-multi-selection-action' );
			$( '.multi-selection-action-input' ).attr( 'disabled', true );
			
		}
		
		$( '.multi-selection-table input:checkbox' ).closest( 'tr' ).removeClass( 'selected' );
		$( '.multi-selection-table input:checkbox:checked' ).closest( 'tr' ).addClass( 'selected' );
		
	}
	
	$( document ).on( 'ready', function( e ){
		
		checkMultiSelectionActionInputs();
		
		$( ".multi-selection-table" ).checkboxes( 'range', true )
		
	});
	
	//]]>
	
</script>
