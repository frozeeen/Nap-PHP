<?php

	/**
	 * Test class
	 */
	class Tests extends Api{

		public function get(){
			echo "You get this using " . $_SERVER['REQUEST_METHOD'] . " request";
		}

		public function post(){
			echo "Posting something using " . $_SERVER['REQUEST_METHOD'] . " request?";
		}

		public function put(){
			echo "Putting using " . $_SERVER["REQUEST_METHOD"] . " request";
		}

		public function delete(){
			echo "You're now scrubbing something using " . $_SERVER["REQUEST_METHOD"] . " request";
		}

		public function foobar(){
			echo "You're now accessing this method by DEFINED_METHOD";
		}

	}

?>