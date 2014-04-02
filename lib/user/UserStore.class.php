<?php 
class UserStore {
	private $objDBConnection;
	public function __construct($objDBConnection) {
		$this->objDBConnection = new $objDBConnection;
	}
	public function addUser($objUser) {
		$sql = "insert ignore into user(email,password,role) values(:email,:password,:role)";	
		$val = array('email'=>$objUser->getEmail(),'password'=>$objUser->getPassword(),'role'=>$objUser->getRole());
		return $this->objDBConnection->query($sql,$val);
	}
	public function editUser($objUser) {
		if(!$objUser->getPassword()) {
			$sql = "update user set role=:role where userId=:userId";	
			$val = array('userId'=>$objUser->getUserId(),'role'=>$objUser->getRole());
		}
		else {
			$sql = "update user set password=:password where userId=:userId";	
			$val = array('userId'=>$objUser->getUserId(),'password'=>$objUser->getPassword());
		}
		return $this->objDBConnection->query($sql,$val);
	}
	public function getUser($email) {
		$sql = "select * from user where email = :email";
		$val = array('email'=>$email);
		return $this->objDBConnection->row($sql,$val);
	}
	public function deleteUser($email) {
		$sql = "delete from user where email = :email";
		$val = array('email'=>$email);
		return $this->objDBConnection->query($sql,$val);
	}
	public function listUser() {
		$sql = "select * from user";
		return $this->objDBConnection->query($sql);
	}
	public function validateEmail($email) {
		$sql = "select * from user where email=:email limit 1";
		$val = array('email'=>$email);
		return $this->objDBConnection->row($sql,$val);
	}
	public function changePassword($email,$password) {
		$sql = "update user set password=:password where email=:email";	
		$val = array('email'=>$email,'password'=>$password);
		return $this->objDBConnection->query($sql,$val);
	}
}
