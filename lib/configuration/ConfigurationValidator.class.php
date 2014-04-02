<?php
class ConfigurationValidator extends BaseValidator {
	private $errorArray;
	public function __construct() {
		$this->errorArray = array();
	}
	public function addValidation($objConfiguration) {
		$applicationId		= $objConfiguration->getApplicationId();
		$serviceId			= $objConfiguration->getServiceId();
		$systemUserId		= $objConfiguration->getSystemUserId();
		$systemUserId2		= $objConfiguration->getSystemUserId2();
		$configurationTag	= $objConfiguration->getConfigurationTag();

		if(!$this->blankCheck($configurationTag)) {
            $this->errorArray['configurationTag'] = 'Please provide Tag';
        }
        elseif(!$this->maxLengthCheck($configurationTag,50)) {
            $this->errorArray['configurationTag'] = 'Max length 50 characters long';
        }
		$objConfigurationManager = NCConfigFactory::getInstance()->getConfigurationManager();
		$status = $objConfigurationManager->validateConfiguration($applicationId,$serviceId,$systemUserId,$systemUserId2);
		if(!$status)
			$this->errorArray['configurationTag'] = 'Tag already exists';
		$status = $objConfigurationManager->validateConfigurationTag($configurationTag);
		if(!$status)
			$this->errorArray['serviceId'] = 'Configuration already exists';
		return $this->errorArray;
	}
    public function editValidation($objConfiguration) {
        $applicationId      = $objConfiguration->getApplicationId();
        $serviceId          = $objConfiguration->getServiceId();
        $systemUserId       = $objConfiguration->getSystemUserId();
        $systemUserId2       = $objConfiguration->getSystemUserId2();
        $configurationTag   = $objConfiguration->getConfigurationTag();

        if(!$this->blankCheck($configurationTag)) {
            $this->errorArray['configurationTag'] = 'Please provide Tag';
        }
        elseif(!$this->maxLengthCheck($configurationTag,50)) {
            $this->errorArray['configurationTag'] = 'Max length 50 characters long';
        }
		/*
        $objConfigurationManager = NCConfigFactory::getInstance()->getConfigurationManager();
        $status = $objConfigurationManager->validateConfiguration($applicationId,$serviceId,$systemUserId);
        if(!$status)
            $this->errorArray['configurationTag'] = 'Tag already exists';
        $status = $objConfigurationManager->validateConfigurationTag($configurationTag);
        if(!$status)
            $this->errorArray['serviceId'] = 'Configuration already exists';
		*/
        return $this->errorArray;
    }
}
?>
