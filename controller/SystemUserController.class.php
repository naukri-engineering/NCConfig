<?php
class SystemUserController extends BaseController {
	private $action;
	public function __construct($module,$action) {
		parent::__construct($module,$action);
		$this->action = $action;
		$this->smarty->assign('MAIN_TAB','manage');
		$this->smarty->assign('MODULE','systemUser');
		$this->objSystemUserManager = NCConfigFactory::getInstance()->getSystemUserManager();
	}
	public function execute() {
		switch($this->action) {
			case 'add':
				$this->addSystemUser();
				break;
			case 'edit':
				$this->editSystemUser();
				break;
			case 'list':
				$this->listSystemUser();
				break;
			case 'delete':
				$this->deleteSystemUser();
				break;
			case 'systemUserDetail':
				$this->systemUserDetail();
				break;
			case 'validateSystemUserRefName':
				$this->validateSystemUserRefName();
				break;
			default:
				$this->listSystemUser();
				break;
		}
	}
	private function addSystemUser() {
		$systemUserId = 0;
		if($this->requestParameter['submit']) {
            $objSystemUser = new SystemUser();
            $objSystemUserValidator = NCConfigFactory::getInstance()->getSystemUserValidator();
            $objSystemUser->setSystemUserRefName($this->requestParameter['systemUserRefName']);
            $objSystemUser->setUsername($this->requestParameter['username']);
            $objSystemUser->setPassword($this->requestParameter['password']);
            $errorArray = $objSystemUserValidator->addValidation($objSystemUser);
            if($errorArray) {
				$errorArray['error'] = 'ERROR';
				echo json_encode($errorArray);
            }
            else {
                $systemUserId = $this->objSystemUserManager->addSystemUser($objSystemUser);
				echo json_encode(array('systemUserId'=>$systemUserId));
            }
		}
	}
    private function editSystemUser() {
		$systemUserId = $this->requestParameter['systemUserId'];
        if($this->requestParameter['submit']) {
            $objSystemUser = new SystemUser();
            $objSystemUserValidator = NCConfigFactory::getInstance()->getSystemUserValidator();
            $objSystemUser->setSystemUserId($systemUserId);
            $objSystemUser->setSystemUserRefName($this->requestParameter['systemUserRefName']);
            $objSystemUser->setUsername($this->requestParameter['username']);
            $objSystemUser->setPassword($this->requestParameter['password']);
            $errorArray = $objSystemUserValidator->editValidation($objSystemUser);
            if($errorArray) {
                $errorArray['error'] = 'ERROR';
                echo json_encode($errorArray);
            }
            else {
                $systemUserId = $this->objSystemUserManager->editSystemUser($objSystemUser);
                echo json_encode(array('systemUserId'=>$systemUserId));
            }
        }
    }
	private function listSystemUser() {
        $action = $this->requestParameter['action'];
        if($action == 'addSystemUser') {
            $this->smarty->assign('success','SystemUser added successfully');
        }
        elseif($action == 'editSystemUser') {
            $this->smarty->assign('success','SystemUser updated successfully');
        }
        $systemUsers = $this->objSystemUserManager->listSystemUser();
        if(!$systemUsers) {
            $this->smarty->assign('warning','You have zero system users added.');
        }
        else {
            $this->smarty->assign('systemUsers',$systemUsers);
        }
		$this->smarty->display('listSystemUser.html');
		die;
	}
	private function deleteSystemUser() {
        $systemUserId = $this->requestParameter['systemUserId'];
        $objSystemUserValidator = NCConfigFactory::getInstance()->getSystemUserValidator();
        $errorArray = $objSystemUserValidator->deleteValidation($systemUserId);
        if($errorArray) {
            $this->smarty->assign('error','SystemUser cannot be deleted');
        }
        else {
            //Delete SystemUser.
            $this->objSystemUserManager->deleteSystemUser($systemUserId);
            $this->smarty->assign('success','SystemUser deleted successfully');
        }
        $this->listSystemUser();
	}
	private function systemUserDetail() {
        $systemUserId = $this->requestParameter['systemUserId'];
        $systemUser = $this->objSystemUserManager->getSystemUser($systemUserId);
        echo json_encode($systemUser);
	}
	private function validateSystemUserRefName() {
        $systemUserRefName = $this->requestParameter['systemUserRefName'];
        $status = $this->objSystemUserManager->validateSystemUserRefName($systemUserRefName);
        echo json_encode(array('status'=>$status));
	}
}
