<?php

	/**
	 * Base Controller
	 * Loads Models and Views
	 */
	
	class Api{

		/**
		 * Check if correct method
		 * @param string $expectingMethod
		 */
		public function expect_method($expectingMethod){
			if( $_SERVER["REQUEST_METHOD"] != $expectingMethod ){
				$this->json([
					"message" => $_SERVER["REQUEST_METHOD"] . " request is not allowed to perform this action"
				], ['code' => 500]);
			}
		}

		/**
		 * Throw error once the CSRF Token is invalid
		 */
		public function csrf_verify(){
			if( $_SERVER["REQUEST_METHOD"] != "GET" && $this->csrf_check_token() === false ){
				$this->json([
					"success" => false,
					"message" => "CSRF Token is Invalid."
				]);
			}
		}

		/**
		 * Verify CSRF Token
		 * @param boolean $refresh
		 */
		private $CSRF_SESSION_NAME = "csrf-token";
		public function csrf_get_token($refresh = false){
			if( empty($_SESSION[$this->CSRF_SESSION_NAME]) || $refresh === true ){
				$_SESSION[$this->CSRF_SESSION_NAME] = bin2hex(random_bytes(32));
			}
			return $_SESSION[$this->CSRF_SESSION_NAME];
		}

        /**
         * Check csrf token
         * @param string $token
         * @return bool
         */
		public function csrf_check_token($token = null){
			if( empty($_SESSION[$this->CSRF_SESSION_NAME]) ){
				return false;
			}else{
				$token = ( is_null($token) ) ? $_POST["_token"] : $token;
				return hash_equals($_SESSION[$this->CSRF_SESSION_NAME], $token);
			}
		}

        /**
         * Load the specific passed model
         * @param string $model Model file name
         * @return mixed
         */
		public function model($model){

			# Require model file
			require_once '../app/setup/models/' . $model . '.php';

			# Instantiate model
			return new $model();
		}

        /**
         * Return the json then exit the request
         * @param array $data The data to be encoded to array
         * @param int $response_code Any additional data or configurations
         */
		public function json($data, $response_code = 200){

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

	}

?>