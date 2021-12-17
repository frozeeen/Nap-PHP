<?php

/**
 * Frozeeen - Validation Class
 * "[postName]" => [
 * 		"value" => [any],
 *		"skip" => [boolean],
 *		"min" => [integer],
 *		"max" => [integer],
 *		"sensitive" => [boolean]
 * ]
 * 
 * DOCUMENTATION
 *	1. Init the Validate class and pass the associative array
 *  2. (Optional) edit initialize public variables
 *  3. Call the check method ( this will return null )
 *  4. Get the data from the public `resultSet` for full result,
 *	   Get the data from the public `result` for global, true if all passed
 */
class Validate{

	public $autorun = true;
	public $result = true;
	public $warning = true;
	public $validating_data;
	public $exitOnError = true;
	public $resultSet = [];

	public function __construct($onrun = true){
		$this->autorun = true;
	}

	/**
	 * Construct the data of this class
	 * @param array `v_data` the array of data passed to the incapsulated data
	 * @param array `flags` the flag of the passed data (see constructor for ref)
	 **/
	public function init($v_data = [], $v_param = []){

		# INITIALIZATION
		if( !is_array($v_data) ) $this->error("Passed data is not an array");
		$this->validating_data = $v_data;

		# AUTO RUN
		if( $this->autorun ) $this->run();

		# Flags 
		if( isset($v_param['exitOnError']) ){
			$this->exitOnError = $v_param['exitOnError'];
		}
	}

    /**
     * Validate data
     * Purpose: Validate the `validating_data` in this class
     * @return void `array-object`
     */
	public function run(){

		// Check every data
		// boolean result push to the `validated` array
		$v_status = true;
		$v_name = "";
		$v_len = 0;

		foreach( $this->validating_data as $key => $data ){

			// Data contains array of constraints and the datas
			$v_status = true;
			$v_name = isset( $data['name'] ) ? $data['name'] : $key;
			$v_len = strlen($data['value']);
			$this->resultSet = array_merge($this->resultSet, [
				$key => [
					"message" => null
				]]
			);

			# CHECK IF A NUMBER
			if( isset($data['number']) && !is_numeric($data['value']) ){
				$v_status = $v_name . " is not a number";
			}
			
			# CHECK FOR CHARACTER SENSITIVITY
			if( isset( $data["alphaNumeric"] ) ){
				if( preg_match("/^[0-9a-zA-ZñÑ_\s]+$/", $data['value']) != 1 ){
					$v_status = $v_name . " contains invalid characters";
				}
			}

			# CHECK FOR CUSTOM REGEX
			if( isset( $data['regex'] ) ){
				if( preg_match($data['regex'], $data['value']) != 1 ){
					$v_status = $v_name . " contains invalid characters";
				}
			}

			# CHECK FOR MINIMUM
			if( isset($data['min']) && $v_len < $data['min'] ){
				$v_status = $v_name . " should be more than " . $data['min'] . " characters";
			}

			# CHECK FOR MAXIMUM
			if( isset($data['max']) && $v_len > $data['max'] ){
				$v_status = $v_name . " should be not greater than " . $data['max'] . " characters";
			}

			# CHECK IF REQUIRED
			if( isset($data['required']) && $v_len == 0 ){
				$v_status = $v_name . " is required";
			}

			# CHEKCK FOR EMAIL
			if( isset($data['email']) && !filter_var($data['value'], FILTER_VALIDATE_EMAIL) ){
				$v_status = "Invalid email";
			}

			# PUSH DATA according to the checked data above
			$this->resultSet[$key]["label"] = $data["name"];
			$this->resultSet[$key]['message'] = $v_status;

			# GLOBAL RESULT
			if( $this->result == true && $v_status !== true ){
				$this->result = false;
			}

		}

		if( $this->exitOnError && !$this->result ){
			$error = array_merge(["success" => false], ["errors" => $this->resultSet]);
			echo json_encode($error);
			exit;
		}
	}

	/*
	 * Throw an error
	 * @return an `echo` and kill
	 */
	private function error($message){
		echo "CLASS@VALIDATE ERROR: " . $message;
		exit;
	}

	/*
	 * Throw a warning
	 * @return an `echo`
	 */
	private function warn($message){
		if( $this->warning ){
			echo "CLASS@VALIDATE WARN: " . $message;
		}
	}
	
}

?>