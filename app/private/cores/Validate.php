<?php

/**
 * Validate class
 * $items = [
 * 		[
 * 			"value"		=> "Value to be checked",
 * 			"key"		=> "The key of the returned object",
 * 			"label"		=> "The name of the value in error message",
 * 			"check"		=> "required|type:TYPE|min:INTEGER|max:INTEGER"
 * 		],
 * 		....
 * ]
 */
class Validate{
	private $value_required = false;
	private $value_length = 0;

	public $error = false;
	public $result = [];

	/**
	 * Constructor
	 * @param array $items
	 * @param boolean $throwError
	 */
	public function __construct($items, $throwError = true){
	
		# Loop every data
		foreach($items as $key => $item){

			# Prepare the data
			$item['value'] = trim($item['value']);

			# Exec data
			$this->value_length 	= strlen($item['value']);
			$this->value_required 	= false;

			# Check if there's check flag
			if( isset($item['checks']) === false ){
				continue;
			}
			
			# Perform every check
			$checks = explode("|", $item['checks']);
			foreach ($checks as $key => $check){
				$methodSplit = explode(":", $check);
				
				# Check if the method exist
				if( method_exists($this, $methodSplit[0]) )	{

					# Check if the value is required
					if( $methodSplit[0] == "required" ) $this->value_required = true;

					# Set default value
					$this->result[ $item['key'] ]['value'] = $item['value'];
					$this->result[ $item['key'] ]['valid'] = true;

					# Execute checker
					if( isset($methodSplit[1]) ){
						$this->{ $methodSplit[0] }($item, $methodSplit[1]);
					}else{
						$this->{ $methodSplit[0] }($item);
					}
				}else{
					echo "Not exist";
				}

				# Don't check further
				if( $this->result[ $item['key'] ]['valid'] == false ){
					break;
				}

			}

			# Perform wrapping up check
			if( $item['value'] == '' && $this->value_required == false ){
				$this->result[ $item['key'] ]['valid'] = true;
				unset( $this->result[ $item['key'] ]['message'] );
			}

		}

		# Check results for errors
		foreach($this->result as $key => $item){
			if( $item['valid'] == false ){
				$this->error = true;
				break;
			}
		}

		# Exec flag
		if( $this->error && $throwError ){
			http_response_code(400);
			echo json_encode([
				"status" 	=> false,
				"errors" 	=> $this->result
			]);
			exit;
		}
	}

	/**
	 * `Required` checker
	 * @param array $item
	 */
	public function required($item){
		if( $item['value'] == '' ){
			$this->result[$item['key']]['valid'] = false;
			$this->result[$item['key']]['message'] = $item['label'] . " is required";
		}
	}

	/**
	 * `Min` checker
	 * @param array $item
	 * @param string $constraint
	 */
	public function min($item, $constraint){
		if( $this->value_length < intval($constraint) ){
			$this->result[$item['key']]['valid'] = false;
			$this->result[$item['key']]['message'] = $item['label'] . " should not be less than $constraint characters";
		}	
	}

	/**
	 * `Max` checker
	 * @param array $item
	 * @param string $constraint
	 */
	public function max($item, $constraint){
		if( $this->value_length > intval($constraint) ){
			$this->result[$item['key']]['valid'] = false;
			$this->result[$item['key']]['message'] = $item['label'] . " should not be more than $constraint characters";
		}	
	}

	/**
	 * `Type` checker
	 * @param array $item
	 * @param string $constraint
	 */
	public function type($item, $constraint){
		switch($constraint){
			case 'email':
				if( filter_var($item['value'], FILTER_VALIDATE_EMAIL) === false ){
					$this->result[$item['key']]['valid'] = false;
					$this->result[$item['key']]['message'] = $item['label'] . " is not a valid email address";
				}
				break;
			case "username":
				if( preg_match("/^[0-9a-zA-ZñÑ_\s]+$/", $item['value']) != 1 ){
					$this->result[$item['key']]['valid'] = false;
					$this->result[$item['key']]['message'] = $item['label'] . " is not a valid username";
				}
				break;
		}
	}
	
}

?>