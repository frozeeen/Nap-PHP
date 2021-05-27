<?php
	
	header('Access-Control-Allow-Methods','*');
	header("Access-Control-Allow-Origin: *");
	header('Access-Control-Allow-Credentials', 'true');
	header('Access-Control-Allow-Headers','Content-Type, Authorization');
	
	require_once '../app/bootstrap.php';

	// Init Core Library
	$init = new Core();

?>