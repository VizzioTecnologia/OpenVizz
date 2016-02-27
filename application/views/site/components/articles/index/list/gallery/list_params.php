<?php

	foreach ( $layout_params[ 'params_spec' ][ 'layout_specific_params' ] as $key => $element ) {
		
		if ( $element[ 'name' ] == 'articles_per_category_order_by' ){
			
			$spec_options = array();
			
			if ( isset( $layout_params[ 'params_spec' ][ 'layout_specific_params' ][ $key ][ 'options' ] ) )
				$spec_options = $layout_params[ 'params_spec' ][ 'layout_specific_params' ][ $key ][ 'options' ];
			
			$layout_params[ 'params_spec' ][ 'layout_specific_params' ][ $key ][ 'options' ] = is_array( $spec_options ) ? $spec_options + $order_by_options : $order_by_options;
			
			break;
			
		}
		
	}
	
?>