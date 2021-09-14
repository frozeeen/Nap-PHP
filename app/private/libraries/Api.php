<?php

	/**
	 * Base Controller
	 * Loads Models and Views
	 */
	
	class Api{

		/**
		 * Load the specific passed model
		 * @param array $model Model file name
		 */
		public function model($model){

			# Require model file
			require_once '../app/setup/models/' . $model . '.php';

			# Instantiate model
			return new $model();

		}

		/**
		 * Return the json then exit the request
		 * @param array $data The data to be encoded to array
		 * @param array $additional Any additional data or configurations
		 */
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