<?php
class SystemUserManager {
	private $objSystemUserStore;
	public function __construct($objSystemUserStore) {
		$this->objSystemUserStore = $objSystemUserStore;
	}
	public function addSystemUser($objSystemUser) {
		return $this->objSystemUserStore->addSystemUser($objSystemUser);		
	}
	public function editSystemUser($objSystemUser) {
		return $this->objSystemUserStore->editSystemUser($objSystemUser);		
	}
	public function getSystemUser($systemUserId) {
		return $this->objSystemUserStore->getSystemUser($systemUserId);
	}
	public function deleteSystemUser($systemUserId) {
		return $this->objSystemUserStore->deleteSystemUser($systemUserId);
	}
	public function listSystemUser() {
		return $this->objSystemUserStore->listSystemUser();
	}
	public function validateSystemUserRefName($systemUserRefName) {
		$status = $this->objSystemUserStore->validateSystemUserRefName($systemUserRefName);
		if($status)
			return false;
		return true;
	}
}
?>
