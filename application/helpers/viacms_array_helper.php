<?php	if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

// ------------------------------------------------------------------------

/**
 *
 * Intersect two multidimensional arrays by its keys
 *
 * @author deceze
 * @link http://stackoverflow.com/questions/12171855/multidimensional-associative-array-intersection-php#answer-12173705
 *
 */
 
function recursive_array_intersect_key( array $array1, array $array2 ) {
	
	$array1 = array_intersect_key( $array1, $array2 );
	
	while ( list( $key, $value ) = each( $array1 ) ) {
		
		if ( is_array( $value ) ) {
			
			$value = recursive_array_intersect_key( $value, $array2[ $key ] );
			
		}
		
	}
	/*
	foreach ( $array1 as $key => &$value ) {
		
		if ( is_array( $value ) ) {
			
			$value = recursive_array_intersect_key( $value, $array2[ $key ] );
			
		}
		
	}
	*/
	return $array1;
	
}

// ------------------------------------------------------------------------

/**
 *
 * Convert multidimensional array into single array
 *
 * @author AlienWebguy
 * @link http://stackoverflow.com/questions/6785355/convert-multidimensional-array-into-single-array
 *
 */
 
function array_flatten( $array ){
	
	if ( ! is_array( $array ) ){
		
		return FALSE;
		
	}
	
	$result = array();
	
	while ( list( $key, $value ) = each( $array ) ) {
		
		if ( is_array( $value ) ){
			
			$result = array_merge( $result, array_flatten( $value ) );
			
		}
		else{
			
			$result[ $key ] = $value;
			
		}
		
	}
	
	/*
	foreach ( $array as $key => $value ){
		
		//echo 'analisando ' . $key . ' => ' . $value . "\n";
		
		if ( is_array( $value ) ){
			
			$result = array_merge( $result, array_flatten( $value ) );
			
		}
		else{
			
			$result[ $key ] = $value;
			
		}
		
	}*/
	
	return $result;

}

// ------------------------------------------------------------------------

/**
 *
 * Function array_search_recursive ( mixed needle, array haystack [ , bool strict[ , array path ] ] )
 *
 * Searches haystack for needle and returns an array of the key path if it is found in the ( multidimensional ) array, FALSE otherwise.
 *
 * @param needle mixed
 *
 * The searched value.
 * If needle is a string, the comparison is done in a case-sensitive manner.
 *
 * @param haystack array
 *
 * The array.
 *
 * @param strict bool[optional]
 *
 * If the third parameter strict is set to true then the array_search function will search for identical elements in the
 * haystack. This means it will also check the types of the needle in the haystack, and objects must be the same
 * instance.
 *
 * If needle is found in haystack more than once, the first matching key is returned. To return the keys for all
 * matching values, use array_keys with the optional search_value parameter instead.
 *
 * @return mixed the key for needle if it is found in the array, false otherwise.
 *
 */

function array_search_recursive( $needle, $haystack, $strict = FALSE, $path = array() ){

	if ( ! is_array( $haystack ) ) {
		
		return FALSE;
		
	}
	
	while ( list( $key, $val ) = each( $haystack ) ) {
		
		if( is_array( $val ) AND $sub_path = array_search_recursive( $needle, $val, $strict, $path ) ) {
			
			$path = array_merge( $path, array( $key ), $sub_path );
			
			return $path;
			
		}
		else if( ( ! $strict AND $val == $needle ) || ( $strict AND $val === $needle ) ) {
			
			$path[] = $key;
			
			return $path;
			
		}
		
	}
	
	/*
	foreach( $haystack as $key => $val ) {
		
		if( is_array( $val ) AND $sub_path = array_search_recursive( $needle, $val, $strict, $path ) ) {
			
			$path = array_merge( $path, array( $key ), $sub_path );
			
			return $path;
			
		}
		else if( ( ! $strict AND $val == $needle ) || ( $strict AND $val === $needle ) ) {
			
			$path[] = $key;
			
			return $path;
			
		}
		
	}*/
	
	return FALSE;

}

// ------------------------------------------------------------------------

/**
 *
 * Function array_clean_empty_values ( array array )
 *
 * Remove empty (strings) keys from arrays, including multidimensional arrays
 *
 * @param array array
 *
 * The array.
 *
 * @return mixed return the array changed, false otherwise.
 *
 */

