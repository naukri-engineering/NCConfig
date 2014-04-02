<?php
class ReleaseLog {
	private $applicationId;
	private $configPath;
	public function getApplicationId() {
		return $this->applicationId;
	}
	public function setApplicationId($applicationId) {
		$this->applicationId = $applicationId;
	}
	public function getReleaseLog() {
		return $this->configPath;
	}
	public function setReleaseLog($configPath) {
		$this->configPath = $configPath;
	}
}
?>
