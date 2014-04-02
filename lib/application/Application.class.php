<?php
class Application {
	private $applicationId;
	private $applicationGroupName;
	private $applicationName;
	private $email;
	public function getApplicationId() {
		return $this->applicationId;
	}
	public function setApplicationId($applicationId) {
		$this->applicationId = $applicationId;
	}
	public function getApplicationGroupName() {
		return $this->applicationGroupName;
	}
	public function setApplicationGroupName($applicationGroupName) {
		$this->applicationGroupName = $applicationGroupName;
	}
	public function getApplicationName() {
		return $this->applicationName;
	}
	public function setApplicationName($applicationName) {
		$this->applicationName = $applicationName;
	}
	public function getEmail() {
		return $this->email;
	}
	public function setEmail($email) {
		$this->email = $email;
	}
}
?>
