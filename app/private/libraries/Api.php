<?php

	/**
	 * Base Controller
	 * Loads Models and Views
	 */
	
	class Api{

		public function __construct(){
			if( $_SERVER["REQUEST_METHOD"] != "GET" && $this->csrf_check_token() === false ){
				$this->json([
					"success" => false,
					"message" => "CSRF Token is Invalid."
				]);
			}
		}

		/**
		 * Verify CSRF Token
		 */
		private $CSRF_SESSION_NAME = "csrf-token";
		public function csrf_get_token(){
			if( empty($_SESSION[$this->CSRF_SESSION_NAME]) ){
				$_SESSION[$this->CSRF_SESSION_NAME] = bin2hex(random_bytes(32));
			}
			return $_SESSION[$this->CSRF_SESSION_NAME];
		}	

		/**
		 * Check csrf token
		 * @param string $token
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
		 * @param array $model Model file name
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
		 * @param array $additional Any additional data or configurations
		 */
		public function json($data, $additional = []){

			# Provide the response code
			http_response_code( isset($additional['code']) ? $additional['code'] : 200 );

			# Return the encoded data
			echo json_encode([
				"success" 	=> true,
				"data" 		=> $data
			]);
			exit;
		}

	}

?>