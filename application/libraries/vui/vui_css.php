<?php

use \Exception;

class Vui_css extends Vui{
	
	var $minify = TRUE;
	
	function __construct(){
		
		if ( ! defined ( 'DS' ) ) define( 'DS', DIRECTORY_SEPARATOR );
		
	}
	
	public function __get( $name ) {
		
		if ( isset ( $this->$name ) ) {
			
			return $this->$name;
			
		}
		return NULL;
		
	}
	
	function gradient( $start_color = '#000', $end_color = '#fff', $direction = 'btt', $prefix = '', $suffix = '' ) {
		
		if ( $direction === 'ttb' OR $direction === 'ltr' ){
			
			$old_start_color = $start_color;
			$start_color = $end_color;
			$end_color = $old_start_color;
			
		}
		
		$css = '';
		
		if ( $direction === 'btt' OR $direction === 'ttb' ){
			
			$css .= "background-image: {$prefix}-moz-linear-gradient(top, " . $start_color . " 0%, " . $end_color . " 100%){$suffix};\n";
			$css .= "background-image: {$prefix}-webkit-gradient(linear, left top, left bottom, color-stop(0%," . $start_color . "), color-stop(100%," . $end_color . ")){$suffix};\n";
			$css .= "background-image: {$prefix}-webkit-linear-gradient(top, " . $start_color . " 0%," . $end_color . " 100%){$suffix};\n";
			$css .= "background-image: {$prefix}-o-linear-gradient(top, " . $start_color . " 0%," . $end_color . " 100%); /* Opera 11.10+ */{$suffix};\n";
			$css .= "background-image: {$prefix}-ms-linear-gradient(top, " . $start_color . " 0%," . $end_color . " 100%){$suffix};\n";
			$css .= "background-image: {$prefix}linear-gradient(to bottom, " . $start_color . " 0%," . $end_color . " 100%){$suffix};\n";
			$css .= "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='" . $start_color . "', endColorstr='" . $end_color . "',GradientType=0 );\n";

			
		}
		else if ( $direction === 'ltr' OR $direction === 'rtl' ){
			
			$css .= "background-image: {$prefix}-moz-linear-gradient(left, " . $start_color . " 0%, " . $end_color . " 100%); /* FF3.6+ */{$suffix}\n";
			$css .= "background-image: {$prefix}-webkit-gradient(linear, left top, right top, color-stop(0%," . $start_color . "), color-stop(100%," . $end_color . ")); /* Chrome,Safari4+ */{$suffix}\n";
			$css .= "background-image: {$prefix}-webkit-linear-gradient(left, " . $start_color . " 0%," . $end_color . " 100%); /* Chrome10+,Safari5.1+ */{$suffix}\n";
			$css .= "background-image: {$prefix}-o-linear-gradient(left, " . $start_color . " 0%," . $end_color . " 100%); /* Opera 11.10+ */{$suffix}\n";
			$css .= "background-image: {$prefix}-ms-linear-gradient(left, " . $start_color . " 0%," . $end_color . " 100%); /* IE10+ */{$suffix}\n";
			$css .= "background-image: {$prefix}linear-gradient(to right, " . $start_color . " 0%," . $end_color . " 100%); /* W3C */{$suffix}\n";
			$css .= "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='" . $start_color . "', endColorstr='" . $end_color . "',GradientType=1 ); /* IE6-9 */\n";
			
		}
		
		// Return our CSS
		return $this->_minify( $css );
		
	}
	function transition( $value = 'all 0.2s ease-in-out' ) {
		
		$css = '';
		
		$css .= "-webkit-transition: $value;";
		$css .= "-moz-transition: $value;";
		$css .= "-o-transition: $value;";
		$css .= "-ms-transition: $value;";
		$css .= "transition: $value;";
		
		// Return our CSS
		return $this->_minify( $css );
		
	}
	
	function word_break() {
		
		$css = '';
		
		$css .= "-ms-word-break: break-all;";
		$css .= "word-break: break-word;";
		$css .= "word-wrap: break-word;";
		$css .= "-webkit-hyphens: auto;";
		$css .= "-moz-hyphens: auto;";
		$css .= "hyphens: auto;";
		
		// Return our CSS
		return $this->_minify( $css );
		
	}
	
