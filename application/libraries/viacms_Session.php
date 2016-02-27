<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 5.1.6 or newer
 *
 * @package		CodeIgniter
 * @author		ExpressionEngine Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc.
 * @license		http://codeigniter.com/user_guide/license.html
 * @link		http://codeigniter.com
 * @since		Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * Session Class
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Sessions
 * @author		ExpressionEngine Dev Team
 * @link		http://codeigniter.com/user_guide/libraries/sessions.html
 */
class Viacms_Session extends CI_Session {

	/**
	 * Session Constructor
	 *
	 * The constructor runs the session routines automatically
	 * whenever the class is instantiated.
	 */
	public function __construct($params = array())
	{

		parent::__construct();





	}

	// --------------------------------------------------------------------

	/**
	 * Add or change data in the "userdata" array
	 *
	 * @access	public
	 * @param	mixed
	 * @param	string
	 * @return	void
	 */
	function set_userdata( $newdata = array(), $newval = '' )
	{

		if ( is_string( $newdata ) )
		{
			$newdata = array( $newdata => $newval );
		}

		if ( count( $newdata ) > 0 )
		{
			foreach ( $newdata as $key => $val )
			{
				$this->userdata[ $key ] = $val;
			}
		}

		$this->sess_write();

		$this->save_session_to_user();

	}

	// --------------------------------------------------------------------

	function save_session_to_user()
	{

		if ( isset( $this->CI->mcm->environment ) ){

			$env = $this->CI->mcm->environment;

			$userdata = $this->envdata( 'user' );
			$id = $userdata[ 'id' ];

			if ( $userdata AND $id ){

				$this->CI->db->select( '*' );
				$this->CI->db->from( 'tb_users' );
				$this->CI->db->where( 'id', $id );
				$this->CI->db->limit( 1 );
				$user = $this->CI->db->get()->row_array();

				$data[ 'params' ] = get_params( $user[ 'params' ] );
				$data[ 'params' ][ $env . '_user_session' ] = $this->all_envdata();
				$data[ 'params' ][ $env . '_user_session' ][ 'last_session_db_save' ] = date( 'Y-m-d H:i:s' );
				//print_r( $data[ 'params' ][ $env . '_user_session' ] ); die;
				$data[ 'params' ] = json_encode( $data[ 'params' ] );

				if ( $this->CI->db->update( 'tb_users', $data, array( 'id' => $user[ 'id' ] ) ) ){

					return TRUE;

				}

			}

		}

	}

	// --------------------------------------------------------------------

	/**
	 * Similar to userdata() function, but this gets specific to a particular environment
	 *
	 * @access	public
	 * @param	mixed
	 * @param	string
	 * @return	void
	 */
	function envdata( $item, $env = NULL ) {

		if ( isset( $this->CI->mcm->environment ) ){

			$env = isset( $env ) ? $env : $this->CI->mcm->environment;

			return ( ! isset( $this->userdata[ 'user_data' ][ $env ][ $item ] ) ) ? FALSE : $this->userdata[ 'user_data' ][ $env ][ $item ];

		}

	}

	// --------------------------------------------------------------------

	/**
	 * Set envdata item
	 *
	 * @access	public
	 * @param	mixed
	 * @param	string
	 * @return	void
	 */
	function set_envdata( $newdata = array(), $newval = '', $env = NULL ) {

		if ( isset( $this->CI->mcm->environment ) ){

			$env = isset( $env ) ? $env : $this->CI->mcm->environment;

			if ( is_string( $newdata ) ) {

				$newdata = array( $newdata => $newval );

			}

			if ( count( $newdata ) > 0 ) {

				foreach ( $newdata as $key => $val ) {

					$this->userdata[ 'user_data' ][ $env ][ $key ] = $val;

				}

			}

			$this->sess_write();

			$this->save_session_to_user();

		}

	}

	// --------------------------------------------------------------------

	/**
	 * Unset envdata item
	 *
	 * @access	public
	 * @param	mixed
	 * @param	string
	 * @return	void
	 */
	function unset_envdata( $newdata = NULL, $env = NULL ) {

		if ( isset( $this->CI->mcm->environment ) ){

			$env = isset( $env ) ? $env : $this->CI->mcm->environment;

			if ( is_string( $newdata ) ) {

				$newdata = array( $newdata => '' );

			}

			if ( count( $newdata ) > 0 ) {

				foreach ( $newdata as $key => $val ) {

					unset( $this->userdata[ 'user_data' ][ $env ][ $key ] );

				}

			}
			else if( ! isset( $newdata ) ) {

				unset( $this->userdata[ 'user_data' ][ $env ] );

			}

			$this->sess_write();

		}

	}

	// --------------------------------------------------------------------

	/**
	 * Fetch all env session data
	 *
	 * @access	public
	 * @return	array
	 */
	function all_envdata( $env = NULL ) {

		if ( isset( $this->CI->mcm->environment ) ){

			$env = isset( $env ) ? $env : $this->CI->mcm->environment;

			return isset( $this->userdata[ 'user_data' ][ $env ] ) ? $this->userdata[ 'user_data' ][ $env ] : NULL;

		}

		return NULL;

	}


}
// END Session Class

/* End of file Session.php */
/* Location: ./system/libraries/Session.php */
