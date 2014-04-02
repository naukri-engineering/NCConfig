<?php
class SystemUserValidator extends BaseValidator {
	private $errorArray;
	public function __construct() {
		$this->errorArray = array();
	}
	public function addValidation($objSystemUser) {
        $username = $objSystemUser->getUsername();
        if(!$this->blankCheck($username)) {
            $this->errorArray['username'] = 'Please provide systemUser Ref name';
        }
        elseif(!$this->maxLengthCheck($username,50)) {
            $this->errorArray['username'] = 'Max length 50 characters long';
        }
        $password = $objSystemUser->getPassword();
        if(!$this->blankCheck($password)) {
            $this->errorArray['password'] = 'Please provide systemUser Ref name';
        }
        elseif(!$this->maxLengthCheck($password,50)) {
            $this->errorArray['password'] = 'Max length 50 characters long';
        }
        $systemUserRefName = $objSystemUser->getSystemUserRefName();
        if(!$this->blankCheck($systemUserRefName)) {
            $this->errorArray['systemUserRefName'] = 'Please provide systemUser Ref name';
        }
        elseif(!$this->maxLengthCheck($systemUserRefName,50)) {
            $this->errorArray['systemUserRefName'] = 'Max length 50 characters long';
        }
        $objSystemUserManager = NCConfigFactory::getInstance()->getSystemUserManager();
        $status = $objSystemUserManager->validateSystemUserRefName($systemUserRefName);
        if(!$status)
            $this->errorArray['systemUserRefName'] = 'SystemUser name already exists';
        return $this->errorArray;
	}
    public function editValidation($objSystemUser) {
        $username = $objSystemUser->getUsername();
        if(!$this->blankCheck($username)) {
            $this->errorArray['username'] = 'Please provide systemUser Ref name';
        }
        elseif(!$this->maxLengthCheck($username,50)) {
            $this->errorArray['username'] = 'Max length 50 characters long';
        }
        $password = $objSystemUser->getPassword();
        if(!$this->blankCheck($password)) {
            $this->errorArray['password'] = 'Please provide systemUser Ref name';
        }
        elseif(!$this->maxLengthCheck($password,50)) {
            $this->errorArray['password'] = 'Max length 50 characters long';
        }
        $systemUserRefName = $objSystemUser->getSystemUserRefName();
        if(!$this->blankCheck($systemUserRefName)) {
            $this->errorArray['systemUserRefName'] = 'Please provide systemUser Ref name';
        }
        elseif(!$this->maxLengthCheck($systemUserRefName,50)) {
            $this->errorArray['systemUserRefName'] = 'Max length 50 characters long';
        }
        return $this->errorArray;
    }
	public function deleteValidation($systemUserId) {
		$ConfigurationManager = NCConfigFactory::getInstance()->getConfigurationManager();
		if($ConfigurationManager->getSystemUserConfiguration($systemUserId))
			return $this->errorArray['error'] = 'ERROR';
		return $this->errorArray;
	}
}
?>
