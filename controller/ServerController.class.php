<?php
class ServerController extends BaseController {
	private $action;
	private $objServerManager;
	private $objServiceTypeManager;
	private $objServiceManager;
	public function __construct($module,$action) {
		parent::__construct($module,$action);
		$this->action = $action;
		$this->smarty->assign('MAIN_TAB','configure');
		$this->smarty->assign('MODULE','server');
		$this->objServerManager = NCConfigFactory::getInstance()->getServerManager();
		$this->objServiceTypeManager = NCConfigFactory::getInstance()->getServiceTypeManager();
		$this->objServiceManager = NCConfigFactory::getInstance()->getServiceManager();
	}
	public function execute() {
		switch($this->action) {
			case 'add':
				$this->addServer();
				break;
			case 'edit':
				$this->editServer();
				break;
			case 'list':
				$this->listServer();
				break;
			case 'view':
				$this->viewServer();
				break;
			case 'delete':
				$this->deleteServer();
				break;
			case 'validateServerName':
				$this->validateServerName();
				break;
			case 'validateServerIP':
				$this->validateServerIP();
				break;
			case 'validateServerRefName':
				$this->validateServerRefName();
				break;
			default:
				$this->listServer();
				break;
		}
	}
	private function addServer() {
		if($this->requestParameter['submit']) {			
			$objServer = new Server();
			$objServerValidator = NCConfigFactory::getInstance()->getServerValidator();
			$objServer->setServerName($this->requestParameter['serverName']);
			$objServer->setServerIP($this->requestParameter['serverIP']);
			$objServer->setServerRefName($this->requestParameter['serverRefName']);
			$errorArray = $objServerValidator->addValidation($objServer);
			if($errorArray) {
				$this->smarty->assign('errors',$errorArray);
			}
			else {
				$serverId = $this->objServerManager->addServer($objServer);
				$this->smarty->assign('success','Server (Id : '.$serverId.') added Successfully.');
				$this->viewServer($serverId);
				die;
			}
		}
		$this->smarty->display('server.html');
	}
	private function editServer() {
		$serverId = $this->requestParameter['serverId'];
		if($this->requestParameter['submit']) {			
			$objServer = new Server();
			$objServerValidator = NCConfigFactory::getInstance()->getServerValidator();
			$objServer->setServerId($this->requestParameter['serverId']);
			$objServer->setServerName($this->requestParameter['serverName']);
			$objServer->setServerIP($this->requestParameter['serverIP']);
			$objServer->setServerRefName($this->requestParameter['serverRefName']);
			$errorArray = $objServerValidator->editValidation($objServer);
			if($errorArray) {
				$this->smarty->assign('errors',$errorArray);
			}
			else {
				$this->objServerManager->editServer($objServer);
				$this->smarty->assign('success','Server (Id : '.$serverId.') updated Successfully.');
				$this->viewServer($serverId);
				die;
			}
		}
		$this->getServices($serverId);
		$this->smarty->display('server.html');
	}
	private function listServer() {
		$servers = $this->objServerManager->listServer();
		if(!$servers) {
			$this->smarty->assign('warning','You have zero servers added.');
		}
		$this->smarty->assign('servers',$servers);
		$this->smarty->display('listServer.html');
	}
	private function viewServer($serverId=0) {
		if(!$serverId) {
			$serverId = $this->requestParameter['serverId'];
		}
		$this->getServices($serverId);
		if($this->requestParameter['action']=='addService') {
			$this->smarty->assign('success','Service added successfully');
		}
		elseif($this->requestParameter['action']=='editService') {
			$this->smarty->assign('success','Service updated successfully');
		}
		elseif($this->requestParameter['action']=='deleteService') {
			$this->smarty->assign('success','Service deleted successfully');
		}
		elseif($this->requestParameter['action']=='deleteServiceError') {
			$this->smarty->assign('error','Service cannot be deleted');
		}
		$this->smarty->display('viewServer.html');
	}
	private function getServices($serverId) {
		$server = $this->objServerManager->getServer($serverId);
		$serviceTypes = $this->objServiceTypeManager->listServiceType();
		$services = $this->objServiceManager->listService($serverId);
		$this->smarty->assign('services',$services);
		$this->smarty->assign('serviceTypes',$serviceTypes);
		$this->smarty->assign('serverId',$serverId);
		$this->smarty->assign('server',$server);
	}
	private function deleteServer() {
		$serverId = $this->requestParameter['serverId'];
		$objServerValidator = NCConfigFactory::getInstance()->getServerValidator();
        $errorArray = $objServerValidator->deleteValidation($serverId);
        if($errorArray) {
            $this->smarty->assign('error','Server cannot be deleted');
        }
		else {
			$this->objServerManager->deleteServer($serverId);
			$this->smarty->assign('success','Server (Id : '.$serverId.') deleted Successfully.');
		}
		$this->listServer();
	}
	private function validateServerName() {
		$serverName = $this->requestParameter['serverName'];
		$status = $this->objServerManager->validateServerName($serverName);
		echo json_encode(array('status'=>$status));
	}
	private function validateServerIP() {
		$serverIP = $this->requestParameter['serverIP'];
		$status = $this->objServerManager->validateServerIP($serverIP);
		echo json_encode(array('status'=>$status));
	}
	private function validateServerRefName() {
		$serverRefName = $this->requestParameter['serverRefName'];
		$status = $this->objServerManager->validateServerRefName($serverRefName);
		echo json_encode(array('status'=>$status));
	}
}
