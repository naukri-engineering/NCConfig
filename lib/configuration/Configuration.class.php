<?php
class Configuration {
	private $configurationId;
	private $applicationId;
	private $serviceId;
	private $systemUserId; //Active
	private $systemUserId2; //Inactive
	private $configurationTag;

	public function getConfigurationId() {
		return $this->configurationId;
	}
	public function setConfigurationId($configurationId) {
		$this->configurationId = $configurationId;
	}
	public function getApplicationId() {
		return $this->applicationId;
	}
	public function setApplicationId($applicationId) {
		$this->applicationId = $applicationId;
	}
	public function getServiceId() {
		return $this->serviceId;
	}
	public function setServiceId($serviceId) {
		$this->serviceId = $serviceId;
	}
	public function getSystemUserId() {
		return $this->systemUserId;
	}
	public function setSystemUserId($systemUserId) {
		$this->systemUserId = $systemUserId;
	}
	public function getSystemUserId2() {
		return $this->systemUserId2;
	}
	public function setSystemUserId2($systemUserId2) {
		$this->systemUserId2 = $systemUserId2;
	}
	public function getConfigurationTag() {
		return $this->configurationTag;
	}
	public function setConfigurationTag($configurationTag) {
		$this->configurationTag = $configurationTag;
	}
}
?>
