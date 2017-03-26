<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function unmask( $val, $mask_type, $mask = '' ) {
	
	$maskared = '';
	$k = 0;
	
	if ( $mask_type == 'money' ) {
		
		$maskared = str_replace( ',', '.', preg_replace( "/([^0-9\\,])/i", "", $val ) );
		
	}
	else if ( $mask_type == 'custom_mask' ) {
		
		$mask = str_replace( '9', '#', $mask );
		
		for ( $i = 0; $i <= strlen( $mask ) - 1; $i++ ) {
			
			if ( $mask[ $i ] == '#' ) {
				
				if ( isset( $val[ $i ] ) ) $maskared .= $val[ $i ];
				
			}
			
		}
		
	}
	else if ( $mask_type == 'zip_brazil' ) {
		
		$maskared = str_replace( ',', '.', preg_replace( "/([^0-9])/i", "", $val ) );
		
	}
	else {
		
		$maskared = $val;
		
	}
	
	return $maskared;
	
}

function remove_accents($str) {
	
	$from = array(
		"á", "à", "â", "ã", "ä", "é", "è", "ê", "ë", "í", "ì", "î", "ï",
		"ó", "ò", "ô", "õ", "ö", "ú", "ù", "û", "ü", "ç", "Á", "À", "Â",
		"Ã", "Ä", "É", "È", "Ê", "Ë", "Í", "Ì", "Î", "Ï", "Ó", "Ò", "Ô",
		"Õ", "Ö", "Ú", "Ù", "Û", "Ü", "Ç"
	);
	
	$to = array(
		"a", "a", "a", "a", "a", "e", "e", "e", "e", "i", "i", "i", "i",
		"o", "o", "o", "o", "o", "u", "u", "u", "u", "c", "A", "A", "A",
		"A", "A", "E", "E", "E", "E", "I", "I", "I", "I", "O", "O", "O",
		"O", "O", "U", "U", "U", "U", "C"
	);
	
	return	str_replace( $from, $to, $str );
	
}

// ------------------------------------------------------------------------

/**
 * generate a random string
 *
 * @access	public
 * @param	int
 * @return	string
 */
function rand_str( $length = 10 ) {
	
	$characters = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	$charactersLength = strlen( $characters );
	$string = '';
	
	for ($i = 0; $i < $length; $i++) {
		
		$string .= $characters[ rand( 0, $charactersLength - 1 ) ];
		
	}
	
	return $string;
	
}

// ------------------------------------------------------------------------

/**
 * Adjust people name
 *
 * Normaliza nomes de pessoas
 *
 * @access	public
 * @param	string
 * @return	string
 */
function adjust_people_name( $name ){

	$apn = new Str_Utils();

	return $apn->normalizarNome( $name );

}

// ------------------------------------------------------------------------


/**
 * Perform a simple text replace
 * This should be used when the string does not contain HTML
 * (off by default)
 */
define('STR_HIGHLIGHT_SIMPLE', 1);

/**
 * Only match whole words in the string
 * (off by default)
 */
define('STR_HIGHLIGHT_WHOLEWD', 2);

/**
 * Case sensitive matching
 * (off by default)
 */
define('STR_HIGHLIGHT_CASESENS', 0);

/**
 * Overwrite links if matched
 * This should be used when the replacement string is a link
 * (off by default)
 */
define('STR_HIGHLIGHT_STRIPLINKS', 8);

/**
 * Highlight a string in text without corrupting HTML tags
 *
 * @author	  Aidan Lister <aidan@php.net>
 * @version	 3.1.1
 * @link		http://aidanlister.com/2004/04/highlighting-a-search-string-in-html-text/
 * @param	   string		  $text		   Haystack - The text to search
 * @param	   array|string	$needle		 Needle - The string to highlight
 * @param	   bool			$options		Bitwise set of options
 * @param	   array		   $highlight	  Replacement string
 * @return	  Text with needle highlighted
 */

function str_highlight( $text, $needle, $options = NULL, $highlight = NULL ){

	// Default highlighting
	if ($highlight === NULL) {
		$highlight = '<span class="search-term-highlight">\1</span>';
	}

	// Select pattern to use
	if ($options & STR_HIGHLIGHT_SIMPLE) {

		$pattern = '#(%s)#';
		$sl_pattern = '#(%s)#';

	} else {

		$pattern = '#(?!<.*?)(%s)(?![^<>]*?>)#';
		$sl_pattern = '#<a\s(?:.*?)>(%s)</a>#';

	}

	// Case sensitivity
	if (!($options & STR_HIGHLIGHT_CASESENS)) {
		$pattern .= 'i';
		$sl_pattern .= 'i';
	}

	$needle = (array) $needle;
	foreach ($needle as $needle_s) {
		$needle_s = preg_quote($needle_s);

		// Escape needle with optional whole word check
		if ($options & STR_HIGHLIGHT_WHOLEWD) {
			$needle_s = '\b' . $needle_s . '\b';
		}

		// Strip links
		if ($options & STR_HIGHLIGHT_STRIPLINKS) {
			$sl_regex = sprintf($sl_pattern, $needle_s);
			$text = preg_replace($sl_regex, '\1', $text);
		}

		$regex = sprintf($pattern, $needle_s);
		$text = preg_replace($regex, $highlight, $text);
	}

	return $text;

}

// ------------------------------------------------------------------------

/* End of file viacms_string_helper.php */
/* Location: ./application/helpers/viacms_string_helper.php */
