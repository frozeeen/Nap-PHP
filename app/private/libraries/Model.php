<?php

class Model extends Database{

	public $db;
	public $data; // Holds the data
	public $exist = false;
	public $tableName = null; // Hold the table name
	public $tableColumnsShow = null;

	public $tableRef = "id"; // The Update and get reference
	public $tableColumns = null;
	private $queryError = false;

	# GET the current data
	public function get($id, $filtered = false){

		// Check table name and terminate the program if `null`
		$this->checkTableName();

		$sql = "SELECT * FROM " . $this->tableName . "
				WHERE " . $this->tableRef . " = :" . $this->tableRef;

		if( $filtered ){
			$this->cquery($sql);
		}else{
			$this->query($sql);
		}

		// Query the passed id
		$this->bind(":" . $this->tableRef, $id);

		// Save data to global data holder object
		$this->data = $this->single();

		if( $this->checkData() ){
			$this->queryError = true;
		}else{
			$this->exist = true;
		}

		return $this->data;
	}

	# EXIST the current data at the column
	public function exist($data, $column){
		$sql = "SELECT * FROM " . $tableName . " WHERE " . $column . " = " . $data;
		$this->query($sql);
		$this->single();

		return $this->rowCount();
	}

	# ADD the current data
	public function insert($data = null){

		// Cast array into an object
		$this->data = (is_null($data)) ? (object)$this->data : (object)$data;

		// Get columns of the current table
		$this->checkTableColumns();

		// Generate query
		$sql = "INSERT INTO " . $this->tableName . "(";
		$sql_end = "";
		foreach($this->tableColumns as $key => $value) {
			if( isset( $this->data->{$value} ) ){
				$sql .= "`". $value . "`, ";
				$sql_end .= ":" . $value . ',';
			}else{
				unset( $value );
			}
		}
		$sql = rtrim($sql, ', ') . ') VALUES(' . rtrim($sql_end, ',') . ')';

		// Binding
		$this->query($sql);
		foreach ($this->data as $key => $value) {
			$this->bind(":" . $key, $value);
		}

		$this->execute();

		// Get the last ID
		$this->query("SELECT LAST_INSERT_ID() AS 'last'");
		$newID = $this->single()->last;
	
		// Push
		return $this->get($newID);
	}

	# UPDATE the current data
	public function update($updating = null){

		# Replace with passed updating data
		if( !is_null($updating) ){
			$this->data = (object)$updating;
		}else{
			$this->data = (object)$this->data;
		}

		# Checking for values
		if( $this->checkData() ) $this->err("Data is empty");

		# Get valid columns
		$this->checkTableColumns();

		# Prepare update
		$sql = 'UPDATE ' . $this->tableName . ' SET ';
		foreach ($this->data as $key => $value) {

			# Check columns
			$found = false;
			foreach ($this->tableColumns as $value) {
				if( strtolower($value) == strtolower($key) ){
					$found = true;
					break;
				}
			}
			if( !$found ) continue;

			$sql .= $key . " = :" . $key . ',';
		}

		# Remove characters and place where condition
		$sql = rtrim($sql, ',');
		$sql .= ' WHERE '. $this->tableRef .' = :' . $this->tableRef;

		$this->query($sql);

		# Bind
		$count = 0;
		foreach($this->data as $key => $value){

			# Check columns
			$found = false;
			foreach ($this->tableColumns as $column) {
				if( strtolower($column) == strtolower($key) ){
					$found = true;
					break;
				}
			}
			if( !$found ) continue;

			$this->bind(":".$key, $value);
		}
		$this->bind(":" . $this->tableRef, $this->data->{$this->tableRef});
		$this->execute();

		return $this->get($this->data->id);
	}

	# DELETE the current pulled data
	public function delete(){

		if( $this->checkData() ){
			return false;
		}

		$sql = "DELETE FROM " . $this->tableName . " WHERE " . $this->tableRef . " = :" . $this->tableRef;
		$this->query($sql);
		$this->bind(":" . $this->tableRef, $this->data->{$this->tableRef});
		$this->execute();

		return true;
	}

	# PREPARE QUERY
	public function cquery($sql){
		$sql = str_replace("*", $this->tableColumnsShow, $sql);
		$this->query($sql);
	}

	# PRIVATE MODEL HELPER
	private function checkData(){

		# Check for primary error in fetching the data
		if( $this->queryError ){
			$this->err("Data doesn't exist!");
			return false;
		}

		# Check the existence of data
		return ( is_null($this->data) || $this->data == '' );
	}
	private function checkTableName(){
		if( $this->tableName == null ){
			echo "ERROR@MODEL.PHP: Table not defined";
			exit;
		}else{
			return true;
		}
	}
	private function checkTableColumns(){
		if( $this->tableColumns == null ){
			$this->err("Column tables not defined");
		}
	}

	# Exit on error
	private function err($message){
		if( DB_ERROR )  echo 'ERROR@MODEL.PHP: ' . $message;
		exit;
	}

	# Continue with warning
	private function warn($message){
		if( DB_ERROR )  echo 'WARNING@MODEL.PHP: ' . $message;
	}
	
}

?>