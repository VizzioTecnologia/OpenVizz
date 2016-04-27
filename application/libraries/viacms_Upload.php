<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Viacms_Upload extends CI_Upload {
	
	protected $_file_field_value;
	
	private function _get_deeper_array_value( $array, & $var ) {
		
		if ( ! is_array( $array[ key( $array ) ] ) ) {
			
			$var = $array[ key( $array ) ];
			
		}
		else if ( is_array( $array ) AND is_array( $array[ key( $array ) ] ) ) {
			
			$var = $this->_get_deeper_array_value( $array[ key( $array ) ], $var );
			
		}
		
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Perform the file upload
	 *
	 * @return	bool
	 */
	public function do_upload( $field = 'userfile', $validate_upload_path = TRUE, $validate_filetype = TRUE, $validate_filesize = TRUE, $validate_image_dimensions = TRUE )
	{
	
	parse_str( $field, $parsed_field );
	
	if ( is_array( $parsed_field ) AND ! empty( $parsed_field ) ) {
		
		$first_key = key( $parsed_field );
		
		if ( isset( $parsed_field[ $first_key ] ) AND is_array( $parsed_field[ $first_key ] ) ) {
			
			$field = rand_str( 10 );
			
			$first_file_field_name = $first_key;
			$second_file_field_name = key( $parsed_field[ $first_key ] );
			$parsed_field_array = $parsed_field[ $first_file_field_name ];
			
			// ------------------------
			// getting the file name
			
			$files_array = isset( $_FILES[ $first_file_field_name ][ 'name' ] ) ? $_FILES[ $first_file_field_name ][ 'name' ] : FALSE;
			
			$intersect = recursive_array_intersect_key( $files_array, $parsed_field_array );
			
			$this->_get_deeper_array_value( $intersect, $this->_file_field_value );
			
			$file_name = $this->_file_field_value;
			
			// getting the file name
			// ------------------------
			
			// ------------------------
			// getting the file type
			
			$files_array = isset( $_FILES[ $first_file_field_name ][ 'type' ] ) ? $_FILES[ $first_file_field_name ][ 'type' ] : FALSE;
			
			$intersect = recursive_array_intersect_key( $files_array, $parsed_field_array );
			
			$this->_get_deeper_array_value( $intersect, $this->_file_field_value );
			
			$file_type = $this->_file_field_value;
			
			// getting the file type
			// ------------------------
			
			// ------------------------
			// getting the file tmp_name
			
			$files_array = isset( $_FILES[ $first_file_field_name ][ 'tmp_name' ] ) ? $_FILES[ $first_file_field_name ][ 'tmp_name' ] : FALSE;
			
			$intersect = recursive_array_intersect_key( $files_array, $parsed_field_array );
			
			$this->_get_deeper_array_value( $intersect, $this->_file_field_value );
			
			$file_tmp_name = $this->_file_field_value;
			
			// getting the file tmp_name
			// ------------------------
			
			// ------------------------
			// getting the file error
			
			$files_array = isset( $_FILES[ $first_file_field_name ][ 'error' ] ) ? $_FILES[ $first_file_field_name ][ 'error' ] : FALSE;
			
			$intersect = recursive_array_intersect_key( $files_array, $parsed_field_array );
			
			$this->_get_deeper_array_value( $intersect, $this->_file_field_value );
			
			$file_error = $this->_file_field_value;
			
			// getting the file error
			// ------------------------
			
			// ------------------------
			// getting the file size
			
			$files_array = isset( $_FILES[ $first_file_field_name ][ 'size' ] ) ? $_FILES[ $first_file_field_name ][ 'size' ] : FALSE;
			
			$intersect = recursive_array_intersect_key( $files_array, $parsed_field_array );
			
			$this->_get_deeper_array_value( $intersect, $this->_file_field_value );
			
			$file_size = $this->_file_field_value;
			
			// getting the file size
			// ------------------------
			
			$files_array = array(
				
				$field => array(
					
					'name' => $file_name,
					'type' => $file_type,
					'tmp_name' => $file_tmp_name,
					'error' => $file_error,
					'size' => $file_size,
					
				)
				
			);
			
		}
		
	}
	else {
		
		$files_array = $_FILES;
		
	}
	
	// Is $_FILES[$field] set? If not, no reason to continue.
		if ( ! isset( $files_array[ $field ] ) )
		{
			$this->set_error('upload_no_file_selected');
			return FALSE;
		}

		// Is the upload path valid?
		if ( $validate_upload_path AND ! $this->validate_upload_path() )
		{
			// errors will already be set by validate_upload_path() so just return FALSE
			return FALSE;
		}

		// Was the file able to be uploaded? If not, determine the reason why.
		if ( ! is_uploaded_file($files_array[$field]['tmp_name']))
		{
			$error = ( ! isset($files_array[$field]['error'])) ? 4 : $files_array[$field]['error'];

			switch($error)
			{
				case 1:	// UPLOAD_ERR_INI_SIZE
					$this->set_error('upload_file_exceeds_limit');
					break;
				case 2: // UPLOAD_ERR_FORM_SIZE
					$this->set_error('upload_file_exceeds_form_limit');
					break;
				case 3: // UPLOAD_ERR_PARTIAL
					$this->set_error('upload_file_partial');
					break;
				case 4: // UPLOAD_ERR_NO_FILE
					$this->set_error('upload_no_file_selected');
					break;
				case 6: // UPLOAD_ERR_NO_TMP_DIR
					$this->set_error('upload_no_temp_directory');
					break;
				case 7: // UPLOAD_ERR_CANT_WRITE
					$this->set_error('upload_unable_to_write_file');
					break;
				case 8: // UPLOAD_ERR_EXTENSION
					$this->set_error('upload_stopped_by_extension');
					break;
				default :   $this->set_error('upload_no_file_selected');
					break;
			}

			return FALSE;
		}


		// Set the uploaded data as class variables
		$this->file_temp = $files_array[$field]['tmp_name'];
		$this->file_size = $files_array[$field]['size'];
		$this->_file_mime_type($files_array[$field]);
		$this->file_type = preg_replace("/^(.+?);.*$/", "\\1", $this->file_type);
		$this->file_type = strtolower(trim(stripslashes($this->file_type), '"'));
		$this->file_name = $this->_prep_filename($files_array[$field]['name']);
		$this->file_ext	 = $this->get_extension($this->file_name);
		$this->client_name = $this->file_name;

		// Is the file type allowed to be uploaded?
		if ( $validate_filetype AND ! $this->is_allowed_filetype())
		{
			$this->set_error('upload_invalid_filetype');
			return FALSE;
		}

		// if we're overriding, let's now make sure the new name and type is allowed
		if ($this->_file_name_override != '')
		{
			$this->file_name = $this->_prep_filename($this->_file_name_override);

			// If no extension was provided in the file_name config item, use the uploaded one
			if (strpos($this->_file_name_override, '.') === FALSE)
			{
				$this->file_name .= $this->file_ext;
			}
			
			// An extension was provided, lets have it!
			else
			{
				$this->file_ext	 = $this->get_extension($this->_file_name_override);
			}

			if ( $validate_filetype AND ! $this->is_allowed_filetype(TRUE))
			{
				$this->set_error('upload_invalid_filetype');
				return FALSE;
			}
		}

		// Convert the file size to kilobytes
		if ( $this->file_size > 0 )
		{
			$this->file_size = round($this->file_size/1024, 2);
		}

		// Is the file size within the allowed maximum?
		if ( $validate_filesize AND ! $this->is_allowed_filesize() )
		{
			$this->set_error('upload_invalid_filesize');
			return FALSE;
		}

		// Are the image dimensions within the allowed size?
		// Note: This can fail if the server has an open_basdir restriction.
		if ( $validate_image_dimensions AND ! $this->is_allowed_dimensions() )
		{
			$this->set_error('upload_invalid_dimensions');
			return FALSE;
		}

		// Sanitize the file name for security
		$this->file_name = $this->clean_file_name($this->file_name);

		// Truncate the file name if it's too long
		if ($this->max_filename > 0)
		{
			$this->file_name = $this->limit_filename_length($this->file_name, $this->max_filename);
		}

		// Remove white spaces in the name
		if ($this->remove_spaces == TRUE)
		{
			$this->file_name = preg_replace("/\s+/", "_", $this->file_name);
		}

		/*
		 * Validate the file name
		 * This function appends an number onto the end of
		 * the file if one with the same name already exists.
		 * If it returns false there was a problem.
		 */
		$this->orig_name = $this->file_name;

		if ($this->overwrite == FALSE)
		{
			$this->file_name = $this->set_filename($this->upload_path, $this->file_name);

			if ($this->file_name === FALSE)
			{
				return FALSE;
			}
		}

		/*
		 * Run the file through the XSS hacking filter
		 * This helps prevent malicious code from being
		 * embedded within a file.  Scripts can easily
		 * be disguised as images or other file types.
		 */
		if ($this->xss_clean)
		{
			if ($this->do_xss_clean() === FALSE)
			{
				$this->set_error('upload_unable_to_write_file');
				return FALSE;
			}
		}
		
		/*
		 * Move the file to the final destination
		 * To deal with different server configurations
		 * we'll attempt to use copy() first.  If that fails
		 * we'll use move_uploaded_file().  One of the two should
		 * reliably work in most environments
		 */
		if ( ! @copy($this->file_temp, $this->upload_path.$this->file_name))
		{
			if ( ! @move_uploaded_file($this->file_temp, $this->upload_path.$this->file_name))
			{
				$this->set_error('upload_destination_error');
				return FALSE;
			}
		}
		
		/*
		 * Set the finalized image dimensions
		 * This sets the image width/height (assuming the
		 * file was an image).  We use this information
		 * in the "data" function.
		 */
		$this->set_image_properties($this->upload_path.$this->file_name);
		
		return TRUE;
	}
	
	// --------------------------------------------------------------------
	
	/**
	 * Prep Filename (just turn this function public)
	 *
	 * Prevents possible script execution from Apache's handling of files multiple extensions
	 * http://httpd.apache.org/docs/1.3/mod/mod_mime.html#multipleext
	 *
	 * @param	string
	 * @return	string
	 */
	public function _prep_filename( $filename ) {
		
		return parent::_prep_filename( $filename );
		
	}
	
}
