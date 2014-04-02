<?php
class ReportValidator extends BaseValidator {
	private $errorArray;
	public function __construct() {
		$this->errorArray = array();
	}
	public function addValidation($objReport) {
		return $this->errorArray;
	}
}
?>
