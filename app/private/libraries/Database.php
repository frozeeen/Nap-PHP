<?php
		
	/*
	 * PHP Database OBJECT Class
	 * Connect to Database
	 * Create Prepared Statements
	 * Bind Values
	 * Return rows and results
	 */

	class Database{
		
		private $host = DB_HOSTNAME;
		private $user = DB_USERNAME;
		private $pass = DB_PASSWORD;
		private $dbname = DB_NAME;

		private $dbh; // Database Handler
		private $stmt; // Database Statement
		private $error; // Database Error

		// Prepare Statement with query
		public function query($sql){

			// Set DSN
			$dsn = 'mysql:host=' . $this->host . ';dbname=' . $this->dbname;
			
			// Set Options
			$options = array(
				PDO::ATTR_PERSISTENT => true,
				PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
              	PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8mb4'
			);

			// Create new PDO Instance
			try {
				$this->dbh = new PDO ($dsn, $this->user, $this->pass, $options);
			} catch (PDOException $e) {
				$this->error = $e->getMessage();
				echo $this->error;
			}

			// Prepare the data
			$this->stmt = $this->dbh->prepare($sql);
		}

		// Bind Values
		public function bind($param, $value, $type = null){

			// Type Check
			if( is_null($type) ){

				switch (true) {

					case is_int($value):
						$type = PDO:: PARAM_INT;
						break;

					case is_bool($value):
						$type = PDO:: PARAM_BOOL;
						break;

					case is_null($value):
						$type = PDO:: PARAM_NULL;
						break;

					default:
						$type = PDO:: PARAM_STR;

				}

			}

			$this->stmt->bindValue($param, $value, $type);
		}

		// Execute
		public function execute(){
			return $this->stmt->execute();
		}

		// Get Result Set as Array of Objects
		public function resultSet(){
			$this->execute();
			return $this->stmt->fetchAll(PDO::FETCH_OBJ);
		}

		// Get Single result as Objects
		public function single(){
			$this->execute();
			return $this->stmt->fetch(PDO::FETCH_OBJ);
		}

		// Get row Count
		public function rowCount(){
			return $this->stmt->rowCount();
		}

	}

?>