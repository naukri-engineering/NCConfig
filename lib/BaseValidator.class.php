<?php
class BaseValidator {
	public function blankCheck($string) {
		if($string)
			return true;
		return false;
	}
	public function minLengthCheck($string,$length) {
		if(strlen($string)>=$length)
			return true;
		return false;
	}
	public function maxLengthCheck($string,$length) {
		if(strlen($string)<=$length)
			return true;
		return false;
	}
}
?>
