<?php
class ApplicationValidator extends BaseValidator {
	private $errorArray;
	public function __construct() {
		$this->errorArray = array();
	}
	public function addValidation($objApplication) {
		$applicationName = $objApplication->getApplicationName();
        if(!$this->blankCheck($applicationName)) {
            $this->errorArray['applicationName'] = 'Please provide application name';
        }
        elseif(!$this->maxLengthCheck($applicationName,50)) {
            $this->errorArray['applicationName'] = 'Max length 50 characters long';
        }
		$applicationGroupName = $objApplication->getApplicationGroupName();
		if(!$this->blankCheck($applicationGroupName)) {
			$this->errorArray['applicationGroupName'] = 'Please provide application Group Name';
		}
		elseif(!$this->maxLengthCheck($applicationGroupName,50)) {
			$this->errorArray['applicationGroupName'] = 'Max length 50 characters long';
		}
		$objApplicationManager = NCConfigFactory::getInstance()->getApplicationManager();
		$status = $objApplicationManager->validateApplicationName($applicationName);
		if(!$status)
			$this->errorArray['applicationName'] = 'Application name already exists';
		return $this->errorArray;
	}
	public function editValidation($objApplication) {
		$applicationName = $objApplication->getApplicationName();
        if(!$this->blankCheck($applicationName)) {
            $this->errorArray['applicationName'] = 'Please provide application name';
        }
        elseif(!$this->maxLengthCheck($applicationName,50)) {
            $this->errorArray['applicationName'] = 'Max length 50 characters long';
        }
		$applicationGroupName = $objApplication->getApplicationGroupName();
		if(!$this->blankCheck($applicationGroupName)) {
			$this->errorArray['applicationGroupName'] = 'Please provide application Group Name';
		}
		elseif(!$this->maxLengthCheck($applicationGroupName,50)) {
			$this->errorArray['applicationGroupName'] = 'Max length 50 characters long';
		}
		return $this->errorArray;
	}
	public function deleteValidation($applicationId) {
		$ApplicationServerMapManager	= NCConfigFactory::getInstance()->getApplicationServerMapManager();
		$ConfigurationManager			= NCConfigFactory::getInstance()->getConfigurationManager();
		$ConfigurationFileManager		= NCConfigFactory::getInstance()->getConfigurationFileManager();
		$ConfigurationPathManager		= NCConfigFactory::getInstance()->getConfigurationPathManager();

		if($ConfigurationPathManager->getConfigurationPath($applicationId))
			return $this->errorArray['error'] = 'ERROR';
		if($ConfigurationFileManager->getConfigurationFile($applicationId))
			return $this->errorArray['error'] = 'ERORR';
		if($ApplicationServerMapManager->getApplicationServerMap($applicationId))
			return $this->errorArray['error'] = 'ERROR';
		if($ConfigurationManager->getApplicationConfiguration($applicationId))
			return $this->errorArray['error'] = 'ERROR';
		return $this->errorArray;
	}
}
?>
