<?php
class CronController extends BaseController {
	private $action;
	public function __construct($module,$action) {
		parent::__construct($module,$action);
		$this->action = $action;
        $this->smarty->assign('MAIN_TAB','cron');
        $this->smarty->assign('MODULE','cron');
		$this->objCronManager = NCConfigFactory::getInstance()->getCronManager();
		$this->objServerManager = NCConfigFactory::getInstance()->getServerManager();
		$this->objApplicationManager = NCConfigFactory::getInstance()->getApplicationManager();
	}
	public function execute() {
		switch($this->action) {
			case 'list':
				$this->listCron();
				break;
			case 'delete':
				$this->deleteCron();
				break;
			case 'comment':
				$this->commentCron();
				break;
			case 'edit':
				$this->editCron();
				break;
			case 'viewLog':
				$this->viewLog();
				break;
			case 'viewGraph':
				$this->viewGraph();
				break;
			case 'deploy':
				$this->deployCron();
				break;
			default:
				$this->add();
				break;
		}
	}
	private function listCron() {
		$applications = $this->objApplicationManager->listApplication();
		$this->smarty->assign('applications',$applications);

		$servers = $this->objServerManager->listServer();
		$this->smarty->assign('servers',$servers);

		if($this->requestParameter['submit']) {
			$serverId = $this->requestParameter['serverId'];
			$applicationId = $this->requestParameter['applicationId'];	
			$user = $this->requestParameter['user'];
			$fromEmail = $this->requestParameter['fromEmail'];
			$toEmail = $this->requestParameter['toEmail'];
			$keyword = $this->requestParameter['keyword'];
			$completionTime = $this->requestParameter['completionTime'];
			$maxConcurrency = $this->requestParameter['maxConcurrency'];
			$timeAlert = $this->requestParameter['timeAlert'];
			$active = $this->requestParameter['active'];

			$objSearch = new Search();
			$objSearch->setApplicationId($this->requestParameter['applicationId']);
			$objSearch->setServerId($this->requestParameter['serverId']);
			$objSearch->setUser($this->requestParameter['user']);
			$objSearch->setFromEmail($this->requestParameter['fromEmail']);
			$objSearch->setToEmail($this->requestParameter['toEmail']);
			$objSearch->setCompletionTime($this->requestParameter['completionTime']);
			$objSearch->setMaxConcurrency($this->requestParameter['maxConcurrency']);
			$objSearch->setTimeAlert($this->requestParameter['timeAlert']);
			$objSearch->setKeyword($this->requestParameter['keyword']);
			$objSearch->setActive($this->requestParameter['active']);
			
			$this->smarty->assign('serverId',$serverId);
			$this->smarty->assign('applicationId',$applicationId);
			$this->smarty->assign('user',$user);
			$this->smarty->assign('fromEmail',$fromEmail);
			$this->smarty->assign('toEmail',$toEmail);
			$this->smarty->assign('keyword',$keyword);
			$this->smarty->assign('completionTime',$completionTime);
			$this->smarty->assign('maxConcurrency',$maxConcurrency);
			$this->smarty->assign('timeAlert',$timeAlert);
			$this->smarty->assign('active',$active);

			$crons = $this->objCronManager->listCron($objSearch);
			foreach($crons as $key=>$cron) {
				$server = $this->objServerManager->getServer($cron['serverId']);
				$application = $this->objApplicationManager->getApplication($cron['applicationId']);
				$crons[$key]['serverName'] = $server['serverRefName'].' - '.$server['serverName'].' ('.$server['serverIP'].')';
				$crons[$key]['applicationName'] = $application['applicationName'].' ('.$application['applicationGroupName'].')';
				$crons[$key]['command'] = htmlentities($crons[$key]['command']);
			}
			if(!$crons) {
				$this->smarty->assign('warning','You have zero crons');
			}
			else {
				$this->smarty->assign('crons',$crons);
			}
		}
		$this->smarty->display('listCron.html');	
	}
	private function deleteCron() {
		$cronId = $this->requestParameter['cronId'];

		$cron = $this->objCronManager->getCron($cronId);
		//Application Check | Start
		$this->isActionAllowed($cron['applicationId']); 
		//Application Check | End

		$this->objCronManager->deleteCron($cronId);
		$this->smarty->assign('success','Cron deleted successfully');
		$this->listCron();		
	}
	private function commentCron() {
		$cronId = $this->requestParameter['cronId'];
		$active = $this->requestParameter['active'];

		$cron = $this->objCronManager->getCron($cronId);
		//Application Check | Start
		$this->isActionAllowed($cron['applicationId']); 
		//Application Check | End

		if($active=='Y') {
			$active = 'N';
			$message= 'Cron commented successfully';
		}
		else if($active=='N') {
			$active = 'Y';
			$message= 'Cron uncommented successfully';
		}
		$this->objCronManager->commentCron($cronId,$active);
		$this->smarty->assign('success',$message);
		$this->listCron();		
	}
	private function add() {
		if($this->requestParameter['submit']) {
            $objCron = new Cron();
            $objCronValidator = NCConfigFactory::getInstance()->getCronValidator();
            $objCron->setApplicationId($this->requestParameter['applicationId']);
            $objCron->setMinute($this->requestParameter['minute']);
            $objCron->setHour($this->requestParameter['hour']);
            $objCron->setDay($this->requestParameter['day']);
            $objCron->setMonth($this->requestParameter['month']);
            $objCron->setWeekday($this->requestParameter['weekday']);
            $objCron->setCommand($this->requestParameter['command']);
            $objCron->setUser($this->requestParameter['user']);
            $objCron->setCompletionTime($this->requestParameter['completionTime']);
            $objCron->setComment($this->requestParameter['comment']);
			$objCron->setServerId($this->requestParameter['serverId']);
			$objCron->setMaxConcurrency($this->requestParameter['maxConcurrency']);
			$objCron->setTimeAlert($this->requestParameter['timeAlert']);
			$objCron->setFromEmail($this->requestParameter['fromEmail']);
			$objCron->setToEmail($this->requestParameter['toEmail']);
			$objCron->setCronAlias($this->requestParameter['cronAlias']);
			$objCron->setCronOutput($this->requestParameter['cronOutput']);
			$objCron->setUpdatedBy($this->userEMAIL);

			//Application Check | Start
			$this->isActionAllowed($this->requestParameter['applicationId']); 
			//Application Check | End

            //$errorArray = $objCronValidator->addValidation($objCron);
            if($errorArray) {
                $errorArray['error'] = 'ERROR';
                echo json_encode($errorArray);
            }
            else {
				$cronId = $this->objCronManager->addCron($objCron);
				$this->smarty->assign('success','Cron Job added successfully');
            }
		}
		$cronUsers = $this->objCronManager->listCronUser();
		$applications = $this->objApplicationManager->listApplication();
		$servers = $this->objServerManager->listServer();
		$this->smarty->assign('servers',$servers);
		$this->smarty->assign('cronUsers',$cronUsers);
		$this->smarty->assign('applications',$applications);		
		$this->smarty->display('cron.html');
	}
	private function editCron() {
		$cronId = $this->requestParameter['cronId'];
		if($this->requestParameter['submit']) {
            $objCron = new Cron();
            $objCronValidator = NCConfigFactory::getInstance()->getCronValidator();
            $objCron->setCronId($this->requestParameter['cronId']);
            $objCron->setApplicationId($this->requestParameter['applicationId']);
            $objCron->setServerId($this->requestParameter['serverId']);
            $objCron->setMinute($this->requestParameter['minute']);
            $objCron->setHour($this->requestParameter['hour']);
            $objCron->setDay($this->requestParameter['day']);
            $objCron->setMonth($this->requestParameter['month']);
            $objCron->setWeekday($this->requestParameter['weekday']);
            $objCron->setCommand($this->requestParameter['command']);
            $objCron->setComment($this->requestParameter['comment']);
            $objCron->setUser($this->requestParameter['user']);
            $objCron->setCompletionTime($this->requestParameter['completionTime']);
            $objCron->setMaxConcurrency($this->requestParameter['maxConcurrency']);
            $objCron->setTimeAlert($this->requestParameter['timeAlert']);
            $objCron->setFromEmail($this->requestParameter['fromEmail']);
            $objCron->setToEmail($this->requestParameter['toEmail']);
            $objCron->setCronAlias($this->requestParameter['cronAlias']);
            $objCron->setCronOutput($this->requestParameter['cronOutput']);
			$objCron->setUpdatedBy($this->userEMAIL);

			//Application Check | Start
			$this->isActionAllowed($this->requestParameter['applicationId']); 
			//Application Check | End

			//$errorArray = $objCronValidator->addValidation($objCron);
			if($errorArray) {
				$errorArray['error'] = 'ERROR';
				echo json_encode($errorArray);
			}
			else {
				$this->objCronManager->editCron($objCron);
				$this->smarty->assign('success','Cron Job updated successfully');
			}
		}
		$cron = $this->objCronManager->getCron($cronId);

		//Application Check | Start
		$this->isActionAllowed($cron['applicationId']);
		//Application Check | End

		$cron['command'] = htmlentities($cron['command']);

		$this->smarty->assign('cron',$cron);

        $applications = $this->objApplicationManager->listApplication();
        $servers = $this->objServerManager->listServer();

		$cronUsers = $this->objCronManager->listCronUser();
		$this->smarty->assign('cronUsers',$cronUsers);

        $this->smarty->assign('servers',$servers);
        $this->smarty->assign('applications',$applications);

		$this->smarty->display('cron.html');
	}
	private function viewLog() {
		$cronId = $this->requestParameter['cronId'];
		if($this->requestParameter['submit']) {
			$cronStatus = $this->requestParameter['cronStatus'];
			$startDate = $this->requestParameter['startDate'];
			$endDate = $this->requestParameter['endDate'];
			$log = $this->objCronManager->getCronLog($cronId,100,$cronStatus,$startDate,$endDate);
		}
		else {
			$log = $this->objCronManager->getCronLog($cronId,15);
		}
		$this->smarty->assign('log',$log);

		$cron = $this->objCronManager->getCron($cronId);
		$server = $this->objServerManager->getServer($cron['serverId']);
		$application = $this->objApplicationManager->getApplication($cron['applicationId']);
		$cron['serverName'] = $server['serverRefName'].' - '.$server['serverName'].' ('.$server['serverIP'].')';
		$cron['applicationName'] = $application['applicationName'].' ('.$application['applicationGroupName'].')';
		$this->smarty->assign('cron',$cron);

		if(!$log) {
			$this->smarty->assign('warning',"This cron (Cron Id : $cronId) has not yet been executed");
		}
		$this->smarty->assign('cronId',$cronId);
		$this->smarty->display('viewLogCron.html');
	}
	private function viewGraph() {
		$cronId = $this->requestParameter['cronId'];
		if($this->requestParameter['submit']) {
			$startDate = $this->requestParameter['startDate'];
			$endDate = $this->requestParameter['endDate'];
			$log = $this->objCronManager->getCronLog($cronId,100,'',$startDate,$endDate);
		}
		else {
			$log	= $this->objCronManager->getCronLog($cronId,100);
		}
		if(!$log) {
			$this->smarty->assign('warning','No Result');
		}
		$group = array();
		foreach($log as $l) {
			$cronStatus = $l['cronStatus'];
			if(isset($group[$cronStatus])) {
				$group[$cronStatus] += 1;
			}
			else {
				$group[$cronStatus] = 1;
			}
		}
		$this->smarty->assign('group',$group);

		$cron	= $this->objCronManager->getCron($cronId);
		$this->smarty->assign('cron',$cron);

		$this->smarty->assign('cronId',$cronId);
		$this->smarty->assign('log',$log);
		$this->smarty->display('viewGraphCron.html');
	}
	private function deployCron() {
		if($this->requestParameter['submit'] || $this->requestParameter['svnSubmit']) {
			$serverId = $this->requestParameter['serverId'];			
			$user = $this->requestParameter['user'];			

			$serverDet = $this->objServerManager->getServer($serverId);
			$serverName= $serverDet['serverName']; 

			$cronFileContent = $this->getServerCrons($serverId,$user);	
			if(!$cronFileContent) {
				$this->smarty->assign('warning','No crons');
			}
			$this->smarty->assign('cronFileContent',nl2br($cronFileContent));
			$this->smarty->assign('serverId',$serverId);
			$this->smarty->assign('user',$user);
			if($this->requestParameter['svnSubmit']) {
				$errorCode = 0;
				$serverDir = CRON_FILE_PATH.$serverName;
				$cronUserFile = CRON_FILE_PATH.$serverName.'/'.$user;
				$cronUserBackupFile = CRON_FILE_BACKUP_PATH.$serverName.'_'.$user.'_'.date('YmdHis');
				if(!is_dir($serverDir)) {
					if(!mkdir($serverDir)) {
						$errorCode = 1001;
					}
				}
				if(!$errorCode) {
					if(is_file($cronUserFile)) { //Take Backup
						if(!copy($cronUserFile,$cronUserBackupFile)) {
							$errorCode = 1002;
						}
					}
					if(!$errorCode) {
						if(!file_put_contents($cronUserFile,$cronFileContent)) { //Write content to file...
							$errorCode = 1003;
						}
					}
				}
				if($errorCode) {
					$this->smarty->assign('error','Whoops! Looks Like Something Broke : Error Code - '.$errorCode);
				}
				else {
					$svnAdd = "svn add --force $serverDir;";
					$svnCom = "svn commit $serverDir --username ".SVN_AUTH_DEFAULT_USERNAME." --password ".SVN_AUTH_DEFAULT_PASSWORD." -m 'Adding $serverName ($user) to SVN ..........' --non-interactive --trust-server-cert --quiet 2>&1;";
					$execute= exec("$svnAdd$svnCom", $output, $error);
					if($error) {
						$this->smarty->assign('warning','Could not save to SVN. Please contact your Administrator');
					}
					$this->smarty->assign('success','Cron file created successfully');
					$this->smarty->assign('cronFileContentOutput',nl2br(file_get_contents($cronUserFile)));
					$this->smarty->assign('svnContentOutput',implode('<br/>',$output));
				}
			}
		}
        $servers = $this->objServerManager->listServer();
		$cronUsers = $this->objCronManager->listCronUser();
        $this->smarty->assign('servers',$servers);
		$this->smarty->assign('cronUsers',$cronUsers);
		$this->smarty->display('deployCron.html');
	}
	private function getServerCrons($serverId,$user) {
        $objSearch = new Search();
        $objSearch->setServerId($serverId);
        $objSearch->setUser($user);
        $crons = $this->objCronManager->listCron($objSearch);
		$cronFileContent = '';
		foreach($crons as $key=>$cron) {
			$minute = $cron['minute'];
			$hour = $cron['hour'];
			$day = $cron['day'];
			$month = $cron['month'];
			$weekday = $cron['weekday'];
			if($cron['active']=='N') {
				$commented = '#';
			}
			else {
				$commented = '';
			}
			$application = $this->objApplicationManager->getApplication($cron['applicationId']);
			$applicationName = $application['applicationName'].' ('.$application['applicationGroupName'].')';
			$json = json_encode(array('cronId'=>$cron['cronId'],'command'=>$cron['command'],'maxConcurrency'=>$cron['maxConcurrency'],'timeAlert'=>$cron['timeAlert'],'fromEmail'=>$cron['fromEmail'],'toEmail'=>$cron['toEmail'],'cronAlias'=>$cron['cronAlias'],'cronOutput'=>$cron['cronOutput']));
			$cronFileContent .= "# $applicationName, Last Updated By - ".$cron['updatedBy'].", Last Updated On - ".$cron['updatedOn']."\n";
			$cronFileContent .= "$commented$minute $hour $day $month $weekday ".BASE_CRON_COMMAND." '".$json."'\n\n";
		}
		return $cronFileContent;
	}
}
