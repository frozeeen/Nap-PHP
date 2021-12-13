<?php
	
	/**
	 * Routing for API
	 */
	$routes = [

		/**
		 * By `REQUEST_METHOD` request
		 * url will go to `TestAPI` API 
		 * depending on $_SERVER['REQUEST_METHOD']
		 */
		"tests" 		=> "TestAPI",
		"tests/test"	=> "TestAPI:test",

		/**
		 * By `CUSTOM_METHOD` request
		 * url will call the `TestAPI` API, calling `foobar` method
		 */
		"tests/foobar" 	=> "TestAPI:foobar",

		/** With Parameter */
		"tests/:id"     => "TestAPI:withParams"

	];

?>