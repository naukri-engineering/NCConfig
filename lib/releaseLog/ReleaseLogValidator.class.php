<?php
class ReleaseLogValidator extends BaseValidator {
	private $errorArray;
	public function __construct() {
		$this->errorArray = array();
	}
	public function addValidation($objReleaseLog) {
		return $this->errorArray;
	}
}
?>
