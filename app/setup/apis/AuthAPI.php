<?php

	/**
	 * Testing AuthAPI
	 */
	class AuthAPI extends API{
		
		function __construct(){
			$this->Authentication = $this->model("Authentication");
		}

		public function login(){

			new Validate([
				[
					# The value to be checked
					"value" => $this->request("username"),
		
					# The key to this item when it is converted as an object
					"key"	=> "username",
		
					# The "key" on object
					"label"	=> "Username",
		
					# Several checks to be perform, separated by pipe "|"
					"checks" => "required|min:3|max:50"
				],
				[
					"value" => $this->request("password"),
					"key"	=> "password",
					"label"	=> "Password",
					"checks" => "required|min:8|max:150"
				]
			], true);
		
			$return = $this->Authentication->login($this->request->username, $this->request->password);
		
			$this->json([
				"status"	=> $return['success'],
				"message" 	=> $return['message']
			]);
		}

		public function check(){
			$this->json([ "message" => $this->Authentication->guarded() ? "Logged in" : "Logged out"]);
		}

		public function register(){
			$return = $this->Authentication->register([
				"username"	=> $this->request->username,
				"password"	=> $this->request->password
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
			$this->json($this->Authentication->logout());
		}

	}

?>