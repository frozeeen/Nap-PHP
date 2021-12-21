<?php

	/***
	 * NAP - Vanilla PHP REST-API Boilerplate
	 * https://github.com/frozeeen/Nap-PHP
	 ***/

	/** API Boilerplate Configurations */
	define('APPROOT', dirname( __FILE__ ) . '\\');
	
	/*** 
	 * Once the API is loaded, this bootstrap will start the sequence
	 * by calling the core constructor which will load the required controller
	 * based on the client request
	 ***/
	session_start();
	require_once 'setup/configs/config.php';
	require_once 'setup/helpers/Utilities.php';

	/** Error Reporting */
	if( ENVIRONMENT != "DEV" ){
		error_reporting(0);
		set_error_handler(function(){
			http_response_code(500);
			echo json_encode([
				"status" => false,
				"message" => "Something went wrong under the hood"
			]); exit;
		});
	}

	/** Load primary cores */
	spl_autoload_register(function($classname){
		require_once 'private/Cores/' . $classname . '.php';
	});
?>