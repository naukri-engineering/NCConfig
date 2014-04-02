<?php
class ServiceController extends BaseController {
	private $action;
	private $objServiceManager;
	public function __construct($module,$action) {
		parent::__construct($module,$action);
		$this->action = $action;
		$this->smarty->assign('MAIN_TAB','configure');
		$this->smarty->assign('MODULE','service');
		$this->objServiceManager = NCConfigFactory::getInstance()->getServiceManager();
	}
	public function execute() {
		switch($this->action) {
			case 'add':
				$this->addService();
				break;
			case 'delete':
				$this->deleteService();
				break;
			case 'serviceDetail':
				$this->serviceDetail();
				break;
			case 'validateServiceRefName':
				$this->validateServiceRefName();
				break;
			case 'validateService':
				$this->validateService();
				break;
			default:
				$this->addService();
				break;
		}
	}
	private function addService() {
		if($this->requestParameter['submit']) {
			$objService = new Service();
			$objServiceValidator = NCConfigFactory::getInstance()->getServiceValidator();
			$objService->setServiceId($this->requestParameter['serviceId']);
			$objService->setServerId($this->requestParameter['serverId']);
			$objService->setServiceTypeId($this->requestParameter['serviceTypeId']);
			$objService->setServicePort($this->requestParameter['servicePort']);
			$objService->setServiceDNS($this->requestParameter['serviceDNS']);
			$objService->setServiceDNS2($this->requestParameter['serviceDNS2']);
			$objService->setServiceDNS3($this->requestParameter['serviceDNS3']);
			$objService->setServiceRefName($this->requestParameter['serviceRefName']);
			if($this->requestParameter['serviceId']) {
				$errorArray = $objServiceValidator->editValidation($objService);
				if($errorArray) {
					$errorArray['error'] = 'ERROR';
					echo json_encode($errorArray);
				}
				else {
					$this->objServiceManager->editService($objService);
					echo json_encode(array('serviceId'=>$this->requestParameter['serviceId']));
				}
			}
			else {
				$errorArray = $objServiceValidator->addValidation($objService);
				if($errorArray) {
					$errorArray['error'] = 'ERROR';
					echo json_encode($errorArray);
				}
				else {
					$serviceId = $this->objServiceManager->addService($objService);
					echo json_encode(array('serviceId'=>$serviceId));
				}
			}
		}
	}
	private function deleteService() {
		$serverId = $this->requestParameter['serverId'];
		$serviceId = $this->requestParameter['serviceId'];
		$objServiceValidator = NCConfigFactory::getInstance()->getServiceValidator();
		$errorArray = $objServiceValidator->deleteValidation($serviceId);
		if($errorArray) {
			$returnUrl = DOMAIN."server/view?serverId=$serverId&action=deleteServiceError";
		}
		else {
			$this->objServiceManager->deleteService($serviceId);
			$returnUrl = DOMAIN."server/view?serverId=$serverId&action=deleteService";
		}
		header("Location: $returnUrl");
	}	
	private function validateService() {
		$serverId		= $this->requestParameter['serverId'];
		$serviceTypeId	= $this->requestParameter['serviceTypeId'];
		$servicePort	= $this->requestParameter['servicePort'];
		$status = $this->objServiceManager->validateService($serverId,$serviceTypeId,$servicePort);
		echo json_encode(array('status'=>$status));
	}
	private function validateServiceRefName() {
		$serviceRefName = $this->requestParameter['serviceRefName'];
		$status = $this->objServiceManager->validateServiceRefName($serviceRefName);
		echo json_encode(array('status'=>$status));
	}
	private function serviceDetail() {
		$serviceId = $this->requestParameter['serviceId'];
		$service = $this->objServiceManager->getService($serviceId);		
		echo json_encode($service);
	}
}
