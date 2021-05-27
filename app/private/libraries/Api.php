<?php

	/*
	 * Base Controller
	 * Loads Models and Views
	 */
	
	class Api{

		# Load the API model
		public function model($model){

			# Require model file
			require_once '../app/setup/models/' . $model . '.php';

			# Instantiate model
			return new $model();

		}

		# Helper to return the JSON
		public function json($data, $additional = []){

			# Provide the response code
			http_response_code( isset($additional['code']) ? $additional['code'] : 200 );

			# Return the encoded data
			echo json_encode([
				"success" 	=> true,
				"data" 		=> $data
			]);
			exit;
		}

	}

?>