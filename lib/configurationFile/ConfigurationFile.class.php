<?php
class ConfigurationFile {
	private $applicationId;
	private $configFile;
	public function getApplicationId() {
		return $this->applicationId;
	}
	public function setApplicationId($applicationId) {
		$this->applicationId = $applicationId;
	}
	public function getConfigurationFile() {
		return $this->configFile;
	}
	public function setConfigurationFile($configFile) {
		$this->configFile = $configFile;
	}
}
?>
