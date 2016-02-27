<?php

use \Exception;

class Vui_color{
	
	var $r;
	var $g;
	var $b;
	var $a;
	
	var $hr;
	var $hg;
	var $hb;
	var $ha;
	
	var $hex;
	var $rgb;
	var $rgba;
	var $hsl;
	
	var $hex_s;
	var $rgb_s;
	var $rgba_s;
	var $hsl_s;
	
	var $rgba_variants = 255; // quantity of alpha variants for rgba colors
	var $default_opacity_index;
	var $comment;
	
	private $_color;
	private $_hsl;
	private $_rgb;
	private $_yiq_limit = 166;
	
	const DEFAULT_AMOUNT = 10;
	
	function __construct( $color = NULL, $default_opacity_index = NULL, $comment = NULL ){
		
		if ( isset( $default_opacity_index ) ) {
			
			$this->default_opacity_index = $default_opacity_index;
			
		}
		else {
			
			$this->default_opacity_index = $this->rgba_variants;
			
		}
		
		if ( isset( $comment ) ) {
			
			$this->comment = ' /* ' . $comment . ' */';
			
		}
		else {
			
			$this->comment = '';
			
		}
		
		if ( $color ){
			
			$this->set_color( $color );
			
		}
		
	}
	
	public function __get( $name ) {
		
		if ( isset ( $this->$name ) ) {
			
			return $this->$name;
			
		}
		return NULL;
		
	}
	
	public function __set( $name, $value ) {
		
		$this->{ $name } = $value;
		
	}
	
	function init(){
		
		if ( isset( $this->_color ) ){
			
			$this->_make( $this->_color );
			
		}
		
	}
	
	function rgba_s( $opacity_index = NULL ){
		
		if ( ! $opacity_index ) {
			
			$opacity_index = $this->default_opacity_index;
			
		}
		
		return 'rgba( ' . ( $this->rgb . ', ' . str_replace( ',', '.', ( 255 / $this->rgba_variants ) * $opacity_index / 255 ) ) . ' )' . $this->comment;
		
	}
	
	function set_color( $color ){
		
		$this->_color = $color;
		$this->init();
		
	}
	
	
	function darken( $amount = 0, $return_obj = FALSE ){
		
		$darkerHSL = self::hslToHex( $this->_darken( $this->_hsl, $amount ) );
		
		if ( $return_obj ){
			
			$new_obj_name = 'vui_darken_color_' . $amount;
			
			if ( isset( $this->{ $new_obj_name } ) ){
				
				return $this->{ $new_obj_name };
				
			}
			else {
				
				$this->{ $new_obj_name } = new Vui_color( $darkerHSL, $this->default_opacity_index );
				return $this->{ $new_obj_name };
				
			}
			
		}
		else {
			
			return $darkerHSL;
			
		}
		
	}
	
	function lighten( $amount = 0, $return_obj = FALSE ){
		
		$lighterHSL = self::hslToHex( $this->_lighten( $this->_hsl, $amount ) );
		
		if ( $return_obj ){
			
			$new_obj_name = 'vui_lighten_color_' . $amount;
			
			if ( isset( $this->{ $new_obj_name } ) ){
				
				return $this->{ $new_obj_name };
				
			}
			else {
				
				$this->{ $new_obj_name } = new Vui_color( $lighterHSL, $this->default_opacity_index );
				return $this->{ $new_obj_name };
				
			}
			
		}
		else {
			
			return $lighterHSL;
			
		}
		
	}
	
	function is_light(){
		
		return $this->_is_light();
		
	}
	
	function is_dark(){
		
		return $this->_is_dark();
		
	}
	
	function get_ro_color( $amount = 50 ){
		
		return $this->_get_ro_color( $amount );
		
	}
	
	/**
	 * Returns your color's HSL array
	 */
	function getHsl() {
		
		return $this->_hsl;
		
	}
	/**
	 * Returns your original color
	 */
	function getHex() {
		
		return $this->hex;
		
	}
	/**
	 * Returns your color's RGB array
	 */
	function getRgb() {
		
		return $this->_rgb;
		
	}
	
