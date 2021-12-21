<?php

	/**
	 * Authentication class
	 * - middleware
	 */
	class Authentication extends Model{

		public $is_logged_in			= false;
		public $authentication_data 	= null;
		public $authentication_session	= "api_authentication";
		public $authentication_handle 	= "username";
		public $authentication_password = "password";

		/** Check middleware information */
		public function __construct(){
			if( isset( $_SESSION[$this->authentication_session] ) ){
				$this->authentication_data = $_SESSION[$this->authentication_session];
				$this->is_logged_in = true;
			}
		}

		/**
		 * Attempt login
		 * @param string $handle
		 * @param string $password
		 */
		public function login($handle, $password){

			# Find row using `handle`
			$sql = "SELECT * FROM `$this->table_name`
					WHERE `$this->authentication_handle` = :handle";
			$this->query($sql);
			$this->bind(":handle", $handle);
			$row = $this->resultSingle();

			# Check existence
			if( $row == false ){
				return [
					"success" => false,
					"label"	  => $this->authentication_handle,
					"message" => $this->authentication_handle . " not found"
				];
			}

			# Check password validity
			if( password_verify($password, $row->{$this->authentication_password}) ){

				# Authenticate user
				unset( $row->{$this->authentication_password} );
				$this->auth($row);

				# Return authenticated user
				return [
					"success" => true,
					"message" => "Authenticated",
					"user"	  => $row
				];
			}else{
				return [
					"success" => false,
					"label"	  => $this->authentication_password,
					"message" => "Password is incorrect"
				];
			}
		}

		/** Unauthenticate user, remove session */
		public function logout(){
			if( $this->is_logged_in ){
				$this->is_logged_in = false;
				unset($_SESSION[$this->authentication_session]);
				return ["status" => true, "message" => "Successfully logged out"];
			}else{
				return ["status" => false, "message" => "Unauthorized access"];
			}
		}

		/**
		 * Attempt Register
		 * @param array $data
		 * 		$ data = [
		 * 			'column' => 'VALUE',
		 * 			...
		 * 		]
		 * @param array $options Optional. 
		 *		$options = [
		 * 			'unique' => ['COLUMN_NAME', 'COLUMN_NAME', ...]
		 * 		]
		 */
		public function register($data, $options){

			# Check all options of unique column
			foreach($options['unique'] as $option){
				$this->query(" SELECT * FROM `$this->table_name` WHERE `$option` = :data ");
				$this->bind(":data", $data[$option]);
				$row = $this->resultSingle();
				if( $row ){
					return [
						"success" 	=> false,
						"label"		=> $option,
						"message" 	=> "$option already registered"
					];
				}
			}

			# Hash password
			$data[$this->authentication_password] = password_hash($data[$this->authentication_password], PASSWORD_DEFAULT);

			# All option passed, time to register
			$registered = $this->insert($data);

			# Return registered
			return [
				"success" => true,
				"registered" => (array)$registered,
				"message" => "Registered"
			];
		}

		/**
		 * Authenticate user
		 * @param array $user_data
		 */
		public function auth($user_data){

			# Save additional information
			$user_data->logged_in = time();

			# Save to session
			$_SESSION[$this->authentication_session] = $user_data;
		}

		/**
		 * Prevent request to continue, if not authenticated
		 * @param boolean $exit_if_not_auth Optional.
		 */
		public function guarded($exit_if_not_auth = true){
			if( isset( $_SESSION[$this->authentication_session] ) === false ){
				if( $exit_if_not_auth ){
					http_response_code(400);
					echo json_encode([
						"status" => false,
						"message" => "Unauthorized action"
					]);
				}else{
					return false;
				}
			}else{
				return true;
			}
		}
		
	}

?>