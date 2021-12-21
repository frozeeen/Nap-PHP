<?php

	class User extends Authentication{
		
		function __construct(){
			parent::__construct();

			$this->table_name = "users";
			$this->table_columns = ['id', 'username', 'password'];

			# Set custom authentication session name
			# Default is `api_authentication`
			$this->authentication_session	= "user_authentication";

			# Add authentication column for handle `email/username`
			# Default is `username`
			$this->authentication_handle	= "username";

			# Default is `password`
			# Hashed by password_hash()
			$this->authentication_password 	= "password";
		}

	}

?>