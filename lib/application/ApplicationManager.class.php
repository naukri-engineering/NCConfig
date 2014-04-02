<?php
class ApplicationManager {
	private $objApplicationStore;
	public function __construct($objApplicationStore) {
		$this->objApplicationStore = $objApplicationStore;
	}
	public function addApplication($objApplication) {
		return $this->objApplicationStore->addApplication($objApplication);		
	}
	public function editApplication($objApplication) {
		return $this->objApplicationStore->editApplication($objApplication);		
	}
	public function getApplication($applicationId) {
		return $this->objApplicationStore->getApplication($applicationId);
	}
	public function deleteApplication($applicationId) {
		return $this->objApplicationStore->deleteApplication($applicationId);
	}
	public function listApplication() {
		return $this->objApplicationStore->listApplication();
	}
	public function validateApplicationName($applicationName) {
		$status = $this->objApplicationStore->validateApplicationName($applicationName);
		if($status)
			return false;
		return true;
	}
	public function getUserApplication($email) {
		return $this->objApplicationStore->getUserApplication($email);
	}
}
?>
