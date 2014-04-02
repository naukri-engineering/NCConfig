<?php
class ApplicationController extends BaseController {
	private $action;
	public function __construct($module,$action) {
		parent::__construct($module,$action);
		$this->action = $action;
		$this->smarty->assign('MAIN_TAB','manage');
		$this->smarty->assign('MODULE','application');
		$this->objApplicationManager = NCConfigFactory::getInstance()->getApplicationManager();
		$this->objUserManager = NCConfigFactory::getInstance()->getUserManager();
	}
	public function execute() {
		switch($this->action) {
			case 'add':
				$this->addApplication();
				break;
			case 'edit':
				$this->editApplication();
				break;
			case 'list':
				$this->listApplication();
				break;
			case 'delete':
				$this->deleteApplication();
				break;
			case 'validateApplicationName':
				$this->validateApplicationName();
				break;
			case 'applicationDetail':
				$this->applicationDetail();
				break;
			default:
				$this->listApplication();
				break;
		}
	}
	private function addApplication() {
		$applicationId = 0;
		if($this->requestParameter['submit']) {
			$objApplication = new Application();
			$objApplicationValidator = NCConfigFactory::getInstance()->getApplicationValidator();
			$objApplication->setApplicationGroupName($this->requestParameter['applicationGroupName']);
			$objApplication->setApplicationName($this->requestParameter['applicationName']);
			$objApplication->setEmail($this->requestParameter['email']);
			$errorArray = $objApplicationValidator->addValidation($objApplication);
			if($errorArray) {
				$errorArray['error'] = 'ERROR';
				echo json_encode($errorArray);
			}
			else {
				$objUser = new User();
				$objUser->setEmail($this->requestParameter['email']);
				$objUser->setPassword(DEFAULT_PASSWORD);
				$objUser->setRole(DEFAULT_ROLE);

				$objUserValidator = NCConfigFactory::getInstance()->getUserValidator();
				$errorArray = $objUserValidator->addValidation($objUser);
				if($errorArray) {
					$errorArray['error'] = 'ERROR';
					echo json_encode($errorArray);
				}
				else {
					$userId = $this->objUserManager->addUser($objUser);	
					$applicationId = $this->objApplicationManager->addApplication($objApplication);
					echo json_encode(array('applicationId'=>$applicationId));
				}
			}
		}
	}
	private function editApplication() {
		$applicationId = $this->requestParameter['applicationId'];
		if($this->requestParameter['submit']) {
			$objApplication = new Application();
			$objApplicationValidator = NCConfigFactory::getInstance()->getApplicationValidator();
			$objApplication->setApplicationId($applicationId);
			$objApplication->setApplicationGroupName($this->requestParameter['applicationGroupName']);
			$objApplication->setApplicationName($this->requestParameter['applicationName']);
			$objApplication->setEmail($this->requestParameter['email']);
			$errorArray = $objApplicationValidator->editValidation($objApplication);
			if($errorArray) {
				$errorArray['error'] = 'ERROR';
				echo json_encode($errorArray);
			}
			else {
				$objUser = new User();
				$objUser->setEmail($this->requestParameter['email']);
				$objUser->setPassword(DEFAULT_PASSWORD);
				$objUser->setRole(DEFAULT_ROLE);
				$objUserValidator = NCConfigFactory::getInstance()->getUserValidator();
				$errorArray = $objUserValidator->editValidation($objUser);
				if($errorArray) {
					$errorArray['error'] = 'ERROR';
					echo json_encode($errorArray);
				}
				else {
					$userId = $this->objUserManager->addUser($objUser);	
					$applicationId = $this->objApplicationManager->editApplication($objApplication);
					echo json_encode(array('applicationId'=>$applicationId));
				}
			}
		}
	}
	private function listApplication() {
		$action = $this->requestParameter['action'];
		if($action == 'addApplication') {
			$this->smarty->assign('success','Application added successfully');
		}
		elseif($action == 'editApplication') {
			$this->smarty->assign('success','Application updated successfully');
		}
		//Getting list of Applications.
		$applications = $this->objApplicationManager->listApplication();

		//If zero applications found.
		if(!$applications) {
			$this->smarty->assign('warning','You have zero applications added.');
		}
		else {
			$this->smarty->assign('applications',$applications);
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
		}
		$this->smarty->display('listApplication.html');
		die;
	}
	private function deleteApplication() {
		$applicationId = $this->requestParameter['applicationId'];
		$objApplicationValidator = NCConfigFactory::getInstance()->getApplicationValidator();
		$errorArray = $objApplicationValidator->deleteValidation($applicationId);
		if($errorArray) {
			$this->smarty->assign('error','Application cannot be deleted');
		}
		else {
			//Delete Application.
			$this->objApplicationManager->deleteApplication($applicationId);
			$this->smarty->assign('success','Application deleted successfully');
		}
		$this->listApplication();		
	}
	private function applicationDetail() {
		$applicationId = $this->requestParameter['applicationId'];
		$application = $this->objApplicationManager->getApplication($applicationId);
		echo json_encode($application);
	}
	private function validateApplicationName() {
		$applicationName = $this->requestParameter['applicationName'];
		$status = $this->objApplicationManager->validateApplicationName($applicationName);
		echo json_encode(array('status'=>$status));
	}
}
