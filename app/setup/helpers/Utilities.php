<?php
	 
	/**
	 * Return the word if singular or plural
	 * Note: Case sensitive, what you send - what you get
	 * 
	 * @param $n is the number or count
	 * @param $s is the singular word
	 * @param $p is the plural, if null return $s appended by "s"
	 * @return string
	 */
	function singularOrPlural($n, $s, $p = ''){

		if( intval($n) <= 1 ){
			return $s;
		}else{
			if( $p == '' ){
				return $s . 's';
			}else{
				return $p;
			}
		}
	}

	/**
	 * Return the json then exit the request
	 * @param array $data The data to be encoded to array
	 * @param array $response_code Any additional data or configurations
	 */
	function returnJSON($data = null, $response_code = 200){

		# Check if null value
		if( is_null($data) ){
			$data = [
				"code" => 404,
				"message" => "API NOT FOUND"
			];
		}

		# Set the response code
		http_response_code($response_code);

		# Main Success
		$mainSuccess = !isset( $data["status"] ) ? true : $data['status'];
		unset($data['status']);

		# Return the encoded data
		echo json_encode([
			"status" 	=> $mainSuccess,
			"data" 		=> $data
		]);
		exit;
	}

	/**
	 * Generate random ID
	 * @param $n number of string to be generated
	 * @param $s base sequence of string to be generated
	 * @return string
	 */
	function str_randomize($n = 7, $s = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"){
		return $gen = substr(str_shuffle($s), 0, $n);
	}


?>