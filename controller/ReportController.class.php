<?php
class ReportController extends BaseController {
	private $action;
	public function __construct($module,$action) {
		parent::__construct($module,$action);
		$this->action = $action;
		$this->smarty->assign('MAIN_TAB','report');
		$this->smarty->assign('MODULE','report');
	}
	public function execute() {
		switch($this->action) {
			case 'graph':
				$this->connectionGraph();
				break;
			case 'server':
				$this->serverReport();
				break;
			case 'application':
				$this->applicationReport();
				break;
			case 'service':
				$this->serviceReport();
				break;
			case 'serviceType':
				$this->serviceTypeReport();
				break;
			case 'systemUser':
				$this->systemUserReport();
				break;
			default:
				$this->connectionGraph();
				break;
		}
	}
	private function serverReport() {
		$objServerManager = NCConfigFactory::getInstance()->getServerManager();
		$servers = $objServerManager->listServer();
		if($this->requestParameter['serverId']) {
			$serverId = $this->requestParameter['serverId'];
			$objReportManager = NCConfigFactory::getInstance()->getReportManager();
			$reportData = $objReportManager->reportData('server',$serverId);
			if(!$reportData) {
				$this->smarty->assign('warning','No data !');
			}
			$this->smarty->assign('serverReport',$reportData);
		}
		$this->smarty->assign('serverId',$serverId);
		$this->smarty->assign('servers',$servers);
		$this->smarty->display('reportServer.html');
	}
	private function applicationReport() {
		$objApplicationManager = NCConfigFactory::getInstance()->getApplicationManager();
		$applications = $objApplicationManager->listApplication();
		if($this->requestParameter['applicationId']) {
			$applicationId = $this->requestParameter['applicationId'];
			$objReportManager = NCConfigFactory::getInstance()->getReportManager();
			$reportData = $objReportManager->reportData('application',$applicationId);
			if(!$reportData) {
				$this->smarty->assign('warning','No data !');
			}
			$this->smarty->assign('applicationReport',$reportData);
		}
		$this->smarty->assign('applicationId',$applicationId);
		$this->smarty->assign('applications',$applications);
		$this->smarty->display('reportApplication.html');
	}

	private function serviceReport() {
		$objServiceManager = NCConfigFactory::getInstance()->getServiceManager();
		$services = $objServiceManager->listService();
		if($this->requestParameter['serviceId']) {
			$serviceId = $this->requestParameter['serviceId'];
			$objReportManager = NCConfigFactory::getInstance()->getReportManager();
			$reportData = $objReportManager->reportData('service',$serviceId);
			if(!$reportData) {
				$this->smarty->assign('warning','No data !');
			}
			$this->smarty->assign('serviceReport',$reportData);
		}
		$this->smarty->assign('serviceId',$serviceId);
		$this->smarty->assign('services',$services);
		$this->smarty->display('reportService.html');
	}
	private function systemUserReport() {
		$objSystemUserManager = NCConfigFactory::getInstance()->getSystemUserManager();
		$systemUsers = $objSystemUserManager->listSystemUser();
		if($this->requestParameter['systemUserId']) {
			$systemUserId = $this->requestParameter['systemUserId'];
			$objReportManager = NCConfigFactory::getInstance()->getReportManager();
			$reportData = $objReportManager->reportData('systemUser',$systemUserId);
			if(!$reportData) {
				$this->smarty->assign('warning','No data !');
			}
			$this->smarty->assign('systemUserReport',$reportData);
		}
		$this->smarty->assign('systemUserId',$systemUserId);
		$this->smarty->assign('systemUsers',$systemUsers);
		$this->smarty->display('reportSystemUser.html');
	}
	private function serviceTypeReport() {
		$objServiceTypeManager = NCConfigFactory::getInstance()->getServiceTypeManager();
		$serviceTypes = $objServiceTypeManager->listServiceType();
		if($this->requestParameter['serviceTypeId']) {
			$serviceTypeId = $this->requestParameter['serviceTypeId'];
			$objReportManager = NCConfigFactory::getInstance()->getReportManager();
			$reportData = $objReportManager->reportData('serviceType',$serviceTypeId);
			if(!$reportData) {
				$this->smarty->assign('warning','No data !');
			}
			$this->smarty->assign('serviceTypeReport',$reportData);
		}
		$this->smarty->assign('serviceTypeId',$serviceTypeId);
		$this->smarty->assign('serviceTypes',$serviceTypes);
		$this->smarty->display('reportServiceType.html');
	}
	private function connectionGraph() {
		if($this->requestParameter['submit']) {
			$applicationId = $this->requestParameter['applicationId'];
			$serviceTypeId = $this->requestParameter['serviceTypeId'];
		}
		$objApplicationManager = NCConfigFactory::getInstance()->getApplicationManager();
		$objServiceTypeManager = NCConfigFactory::getInstance()->getServiceTypeManager();
		$objReportManager = NCConfigFactory::getInstance()->getReportManager();
		$applications = $objApplicationManager->listApplication();
		if($applicationId) {
			$apps[] = $objApplicationManager->getApplication($applicationId);
		}
		else {
			$apps = $applications;
		}
		$serviceTypes = $objServiceTypeManager->listServiceType();

		$colors = $this->getColorCodes();
		$colorCodes = array();
		$i = 0;
		foreach($apps as $application) {	
			$colorCodes[$application['applicationId']] = $colors[$i];
			$i++;
		}
		$connectionGraph = $objReportManager->connectionGraph($applicationId,$serviceTypeId);
		foreach($connectionGraph as $key=>$conn) {
			$conn['color'] = $colorCodes[$conn['applicationId']];
			$connectionGraph[$key] = $conn;
		}
		$servers = array();
		foreach($connectionGraph as $connection) {
			$servers[] = array('serverId'=>$connection['serverId'],'serverName'=>$connection['serverName']);
		}
		$this->smarty->assign('servers',$servers);
		$this->smarty->assign('applications',$applications);
		$this->smarty->assign('apps',$apps);
		$this->smarty->assign('serviceTypes',$serviceTypes);
		$this->smarty->assign('applicationId',$applicationId);
		$this->smarty->assign('serviceTypeId',$serviceTypeId);
		$this->smarty->assign('connectionInfo',$connectionGraph);
		$this->smarty->display('connectionGraph.html');	
	}
	private function getColorCodes() {
		return array(
		'#A52A2A','#FF0000','#0000FF','#00FF00','#FFFF00','#FF00FF','#00008B','#006400','#800080','#ADDFFF','#646060','#15317E','#8D38C9','#7A5DC7',
		'#F6358A','#4AA02C','#A52A2A','#FF0000','#0000FF','#00FF00','#FFFF00','#FF00FF','#00008B','#006400','#800080','#ADDFFF','#646060','#15317E',
		'#8D38C9','#7A5DC7','#F6358A','#4AA02C','#A52A2A','#FF0000','#0000FF','#00FF00','#FFFF00','#FF00FF','#00008B','#006400','#800080','#ADDFFF',
		'#646060','#15317E','#8D38C9','#7A5DC7','#F6358A','#4AA02C','#A52A2A','#FF0000','#0000FF','#00FF00','#FFFF00','#FF00FF','#00008B','#006400',
		'#800080','#ADDFFF','#646060','#15317E','#8D38C9','#7A5DC7','#F6358A','#4AA02C','#A52A2A','#FF0000','#0000FF','#00FF00','#FFFF00','#FF00FF',
		'#00008B','#006400');
	}
}
