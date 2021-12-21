<?php
	
	# |===============================================
	# | DEVELOPMENT ENVIRONMENT
	# | Auto move to production environment when not running on local network
	# |===============================================
	$devEnvironments = ['localhost', '127.0.0.1', '::1'];
	if( in_array($_SERVER['REMOTE_ADDR'], $devEnvironments) === TRUE && TRUE ){

		# MYSQL Database Configuration
		define('DB_HOSTNAME', 'localhost');
		define('DB_USERNAME', 'root');
		define('DB_PASSWORD', '');
		define('DB_NAME', 'nap');
		define('DB_ERROR', true);
		define("ENVIRONMENT", "DEV");

	# |===============================================
	# | PRODUCTION CONFIGURATION
	# |===============================================
	}else{

		# MYSQL Database Configuration
		define('DB_HOSTNAME', '');
		define('DB_USERNAME', '');
		define('DB_PASSWORD', '');
		define('DB_NAME', '');
		define('DB_ERROR', false);
		define('ENVIRONMENT', "PROD");

	}
	
?>