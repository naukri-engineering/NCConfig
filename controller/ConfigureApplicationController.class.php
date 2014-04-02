<?php
class ConfigureApplicationController extends BaseController {
	private $action;
	public function __construct($module,$action) {
		parent::__construct($module,$action);
		$this->action = $action;
		$this->smarty->assign('MAIN_TAB','configure');
		$this->smarty->assign('MODULE','configureApplication');
		$this->objApplicationManager = NCConfigFactory::getInstance()->getApplicationManager();
		$this->objServerManager = NCConfigFactory::getInstance()->getServerManager();
		$this->objConfigurationPathManager = NCConfigFactory::getInstance()->getConfigurationPathManager();
		$this->objConfigurationFileManager = NCConfigFactory::getInstance()->getConfigurationFileManager();
		$this->objApplicationServerMapManager = NCConfigFactory::getInstance()->getApplicationServerMapManager();
	}
	public function execute() {
		switch($this->action) {
			case 'add':
				$this->addConfigureApplication();
				break;
			case 'edit':
				$this->editConfigureApplication();
				break;
			case 'addConfigFile':
				$this->addConfigFile();
				break;
			case 'deleteConfigFile':
				$this->deleteConfigurationFile();
				break;
			case 'downloadConfigFile':
				$this->downloadConfigFile();
				break;
			case 'view':
				$this->viewConfigureApplication();
				break;
			case 'validateConfigureApplication':
				$this->validateConfigureApplication();
				break;
			default:
				$this->addConfigureApplication();
				break;
		}
	}
	private function addConfigureApplication() {
		if($this->requestParameter['submit']) {
			$applicationId = $this->requestParameter['applicationId'];
			$objConfigurationPath	= new ConfigurationPath();
			$objConfigurationPath->setApplicationId($applicationId);
			$objConfigurationPath->setConfigurationPath($this->requestParameter['configPath']);

			/*
			   $objConfigurationFile	= new ConfigurationFile();
			   $objConfigurationFile->setApplicationId($applicationId);
			   $objConfigurationFile->setConfigurationFile($_FILES["configFile"]["name"]);
			 */
			$objApplicationServerMap= new ApplicationServerMap();
			$objApplicationServerMap->setApplicationId($applicationId);
			$objApplicationServerMap->setServerId($this->requestParameter['serverId']);

			$this->objConfigurationPathManager->addConfigurationPath($objConfigurationPath);
			//$this->objConfigurationFileManager->addConfigurationFile($objConfigurationFile);
			$this->objApplicationServerMapManager->addApplicationServerMap($objApplicationServerMap);

			//$this->manageFile($this->requestParameter['applicationId']);	

			$this->smarty->assign('success','Application Configured Successfully.');
			$this->viewConfigureApplication();
		}
		$applications = $this->objApplicationManager->listApplication();
		$servers = $this->objServerManager->listServer();
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


		$this->smarty->assign('applications',$applications);
		$this->smarty->assign('servers',$servers);
		$this->smarty->assign('DEFAULT_CONFIG_PATH',DEFAULT_CONFIG_PATH);
		$this->smarty->display('configureApplication.html');	
	}
	private function downloadConfigFile() {
		$applicationId	= $this->requestParameter['applicationId'];
		$configFile		= $this->requestParameter['configFile'];	
		header("Content-type: text/plain");
		header("Content-Disposition: attachment; filename=$configFile");
		$file = CONFIG_UPLOAD_PATH.$applicationId.'/'.$configFile;
		readfile("$file");
		die;
	}
	private function addConfigFile() {
		if($this->requestParameter['submit']) {
			$applicationId = $this->requestParameter['applicationId'];
			$objConfigurationFile	= new ConfigurationFile();
			$objConfigurationFile->setApplicationId($applicationId);
			$objConfigurationFile->setConfigurationFile($_FILES["configFile"]["name"]);
			$this->objConfigurationFileManager->addConfigurationFile($objConfigurationFile);
			$this->manageFile($this->requestParameter['applicationId']);	
			$this->smarty->assign('success','Added successfully ..');
			$this->viewConfigureApplication();
		}
	}
	private function editConfigureApplication() {
		$applicationId = $this->requestParameter['applicationId'];

		$applications = $this->objApplicationManager->listApplication();
		$servers = $this->objServerManager->listServer();

		$configPath = $this->objConfigurationPathManager->getConfigurationPath($applicationId);
		$appServers = $this->objApplicationServerMapManager->getApplicationServerMap($applicationId);
		$serverIds = array();
		foreach($appServers as $appServer) {
			$serverIds[] = $appServer['serverId'];
		}
		$application= $this->objApplicationManager->getApplication($applicationId);
		if($this->requestParameter['submit']) {
			$objConfigurationPath   = new ConfigurationPath();
			$objConfigurationPath->setApplicationId($applicationId);
			$objConfigurationPath->setConfigurationPath($this->requestParameter['configPath']);

			$objApplicationServerMap= new ApplicationServerMap();
			$objApplicationServerMap->setApplicationId($applicationId);
			$objApplicationServerMap->setServerId($this->requestParameter['serverId']);

			$this->objConfigurationPathManager->editConfigurationPath($objConfigurationPath);
			$this->objApplicationServerMapManager->editApplicationServerMap($objApplicationServerMap);
			$this->smarty->assign('success','Updated successfully ..');
			$this->viewConfigureApplication();
		}
		$this->smarty->assign('application',$application);
		$this->smarty->assign('applicationId',$applicationId);
		$this->smarty->assign('serverIds',$serverIds);
		$this->smarty->assign('configPath',$configPath);

		$this->smarty->assign('applications',$applications);
		$this->smarty->assign('servers',$servers);	
		$this->smarty->display('configureApplication.html');
	}
	private function viewConfigureApplication() {
		$applicationId = $this->requestParameter['applicationId'];
		$application= $this->objApplicationManager->getApplication($applicationId);
		$configPath = $this->objConfigurationPathManager->getConfigurationPath($applicationId);
		$configFiles= $this->objConfigurationFileManager->getConfigurationFile($applicationId);
		$appServers = $this->objApplicationServerMapManager->getApplicationServerMap($applicationId);
		$servers = $this->objServerManager->listServer();
		if(!$appServers && !$configPath) {
			$this->smarty->assign('warning','Servers & Config Path not yet configured');
		}
		elseif(!$appServers) {
			$this->smarty->assign('warning','Servers not yet configured');
		}
		elseif(!$configPath) {
			$this->smarty->assign('warning','Config Path not yet configured');
		}
		$this->smarty->assign('servers',$servers);
		$this->smarty->assign('application',$application);
		$this->smarty->assign('appServers',$appServers);
		$this->smarty->assign('configFiles',$configFiles);
		$this->smarty->assign('configPath',$configPath);	
		$this->smarty->display('viewConfigureApplication.html');	
		die;	
	}
	private function deleteConfigurationFile() {
		$applicationId	= $this->requestParameter['applicationId'];
		$configFile		= $this->requestParameter['configFile'];
		$this->objConfigurationFileManager->deleteConfigurationFile($applicationId,$configFile);
		$this->deleteFile($applicationId,$configFile);
		$this->smarty->assign('success','File Deleted Successfully');
		$this->viewConfigureApplication();
	}
	private function deleteFile($applicationId,$configFile) {
		$applicationDir = CONFIG_UPLOAD_PATH.$applicationId;
		if($configFile) {
			$configFilePath = $applicationDir.'/'.$configFile;
			if(is_file($configFilePath)) {
				unlink($configFilePath);
			}
		}
	}
	private function manageFile($applicationId) {
		$applicationDir = CONFIG_UPLOAD_PATH.$applicationId;
		$configFileName = $_FILES["configFile"]["name"];
		$configFilePath = $applicationDir.'/'.$configFileName;
		if(!is_dir($applicationDir)) {
			mkdir($applicationDir,0777);
		}
		if(is_file($configFilePath)) {
			unlink($configFilePath);
		}	
		move_uploaded_file($_FILES["configFile"]["tmp_name"],$configFilePath);
	}
	private function validateConfigureApplication() {
		$applicationId = $this->requestParameter['applicationId'];
		$appServers = $this->objApplicationServerMapManager->getApplicationServerMap($applicationId);
		if($appServers) {
			echo json_encode(array('status'=>false));
		}
		else {
			echo json_encode(array('status'=>true));
		}		
	}
}
