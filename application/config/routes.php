<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

//$route['(:num)/(:any)'] = '$2'; // O primeiro parâmetro é um número referente ao ID de item de menu

$route[ 'admin' ] = 'admin/main/index';

if ( file_exists( APPPATH . 'cache/urls.php' ) ){
	
	require_once APPPATH . 'cache/urls.php';
	
}

if ( ! isset( $route[ 'default_controller' ] ) ){
	
	$route[ '404_override' ] = '';
	$route[ 'default_controller' ] = 'main/update_urls_cache';
	
}


 
 /*
 
require_once( BASEPATH .'database/DB'. EXT );
$db =& DB();
$query = $db->get( 'tb_urls' );

if ( $urls ){
		$reverse_urls = array();
	foreach( $urls as $url ){
		$route[ $url->sef_url ]						= $url->target;
		//$route[ $url->sef_url.'/:any' ]				= $url->target;
		//$route[ $url->target ]						= 'error404';
		//$route[ $url->target.'/:any' ]				= 'error404';
		if ( $url->sef_url != 'default_controller' ){
			$reverse_urls[$url->target] = $url->sef_url;
		}
	}
	//print_r($route);
	
	$this->config->set_item('reverse_urls',$reverse_urls);
}
else{
	
	show_404();
	
}
*/
/* End of file routes.php */
/* Location: ./application/config/routes.php */