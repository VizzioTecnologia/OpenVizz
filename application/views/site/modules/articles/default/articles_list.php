<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
	
	$use_introtext = FALSE;
	$use_fulltext = FALSE;
	$use_readmore = FALSE;
	$orig_text = $final_text = '';
	
	if ( $article[ 'params' ][ 'show_introtext_on_list_view' ] AND $article[ 'introtext' ] ) {
		
		$use_introtext = TRUE;
		$use_readmore = TRUE;
		
		$orig_text .= $article[ 'introtext' ];
		
	}
	
	if ( $article[ 'params' ][ 'show_full_text_on_list_view' ] AND $article[ 'fulltext' ] ) {
		
		// if auto
		if ( ( $article[ 'params' ][ 'show_full_text_on_list_view' ] == 1 ) OR ( $article[ 'params' ][ 'show_full_text_on_list_view' ] == 2 AND ! $use_introtext ) ) {
			
			$use_fulltext = TRUE;
			$use_readmore = FALSE;
			
		}
		
		if ( $use_fulltext ) {
			
			$orig_text .= ' ' . $article[ 'fulltext' ];
			
		}
		
	}
	
	$final_text = $orig_text;
	
	if ( $parsed_params[ 'keep_html_content' ] ) {
		
		$final_text = html_entity_decode( $final_text );
		
	}
	else {
		
		$final_text = strip_tags( html_entity_decode( $final_text ) );
		
	}
	
	if ( check_var( $parsed_params[ 'use_word_limiter' ] ) AND $parsed_params[ 'word_limiter' ] > 0 ) {
		
		$final_text = word_limiter( $final_text, $parsed_params[ 'word_limiter' ], $parsed_params[ 'word_limiter_str' ] );
		
	}
	
	// Se o texto original for diferente do texto final, quer dizer que o
	// texto foi cortado pela função word_limit(), logo ativamos o readmore
	if ( $orig_text !== $final_text ) {
		
		$use_readmore = TRUE;
		
	}
	
	require( 'article_item.php' );
	
?>