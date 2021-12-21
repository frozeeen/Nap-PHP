<?php
	 
	/**
	 * Return the word if singular or plural
	 * Note: Case sensitive, what you send - what you get
	 * 
	 * @param integer $n is the number or count
	 * @param string $s is the singular word
	 * @param string $p is the plural, if null return $s appended by "s"
	 * @return string
	 */
	function singular_or_plural($n, $s, $p = ''){

		if( intval($n) <= 1 ){
			return $s;
		}else{
			if( $p == '' ){
				return $s . 's';
			}else{
				return $p;
			}
		}
	}

	/**
	 * Load third party libraries / package from `setup/third-parties` folder
	 * @param string $third_party_name 
	 */
	function load_composer_package($third_party_name){
		require_once APPROOT . "setup". DIRECTORY_SEPARATOR ."composer". DIRECTORY_SEPARATOR . $third_party_name;
	}

	/**
	 * Generate random ID
	 * @param integer $n number of string to be generated
	 * @param string $s base sequence of string to be generated
	 * @return string
	 */
	function str_randomize($n = 7, $s = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789"){

		return $gen = substr(str_shuffle($s), 0, $n);
	}


?>