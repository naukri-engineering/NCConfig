<?php
class UserController extends BaseController {
	private $action;
	public function __construct($module,$action) {
		parent::__construct($module,$action);
		$this->action = $action;
		$this->smarty->assign('MAIN_TAB','manage');
		$this->smarty->assign('MODULE','user');
		$this->objUserManager = NCConfigFactory::getInstance()->getUserManager();
	}
	public function execute() {
		switch($this->action) {
			case 'add':
				$this->addUser();
				break;
			case 'list':
				$this->listUser();
				break;
			case 'delete':
				$this->deleteUser();
				break;
			case 'role':
				$this->changeRole();
				break;
			case 'validateEmail':
				$this->validateEmail();
				break;
			case 'changePassword':
				$this->changePassword();
				break;
			default:
				$this->listUser();
				break;
		}
	}
	private function addUser() {
		$userId = 0;
		if($this->requestParameter['submit']) {
            $objUser = new User();
            $objUserValidator = NCConfigFactory::getInstance()->getUserValidator();
            $objUser->setEmail($this->requestParameter['email']);
			$password = $this->requestParameter['password'];
			if($password) {
				$objUser->setPassword(md5($password));
			}
			else {
				$objUser->setPassword(DEFAULT_PASSWORD);
			}
            $objUser->setRole($this->requestParameter['role']);
            $errorArray = $objUserValidator->addValidation($objUser);
            if($errorArray) {
                $errorArray['error'] = 'ERROR';
                echo json_encode($errorArray);
            }
            else {
                $userId = $this->objUserManager->addUser($objUser);
				echo json_encode(array('userId'=>$userId));
            }
		}
	}
	private function changeRole() {
		$userId = $this->requestParameter['userId'];
		if($this->requestParameter['submit']) {
			$objUser = new User();
			$objUser->setUserId($this->requestParameter['userId']);
            $objUser->setRole($this->requestParameter['role']);
			$this->objUserManager->editUser($objUser);
			echo json_encode(array('userId'=>$userId));
		}
	}
	private function listUser() {
        $action = $this->requestParameter['action'];
        if($action == 'addUser') {
            $this->smarty->assign('success','User added successfully');
        }
        elseif($action == 'changeRole') {
            $this->smarty->assign('success','Role changed successfully');
        }
        $users = $this->objUserManager->listUser();
        if(!$users) {
            $this->smarty->assign('warning','You have zero users added.');
        }
        else {
            $this->smarty->assign('users',$users);
        }
		$this->smarty->display('listUser.html');
		die;
	}
	private function deleteUser() {
        $email = $this->requestParameter['email'];
        $objUserValidator = NCConfigFactory::getInstance()->getUserValidator();
        $errorArray = $objUserValidator->deleteValidation($email);
        if($errorArray) {
            $this->smarty->assign('error','User cannot be deleted');
        }
        else {
            $this->objUserManager->deleteUser($email);
            $this->smarty->assign('success','User deleted successfully');
        }
        $this->listUser();
	}
	private function validateEmail() {
        $email = $this->requestParameter['email'];
        $status = $this->objUserManager->validateEmail($email);
        echo json_encode(array('status'=>$status));
	}
	private function changePassword() {
		$email = $this->userEMAIL;
		if($this->requestParameter['submit']) {
			$password = $this->requestParameter['password'];
			$confirmPassword = $this->requestParameter['confirmPassword'];
			$objUser = new User();
			$objUserValidator = NCConfigFactory::getInstance()->getUserValidator();
			$errorArray = $objUserValidator->changePasswordValidation($password,$confirmPassword);
			if($errorArray) {
				$this->smarty->assign('password',$password);
				$this->smarty->assign('confirmPassword',$confirmPassword);
				$this->smarty->assign('errors',$errorArray);
			}
			else {
				$this->objUserManager->changePassword($email,md5($password));
				$this->smarty->assign('success','Your password has been changed successfully');
			}
		}
		$this->smarty->display('changePassword.html');
	}
}
