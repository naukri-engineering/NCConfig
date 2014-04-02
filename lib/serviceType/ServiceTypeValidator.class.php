<?php
class ServiceTypeValidator extends BaseValidator {
	private $errorArray;
	public function __construct() {
		$this->errorArray = array();
	}
	public function addValidation($objServiceType) {
		return $this->errorArray;
	}
}
?>
