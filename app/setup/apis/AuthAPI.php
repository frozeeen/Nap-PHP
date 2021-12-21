<?php

	/**
	 * Testing AuthAPI
	 */
	class AuthAPI extends API{
		
		function __construct(){
			$this->UserAuth = $this->model("User");
		}

		public function login(){

			# Validaate incoming input
			new Validate([
				[
					"value" => $this->request->username,
					"key"	=> "username",
					"label"	=> "Username",
					"check" => "type:username|min:3|max:50"
				],
				[
					"value" => $this->request->password,
					"key"	=> "password",
					"label"	=> "Password",
					"check" => "min:8|max:150"
				]
			]);

			# Attempt login
			$return = $this->UserAuth->login($_POST['username'], $_POST['password']);

			# Return login
			$this->json([
				"status"	=> $return['success'],
				"message" 	=> $return['message']
			]);
		}

		public function register(){

			# Validate incoming input
			new Validate([
				[
					"value" => $_POST['username'],
					"key"	=> "username",
					"label"	=> "Username",
					"check" => "type:username|min:3|max:50"
				],
				[
					"value" => $_POST['password'],
					"key"	=> "password",
					"label"	=> "Password",
					"check" => "min:8|max:150"
				]
			]);

			# Register
			$return = $this->UserAuth->register([
				"username"	=> $_POST['username'],
				"password"	=> $_POST['password']
			], [
				"unique"	=> ['username']
			]);


			# Return response
			$this->json([
				"status"	=> $return['success'],
				"message" 	=> $return['message']
			]);
		}

		public function logout(){
			$this->json($this->UserAuth->logout());
		}

	}

?>