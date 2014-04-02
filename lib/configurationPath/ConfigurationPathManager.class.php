<?php
class ConfigurationPathManager {
	private $objConfigurationPathStore;
	public function __construct($objConfigurationPathStore) {
		$this->objConfigurationPathStore = $objConfigurationPathStore;
	}
	public function addConfigurationPath($objConfigurationPath) {
		return $this->objConfigurationPathStore->addConfigurationPath($objConfigurationPath);		
	}
	public function editConfigurationPath($objConfigurationPath) {
		return $this->objConfigurationPathStore->editConfigurationPath($objConfigurationPath);		
	}
	public function getConfigurationPath($applicationId) {
		return $this->objConfigurationPathStore->getConfigurationPath($applicationId);
	}
	public function deleteConfigurationPath($applicationId) {
		return $this->objConfigurationPathStore->deleteConfigurationPath($applicationId);
	}
	public function listConfigurationPath() {
		return $this->objConfigurationPathStore->listConfigurationPath();
	}
}
?>