function array_clean_empty_values( & $array ) {
	
	if( is_array( $array ) ) {
		
		reset( $array );
		
		while ( list( $k, $v ) = each( $array ) ) {
			
			if ( $v == '' ) {
				
				unset( $array[ $k ] );
				
			}
			else if ( is_array( $v ) ) {
				
				$_tmp = array_clean_empty_values( $v );
				
				empty( $_tmp );
				
				if ( empty( $v ) ) {
					
					unset( $array[ $k ] );
					
				}
				
			}
			
		}
		
		/*
		foreach ( $array as $k => & $v ) {
			
			if ( $v == '' ) {
				
				unset( $array[ $k ] );
				
			}
			else if ( is_array( $v ) ) {
				
				$_tmp = array_clean_empty_values( $v );
				
				empty( $_tmp );
				
				if ( empty( $v ) ) {
					
					unset( $array[ $k ] );
					
				}
				
			}
			
		}
		*/
	}
	
	return FALSE;
	
}

// ------------------------------------------------------------------------

function array_find( $needle, $haystack ) {
	
	while ( list( $key, $item ) = each( $haystack ) ) {
		
		if ( strpos( strtolower( $item ), strtolower( $needle ) ) !== FALSE ) {
			
			return TRUE;
			
		}
		
	}
	
	/*
	foreach ( $haystack as $item ) {
		
		if ( strpos( strtolower( $item ), strtolower( $needle ) ) !== FALSE ) {
			
			return TRUE;
			
		}
		
	}
	*/
}

// ------------------------------------------------------------------------

/**
 *
 * @param array $array1
 * @param array $array2
 * @return array
 * @author Daniel <daniel@danielsmedegaardbuus.dk>
 * @author Gabriel Sobrinho <gabriel.sobrinho@gmail.com>
 * @link http://php.net/manual/pt_BR/function.array-merge-recursive.php#92195
 *
 */
function array_merge_recursive_distinct ( & $array1 = NULL, & $array2 = NULL ){
	
	if ( is_array( $array1 ) AND is_array( $array2 ) ) {
		
		$merged = $array1;
		
		reset( $array2 );
		
		while ( list( $key, $value ) = each( $array2 ) ) {
			
			if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) ) {
				
				$merged [$key] = array_merge_recursive_distinct ( $merged [$key], $value );
				
			}
			else {
				
				$merged [$key] = $value;
				
			}
			
		}
		
		/*
		foreach ( $array2 as $key => & $value ) {
			
			if ( is_array ( $value ) && isset ( $merged [$key] ) && is_array ( $merged [$key] ) ) {
				
				$merged [$key] = array_merge_recursive_distinct ( $merged [$key], $value );
				
			}
			else {
				
				$merged [$key] = $value;
				
			}
			
		}
		*/
		return $merged;
		
	}
	else if ( is_array( $array1 ) ) {
		
		return $array1;
		
	}
	else if ( is_array( $array2 ) ) {
		
		return $array2;
		
	}
	else {
		
		return array();
		
	}
	
}

// ------------------------------------------------------------------------

/**
 *
 * Function array_push_pos ( array original_array, array array_to_insert, int pos )
 *
 * Insert a array preserving keys names
 *
 * @param array $original_array
 * @param array $array_to_insert
 * @param int $pos
 * @author Frank Souza <franksouza183@gmail.com>
 *
 */
function array_push_pos ( & $original_array, $array_to_insert, $pos ) {
	
	$array_tmp = array_splice ( $original_array, 0, $pos );
	$original_array = array_merge ( $array_tmp, $array_to_insert, $original_array );
	
}

function multi_sort() { 
    //get args of the function 
    $args = func_get_args(); 
    $c = count($args); 
    if ($c < 2) { 
        return false; 
    } 
    //get the array to sort 
    $array = array_splice($args, 0, 1);
    
    $array = $array[0];
    
    //sort with an anoymous function using args 
    usort( $array, function( $a, $b ) use( $args ) { 

        $i = 0; 
        $c = count($args); 
        $cmp = 0; 
        while($cmp == 0 && $i < $c) 
        { 
            $cmp = strcmp($a[ $args[ $i ] ], $b[ $args[ $i ] ]); 
            $i++; 
        } 

        return $cmp; 

    }); 

    return $array; 

}



/* End of file VECMS_array_helper.php */
/* Location: ./application/helpers/VECMS_array_helper.php */
