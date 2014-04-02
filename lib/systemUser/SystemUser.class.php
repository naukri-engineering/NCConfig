<?php
class SystemUser {
	private $systemUserId;
	private $password;
	private $username;
	private $systemUserRefName;

	public function getSystemUserId() {
		return $this->systemUserId;
	}
	public function setSystemUserId($systemUserId) {
		$this->systemUserId = $systemUserId;
	}
	public function getUsername() {
		return $this->username;
	}
	public function setUsername($username) {
		$this->username = $username;
	}
	public function getPassword() {
		return $this->password;
	}
	public function setPassword($password) {
		$this->password = $password;
	}
	public function getSystemUserRefName() {
		return $this->systemUserRefName;
	}
	public function setSystemUserRefName($systemUserRefName) {
		$this->systemUserRefName = $systemUserRefName;
	}
}
?>
