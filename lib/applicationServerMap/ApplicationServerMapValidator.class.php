<?php
class ApplicationServerMapValidator extends BaseValidator {
	private $errorArray;
	public function __construct() {
		$this->errorArray = array();
	}
	public function addValidation($objApplicationServerMap) {
		return $this->errorArray;
	}
}
?>
