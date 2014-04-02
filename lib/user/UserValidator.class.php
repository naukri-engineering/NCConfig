<?php
class UserValidator extends BaseValidator {
	private $errorArray;
	public function __construct() {
		$this->errorArray = array();
	}
	public function addValidation($objUser) {
		$email = $objUser->getEmail();
		if(!$this->blankCheck($email)) {
			$this->errorArray['email'] = 'Please provide a email';
		}
		elseif(!$this->maxLengthCheck($email,50)) {	
			$this->errorArray['email'] = 'maximum 30 characters long';
		}
		return $this->errorArray;
	}
	public function editValidation($objUser) {
		$email = $objUser->getEmail();
		if(!$this->blankCheck($email)) {
			$this->errorArray['email'] = 'Please provide a email';
		}
		elseif(!$this->maxLengthCheck($email,50)) {	
			$this->errorArray['email'] = 'maximum 30 characters long';
		}
		return $this->errorArray;
	}
	public function changePasswordValidation($password,$confirmPassword) {
		if(!$this->blankCheck($password)) {
			$this->errorArray['password'] = 'Please provide a password';
		}
		elseif(!$this->minLengthCheck($password,5)) {
			$this->errorArray['password'] = 'Your password must be at least 5 characters long';	
		}
		elseif(!$this->maxLengthCheck($password,30)) {
			$this->errorArray['password'] = 'Your password must be at maximum 30 characters long';	
		}
		if(!$this->blankCheck($confirmPassword)) {
			$this->errorArray['confirmPassword'] = 'Please provide a password';
		}
		elseif(!$this->minLengthCheck($confirmPassword,5)) {
			$this->errorArray['confirmPassword'] = 'Your password must be at least 5 characters long';	
		}
		elseif(!$this->maxLengthCheck($confirmPassword,30)) {
			$this->errorArray['confirmPassword'] = 'Your password must be at maximum 30 characters long';	
		}
		if(!$this->errorArray) {
			if($password!=$confirmPassword) {
				$this->errorArray['confirmPassword'] = 'Please enter the same password as above';
			}
		}
		return $this->errorArray;
	}
	public function deleteValidation($email) {
		$objApplicationManager = NCConfigFactory::getInstance()->getApplicationManager();
		$applications = $objApplicationManager->listApplication();	
		foreach($applications as $application) {
			if($email == $application['email']) {
				$this->errorArray['error'] = 'ERROR';
				break;
			}
		}
		return $this->errorArray;
	}
}
?>
