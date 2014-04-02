<?php
class UserManager {
	private $objUserStore;
	public function __construct($objUserStore) {
		$this->objUserStore = $objUserStore;
	}
	public function addUser($objUser) {
		return $this->objUserStore->addUser($objUser);		
	}
	public function editUser($objUser) {
		return $this->objUserStore->editUser($objUser);		
	}
	public function getUser($email) {
		return $this->objUserStore->getUser($email);
	}
	public function deleteUser($email) {
		return $this->objUserStore->deleteUser($email);
	}
	public function listUser() {
		return $this->objUserStore->listUser();
	}
	public function validateEmail($email) {
		$status = $this->objUserStore->validateEmail($email);
		if($status)
			return false;
		return true;
	}
	public function changePassword($email,$password) {
		return $this->objUserStore->changePassword($email,$password);
	}
}
?>
