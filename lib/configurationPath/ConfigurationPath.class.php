<?php
class ConfigurationPath {
	private $applicationId;
	private $configPath;
	public function getApplicationId() {
		return $this->applicationId;
	}
	public function setApplicationId($applicationId) {
		$this->applicationId = $applicationId;
	}
	public function getConfigurationPath() {
		return $this->configPath;
	}
	public function setConfigurationPath($configPath) {
		$this->configPath = $configPath;
	}
}
?>
