<?php
class CronValidator extends BaseValidator {
	private $errorArray;
	public function __construct() {
		$this->errorArray = array();
	}
	public function addValidation($objCron) {
		$cronName = $objCron->getCronName();
        if(!$this->blankCheck($cronName)) {
            $this->errorArray['cronName'] = 'Please provide cron name';
        }
        elseif(!$this->maxLengthCheck($cronName,50)) {
            $this->errorArray['cronName'] = 'Max length 50 characters long';
        }
		$cronGroupName = $objCron->getCronGroupName();
		if(!$this->blankCheck($cronGroupName)) {
			$this->errorArray['cronGroupName'] = 'Please provide cron Group Name';
		}
		elseif(!$this->maxLengthCheck($cronGroupName,50)) {
			$this->errorArray['cronGroupName'] = 'Max length 50 characters long';
		}
		$objCronManager = NCConfigFactory::getInstance()->getCronManager();
		$status = $objCronManager->validateCronName($cronName);
		if(!$status)
			$this->errorArray['cronName'] = 'Cron name already exists';
		return $this->errorArray;
	}
	public function editValidation($objCron) {
		$cronName = $objCron->getCronName();
        if(!$this->blankCheck($cronName)) {
            $this->errorArray['cronName'] = 'Please provide cron name';
        }
        elseif(!$this->maxLengthCheck($cronName,50)) {
            $this->errorArray['cronName'] = 'Max length 50 characters long';
        }
		$cronGroupName = $objCron->getCronGroupName();
		if(!$this->blankCheck($cronGroupName)) {
			$this->errorArray['cronGroupName'] = 'Please provide cron Group Name';
		}
		elseif(!$this->maxLengthCheck($cronGroupName,50)) {
			$this->errorArray['cronGroupName'] = 'Max length 50 characters long';
		}
		return $this->errorArray;
	}
	public function deleteValidation($cronId) {
		$CronServerMapManager	= NCConfigFactory::getInstance()->getCronServerMapManager();
		$ConfigurationManager			= NCConfigFactory::getInstance()->getConfigurationManager();
		$ConfigurationFileManager		= NCConfigFactory::getInstance()->getConfigurationFileManager();
		$ConfigurationPathManager		= NCConfigFactory::getInstance()->getConfigurationPathManager();

		if($ConfigurationPathManager->getConfigurationPath($cronId))
			return $this->errorArray['error'] = 'ERROR';
		if($ConfigurationFileManager->getConfigurationFile($cronId))
			return $this->errorArray['error'] = 'ERORR';
		if($CronServerMapManager->getCronServerMap($cronId))
			return $this->errorArray['error'] = 'ERROR';
		if($ConfigurationManager->getCronConfiguration($cronId))
			return $this->errorArray['error'] = 'ERROR';
		return $this->errorArray;
	}
}
?>
