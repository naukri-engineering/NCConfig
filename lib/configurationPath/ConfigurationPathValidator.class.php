<?php
class ConfigurationPathValidator extends BaseValidator {
	private $errorArray;
	public function __construct() {
		$this->errorArray = array();
	}
	public function addValidation($objConfigurationPath) {
		return $this->errorArray;
	}
}
?>
