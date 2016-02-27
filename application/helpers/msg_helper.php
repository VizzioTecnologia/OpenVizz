<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');


function msg( $message = NULL, $type = NULL ){
	
	if ( $message ){

		$CI =& get_instance();
		
		if ( $CI->session->envdata( 'msg' ) ) {
			
			$msg[ 'msg' ] = $CI->session->envdata( 'msg' );
			
		}
		else{

			$msg[ 'msg' ] = array();

		}

		$msg[ 'msg' ][] = array(

			'msg' => $message,
			'type' => $type,

		);

		$CI->session->set_envdata( $msg );

	}
	else {
		return FALSE;
	}

}

function loadMsg(){

	$CI =& get_instance();

	$html_msg = '';

	if ( $CI->session->envdata( 'msg' ) ) {

		$html_msg .= '<div class="msg">';

		$msg = $CI->session->envdata( 'msg' );
		foreach ($msg as $item) {

			$html_msg .= '<div class="msg-item msg-type-'.$item['type'].'">';
			$html_msg .= lang( $item['msg'] );
			$html_msg .= '</div>';

		}

		$CI->session->unset_envdata( 'msg' );

		$html_msg .= '</div>';

		return $html_msg;

	}

}
/* End of file msg_helper.php */
/* Location: ./application/helpers/msg_helper.php */