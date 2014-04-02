<?php
class ConfigurationManager {
	private $objConfigurationStore;
	public function __construct($objConfigurationStore) {
		$this->objConfigurationStore = $objConfigurationStore;
	}
	public function addConfiguration($objConfiguration) {
		return $this->objConfigurationStore->addConfiguration($objConfiguration);		
	}
	public function editConfiguration($objConfiguration) {
		return $this->objConfigurationStore->editConfiguration($objConfiguration);		
	}
	public function getConfiguration($configurationId) {
		return $this->objConfigurationStore->getConfiguration($configurationId);
	}
	public function getApplicationConfiguration($applicationId) {
		return $this->objConfigurationStore->getApplicationConfiguration($applicationId);
	}
    public function getSystemUserConfiguration($systemUserId) {
        return $this->objConfigurationStore->getSystemUserConfiguration($systemUserId);
    }
    public function getServiceConfiguration($serviceId) {
        return $this->objConfigurationStore->getServiceConfiguration($serviceId);
    }
	public function deleteConfiguration($configurationId) {
		return $this->objConfigurationStore->deleteConfiguration($configurationId);
	}
	public function listConfiguration() {
		//Application...
        $objApplicationManager = NCConfigFactory::getInstance()->getApplicationManager();
        $applications = $objApplicationManager->listApplication();
        $applicationsArr = array();
        foreach($applications as $application) {
            $applicationsArr[$application['applicationId']] = $application['applicationName'];
        }
        //Service...
        $objServiceManager = NCConfigFactory::getInstance()->getServiceManager();
        $services = $objServiceManager->listService();
        $servicesArr = array();
        foreach($services as $service) {
            $servicesArr[$service['serviceId']] = $service['serviceTypeName']." (".$service['servicePort'].") - ".$service['serviceRefName'];
        }
        //SystemUser...
        $objSystemUserManager = NCConfigFactory::getInstance()->getSystemUserManager();
        $systemUsers = $objSystemUserManager->listSystemUser();
        $systemUsersArr = array();
        foreach($systemUsers as $systemUser) {
            $systemUsersArr[$systemUser['systemUserId']] = $systemUser['systemUserRefName']." (".$systemUser['username'].")";
        }

		$configurations = $this->objConfigurationStore->listConfiguration();
		foreach($configurations as $key=>$configuration) {
			$configurations[$key]['applicationName'] = $applicationsArr[$configuration['applicationId']];
			$configurations[$key]['service'] = $servicesArr[$configuration['serviceId']];
			$configurations[$key]['systemUser'] = $systemUsersArr[$configuration['systemUserId']];
		}
		return $configurations;
	}
	public function validateConfiguration($applicationId,$serviceId,$systemUserId) {
		$status = $this->objConfigurationStore->validateConfiguration($applicationId,$serviceId,$systemUserId);
		if($status)
			return false;
		return true;
	}
	public function validateConfigurationTag($configurationTag) {
		$status = $this->objConfigurationStore->validateConfigurationTag($configurationTag);
		if($status)
			return false;
		return true;
	}
}
?>
