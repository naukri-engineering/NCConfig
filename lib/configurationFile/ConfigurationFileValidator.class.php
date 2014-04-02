<?php
class ConfigurationFileValidator extends BaseValidator {
	private $errorArray;
	public function __construct() {
		$this->errorArray = array();
	}
	public function addValidation($objConfigurationFile) {
		return $this->errorArray;
	}
}
?>
