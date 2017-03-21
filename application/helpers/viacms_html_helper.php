<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * CodeIgniter HTML Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		Frank Souza
 */

// ------------------------------------------------------------------------

// ------------------------------------------------------------------------

/**
 * ul menu
 *
 * Generates an HTML ul menu from an single or multi-dimensional array.
 *
 * @access	public
 * @param	array
 * @param	mixed
 * @return	string
 */

function ul_menu( $menuArray ){
	
	$out = '<ul class="menu">';
	$out .= _ul_menu_items( $menuArray );
	$out .= '</ul>';
	
	return $out;
	
}

function _ul_menu_items( $menuArray ){
	
	$out = '';
	
	foreach ( $menuArray as $node ){
		
		//echo 'node id: ' . $node['id'] . '<br/>';
		$link = '';
		
		if ( isset( $node[ 'link' ] ) AND $node[ 'link' ] !== '#' ){
			
			if ( isset( $node[ 'home' ] ) AND $node[ 'home' ] ){
				
				$link = site_url();
				//echo 'home <br/>';
				
			}
			else if ( ( isset( $node[ 'link' ] ) AND $node[ 'link' ] ) AND ( isset( $node[ 'id' ] ) AND $node[ 'id' ] ) ){
				
				$link = get_url( $node['link'], $node['id'] );
				//echo 'normal <br/>';
				
			}
			else if ( isset( $node[ 'link' ] ) AND $node[ 'link' ] ){
				
				$link = get_url( $node['link'] );
				//echo 'somente link <br/>';
				
			}
			else{
				
				$link = FALSE;
				//echo 'falso <br/>';
				
			}
			//echo 'node link: ' . $link . '<br/>';
			//echo '<br/>';
			
			
		}
		else{
			
			$CI =& get_instance();
			
			$link = $CI->uri->uri_string() . '#';
			
		}
		
		$env = environment();
		
		if ( $env == SITE_ALIAS ) {
			
			$tag = ( $link ) ? 'a' : 'span';
			
			$li_class = 'menu-item';
			$li_class .= ( ! empty( $node['children'] ) ? ' parent' : '' ) . ' ' . ( ( isset( $node[ 'current' ] ) AND $node[ 'current' ] ) ? 'current' : '' );
			$li_class .= ( ( isset( $node[ 'home' ] ) AND $node[ 'home' ] ) ? ' home' : '' );
			$li_class .= ( ( isset( $node[ 'type' ] ) AND $node[ 'type' ] ) ? ' menu-type-' . $node[ 'type' ] : '' );
			$li_class .= ( ( isset( $node[ 'link' ] ) AND $node[ 'link' ] === '#' ) ? ' hash-link' : '' );
			$li_class = ( trim( $li_class ) ) != '' ? 'class="' . trim( $li_class ) . '"' : '';
			
			$out .= '<li ' . $li_class . '><' . $tag . ' class="' . ( ( $link ) ? 'menu-item-link' : 'menu-item-no-link' ) . '" ' . ( $link ? 'href="' . $link . '"' : '' ) . '/><span class="menu-item-content">' . lang( $node['title'] ) . '</span></' . $tag . '>';
			
			if ( ! empty( $node['children'] ) ) {
				$out .= '<ul class="sub-menu">';
				$out .= _ul_menu_items( $node['children'] );
				$out .= '</ul>';
			}
			$out .= '</li>';
			
		}
		else if ( $env == ADMIN_ALIAS ) {
			
			$btn = vui_el_button(
				
				array(
					
					'url' => $link,
					'title' => lang( $node['title'] ),
					'text' => lang( $node['title'] ),
					'icon' => isset( $node['icon'] ) ? $node['icon'] : NULL,
					'target' => isset( $node[ 'target' ] ) ? $node[ 'target' ] : NULL,
					
				)
				
			);
			
			
			$li_class = 'menu-item';
			$li_class .= ( ! empty( $node['children'] ) ? ' parent' : '' ) . ' ' . ( ( isset( $node[ 'current' ] ) AND $node[ 'current' ] ) ? 'current' : '' );
			$li_class .= ( ( isset( $node[ 'home' ] ) AND $node[ 'home' ] ) ? ' home' : '' );
			$li_class .= ( ( isset( $node[ 'type' ] ) AND $node[ 'type' ] ) ? ' menu-type-' . $node[ 'type' ] : '' );
			$li_class .= ( ( isset( $node[ 'link' ] ) AND $node[ 'link' ] === '#' ) ? ' hash-link' : '' );
			$li_class = ( trim( $li_class ) ) != '' ? 'class="' . trim( $li_class ) . '"' : '';
			
			$out .= '<li ' . $li_class . '>' . $btn;
			
			if ( ! empty( $node['children'] ) ) {
				$out .= '<ul class="sub-menu">';
				$out .= _ul_menu_items( $node['children'] );
				$out .= '</ul>';
			}
			$out .= '</li>';
			
		}
		
	}
	
	return $out;
	
}

// ------------------------------------------------------------------------

/**
 * ul menu
 *
 * Generates an HTML ul menu from an single or multi-dimensional array.
 *
 * @access	public
 * @param	array
 * @param	mixed
 * @return	string
 */

function element_title( $str ){
	
	$out = 'title="' . strip_tags( $str, 'br' ) . '" data-ext-tip="' . rawurlencode( $str ) . '"';
	
	return $out;
	
}

function tag_replace($matches) {
	
	switch($matches[1]) {
		case 'img':
			return '<img src="'.$matches[2].'" alt="Image" />';
			break;
		default:
			return $matches[0];
			break;
	}
	
}

function minify_html( $html ) {

	$search = array(
		'/\>[^\S ]+/s',  // strip whitespaces after tags, except space
		'/[^\S ]+\</s',  // strip whitespaces before tags, except space
		'/(\s)+/s'	   // shorten multiple whitespace sequences
	);

	$replace = array(
		'>',
		'<',
		'\\1'
	);

	$html = preg_replace($search, $replace, $html);

	return $html;
	
}

/* End of file html_helper.php */
/* Location: ./system/helpers/html_helper.php */
