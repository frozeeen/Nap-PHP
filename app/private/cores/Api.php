<?php

	/**
	 * API base controller
	 * - csrf
	 * - load models
	 * - responses
	 */
	
	class Api{

		public $request = [];
		private $CSRF_SESSION_NAME = "csrf-token";

		/**
		 * Check `REQUEST_METHOD`
		 * @param string $expectingMethod
		 */
		public function expectMethod($expectingMethod){
			if( $_SERVER["REQUEST_METHOD"] != strtoupper($expectingMethod) ){
				$this->json([
					"message" => $_SERVER["REQUEST_METHOD"] . " request is not allowed to perform this action"
				], 400);
			}else{
				return true;
			}
		}

		/**
		 * Throw error once the CSRF Token is invalid
		 */
		public function csrfVerify(){
			if( $_SERVER["REQUEST_METHOD"] != "GET" && $this->csrf_check_token() === false ){
				$this->json([
					"status" => false,
					"message" => "CSRF Token is Invalid."
				], 400);
			}else{
				return true;
			}
		}

		/**
		 * Get or refresh CSRF Token
		 * @param boolean $refresh
		 */
		public function csrfGetToken($refresh = false){
			if( empty($_SESSION[$this->CSRF_SESSION_NAME]) || $refresh === true ){
				$_SESSION[$this->CSRF_SESSION_NAME] = bin2hex(random_bytes(32));
			}
			return $_SESSION[$this->CSRF_SESSION_NAME];
		}	

		/**
		 * Check CSRF if valid
		 * @param string $token
		 * @return bool
		 */
		public function csrfCheckToken($token = null){
			if( empty($_SESSION[$this->CSRF_SESSION_NAME]) || isset($this->_token) === false ){
				return false;
			}else{
				$token = ( is_null($token) ) ? $this->_token : $token;
				return hash_equals($_SESSION[$this->CSRF_SESSION_NAME], $token);
			}
		}

		/**
		 * Load the specific model
		 * @param string $model Mode file name
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

			# Check if an API is found
			if( is_null($data) ){
				$data = [ "message" => "API NOT FOUND" ];
				$data['status'] = false;
				$response_code = 404;
			}

			# Set the response code
			http_response_code($response_code);

			# Main Success
			$mainStatus = !isset( $data["status"] ) ? true : $data['status'];
			unset($data['status']);

			# Return the encoded data
			echo json_encode([
				"status" 	=> $mainStatus,
				"data" 		=> $data
			]);
			exit;
		}

	}

?>