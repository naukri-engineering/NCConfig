<?php
class ServiceValidator extends BaseValidator {
	private $errorArray;
	public function __construct() {
		$this->errorArray = array();
	}
	public function addValidation($objService) {
        $servicePort = $objService->getServicePort();
        if(!$this->blankCheck($servicePort)) {
            $this->errorArray['servicePort'] = 'Please provide service port';
        }
        elseif(!$this->maxLengthCheck($servicePort,50)) {
            $this->errorArray['servicePort'] = 'Max length 50 characters long';
        }
        $serviceRefName = $objService->getServiceRefName();
        if(!$this->blankCheck($serviceRefName)) {
            $this->errorArray['serviceRefName'] = 'Please provide service Ref name';
        }
        elseif(!$this->maxLengthCheck($serviceRefName,50)) {
            $this->errorArray['serviceRefName'] = 'Max length 50 characters long';
        }
		$objServiceManager = NCConfigFactory::getInstance()->getServiceManager();
		$status = $objServiceManager->validateServiceRefName($serviceRefName);
		if(!$status)
			$this->errorArray['serviceRefName'] = 'Service Ref name already exists';
		return $this->errorArray;
	}
    public function editValidation($objService) {
        $servicePort = $objService->getServicePort();
        if(!$this->blankCheck($servicePort)) {
            $this->errorArray['servicePort'] = 'Please provide service port';
        }
        elseif(!$this->maxLengthCheck($servicePort,50)) {
            $this->errorArray['servicePort'] = 'Max length 50 characters long';
        }
        $serviceRefName = $objService->getServiceRefName();
        if(!$this->blankCheck($serviceRefName)) {
            $this->errorArray['serviceRefName'] = 'Please provide service Ref name';
        }
        elseif(!$this->maxLengthCheck($serviceRefName,50)) {
            $this->errorArray['serviceRefName'] = 'Max length 50 characters long';
        }
        return $this->errorArray;
    }

	public function deleteValidation($serviceId) {
		$ConfigurationManager = NCConfigFactory::getInstance()->getConfigurationManager();
		if($ConfigurationManager->getServiceConfiguration($serviceId)) {
			return $this->errorArray['error'] = 'ERROR';	
		}
		return $this->errorArray;
	}
}
?>
