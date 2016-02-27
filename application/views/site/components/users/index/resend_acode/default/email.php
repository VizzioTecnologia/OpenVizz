<?php if ( ! defined( 'BASEPATH' ) ) exit('No direct script access allowed' );
	
	$url = get_url( $this->uri->ruri_string() );
	
?>

link de ativação: <a href="' . get_url( $this->get_link_activate_account_page( $db_data[ 'code' ] ) ) . '">' . get_url( $this->get_link_activate_account_page( $db_data[ 'code' ] ) ) . '</a><br /><br />' . print_r( $user, TRUE )