	public static function hexToHsl( $color ){

		// Sanity check
		$color = self::_checkHex($color);

		// Convert HEX to DEC
		$R = hexdec($color[0].$color[1]);
		$G = hexdec($color[2].$color[3]);
		$B = hexdec($color[4].$color[5]);

		$HSL = array();

		$var_R = ($R / 255);
		$var_G = ($G / 255);
		$var_B = ($B / 255);

		$var_Min = min($var_R, $var_G, $var_B);
		$var_Max = max($var_R, $var_G, $var_B);
		$del_Max = $var_Max - $var_Min;

		$L = ($var_Max + $var_Min)/2;

		if ($del_Max == 0)
		{
			$H = 0;
			$S = 0;
		}
		else
		{
			if ( $L < 0.5 ) $S = $del_Max / ( $var_Max + $var_Min );
			else			$S = $del_Max / ( 2 - $var_Max - $var_Min );

			$del_R = ( ( ( $var_Max - $var_R ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
			$del_G = ( ( ( $var_Max - $var_G ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;
			$del_B = ( ( ( $var_Max - $var_B ) / 6 ) + ( $del_Max / 2 ) ) / $del_Max;

			if	  ($var_R == $var_Max) $H = $del_B - $del_G;
			else if ($var_G == $var_Max) $H = ( 1 / 3 ) + $del_R - $del_B;
			else if ($var_B == $var_Max) $H = ( 2 / 3 ) + $del_G - $del_R;

			if ($H<0) $H++;
			if ($H>1) $H--;
		}

		$HSL['H'] = ($H*360);
		$HSL['S'] = $S;
		$HSL['L'] = $L;

		return $HSL;
	}
	
	public static function hslToHex( $hsl = array() ){
		 // Make sure it's HSL
		if(empty($hsl) || !isset($hsl["H"]) || !isset($hsl["S"]) || !isset($hsl["L"]) ) {
			throw new Exception("Param was not an HSL array");
		}

		list($H,$S,$L) = array( $hsl['H']/360,$hsl['S'],$hsl['L'] );

		if( $S == 0 ) {
			$r = $L * 255;
			$g = $L * 255;
			$b = $L * 255;
		} else {

			if($L<0.5) {
				$var_2 = $L*(1+$S);
			} else {
				$var_2 = ($L+$S) - ($S*$L);
			}

			$var_1 = 2 * $L - $var_2;

			$r = round(255 * self::_huetorgb( $var_1, $var_2, $H + (1/3) ));
			$g = round(255 * self::_huetorgb( $var_1, $var_2, $H ));
			$b = round(255 * self::_huetorgb( $var_1, $var_2, $H - (1/3) ));

		}

		// Convert to hex
		$r = dechex($r);
		$g = dechex($g);
		$b = dechex($b);

		// Make sure we get 2 digits for decimals
		$r = (strlen("".$r)===1) ? "0".$r:$r;
		$g = (strlen("".$g)===1) ? "0".$g:$g;
		$b = (strlen("".$b)===1) ? "0".$b:$b;

		return $r . $g . $b;
		//return $hsl["H"] . '-' . $hsl["S"] . '-' . $hsl["L"];
	}
	
	
	
	
	function getCssGradient( $amount = self::DEFAULT_AMOUNT, $direction = 'btt', $vintageBrowsers = FALSE, $prefix = "", $suffix = "" ) {
		
		// Get the recommended gradient
		$g = $this->makeGradient( $amount );
		
		if ( ! isset( $direction ) ) {
			
			$direction = 'btt';
			
		}
		
		if ( $direction === 'btt' OR $direction === 'rtl' ){
			
			$start_color = $this->lighten( $amount );
			$end_color = $this->darken( $amount );
			
		}
		else if ( $direction === 'ttb' OR $direction === 'ltr' ){
			
			$start_color = $this->darken( $amount );
			$end_color = $this->lighten( $amount );
			
		}
		
		$css = '';
		
		if ( $direction === 'btt' OR $direction === 'ttb' ){
			
			$css .= "background-color: " . $this->hex_s . ";\n";
			$css .= "background-image: {$prefix}-moz-linear-gradient(top,  #" . $start_color . " 0%, #" . $end_color . " 100%){$suffix};\n";
			$css .= "background-image: {$prefix}-webkit-gradient(linear, left top, left bottom, color-stop(0%,#" . $start_color . "), color-stop(100%,#" . $end_color . ")){$suffix};\n";
			$css .= "background-image: {$prefix}-webkit-linear-gradient(top,  #" . $start_color . " 0%,#" . $end_color . " 100%){$suffix};\n";
			$css .= "background-image: {$prefix}-o-linear-gradient(top,  #" . $start_color . " 0%,#" . $end_color . " 100%); /* Opera 11.10+ */{$suffix};\n";
			$css .= "background-image: {$prefix}-ms-linear-gradient(top,  #" . $start_color . " 0%,#" . $end_color . " 100%){$suffix};\n";
			$css .= "background-image: {$prefix}linear-gradient(to bottom,  #" . $start_color . " 0%,#" . $end_color . " 100%){$suffix};\n";
			$css .= "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#" . $start_color . "', endColorstr='#" . $end_color . "',GradientType=0 );\n";

			
		}
		else if ( $direction === 'ltr' OR $direction === 'rtl' ){
			
			$css .= "{$prefix}background-color: " . $this->hex_s . "; /* Old browsers */{$suffix}\n";
			$css .= "background-image: {$prefix}-moz-linear-gradient(left,  #" . $start_color . " 0%, #" . $end_color . " 100%); /* FF3.6+ */{$suffix}\n";
			$css .= "background-image: {$prefix}-webkit-gradient(linear, left top, right top, color-stop(0%,#" . $start_color . "), color-stop(100%,#" . $end_color . ")); /* Chrome,Safari4+ */{$suffix}\n";
			$css .= "background-image: {$prefix}-webkit-linear-gradient(left,  #" . $start_color . " 0%,#" . $end_color . " 100%); /* Chrome10+,Safari5.1+ */{$suffix}\n";
			$css .= "background-image: {$prefix}-o-linear-gradient(left,  #" . $start_color . " 0%,#" . $end_color . " 100%); /* Opera 11.10+ */{$suffix}\n";
			$css .= "background-image: {$prefix}-ms-linear-gradient(left,  #" . $start_color . " 0%,#" . $end_color . " 100%); /* IE10+ */{$suffix}\n";
			$css .= "background-image: {$prefix}linear-gradient(to right,  #" . $start_color . " 0%,#" . $end_color . " 100%); /* W3C */{$suffix}\n";
			$css .= "filter: progid:DXImageTransform.Microsoft.gradient( startColorstr='#" . $start_color . "', endColorstr='#" . $end_color . "',GradientType=1 ); /* IE6-9 */\n";
			
		}
		
		
		
		
		// Return our CSS
		return $css;
		
	}
	
	function makeGradient( $amount = self::DEFAULT_AMOUNT ) {
		
		if( $this->is_light() ) {
			
			$amount_dark = $amount;
			$amount_light = $amount * 0.8;
			
		} else {
			
			$amount_dark = $amount * 0.8;
			$amount_light = $amount;
			
		}
		
		// Return our gradient array
		return array( "light" => $this->lighten( $amount_light ), "dark" => $this->darken( $amount_dark ) );
		
	}
	
	
	
	
	private static function _huetorgb( $v1,$v2,$vH ) {
		
		if( $vH < 0 ) {
			$vH += 1;
		}

		if( $vH > 1 ) {
			$vH -= 1;
		}

		if( (6*$vH) < 1 ) {
			   return ($v1 + ($v2 - $v1) * 6 * $vH);
		}

		if( (2*$vH) < 1 ) {
			return $v2;
		}

		if( (3*$vH) < 2 ) {
			return ($v1 + ($v2-$v1) * ( (2/3)-$vH ) * 6);
		}

		return $v1;

	}
	
	
	
	private function _rgb2hsl(){
		
		$r = $this->r / 255;
		$g = $this->g / 255;
		$b = $this->b / 255;
		
		$max = max( $r, $g, $b );
		$min = min( $r, $g, $b );
		
		$h;
		$s;
		$l = ( $max + $min ) / 2;
		$d = $max - $min;
		
		if( $d == 0 ){
			
			$h = $s = 0; // achromatic
			
		} else {
			
			$s = $d / ( 1 - abs( 2 * $l - 1 ) );
			
			switch( $max ){
				
				case $r:
					$h = 60 * fmod( ( ( $g - $b ) / $d ), 6 ); 
						if ($b > $g) {
						$h += 360;
					}
					break;
					
				case $g: 
					$h = 60 * ( ( $b - $r ) / $d + 2 ); 
					break;
					
				case $b: 
					$h = 60 * ( ( $r - $g ) / $d + 4 ); 
					break;
			}
			
		}
		
		return array( 'H' => round( $h, 2 ), 'S' => round( $s, 2 ), 'L' => round( $l, 2 ) );
		
	}
	
	private function _hsl2rgb( $h, $s, $l ){
		
		$r;
		$g;
		$b;
		
		$c = ( 1 - abs( 2 * $l - 1 ) ) * $s;
		$x = $c * ( 1 - abs( fmod( ( $h / 60 ), 2 ) - 1 ) );
		$m = $l - ( $c / 2 );
		
		if ( $h < 60 ) {
			
			$r = $c;
			$g = $x;
			$b = 0;
			
		} else if ( $h < 120 ) {
			
			$r = $x;
			$g = $c;
			$b = 0;
			
		} else if ( $h < 180 ) {
			
			$r = 0;
			$g = $c;
			$b = $x;
			
		} else if ( $h < 240 ) {
			
			$r = 0;
			$g = $x;
			$b = $c;
			
		} else if ( $h < 300 ) {
			
			$r = $x;
			$g = 0;
			$b = $c;
			
		} else {
			
			$r = $c;
			$g = 0;
			$b = $x;
		}
		
		$r = ( $r + $m ) * 255;
		$g = ( $g + $m ) * 255;
		$b = ( $b + $m  ) * 255;
		
		return array( floor( $r ), floor( $g ), floor( $b ) );
		
	}
	
	private function _make(){
		
		$a = 'ff';
		
		// Verificando o tipo de cor recebido
		if ( gettype( $this->_color ) === 'string' ){
			
			$this->_color = str_replace( '#', '', $this->_color );
			
			// Checking if string has a valid hex value
			if ( ! ctype_xdigit( $this->_color ) ) {
				
				$this->_color = '000000';
				
			}
			else {
			
				// Make sure it's 3, 6 or 8 digits
				if ( strlen( $this->_color ) === 3 ) {
					
					$this->_color = $this->_color[ 0 ] . $this->_color[ 0 ] . $this->_color[ 1 ] . $this->_color[ 1 ] . $this->_color[ 2 ] . $this->_color[ 2 ];
					
				}
				else if ( strlen( $this->_color ) != 6 AND strlen( $this->_color ) != 8 ) {
					
					$this->_color = '000000';
					
				}
				
			}
			
		}
		
		$this->_color = $this->_color . $a;
		
		$this->_make_colors( $this->_color );
		
	}
	
	private function _make_colors(){
		
		$this->hr = $this->_color[ 0 ] . $this->_color[ 1 ];
		$this->hg = $this->_color[ 2 ] . $this->_color[ 3 ];
		$this->hb = $this->_color[ 4 ] . $this->_color[ 5 ];
		$this->ha = $this->_color[ 6 ] . $this->_color[ 7 ];
		
		$this->r = hexdec( $this->hr );
		$this->g = hexdec( $this->hg );
		$this->b = hexdec( $this->hb );
		$this->a = hexdec( $this->ha );
		
		$this->hex = $this->hr . $this->hg . $this->hb;
		$this->hex_s = '#' . $this->hex . $this->comment;
		
		$this->hexa = $this->hex . $this->ha;
		$this->hexa_s = $this->hex_s . $this->ha . $this->comment;
		
		$this->rgb = $this->r . ',' . $this->g . ','  . $this->b;
		$this->rgb_s = 'rgb( ' . $this->r . ', ' . $this->g . ', '  . $this->b . ' )' . $this->comment;
		
		$this->rgba = $this->rgb . ', '  . ( $this->a / 255 );
		$this->rgba_s = 'rgba( ' . $this->r . ', ' . $this->g . ', '  . $this->b . ', '  . ( $this->a / 255 ) . ' )' . $this->comment;
		
		$this->_hsl = $this->_rgb2hsl();
		
	}
	
	private function _is_dark(){
		
		$yiq = $this->_get_yiq();
		
		if ( $yiq >= $this->_yiq_limit )
			
			return FALSE;
			
		return TRUE;
		
	}
	
	private function _is_light(){
		
		$yiq = $this->_get_yiq();
		
		if ( $yiq >= $this->_yiq_limit )
			
			return TRUE;
			
		return FALSE;
		
	}
	
	private function _get_yiq(){
		
		return ( 299 * $this->r + 587 * $this->g + 114 * $this->b ) / 1000;
		
	}
	
	private function _get_ro_color( $amount = 50 ){
		
		$yiq = $this->_get_yiq();
		
		if ( $yiq >= $this->_yiq_limit )
			
			$color = new Vui_color( $this->darken( (int)$amount ) );
			
		else
			
			$color = new Vui_color( $this->lighten( (int)$amount ) );
			
		$this->ro_color = $color;
		
		return $this->ro_color;
		
	}
	
	private function _darken( $hsl, $amount = 0 ){
		
		$h = ( $hsl[ 'H' ] * 100 );
		$s = ( $hsl[ 'S' ] * 100 );
		$l = ( $hsl[ 'L' ] * 100 );
		
		$new_l = $l - ( $amount ? $amount : 39 );
		
		$new_perc = ( $new_l ) / ( $l != 0 ? $l : 1 );
		
		if( $this->is_light() ) {
			
			$h_factor = 1;
			$s_factor = 1;
			$l_factor = 1;
			
		} else {
			
			$h_factor = 0.1 * $new_perc;
			$s_factor = 0.5 * $new_perc;
			$l_factor = 0.8;
			
		}
		
		$hsl[ 'H' ] = ( ( $hsl[ 'H' ] * 100 ) / 360 ) - ( 0.5 * $h_factor );
		$hsl[ 'H' ] = ( ( $hsl[ 'H' ] < 0 ) ? $hsl[ 'H' ] + 1 : $hsl[ 'H' ] / 100 ) * 360;
		
		$hsl[ 'S' ] = ( $hsl[ 'S' ] * 100 ) - ( 1 * $s_factor );
		$hsl[ 'S' ] = ( $hsl[ 'S' ] < 0 ) ? 0 : $hsl[ 'S' ] / 100;
		
		$hsl[ 'L' ] = ( $hsl[ 'L' ] * 100 ) - ( $amount * $l_factor );
		$hsl[ 'L' ] = ( $hsl[ 'L' ] < 0 ) ? 0 : $hsl[ 'L' ] / 100;
		
		return $hsl;
		
	}
	
	private function _lighten( $hsl, $amount = 0 ){
		
		$h = ( $hsl[ 'H' ] * 100 );
		$s = ( $hsl[ 'S' ] * 100 );
		$l = ( $hsl[ 'L' ] * 100 );
		
		$new_l = $l + ( $amount ? $amount : 39 );
		
		$new_perc = ( $new_l ) / ( $l != 0 ? $l : 1 );
		
		if( $this->is_light() ) {
			
			$h_factor = 0.1 * $new_perc;
			$s_factor = 0.5 * $new_perc;
			$l_factor = 0.8;
			
		} else {
			
			$h_factor = 1;
			$s_factor = 1;
			$l_factor = 1;
			
		}
		
		$hsl[ 'H' ] = ( ( $hsl[ 'H' ] * 100 ) / 360 ) + ( 0.5 * $h_factor );
		$hsl[ 'H' ] = ( ( $hsl[ 'H' ] > 100 ) ? $hsl[ 'H' ] - 1 : $hsl[ 'H' ] / 100 ) * 360;
		
		$hsl[ 'S' ] = ( $hsl[ 'S' ] * 100 ) + ( 1 * $s_factor );
		$hsl[ 'S' ] = ( $hsl[ 'S' ] > 100 ) ? 1 : $hsl[ 'S' ] / 100;
		
		$hsl[ 'L' ] = ( $hsl[ 'L' ] * 100 ) + ( $amount * $l_factor );
		$hsl[ 'L' ] = ( $hsl[ 'L' ] > 100 ) ? 1 : $hsl[ 'L' ] / 100;
		
		return $hsl;
		
	}
	
}

?>
