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
	 * @param array $additional Any additional data or configurations
	 */
	function returnJSON($data, $additional = []){

		# Check if null value
		if( is_null($data) ){
			$data = [
				"code" => 404,
				"message" => "API NOT FOUND"
			];
			$additional["code"] = 404;
		}

		# Provide the response code
		http_response_code( isset($additional['code']) ? $additional['code'] : 200 );

		# Main Success
		$mainSuccess = !isset( $data["success"] );
		unset($data['success']);

		# Return the encoded data
		echo json_encode([
			"success" 	=> $mainSuccess,
			"data" 		=> $data
		]);
		exit;
	}


?>