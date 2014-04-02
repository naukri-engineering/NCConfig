<?php
class Report {
	private $applicationId;
	private $configPath;
	public function getApplicationId() {
		return $this->applicationId;
	}
	public function setApplicationId($applicationId) {
		$this->applicationId = $applicationId;
	}
	public function getReport() {
		return $this->configPath;
	}
	public function setReport($configPath) {
		$this->configPath = $configPath;
	}
}
?>
