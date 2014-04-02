<?php 
class SystemUserStore {
	private $objDBConnection;
	public function __construct($objDBConnection) {
		$this->objDBConnection = new $objDBConnection;
	}
	public function addSystemUser($objSystemUser) {
		$sql = "insert into systemUser(username,password,systemUserRefname) values(:username,:password,:systemUserRefName)";	
		$val = array('username'=>$objSystemUser->getUsername(),'password'=>$objSystemUser->getPassword(),'systemUserRefName'=>$objSystemUser->getSystemUserRefName());
		return $this->objDBConnection->query($sql,$val);
	}
	public function editSystemUser($objSystemUser) {
		$sql = "update systemUser set username=:username,password=:password,systemUserRefname=:systemUserRefName where systemUserId=:systemUserId";	
		$val = array('systemUserId'=>$objSystemUser->getSystemUserId(),'password'=>$objSystemUser->getPassword(),'username'=>$objSystemUser->getUsername(),'systemUserRefName'=>$objSystemUser->getSystemUserRefName());
		return $this->objDBConnection->query($sql,$val);
	}
	public function getSystemUser($systemUserId) {
		$sql = "select * from systemUser where systemUserId = :systemUserId";
		$val = array('systemUserId'=>$systemUserId);
		return $this->objDBConnection->row($sql,$val);
	}
	public function deleteSystemUser($systemUserId) {
		$sql = "delete from systemUser where systemUserId = :systemUserId";
		$val = array('systemUserId'=>$systemUserId);
		return $this->objDBConnection->query($sql,$val);
	}
	public function listSystemUser() {
		$sql = "select * from systemUser";
		return $this->objDBConnection->query($sql);
	}
	public function validateSystemUserRefName($systemUserRefName) {
		$sql = "select * from systemUser where systemUserRefName=:systemUserRefName limit 1";
		$val = array('systemUserRefName'=>$systemUserRefName);
		return $this->objDBConnection->row($sql,$val);
	}
}
