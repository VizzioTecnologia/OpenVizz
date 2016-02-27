<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

set_include_path( get_include_path() . PATH_SEPARATOR . APPPATH . 'libraries' . DS . 'facebook/php-sdk-v4-4.0-dev' );

if ( session_status() == PHP_SESSION_NONE ) {
	session_start();
}
 
// Autoload the required files
require_once( 'autoload.php' );
 
use Facebook\FacebookRedirectLoginHelper;
use Facebook\FacebookSession;
use Facebook\FacebookRequest;
use Facebook\GraphUser;
use Facebook\FacebookRequestException;
 
 
class Facebook {
	
	var $ci;
	var $helper;
	var $session;
	var $permissions;
	
	public function __construct() {
		
		$this->ci =& get_instance();
		
		log_message( 'Debug', 'Facebook SDK v4 library is loaded.' );
		
		$facebook_config = $this->ci->config->item( 'facebook' );
		
		$this->permissions = $facebook_config[ 'permissions' ];
		
		// Initialize the SDK
		FacebookSession::setDefaultApplication( $facebook_config[ 'app_id' ], $facebook_config[ 'app_secret' ] );
		
		// Create the login helper and replace REDIRECT_URI with your URL
		// Use the same domain you set for the apps 'App Domains'
		// e.g. $helper = new FacebookRedirectLoginHelper( 'http://mydomain.com/redirect' );
		$this->helper = new FacebookRedirectLoginHelper( $facebook_config[ 'redirect_url' ] );
		
		if ( $this->ci->session->userdata( 'fb_token' ) ) {
			
			$this->session = new FacebookSession( $this->ci->session->userdata('fb_token') );
			
			// Validate the access_token to make sure it's still valid
			try {
				if ( ! $this->session->validate() ) {
					
					$this->session = NULL;
					
				}
			} catch ( Exception $e ) {
				
				// Catch any exceptions
				$this->session = NULL;
				
			}
		} else {
			
			// No session exists
			
			try {
				
				$this->session = $this->helper->getSessionFromRedirect();
				
			} catch( FacebookRequestException $ex ) {
				
				// When Facebook returns an error
				
			} catch( Exception $ex ) {
				
				// When validation fails or other local issues
				
			}
		}
		
		if ( $this->session ) {
			$this->ci->session->set_userdata( 'fb_token', $this->session->getToken() );
			
			$this->session = new FacebookSession( $this->session->getToken() );
		}
	}
	
	/**
	 * Returns the login URL.
	 */
	public function login_url() {
		
		return $this->helper->getLoginUrl( $this->permissions );
		
	}
	
	/**
	 * Returns the current user's info as an array.
	 */
	public function get_user( $session = NULL ) {
		
		if ( $session ) {
			/**
			 * Retrieve User’s Profile Information
			 */
			// Graph API to request user data
			$user_profile = new FacebookRequest( $session, 'GET', '/me' );
			$user_profile = $user_profile->execute()->getGraphObject( GraphUser::className() );
			
			// Get response as an array
			$user_profile = $user_profile->asArray();
			
			
			$usr_picture = array();
			
			$img_rqst = ( new FacebookRequest( $session, 'GET', '/me/picture?type=square&redirect=false' ) );
			$img_rqst = $img_rqst->execute()->getGraphObject()->asArray();
			$usr_picture[ 'square' ] = $img_rqst[ 'url' ];
			
			$img_rqst = ( new FacebookRequest( $session, 'GET', '/me/picture?type=small&redirect=false' ) );
			$img_rqst = $img_rqst->execute()->getGraphObject()->asArray();
			$usr_picture[ 'small' ] = $img_rqst[ 'url' ];
			
			$img_rqst = ( new FacebookRequest( $session, 'GET', '/me/picture?type=normal&redirect=false' ) );
			$img_rqst = $img_rqst->execute()->getGraphObject()->asArray();
			$usr_picture[ 'normal' ] = $img_rqst[ 'url' ];
			
			$img_rqst = ( new FacebookRequest( $session, 'GET', '/me/picture?type=large&redirect=false' ) );
			$img_rqst = $img_rqst->execute()->getGraphObject()->asArray();
			$usr_picture[ 'large' ] = $img_rqst[ 'url' ];
			
			$user_profile[ 'picture' ] = $usr_picture;
			
			return $user_profile;
		}
		
		return FALSE;
		
	}
	
	public function facebook_request( $method, $parameters = null, $version = null, $etag = null ) {
		
		if ( $this->session ) {
			
			$request = new FacebookRequest( $this->session, $method, $parameters );
			$request = $request->execute();
			$user = $request->getGraphObject()->asArray();
			return $user;
			
		}
		
		return FALSE;
		
	}
	
}