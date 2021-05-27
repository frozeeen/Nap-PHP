<?php
	 
	/**
	 * Return the word if singular or plural
	 * Note: Case sensitive, what you send - what you get
	 *
	 * @param $n is the number or count
	 * @param $s is the singular word
	 * @param $p is the plural, if null return $s appended by "s"
	 * @return string
	 */
	function singularOrPlural($n, $s, $p = ''){

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

?>