	function placeholder( $styles, $prefix = '' ) {
		
		$css = '';
		
		if ( is_string( $prefix ) ) {
			
			$_tmp_str = explode( ',', $prefix );
			
			if ( count( $_tmp_str ) > 1 ) {
				
				$prefixes = $_tmp_str;
				
			}
			else {
				
				$prefixes = array(
					
					$prefix,
					
				);
				
			}
			
		}
		else {
			
			$prefixes = $prefix;
			
		}
		
		if ( is_array( $prefixes ) ) {
			
			$_selectors = array(
				
				'::-webkit-input-placeholder',
				':-moz-placeholder',
				'::-moz-placeholder',
				':-ms-input-placeholder',
				
			);
			
			for( $i = 0; $i <= 3; $i++ ) {
				
				$_current_selector = array();
				
				foreach( $prefixes as $prefix ) {
					
					$_current_selector[] = trim( $prefix ) . $_selectors[ $i ];
					
				}
				
				$_current_selector = join( $_current_selector, ',' );
				
				$css .= "$_current_selector {";
				$css .= $styles;
				$css .= "}";
				
			}
			
			// Return our CSS
			return $this->_minify( $css );
			
		}
		
	}
	function text_shadow_stroke( $border_width, $color, $comp_text_shadow ) {
		
		$css = '';
		
		for ( $x = -$border_width; $x <= $border_width; $x++  ) {
			
			for ( $y = -$border_width; $y <= $border_width; $y++ ) {
				
				$css .= $x . 'px ' . $y . 'px 0 ' . $color . ",\n";
				$css .= $x . 'px ' . $y . 'px 0 ' . $color;
				
				if ( ! ( $x == $border_width AND $y == $border_width ) ){
					
					$css .= ",\n";
					
				}
				
			}
			
		}
		
		if ( $comp_text_shadow ) {
			
			$css .=  ',' . $comp_text_shadow;
			
		}
		
		$css = $this->text_shadow( $css );
		
		// Return our CSS
		return $this->_minify( $css );
		
	}
	function transform_style( $value = 'preserve-3d' ) {
		
		if ( $value ){
			
			$css = '';
			
			$css .= "-webkit-transform-style: $value;";
			$css .= "-moz-transform-style: $value;";
			$css .= "-o-transform-style: $value;";
			$css .= "-ms-transform-style: $value;";
			$css .= "transform-style: $value;";
			
			// Return our CSS
			return $this->_minify( $css );
			
		}
		
		return FALSE;
		
	}
	function column_count( $value = '2' ) {
		
		if ( $value ){
			
			$css = '';
			
			$css .= "-moz-column-count: $value;";
			$css .= "-webkit-column-count: $value;";
			$css .= "column-count: $value;";
			
			// Return our CSS
			return $this->_minify( $css );
			
		}
		
		return FALSE;
		
	}
	function column_gap( $value = '1px' ) {
		
		if ( $value ){
			
			$css = '';
			
			$css .= "-moz-column-gap: $value;";
			$css .= "-webkit-column-gap: $value;";
			$css .= "column-gap: $value;";
			
			// Return our CSS
			return $this->_minify( $css );
			
		}
		
		return FALSE;
		
	}
	function break_inside( $value = 'avoid' ) {
		
		if ( $value ){
			
			$css = '';
			
			$css .= "-webkit-column-break-inside: $value;";
			$css .= "page-break-inside: $value;";
			$css .= "break-inside: $value;";
			
			// Return our CSS
			return $this->_minify( $css );
			
		}
		
		return FALSE;
		
	}
	function backface_visibility( $value = 'hidden' ) {
		
		if ( $value ){
			
			$css = '';
			
			$css .= "-webkit-backface-visibility: $value;";
			$css .= "-moz-backface-visibility: $value;";
			$css .= "-o-backface-visibility: $value;";
			$css .= "-ms-backface-visibility: $value;";
			$css .= "backface-visibility: $value;";
			
			if ( $value == 'hidden' ) {
				
				$css .= "outline: 1px solid transparent;";
				
			}
			
			// Return our CSS
			return $this->_minify( $css );
			
			
		}
		
		return FALSE;
		
	}
	function transform_origin( $value = '50% 50%', $minify = TRUE ) {
		
		if ( $value ){
			
			$css = '';
			
			$css .= "-webkit-transform-origin: $value;";
			$css .= "-moz-transform-origin: $value;";
			$css .= "-o-transform-origin: $value;";
			$css .= "-ms-transform-origin: $value;";
			$css .= "transform-origin: $value;";
			
			// Return our CSS
			return $minify ? $this->_minify( $css ) : $css;
			
		}
		
		return FALSE;
		
	}
	function user_select( $value = NULL ) {
		
		if ( $value ){
			
			$css = '';
			
			$css .= "-webkit-tap-highlight-color: rgba(0,0,0,0);";
			$css .= "-webkit-tap-highlight-color: transparent;";
			$css .= "-webkit-touch-callout: $value;";
			$css .= "-webkit-user-select: $value;";
			$css .= "-khtml-user-select: $value;";
			$css .= "-moz-user-select: $value;";
			$css .= "-ms-user-select: $value;";
			$css .= "-o-user-select: $value;";
			$css .= "user-select: $value;";
			$css .= "outline-style: $value;";
			
			// Return our CSS
			return $this->_minify( $css );
			
			
		}
		
		return FALSE;
		
	}
	function transform( $value = NULL ) {
		
		if ( $value ){
			
			$css = '';
			
			$css .= "-webkit-transform: $value;";
			$css .= "-moz-transform: $value;";
			$css .= "-o-transform: $value;";
			$css .= "-ms-transform: $value;";
			$css .= "transform: $value;";
			
			// Return our CSS
			return $this->_minify( $css );
			
			
		}
		
		return FALSE;
		
	}
	function animation( $value = NULL ) {
		
		if ( $value ){
			
			$css = '';
			
			$css .= "-webkit-animation: $value;";
			$css .= "-moz-animation: $value;";
			$css .= "-o-animation: $value;";
			$css .= "-ms-animation: $value;";
			$css .= "animation: $value;";
			
			// Return our CSS
			return $this->_minify( $css );
			
			
		}
		
		return FALSE;
		
	}
	function animation_delay( $value = NULL ) {
		
		if ( $value ){
			
			$css = '';
			
			$css .= "-webkit-animation-delay: $value;";
			$css .= "-moz-animation-delay: $value;";
			$css .= "-o-animation-delay: $value;";
			$css .= "-ms-animation-delay: $value;";
			$css .= "animation-delay: $value;";
			
			// Return our CSS
			return $this->_minify( $css );
			
			
		}
		
		return FALSE;
		
	}
	function animation_timing_function( $value = NULL ) {
		
		if ( $value ){
			
			$css = '';
			
			$css .= "-webkit-animation-timing-function: $value;";
			$css .= "-moz-animation-timing-function: $value;";
			$css .= "-o-animation-timing-function: $value;";
			$css .= "-ms-animation-timing-function: $value;";
			$css .= "animation-timing-function: $value;";
			
			// Return our CSS
			return $this->_minify( $css );
			
			
		}
		
		return FALSE;
		
	}
	function animation_iteration_count( $value = NULL ) {
		
		if ( $value ){
			
			$css = '';
			
			$css .= "-webkit-animation-iteration-count: $value;";
			$css .= "-moz-animation-iteration-count: $value;";
			$css .= "-o-animation-iteration-count: $value;";
			$css .= "-ms-animation-iteration-count: $value;";
			$css .= "animation-iteration-count: $value;";
			
			// Return our CSS
			return $this->_minify( $css );
			
			
		}
		
		return FALSE;
		
	}
	function keyframes( $name = NULL, $value = NULL, $minify = TRUE ){
		
		if ( $name AND $value ){
			
			$css = '';
			
			$css .= "@-webkit-keyframes $name { $value }";
			$css .= "@-moz-keyframes $name { $value }";
			$css .= "@-o-keyframes $name { $value }";
			$css .= "@-ms-keyframes $name { $value }";
			$css .= "@keyframes $name { $value }";
			
			// Return our CSS
			return $minify ? $this->_minify( $css ) : $css;
			
		}
		
		return FALSE;
		
	}
	function display_inline_block(){
		
		$css = '';
		
		$css .= "display: -moz-inline-stack;";
		$css .= "display: inline-block;";
		$css .= "zoom: 1;";
		$css .= "*display: inline;";
		
		// Return our CSS
		return $this->_minify( $css );
		
	}
	function display_box(){
		
		$css = '';
		
		$css .= "display: -webkit-box;";
		$css .= "display: -moz-box;";
		$css .= "display: -o-box;";
		$css .= "display: -ms-box;";
		$css .= "display: box;";
		
		// Return our CSS
		return $this->_minify( $css );
		
	}
	function display_inline_box(){
		
		$css = '';
		
		$css .= "display: -webkit-inline-box;";
		$css .= "display: -moz-inline-box;";
		$css .= "display: -o-inline-box;";
		$css .= "display: -ms-inline-box;";
		$css .= "display: inline-box;";
		
		// Return our CSS
		return $this->_minify( $css );
		
	}
	function box_orient( $value = 'vertical' ){
		
		$css = '';
		
		$css .= "-webkit-box-orient: $value;";
		$css .= "-moz-box-orient: $value;";
		$css .= "-o-box-orient: $value;";
		$css .= "-ms-box-orient: $value;";
		$css .= "box-orient: $value;";
		
		// Return our CSS
		return $this->_minify( $css );
		
	}
	function box_ordinal_group( $value = 'auto' ){
		
		$css = '';
		
		$css .= "-webkit-box-ordinal-group: $value;";
		$css .= "-moz-box-ordinal-group: $value;";
		$css .= "-o-box-ordinal-group: $value;";
		$css .= "-ms-box-ordinal-group: $value;";
		$css .= "box-ordinal-group: $value;";
		
		// Return our CSS
		return $this->_minify( $css );
		
	}
	function box_sizing( $value = 'border-box' ){
		
		$css = '';
		
		$css .= "-webkit-box-sizing: $value;";
		$css .= "-moz-box-sizing: $value;";
		$css .= "-o-box-sizing: $value;";
		$css .= "-ms-box-sizing: $value;";
		$css .= "box-sizing: $value;";
		
		// Return our CSS
		return $this->_minify( $css );
		
	}
	function box_shadow( $value = 'initial' ){
		
		$css = '';
		
		$css .= "-webkit-box-shadow: $value;";
		$css .= "-moz-box-shadow: $value;";
		$css .= "-o-box-shadow: $value;";
		$css .= "-ms-box-shadow: $value;";
		$css .= "box-shadow: $value;";
		
		// Return our CSS
		return $this->_minify( $css );
		
	}
	function text_shadow( $value = 'initial' ){
		
		$css = '';
		
		$css .= "-webkit-text-shadow: $value;";
		$css .= "-moz-text-shadow: $value;";
		$css .= "-o-text-shadow: $value;";
		$css .= "-ms-text-shadow: $value;";
		$css .= "text-shadow: $value;";
		
		// Return our CSS
		return $this->_minify( $css );
		
	}
	function border_radius( $value = '0', $corner = '' ){
		
		$css = '';
		
		$css .= "-webkit-border" . ( $corner ? '-' . $corner : '' ) . "-radius: $value;";
		$css .= "-moz-border" . ( $corner ? '-' . $corner : '' ) . "-radius: $value;";
		$css .= "-o-border" . ( $corner ? '-' . $corner : '' ) . "-radius: $value;";
		$css .= "-ms-border" . ( $corner ? '-' . $corner : '' ) . "-radius: $value;";
		$css .= "border" . ( $corner ? '-' . $corner : '' ) . "-radius: $value;";
		$css .= "-webkit-background-clip: padding-box; ";
		$css .= "-moz-background-clip:    padding; ";
		$css .= "background-clip:         padding-box;";
		
		// Return our CSS
		return $this->_minify( $css );
		
	}
	function unselectable(){
		
		$css = '';
		
		$css .= "-webkit-touch-callout: none;";
		$css .= "-webkit-user-select: none;";
		$css .= "-khtml-user-select: none;";
		$css .= "-moz-user-select: none;";
		$css .= "-ms-user-select: none;";
		$css .= "user-select: none;";
		
		// Return our CSS
		return $this->_minify( $css );
		
	}
	function opacity( $value = 1 ){
		
		$css = '';
		$css .= "-ms-filter: \"progid:DXImageTransform.Microsoft.Alpha(Opacity=" . ( $value * 100 ) . ")\";";
		$css .= "filter: alpha(opacity=" . ( $value * 100 ) . ");";
		$css .= "-moz-opacity: $value;";
		$css .= "-khtml-opacity: $value;";
		$css .= "opacity: $value;";
		
		// Return our CSS
		return $this->_minify( $css );
		
	}
	function appearance( $value = 'initial' ){
		
		$css = '';
		
		$css .= "-webkit-appearance: $value;";
		$css .= "-moz-appearance: $value;";
		$css .= "-o-appearance: $value;";
		$css .= "-ms-appearance: $value;";
		$css .= "appearance: $value;";
		
		// Return our CSS
		return $this->_minify( $css );
		
	}
	function selection( $value = '' ){
		
		$css = "::-moz-selection { $value }";
		$css = "::selection { $value }";
		
		// Return our CSS
		return $this->_minify( $css );
		
	}
	function filter( $value ){
		
		if ( is_array( $value ) ){
			
			$css_value = '';
			$svg = '<svg height="0" xmlns="http://www.w3.org/2000/svg">';
			$svg .= '<filter id="filter">';
			
			if ( isset( $value[ 'blur' ] ) ){
				
				$svg .= '<feGaussianBlur in="SourceGraphic" stdDeviation="' . $value[ 'blur' ] . '"/>';
				
				$css_value .= ' blur(' . $value[ 'blur' ] . 'px)';
				
			}
			if ( isset( $value[ 'brightness' ] ) ){
				
				$svg .= '<feComponentTransfer>';
				$svg .= '<feFuncR type="linear" slope="' . $value[ 'brightness' ] . '"/>';
				$svg .= '<feFuncG type="linear" slope="' . $value[ 'brightness' ] . '"/>';
				$svg .= '<feFuncB type="linear" slope="' . $value[ 'brightness' ] . '"/>';
				$svg .= '</feComponentTransfer>';
				
				$css_value .= ' brightness(' . $value[ 'brightness' ] . ')';
				
			}
			if ( isset( $value[ 'contrast' ] ) ){
				
				$svg .= '<feComponentTransfer>';
				$svg .= '<feFuncR type="linear" slope="' . $value[ 'contrast' ] . '" intercept="-(0.5 * ' . $value[ 'contrast' ] . ') + 0.5"/>';
				$svg .= '<feFuncG type="linear" slope="' . $value[ 'contrast' ] . '" intercept="-(0.5 * ' . $value[ 'contrast' ] . ') + 0.5"/>';
				$svg .= '<feFuncB type="linear" slope="' . $value[ 'contrast' ] . '" intercept="-(0.5 * ' . $value[ 'contrast' ] . ') + 0.5"/>';
				$svg .= '</feComponentTransfer>';
				
				$css_value .= ' contrast(' . $value[ 'contrast' ] . ')';
				
			}
			if ( isset( $value[ 'opacity' ] ) ){
				
				$svg .= '<feComponentTransfer>';
				$svg .= '<feFuncA type="table" tableValues="0 ' . $value[ 'contrast' ] . '"></feFuncA>';
				$svg .= '</feComponentTransfer>';
				
				$css_value .= ' opacity(' . $value[ 'opacity' ] . ')';
				
			}
			if ( isset( $value[ 'saturate' ] ) ){
				
				$svg .= '<feColorMatrix type="saturate" values="' . $value[ 'saturate' ] . '"></feColorMatrix>';
				
				$css_value .= ' saturate(' . $value[ 'saturate' ] . ')';
				
			}
			if ( isset( $value[ 'hue-rotate' ] ) ){
				
				$svg .= '<feColorMatrix type="hueRotate" values="' . $value[ 'hue-rotate' ] . '"></feColorMatrix>';
				
				$css_value .= ' hue-rotate(' . $value[ 'hue-rotate' ] . 'deg)';
				
			}
			
			$svg .= '</filter>';
			$svg .= '</svg>';
			
			$css = '';
			$css .= 'data:image/svg+xml;base64,' . base64_encode( $svg );
			
			$css = "filter: url($css#filter);";
			
			$css .= "-webkit-filter: $css_value;";
			$css .= "-moz-filter: $css_value;";
			$css .= "-o-filter: $css_value;";
			$css .= "-ms-filter: $css_value;";
			$css .= "filter: $css_value;";
			
			// Return our CSS
			return $this->_minify( $css );
			
		}
		else {
			
			return FALSE;
			
		}
	}
	
	public function _minify( $buffer ){
		
		if ( $buffer AND $this->minify ){
			
			// Remove comments
			$buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
			 
			// Remove space after colons
			$buffer = str_replace(': ', ':', $buffer);
			 
			// Remove whitespace
			$buffer = str_replace(array("\r\n", "\r", "\n", "\t"), '', $buffer);
			 
			// Collapse adjacent spaces into a single space
			$buffer = preg_replace('/\s+/', ' ',$buffer);
			
			// Remove spaces that might still be left where we know they aren't needed
			$buffer = str_replace(array('} '), '}', $buffer);
			$buffer = str_replace(array('{ '), '{', $buffer);
			$buffer = str_replace(array('; '), ';', $buffer);
			$buffer = str_replace(array(', '), ',', $buffer);
			$buffer = str_replace(array(' }'), '}', $buffer);
			$buffer = str_replace(array(' {'), '{', $buffer);
			$buffer = str_replace(array(' ;'), ';', $buffer);
			$buffer = str_replace(array(' ,'), ',', $buffer);
			
			return $buffer;
			
		}
		
	}
	
}

?>
