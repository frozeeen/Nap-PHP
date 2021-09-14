<?php

	/***
	 * PURE PHP REST-API boilerplate
	 ***/
	
	/*** 
	 * Once the API is loaded, this boostrap will start the sequence
	 * by calling the core which load the required controller
	 * based on the clients request
	 ***/
	require_once 'setup/config/config.php';
	require_once 'setup/helpers/Utilities.php';

	spl_autoload_register(function($classname){
		require_once 'private/libraries/' . $classname . '.php';
	});
?>