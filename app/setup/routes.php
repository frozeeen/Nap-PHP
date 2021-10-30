<?php
	
	/**
	 * Routing for API
	 */
	$routes = [

		/**
		 * By `REQUEST_METHOD` request
		 * url will go to `test` API 
		 * depending to its $_SERVER['REQUEST_METHOD']
		 */
		"tests" 		=> "tests",

		/**
		 * By `DEFINED_METHOD` request
		 * url will go to `test` API calling `foobar` method
		 */
		"tests/foobar" 	=> "tests.foobar",

		/** With Parameter */
		"tests/:id"		=> "tests"

	];

?>