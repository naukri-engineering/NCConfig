<?php 
class ApplicationStore {
	private $objDBConnection;
	public function __construct($objDBConnection) {
		$this->objDBConnection = new $objDBConnection;
	}
	public function addApplication($objApplication) {
		$sql = "insert ignore into application(applicationName,applicationGroupName,email) values(:applicationName,:applicationGroupName,:email)";	
		$val = array('applicationName'=>$objApplication->getApplicationName(),'applicationGroupName'=>$objApplication->getApplicationGroupName(),'email'=>$objApplication->getEmail());
		return $this->objDBConnection->query($sql,$val);
	}
	public function editApplication($objApplication) {
		$sql = "update application set applicationName=:applicationName,applicationGroupName=:applicationGroupName,email=:email where applicationId=:applicationId";	
		$val = array('applicationId'=>$objApplication->getApplicationId(),'applicationName'=>$objApplication->getApplicationName(),'applicationGroupName'=>$objApplication->getApplicationGroupName(),'email'=>$objApplication->getEmail());
		return $this->objDBConnection->query($sql,$val);
	}
	public function getApplication($applicationId) {
		$sql = "select * from application where applicationId = :applicationId";
		$val = array('applicationId'=>$applicationId);
		return $this->objDBConnection->row($sql,$val);
	}
	public function deleteApplication($applicationId) {
		$sql = "delete from application where applicationId = :applicationId";
		$val = array('applicationId'=>$applicationId);
		return $this->objDBConnection->query($sql,$val);
	}
	public function listApplication() {
		$sql = "select * from application";
		return $this->objDBConnection->query($sql);
	}
	public function validateApplicationName($applicationName) {
		$sql = "select * from application where applicationName=:applicationName limit 1";
		$val = array('applicationName'=>$applicationName);
		return $this->objDBConnection->row($sql,$val);
	}
	public function getUserApplication($email) {
		$sql = "select * from application where email = :email";
		$val = array('email'=>$email);
		return $this->objDBConnection->query($sql,$val);
	}
}
