<?php

class Model extends Database{

	public $db;

	// Holds the data
	public $data;
	public $exist = false;

	// Table name of the model
	public $table_name = null;

	// Global query of this model to be use by `cquery`
	public $table_column_query = null;

	// Specify the table columns
	public $table_columns = null;

	// Table `get` and `update` reference
	public $table_ref = "id"; 
	private $queryError = false;

	/**
	 * Get data by data and `table_ref`
	 * @param string|array $data
	 */
	public function get($data){

		# Check table name if set
		$this->checktable_name();

		# Transform to array
		if( !is_array($data) ){
			$data = [ $this->table_ref => $data ];
		}

		# Generate array
		$sql = "SELECT * FROM ". $this->table_name ." WHERE ";

		# Append conditional
		$count = count($data) - 1;
		$counted = 0;
		foreach ($data as $key => $value) {
			$sql .= "$key = :$key";
			if( $counted < $count ) $sql .= " AND ";
			$counted++;
		}

		# Pass query
		$this->query($sql);

		# Query based on condition
		foreach ($data as $key => $value) {
			$this->bind(":$key", $value);
		}

		# Save data to global data holder object
		$this->data = $this->resultSingle();
		$this->exist = ($this->rowCount() > 0);

		return $this->data;
	}

	/**
	 * Check if row exist
	 * @param string $column
	 * @param string $data
	 */
	public function exist($column, $data){
		$this->query("SELECT * FROM ". $this->table_name ." WHERE ". $column ." = :data");
		$this->bind(":data", $data);
		$this->resultSingle();
		return $this->rowCount();
	}

	/**
	 * Insert data
	 * @param array $data Optional.
	 */
	public function insert($data = null){

		# Cast array into an object
		$this->data = (is_null($data)) ? (object)$this->data : (object)$data;

		# Get columns of the current table
		$this->checkTableColumns();

		# Generate query
		$sql = "INSERT INTO `" . $this->table_name . "` (";
		$sql_end = "";
		foreach($this->table_columns as $key => $value) {
			if( isset( $this->data->{$value} ) ){
				$sql .= "`". $value . "`, ";
				$sql_end .= ":" . $value . ',';
			}else{
				unset( $value );
			}
		}
		$sql = rtrim($sql, ', ') . ') VALUES(' . rtrim($sql_end, ',') . ')';

		# Binding parameters
		$this->query($sql);
		foreach ($this->data as $key => $value) {
			$this->bind(":" . $key, $value);
		}
		$this->execute();

		# Get the inserted ID
		$this->query("SELECT LAST_INSERT_ID() AS 'last' FROM " . $this->table_name);
		$newID = $this->resultSingle()->last;
	
		# Get inserted data
		return $this->get($newID);
	}

	/**
	 * Update data in the database
	 * @param array $data Optional.
	 * @param array $where
	 */
	public function update($data = null){

		# Get referencing data
		if( isset($data['id']) ){
			$row_ref = $data[$this->table_ref];
		}else{
			$row_ref = $this->data->{ $this->table_ref };
		}

		# Replace with passed updating data if exist
		$data = $data ? (object)$data : (object)$this->data;

		# Get valid columns
		$this->checkTableColumns();

		# Prepare update, check for table columns
		$sql = 'UPDATE ' . $this->table_name . ' SET ';
		foreach ($data as $key => $value) {
			if( in_array($key, $this->table_columns) ){
				$sql .= $key . " = :" . $key . ',';
			}
		}

		# Trim invalid characters
		$sql = rtrim($sql, ',');
		$sql .= ' WHERE '. $this->table_ref .' = :' . $this->table_ref;
		$this->query($sql);

		# Bind parameters
		$count = 0;
		foreach($data as $key => $value){
			if( in_array($key, $this->table_columns) ){
				$this->bind(":".$key, $value);
			}
		}
		
		# Bind the query
		$this->bind(":" . $this->table_ref, $row_ref);
		$this->execute();

		# Return the updated data
		return $this->get($row_ref);
	}

	/**
	 * DELETE the current pulled data or passed data
	 * @param string|arrray $condition Optional.
	 */
	public function delete($condition = null){

		# Transform to array
		if( !is_array($condition) ){
			$condition = [ $this->table_ref => $condition ];
		}

		# Build query, bind parameter and execute query
		$sql = "DELETE FROM " . $this->table_name . " WHERE ";

		# Append conditional
		$count = count($condition) - 1;
		$counted = 0;
		foreach ($condition as $key => $value) {
			$sql .= "$key = :$key";
			if( $counted < $count ) $sql .= " AND ";
			$counted++;
		}

		# Bind parameters
		$this->query($sql);
		foreach ($condition as $key => $value) {
			$this->bind(":" . $key, $value);
		}

		$this->execute();
		return $this->rowCount() > 0;
	}

	/**
	 * Replace wildcard by the `table_column_query` 
	 * @param string $sql
	 * @param boolean $wildcard
	 */
	public function cquery($sql, $wildcard = "*"){
		$sql = str_replace($wildcard, $this->table_column_query, $sql);
		$this->query($sql);
	}

	# PRIVATE MODEL HELPER
	private function checktable_name(){
		if( $this->table_name == null ){
			echo "ERROR@MODEL.PHP: Table not defined";
			exit;
		}else{
			return true;
		}
	}
	private function checkTableColumns(){
		if( $this->table_columns == null ){
			$this->err("Column tables not is defined");
		}
	}

	/**
	 * Throw error message
	 * @param string $message
	 */
	private function err($message){
		if( DB_ERROR )  echo 'ERROR@MODEL.PHP: ' . $message;
		exit;
	}

	/**
	 * Throw warning message
	 * @param string $message
	 */
	private function warn($message){
		if( DB_ERROR )  echo 'WARNING@MODEL.PHP: ' . $message;
	}
	
}

?>