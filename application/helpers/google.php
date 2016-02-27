<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function google_contacts_get_all( $message = NULL, $type = NULL ){

	if ( $message ){

		$CI =& get_instance();

		if ( $CI->session->envdata( 'msg' ) ) {

			$current_msg = $CI->session->envdata( 'msg' );
			$msg = array(

				 'msg'  => $current_msg,

			);

			$msg[ 'msg' ][] = array(

				'msg' => $message,
				'type' => $type,

			);

		}
		else{
			$msg = array(

				'msg'  => array(),

			);

			$msg[ 'msg' ][] = array(

				'msg' => $message,
				'type' => $type,

			);
		}

		$CI->session->set_envdata( $msg );

	}
	else {
		return FALSE;
	}

}

/* End of file msg_helper.php */
/* Location: ./application/helpers/msg_helper.php */