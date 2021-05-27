<?php

class Student extends Model{
	
	// Table and Model configuration
	function __construct(){
		$this->tableID = "id";
		$this->tableName = "students";
		$this->tableColumnsShow = "id, name, birthday";
	}

	public function mostRecent(){
		$this->fquery("SELECT * FROM " . $this->tableName . " ORDER BY DateAdded DESC");
		return $this->resultSet();
	}

}

?>