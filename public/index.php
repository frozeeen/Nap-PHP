<?php
	header('Access-Control-Allow-Methods','*');
	header("Access-Control-Allow-Origin: *");
	header('Access-Control-Allow-Credentials', 'true');
	header('Access-Control-Allow-Headers','Content-Type, Authorization');
	header("Content-Type: application/json; charset=UTF-8");
	
	require_once '../app/bootstrap.php';

	$init = new Core();
?>