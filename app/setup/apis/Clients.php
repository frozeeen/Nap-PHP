<?php

class Clients extends Api{
	
	public function __construct(){
		$this->dbStudent = $this->model("student");
	}

	public function get(){

		$this->dbStudent->data = [
			"name" => "Inserted student"
		];
		echo "ID of inserted data is : " . $this->dbStudent->insert();

	}

}

?>