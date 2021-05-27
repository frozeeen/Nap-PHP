<?php
	 
	/**
	 * @description return the word if singular or plural (case sensitive)
	 *
	 * @param int $n - the number or count
	 * @param string $s - the singular word
	 * @param string $p - the plural, if null return $s appended by "s"
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
