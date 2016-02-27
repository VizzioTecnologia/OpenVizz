<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Image_cropper_plugin extends Plugins_mdl{
	
	public function run( & $params = NULL ){
		
		$post = check_var( $params[ 'post' ] ) ? $params[ 'post' ] : FALSE;
		$return = FALSE;
		
		if ( $post ){
			
				get_url();
				
			log_message( 'debug', '[Plugins] Image cropper plugin initialized with POST request' );
			
			$image_source =								( check_var( $post[ 'image_source' ] ) AND file_exists( FCPATH . str_replace( base_url(), '', urldecode( $post[ 'image_source' ] ) ) ) ) ? FCPATH . str_replace( base_url(), '', urldecode( $post[ 'image_source' ] ) ) : FALSE;
			
			$crop =										check_var( $post[ 'crop' ] ) ? TRUE : FALSE;
			$crop_x =									isset( $post[ 'crop' ][ 'image_data' ][ 'x' ] ) ? (int)$post[ 'crop' ][ 'image_data' ][ 'x' ] : FALSE;
			$crop_y =									isset( $post[ 'crop' ][ 'image_data' ][ 'y' ] ) ? (int)$post[ 'crop' ][ 'image_data' ][ 'y' ] : FALSE;
			$crop_w =									isset( $post[ 'crop' ][ 'image_data' ][ 'width' ] ) ? (int)$post[ 'crop' ][ 'image_data' ][ 'width' ] : FALSE;
			$crop_h =									isset( $post[ 'crop' ][ 'image_data' ][ 'height' ] ) ? (int)$post[ 'crop' ][ 'image_data' ][ 'height' ] : FALSE;
			
			$resize =									check_var( $post[ 'resize' ] ) ? TRUE : FALSE;
			$resize_w =									isset( $post[ 'resize' ][ 'image_data' ][ 'width' ] ) ? (int)$post[ 'resize' ][ 'image_data' ][ 'width' ] : FALSE;
			$resize_h =									isset( $post[ 'resize' ][ 'image_data' ][ 'height' ] ) ? (int)$post[ 'resize' ][ 'image_data' ][ 'height' ] : FALSE;
			
			if ( $image_source ){
				
				$image_path_info = pathinfo( $image_source );
				
				$image_path = $image_path_info[ 'dirname' ] . DS;
				$image_file_name = $image_path_info[ 'basename' ];
				$tmp_image_file_name = $image_path_info[ 'filename' ] . '_tmp.' . $image_path_info[ 'extension' ];
				$tmp_image_source = $image_path . $tmp_image_file_name;
				$image_thumb_path = FCPATH . 'thumbs' . DS . str_replace( FCPATH, '', $image_path ) . DS;
				$new_image = $image_thumb_path . $image_file_name;
				
				copy( $image_source, $tmp_image_source );
				
			}
			
			if ( $crop AND $image_source AND is_int( $crop_x ) AND is_int( $crop_y ) AND is_int( $crop_w ) AND is_int( $crop_h ) ){
				
				$this->load->library( 'image_lib' );
				
				$config[ 'image_library' ] = 'gd2';
				$config['source_image']	= $tmp_image_source;
				$config['width']	= $crop_w;
				$config['height']	= $crop_h;
				$config['x_axis'] = $crop_x;
				$config['y_axis'] = $crop_y;
				$config['maintain_ratio'] = FALSE;
				
				$this->image_lib->initialize( $config );
				
				if ( ! $this->image_lib->crop() ){
					
					echo $this->image_lib->display_errors();
					
				}
				
				//copy( $image_source, FCPATH . 'thumbs' . DS . str_replace( FCPATH, '', $image_source ) );
				
				$this->image_lib->clear();
				
			}
			
			if ( $resize AND $image_source AND is_int( $resize_w ) AND is_int( $resize_h ) ){
				
				$this->load->library('image_lib');
				
				$config['image_library'] = 'gd2';
				$config['source_image']	= $tmp_image_source;
				$config['width']	= $resize_w;
				$config['height']	= $resize_h;
				$config['maintain_ratio'] = FALSE;
				
				$this->image_lib->initialize( $config );
				
				if ( ! $this->image_lib->resize() ){
					
					echo $this->image_lib->display_errors();
					
				}
				
				$this->image_lib->clear();
				
			}
			
			copy( $tmp_image_source, $new_image );
			unlink( $tmp_image_source );
			
			$return = TRUE;
			
		}
		else {
			
			if ( parent::_performed( 'fancybox' ) ) {
				
				log_message( 'debug', '[Plugins] Image cropper plugin initialized' );
				
				$this->voutput->append_head_stylesheet( 'image_cropper', JS_DIR_URL . '/plugins/image_cropper/cropper.css' );
				$this->voutput->append_head_script( 'image_cropper', JS_DIR_URL . '/plugins/image_cropper/cropper.js' );
				$this->voutput->append_head_script_declaration( 'image_cropper', $this->load->view( 'admin/plugins/image_cropper/default/image_cropper', NULL, TRUE ), NULL, NULL );
				
			}
			else{
				
				log_message( 'debug', '[Plugins] Image cropper plugin could not be executed! Fancybox plugin not performed!' );
				
			}
			
			$return = TRUE;
			
		}
		
		parent::_set_performed( 'image_cropper' );
		
		return $return;
		
	}
	
}
