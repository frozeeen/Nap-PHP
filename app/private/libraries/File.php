<?php

/**
 * FMVC - File manager 
 *
 * DOCUMENTATION
 *	1. Init the File class and pass the location to be uploaded
 *  2. (Optional) edit initialize public variables
 *  3. Call the check method passing the array of files for `CHECKING`
 *  4. Call the upload method passing the array of files for `UPLOADING`
 */
class File{
	
	private $DEFAULT_location = "";
	public $DEFAULT_maximum_size = 5242880;
	public $result = false;
	public $files = [];

	/**
	 * FIle constructor
	 * @param `location` setup the default location for the upload
	 **/
	public function __construct($location = null){

		// Set the active location of the uploading files
		// If exist
		if( $location != null ){
			$this->DEFAULT_location = $location;
		}else{
			$this->DEFAULT_location = $_SERVER['DOCUMENT_ROOT'] . "/frozen/fmvc/public/uploads/";
		}
	}

	/**
	 * Error Checking then handle
	 * @param `files` contains all the files
	 * @param `file_name` contain the id or the key of the said image
	 * @param `array` contains all the returned parameters
	 **/
	public function handle($files, $file_name = "", $upload = false){

		# Orgaize files of the multiple uploads from the $_FILES
		$allowed_type = ["png", "jpeg", "jpg", "docx", "pdf", "ppt"];

		# Organize `multiple` uploads
		if( is_array($files['name']) ){
			$files = $this->organize($files);

		# Organize `single` upload
		}else{

			// Store, reset, and update
			$temp = $files;
			$files = [];
			array_push($files, $temp);

		}

		# Return if no value
		if( $files[0]['name'] == '' ){
			return "No file is uploaded";
		}

		for( $i = 0, $l = count($files); $i < $l; $i++ ){

			# Check for the Extension name
			$type = explode('.', $files[$i]['name']);
			$type = strtolower( end($type) );

			if( !in_array($type, $allowed_type) ){
				return "Invalid file type";
			}

			# Check for error
			if( $files[$i]['error'] == 1 ){
				return "Corrupted file";
			}

			# Check for maximum sizes
			if( $files[$i]['size'] >= $this->DEFAULT_maximum_size ){
				return "Files is too big";
			}

			# Generate the actual name

			// Only one [filename].[type] || Bunch [filename_count].[type]
			$actual_name = ( $i + 1 == $l && $i == 0 ) ? $file_name . "." . $type : $file_name . "_" . $i . "." . $type;

			# Merge the files data and the actual name array
			$files[$i] = array_merge($files[$i], ["actual_name" => $actual_name]);

		}

		# Check for upload, or return `true` if no error
		if( $upload ){
			$this->files = $files;
			return $this->upload($files);
		}else{
			$this->files = $files;
			return $files;
		}
	}

	/**
	 * Organize the files
	 * @param `files` files to organize
	 * @return `array` organized files
	 **/
	public function organize($files){

		// Returning array
		$returning = [];

		// Loop every files
		for( $i = 0, $l = count( $files['name'] ); $i < $l; $i++ ){

			$inner = [
				'name' => $files['name'][$i],
				'type' => $files['type'][$i],
				'size' => $files['size'][$i],
				'tmp_name' => $files['tmp_name'][$i],
				'error' => $files['error'][$i]
			];

			array_push($returning, $inner);

		}

		// Return the new data
		return $returning;
	}

	/** 
	 * File uploading 
	 * @param `files` contains all the files
	 **/
	public function upload($files){

		// Upload every image
		for( $i = 0, $l = count($files); $i < $l; $i++ ){

			// Move to the upload folder
			if( !move_uploaded_file( $files[$i]['tmp_name'] , $this->DEFAULT_location . $files[$i]['actual_name']) ){
				$this->result = false;
				return false;
			}

		}

		// Return flag
		$this->result = true;
		return true;
	}

}

?>