<?php	if ( ! defined( 'BASEPATH' ) ) exit( 'No direct script access allowed' );

function scandir_to_array( $dir , $subdir = 0 ){
	
	if ( !is_dir( $dir ) ) { return false; }
	
	
	$result = array();
	$scan = scandir( $dir );

	foreach( $scan as $key => $val ) {
		
		if ( $val[0] == "." ) { continue; }

		if ( is_dir( $dir . "/" . $val ) ) {
			
			$result = $result + array( $dir . DS . $val => str_repeat( "--", $subdir ) . $val );

			if ( $val[0] !="." ) {
				$result = $result + scandir_to_array( $dir . DS . $val , $subdir + 1 );
			}
			
		}
		
	}

	return $result;
	
}

function dir_to_array( $dir, $separator = DS, $paths = 'relative' ){
	
	$result = array();
	$cdir = scandir( $dir );
	foreach ( $cdir as $key => $value ){
		if ( !in_array( $value, array( ".", ".." ) ) ){
			if ( is_dir( $dir . $separator . $value ) ){
				$result[$value] = dir_to_array( $dir . $separator . $value, $separator, $paths );
			}
			else{
				if ( $paths == 'relative' ){
					$result[] = $dir . '/' . $value;
				}
				elseif ( $paths == 'absolute' ){
					$result[] = base_url() . $dir . '/' . $value;
				}
			}
		}
	}
	return $result;
	
}

function dir_list_to_array( $dir, $separator = DS, $paths = 'relative' ){
	
	$result = array();
	
	// verificando se o diretório existe
	if ( file_exists( $dir ) AND is_dir( $dir ) ){
		
		$cdir = scandir( $dir );
		
		foreach ( $cdir as $key => $value ){
			
			if ( ! in_array( $value, array( ".", ".." ) ) ){
				
				if ( is_dir( $dir . $separator . $value ) ){
					
					if ( $paths == 'relative' ){
						
						$result[$value] = lang( $value );
						
					}
					
				}
				
			}
			
		}
		
	}
	
	return $result;
	
}

function is_dir_empty( $dir ) {
	
	if ( ! is_readable( $dir ) ) return NULL;
	
	$handle = opendir( $dir );
	
	while ( false !== ( $entry = readdir( $handle ) ) ) {
		
		if ( $entry != "." && $entry != ".." ) {
			
			return FALSE;
			
		}
		
	}
	
	return TRUE;
	
}

function file_list_to_array( $dir, $matche_pattern ){
	
	$result = array();
	
	// verificando se o diretório existe
	if ( file_exists( $dir ) AND is_dir( $dir ) ){
		
		foreach ( glob( rtrim( $dir, DS ) . DS . $matche_pattern ) as $file ) {
			
			$result[ basename( $file ) ] = basename( $file );
			
		}
		
	}
	
	return $result;
	
}

function rrmdir( $dir ) {
	
	if ( is_dir( $dir ) AND $dir != '/' ) {
		
		$objects = scandir( $dir );
		
		foreach ( $objects as $object ) {
			
			if ( $object != "." AND $object != ".." ) {
				
				if ( filetype( $dir . "/" . $object ) == "dir" ) {
					
					rrmdir( $dir . "/" . $object );
					
				}
				else {
					
					unlink( $dir . "/" . $object );
				}
				
			}
			
		}
		
		reset( $objects );
		rmdir( $dir );
		
	}
	
}

function mkpath( $path ) {
	/*
	$dir = pathinfo( $path , PATHINFO_DIRNAME );
	
	if ( is_dir( $dir ) ) {
		
		return TRUE;
		
	}
	else {
		
		if ( mkpath( $dir ) ) {
			if( mkdir( $dir ) ) {
				
				$current_umask = umask(0);
				
				$perm = 777 - $current_umask;
				
				chmod( $dir , $perm );
				return TRUE;
			}
		}
	}
	
	return FALSE;
	*/
	$dirs = explode( DS, $path );
	$dir='';
	foreach ( $dirs as $part ) {
		
		$dir .= $part . DS;
		
		if ( ! is_dir( $dir ) && strlen( $dir ) > 0 ) {
			
			$current_umask = umask();
			
			$perm = 777 - $current_umask;
			
			@mkdir( $dir );
			
			chmod( trim( $dir, DS ) , $perm );
			
		}
		
	}
	
	return TRUE;
	
}


/* End of file VECMS_directory_helper.php */
/* Location: ./application/helpers/VECMS_directory_helper.php */
