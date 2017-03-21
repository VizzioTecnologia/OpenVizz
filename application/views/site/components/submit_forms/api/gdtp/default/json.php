<?php
	
	header( 'Content-Type: application/json' );
	
	$out = '';
	
	$out = $data_schemes;
	
	$out = @json_encode ( $out );
	//$out = mb_convert_encoding( $out,'UTF-8','UTF-8' );
	//echo '<pre>' . print_r( $out, TRUE ) . '</pre>'; exit;
	echo $out;
	
?>


