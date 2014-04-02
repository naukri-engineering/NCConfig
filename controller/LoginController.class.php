<?php
class LoginController extends BaseController {
	private $action;
	public function __construct($module,$action) {
		if($action=='index' || $action=='') {
			$action = 'login';
		}
		parent::__construct($module,$action);
		$this->action = $action;
	}
	public function execute() {
		switch($this->action) {
			case 'login':
				$this->login();
				break;
			case 'logout':
				$this->logout();
				break;
			default:
				$this->login();
				break;
		}
	}
	private function login() {
		if($this->requestParameter['submit']) {
			$email		= $this->requestParameter['email'];
			$password	= $this->requestParameter['password'];
			if($this->objAuthenticateManager->checkPassword($email,$password)) {
				$url = DOMAIN.'server/list';
				header("Location:$url");
				die;
			}
			else {
				$this->smarty->assign('error','Wrong Email and password combination.');
			}
		}
		$this->smarty->display('login.html');
	}
	private function logout() {
		$this->objAuthenticateManager->logout();
		$this->smarty->assign('success',"You've signed out.");
		$this->smarty->display('login.html');	
	}
}
