<?php
class ConfigurationController extends BaseController {
	private $action;
	public function __construct($module,$action) {
		parent::__construct($module,$action);
		$this->action = $action;
		$this->smarty->assign('MAIN_TAB','configure');
		$this->smarty->assign('MODULE','configuration');
		$this->objApplicationManager = NCConfigFactory::getInstance()->getApplicationManager();
		$this->objServiceManager = NCConfigFactory::getInstance()->getServiceManager();
		$this->objSystemUserManager = NCConfigFactory::getInstance()->getSystemUserManager();
		$this->objConfigurationManager = NCConfigFactory::getInstance()->getConfigurationManager();
	}
	public function execute() {
		switch($this->action) {
			case 'add':
				$this->addConfiguration();
				break;
			case 'edit':
				$this->editConfiguration();
				break;
			case 'list':
				$this->listConfiguration();
				break;
			case 'delete':
				$this->deleteConfiguration();
				break;
			case 'validateConfigurationTag':
				$this->validateConfigurationTag();
				break;
			case 'validateConfiguration':
				$this->validateConfiguration();
				break;
			default:
				$this->listConfiguration();
				break;
		}
	}
	private function addConfiguration() {
		if($this->requestParameter['submit']) {
			$objConfiguration = new Configuration();
			$objConfigurationValidator = NCConfigFactory::getInstance()->getConfigurationValidator();
			$objConfiguration->setApplicationId($this->requestParameter['applicationId']);
			$objConfiguration->setServiceId($this->requestParameter['serviceId']);
			$objConfiguration->setSystemUserId($this->requestParameter['systemUserId']);
			$objConfiguration->setSystemUserId2($this->requestParameter['systemUserId2']);
			$objConfiguration->setConfigurationTag($this->requestParameter['configurationTag']);
			$errorArray = $objConfigurationValidator->addValidation($objConfiguration);
			if($errorArray) {
				$this->smarty->assign('errors',$errorArray);
			}
			else {
				$serverId = $this->objConfigurationManager->addConfiguration($objConfiguration);
				$this->smarty->assign('success','Configuration (Id : '.$serverId.') added Successfully.');
				$this->listConfiguration();
				die;
			}
		}
		$applications = $this->objApplicationManager->listApplication();
		$services = $this->objServiceManager->listService();
		$systemUsers = $this->objSystemUserManager->listSystemUser();
		$this->smarty->assign('applications',$applications);
		$this->smarty->assign('services',$services);
		$this->smarty->assign('systemUsers',$systemUsers);
		$applicationGroupNames = array();
		foreach($applications as $application) {
			$groupName = $application['applicationGroupName'];
			$applicationGroupNames[$groupName] = $groupName;
		}
		$this->smarty->assign('sugg_applicationGroupNames',json_encode($applicationGroupNames));
		$objUserManager = NCConfigFactory::getInstance()->getUserManager();
		$users = $objUserManager->listUser();
		$sugg_Emails = array();
		foreach($users as $user) {
			$email = $user['email'];
			$sugg_Emails[$email] = $email;
		}
		$this->smarty->assign('sugg_Emails',json_encode($sugg_Emails));

		$this->smarty->display('configuration.html');
	}
	private function editConfiguration() {
		$configurationId = $this->requestParameter['configurationId'];
		$configuration = $this->objConfigurationManager->getConfiguration($configurationId);
		if($this->requestParameter['submit']) {
			$objConfiguration = new Configuration();
			$objConfigurationValidator = NCConfigFactory::getInstance()->getConfigurationValidator();
			$objConfiguration->setConfigurationId($this->requestParameter['configurationId']);
			$objConfiguration->setApplicationId($this->requestParameter['applicationId']);
			$objConfiguration->setServiceId($this->requestParameter['serviceId']);
			$objConfiguration->setSystemUserId($this->requestParameter['systemUserId']);
			$objConfiguration->setSystemUserId2($this->requestParameter['systemUserId2']);
			$objConfiguration->setConfigurationTag($this->requestParameter['configurationTag']);
			$errorArray = $objConfigurationValidator->editValidation($objConfiguration);
			if($errorArray) {
				$this->smarty->assign('errors',$errorArray);
			}
			else {
				$serverId = $this->objConfigurationManager->editConfiguration($objConfiguration);
				$this->smarty->assign('success','Configuration (Id : '.$configurationId.') updated Successfully.');
				$this->listConfiguration();
				die;
			}
		}
		$applications = $this->objApplicationManager->listApplication();
		$services = $this->objServiceManager->listService();
		$systemUsers = $this->objSystemUserManager->listSystemUser();
		$this->smarty->assign('configuration',$configuration);
		$this->smarty->assign('applications',$applications);
		$this->smarty->assign('services',$services);
		$this->smarty->assign('systemUsers',$systemUsers);
		$this->smarty->display('configuration.html');
	}	
	private function listConfiguration() {
		$configurations = $this->objConfigurationManager->listConfiguration();
		if(!$configurations) {
			$this->smarty->assign('warning','You have zero configurations added.');
		}
		$this->smarty->assign('configurations',$configurations);
		$this->smarty->assign('TAG_DELIMITER',TAG_DELIMITER);
		$this->smarty->display('listConfiguration.html');
	}
	private function deleteConfiguration() {
		$configurationId = $this->requestParameter['configurationId'];
		$this->objConfigurationManager->deleteConfiguration($configurationId);
		$this->smarty->assign('success',"Configuration (Id : $configurationId) deleted successfully");
		$this->listConfiguration();
	}
	private function validateConfiguration() {
		$applicationId  = $this->requestParameter['applicationId'];
		$serviceId		= $this->requestParameter['serviceId'];
		$systemUserId   = $this->requestParameter['systemUserId'];
		$systemUserId2   = $this->requestParameter['systemUserId2'];
		$status = $this->objConfigurationManager->validateConfiguration($applicationId,$serviceId,$systemUserId,$systemUserId2);
		echo json_encode(array('status'=>$status));
	}
	private function validateConfigurationTag() {
		$configurationTag = $this->requestParameter['configurationTag'];
		$status = $this->objConfigurationManager->validateConfigurationTag($configurationTag);
		echo json_encode(array('status'=>$status));
	}
}
