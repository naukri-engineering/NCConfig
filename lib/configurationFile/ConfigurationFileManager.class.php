<?php
class ConfigurationFileManager {
	private $objConfigurationFileStore;
	public function __construct($objConfigurationFileStore) {
		$this->objConfigurationFileStore = $objConfigurationFileStore;
	}
	public function addConfigurationFile($objConfigurationFile) {
		return $this->objConfigurationFileStore->addConfigurationFile($objConfigurationFile);		
	}
	public function editConfigurationFile($objConfigurationFile) {
		return $this->objConfigurationFileStore->editConfigurationFile($objConfigurationFile);		
	}
	public function getConfigurationFile($applicationId) {
		return $this->objConfigurationFileStore->getConfigurationFile($applicationId);
	}
	public function deleteConfigurationFile($applicationId,$configFile) {
		return $this->objConfigurationFileStore->deleteConfigurationFile($applicationId,$configFile);
	}
	public function listConfigurationFile() {
		return $this->objConfigurationFileStore->listConfigurationFile();
	}
}
?>
