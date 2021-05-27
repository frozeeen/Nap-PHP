<?php

class Base extends Api{

	public function index(){
		echo json_encode(['status' => false, 'message' => 'Error API request']);
	}

	public function notFound(){
		http_response_code(404);
		echo json_encode(['status' => false, 'message' => 'The API request is not found.', 'result' => 404]);
	}

}

?>