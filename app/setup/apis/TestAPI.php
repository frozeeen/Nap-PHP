<?php

	/**
	 * Test class
	 */
	class TestAPI extends Api{

		public function get(){
			$this->json([
				"message" => "You get this using " . $_SERVER['REQUEST_METHOD'] . " request"
			]);
		}

		public function post(){
			$this->json([
				"message" => "Posting something using " . $_SERVER['REQUEST_METHOD'] . " request?"
			]);
		}

		public function put(){
			$this->json([
				"message" => "Updating something using " . $_SERVER['REQUEST_METHOD'] . " request?"
			]);
		}

		public function delete(){
			$this->json([
				"message" => "You're now scrubbing something using " . $_SERVER["REQUEST_METHOD"] . " request"
			]);
		}

		public function foobar(){
			$this->json([
				"message" => "You're now accessing this method by DEFINED_METHOD, any REQUEST_METHOD will be accepted here"
			]);
		}

		public function withParams($params){
			$this->json([
				"params" => $params,
				"message" => "Accessing this page with params"
			]);
		}

	}

?>