<?php
class ReleaseController extends BaseController {
	private $action;
	private $objReleaseLogManager;
	public function __construct($module,$action) {
		parent::__construct($module,$action);
		$this->action = $action;
        $this->smarty->assign('MAIN_TAB','release');
        $this->smarty->assign('MODULE','release');

		$this->objReleaseLogManager = NCConfigFactory::getInstance()->getReleaseLogManager();
	}
	public function execute() {
		switch($this->action) {
			case 'list':
				$this->listConfigFile();
				break;
			case 'changeConfigFile':
				$this->changeConfigFile();
				break;
			case 'downloadConfigFile':
				$this->downloadConfigFile();
				break;
			case 'deploy':
				$this->runDeploy();
				break;
			case 'readLogFile':
				$this->readLogFile();
				break;
			default:
				$this->listConfigFile();
				break;
		}
	}
	private function readLogFile() {
		$releaseId = $this->requestParameter['releaseId'];
		$logFile = $this->requestParameter['logFile'];
		$file = file_get_contents(LOG_PATH.$logFile);
		if(strpos($file,'command finished') !== false) {
			$this->objReleaseLogManager->updateReleaseDeploymentStatus($releaseId,'COMPLETE');
		}
		echo $file;
		die;
	}
	private function runDeploy() {
		$release = $this->requestParameter['release'];
        $applicationId  = $this->requestParameter['applicationId'];
		$this->smarty->assign('release',$release);
		$this->smarty->assign('applicationId',$applicationId);
		if($this->requestParameter['submit']) {
			$username	= $this->requestParameter['username'];
			$password	= $this->requestParameter['password'];
			$port		= $this->requestParameter['port'];
			$objApplicationServerMap = NCConfigFactory::getInstance()->getApplicationServerMapManager();
			$objConfigurationPath = NCConfigFactory::getInstance()->getConfigurationPathManager();
			$servers = $objApplicationServerMap->getApplicationServerMap($applicationId);
			$configuration = $objConfigurationPath->getConfigurationPath($applicationId);
			$deploy_to = $configuration['configPath'];
			$serverList = array();
			foreach($servers as $server) {
				$serverList[] = $username.'@'.$server['serverName'];
			}
			$servers = implode('","',$serverList);
			$repository = PROD_READY_FILES.$release.'/'.$applicationId;
			$this->prepareDeployScript($applicationId,$release,$password,$repository,$deploy_to,$servers,$port);
			$CapFile = CAP_FILE_PATH.'Capfile';
			$logFile = "release_".$release."_".date('Y_m_d_H_i_s').".log";
			$releaseLogFilePath = LOG_PATH.$logFile;
			$CapCommand = CAP_COMMAND_PATH;
			system("$CapCommand deploy:config -f $CapFile > $releaseLogFilePath 2>&1 &");
			$this->smarty->assign('success','Deploying Config ......');
			$this->requestParameter['applicationId'] = '';
			$this->smarty->assign('running',1);
			$this->smarty->assign('logFile',$logFile);
			//$this->listConfigFile();
			
			$releaseId = $this->objReleaseLogManager->addReleaseDeployment(date('Y-m-d'),$release,$applicationId,date('Y-m-d H:i:s'),'STARTED',$this->userEMAIL);		
			$this->smarty->assign('releaseId',$releaseId);
		}
		$this->smarty->display('deploy.html');
	}
	private function prepareDeployScript($APPLICATION_ID,$RELEASE,$PASSWORD,$REPOSITORY,$DEPLOY_TO,$SERVERS,$PORT) {
		$command = 'cp '.CAP_FILE_PATH.'Capfile_Template '.CAP_FILE_PATH.'Capfile';
		$Capfile = CAP_FILE_PATH.'Capfile';
		shell_exec($command);		
		chmod($CapFile,0777);
		file_put_contents($Capfile,str_replace('APPLICATION_ID',$APPLICATION_ID,file_get_contents($Capfile)));
		file_put_contents($Capfile,str_replace('RELEASE_TIME',date('YmdHis'),file_get_contents($Capfile)));
		file_put_contents($Capfile,str_replace('RELEASE',$RELEASE,file_get_contents($Capfile)));
		file_put_contents($Capfile,str_replace('PASSWORD',$PASSWORD,file_get_contents($Capfile)));
		file_put_contents($Capfile,str_replace('REPOSITORY',$REPOSITORY,file_get_contents($Capfile)));
		file_put_contents($Capfile,str_replace('DEPLOY_TO',$DEPLOY_TO,file_get_contents($Capfile)));
		file_put_contents($Capfile,str_replace('SERVERS',$SERVERS,file_get_contents($Capfile)));
		file_put_contents($Capfile,str_replace('PORT',$PORT,file_get_contents($Capfile)));
		file_put_contents($Capfile,str_replace('HOME_PATH',HOME_PATH,file_get_contents($Capfile)));
	}
	private function listConfigFile() {
		$release = $this->requestParameter['release'];
        $applicationId  = $this->requestParameter['applicationId'];
		$releases = $this->objReleaseLogManager->listReleaseLog($release,$applicationId);	
		if(!$releases) {
			$this->smarty->assign('warning','No release created.');
		}
		$this->smarty->assign('release',$release);
		$this->smarty->assign('applicationId',$applicationId);
		$this->smarty->assign('releases',$releases);
		$this->smarty->display('listReleaseLog.html');	
		die;
	}
	private function downloadConfigFile() {
        $applicationId  = $this->requestParameter['applicationId'];
		$release		= $this->requestParameter['release'];
        $configFile     = $this->requestParameter['configFile'];
        header("Content-type: text/plain");
        header("Content-Disposition: attachment; filename=$configFile");
		$file = PROD_READY_FILES.$release.'/'.$applicationId.'/'.$configFile;
        readfile("$file");
		die;
        //$this->listConfigFile();
	}
	private function changeConfigFile() {
		$date	= date("Y-m-d");
		$release= date("YmdHis");
		shell_exec('cp -r '.CONFIG_UPLOAD_PATH.' '.PROD_READY_FILES.$release.'_tmp');
		//Execute | File Change | Start
		$objApplicationManager = NCConfigFactory::getInstance()->getApplicationManager();
		$objConfigurationManager = NCConfigFactory::getInstance()->getConfigurationManager();
		$objConfigurationFileManager = NCConfigFactory::getInstance()->getConfigurationFileManager();
		$objServiceManager = NCConfigFactory::getInstance()->getServiceManager();
		$objSystemUserManager = NCConfigFactory::getInstance()->getSystemUserManager();
		$objServerManager = NCConfigFactory::getInstance()->getServerManager();
		$applications = $objApplicationManager->listApplication();
		$releaseLog = array();
		foreach($applications as $application) {
			$applicationId		= $application['applicationId'];
			$configurations		= $objConfigurationManager->getApplicationConfiguration($applicationId);
			$configurationFile	= $objConfigurationFileManager->getConfigurationFile($applicationId);
			foreach($configurations as $configuration) {
				$serviceId	= $configuration['serviceId'];
				$systemUserId=$configuration['systemUserId'];
				$service	= $objServiceManager->getService($serviceId);
				$systemUser	= $objSystemUserManager->getSystemUser($systemUserId);
				$server		= $objServerManager->getServer($service['serverId']);
				$serverIP	= $server['serverIP'];
				$servicePort= $service['servicePort'];
				$serviceDNS	= $service['serviceDNS'];
				$serviceDNS2= $service['serviceDNS2'];
				$serviceDNS3= $service['serviceDNS3'];
				$username	= $systemUser['username'];
				$password	= $systemUser['password'];
				$configurationTag	= $configuration['configurationTag'];
				foreach($configurationFile as $file) {
					$configFile = $file['configFile'];
					$configFilePath = PROD_READY_FILES.$release.'_tmp'.'/'.$applicationId.'/'.$configFile;
					file_put_contents($configFilePath,str_replace(TAG_DELIMITER.$configurationTag.'_IP'.TAG_DELIMITER,$serverIP,file_get_contents($configFilePath)));
					file_put_contents($configFilePath,str_replace(TAG_DELIMITER.$configurationTag.'_USERNAME'.TAG_DELIMITER,$username,file_get_contents($configFilePath)));
					file_put_contents($configFilePath,str_replace(TAG_DELIMITER.$configurationTag.'_PASSWORD'.TAG_DELIMITER,$password,file_get_contents($configFilePath)));
					file_put_contents($configFilePath,str_replace(TAG_DELIMITER.$configurationTag.'_PORT'.TAG_DELIMITER,$servicePort,file_get_contents($configFilePath)));
					file_put_contents($configFilePath,str_replace(TAG_DELIMITER.$configurationTag.'_DNS'.TAG_DELIMITER,$serviceDNS,file_get_contents($configFilePath)));
					file_put_contents($configFilePath,str_replace(TAG_DELIMITER.$configurationTag.'_DNS2'.TAG_DELIMITER,$serviceDNS2,file_get_contents($configFilePath)));
					file_put_contents($configFilePath,str_replace(TAG_DELIMITER.$configurationTag.'_DNS3'.TAG_DELIMITER,$serviceDNS3,file_get_contents($configFilePath)));
					$releaseLog[$applicationId][$configFile] = $configFile;
				}
			}
		}
		$this->objReleaseLogManager = NCConfigFactory::getInstance()->getReleaseLogManager();
		foreach($releaseLog as $applicationId=>$files) {
			foreach($files as $file) {
				$this->objReleaseLogManager->addReleaseLog($date,$release,$applicationId,$file,$this->userEMAIL);
			}
		}
		$releaseComment = $this->requestParameter['releaseComment'];
		$this->objReleaseLogManager->addReleaseComment($date,$release,date('Y-m-d H:i:s'),$releaseComment,$this->userEMAIL);
		//Execute | File Change | End
		rename(PROD_READY_FILES.'/'.$release.'_tmp',PROD_READY_FILES.$release);
		$this->smarty->assign('success','Config files changed successfully');
		$this->listConfigFile();
	}	
}
