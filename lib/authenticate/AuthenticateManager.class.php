<?php
class AuthenticateManager {
	private $objDBConnection;
	public function __construct($objDBConnection) {
		$this->objDBConnection = $objDBConnection;
	}
	public function checkPassword($email,$password) {
		$user		= $this->getPassword($email);
		$PASSWORD	= $user['password'];
		$ROLE		= $user['role'];	
		if(md5(trim($password)) == trim($PASSWORD)) {
			$uniqId 			= uniqid();
			$_SESSION['userUniqid'] = $uniqId;
			$_SESSION['userEmail'] 	= $email;
			$_SESSION['userRole'] 	= $ROLE;
			setcookie("userUniqid",$uniqId,time()+TIME_OUT, "/");
			$sql = "insert into connect(email,uniqueId,loginTime) values(:email,:uniqueId,:loginTime)";
			$val = array('email'=>$email,'uniqueId'=>$uniqId,'loginTime'=>date('Y-m-d H:i:s'));
			$this->objDBConnection->query($sql,$val);
			return true;
		}
		return false;
	}
	public function getRole() {
		return $_SESSION['userRole'];
	}
	public function getEmail() {
		return $_SESSION['userEmail'];
	}
	private function getPassword($email) {
		$objUserManager = NCConfigFactory::getInstance()->getUserManager();
		return $objUserManager->getUser($email);
	}
	public function logout() {
		$sql = "update connect set logoutTime=:logoutTime where email=:email and uniqueId=:uniqueId";
		$val = array('logoutTime'=>date('Y-m-d H:i:s'),'email'=>$this->getEmail(),'uniqueId'=>$_SESSION['userUniqid']);
		$this->objDBConnection->query($sql,$val);
		unset($_COOKIE['userUniqid']);
		unset($_SESSION['userUniqid']);
	}
	public function authenticate() {
		if(!$_COOKIE['userUniqid'] || !$_SESSION['userUniqid'])
			return false;
		if($_COOKIE['userUniqid'] == $_SESSION['userUniqid']) {
			setcookie("userUniqid",$_COOKIE['userUniqid'],time()+TIME_OUT, "/");
			$sql = "update connect set lastAccessTime=:lastAccessTime where email=:email and uniqueId=:uniqueId";
			$val = array('lastAccessTime'=>date('Y-m-d H:i:s'),'email'=>$this->getEmail(),'uniqueId'=>$_SESSION['userUniqid']);
			$this->objDBConnection->query($sql,$val);
			return true;
		}
		return false;
	}
}
?>
