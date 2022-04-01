<?php

	/**
	 * Testing AuthAPI
	 */
	class AuthAPI extends API{
		
		function __construct(){
			$this->Authentication = $this->model("Authentication");
		}

		public function login(){
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