<?php
require_once SMARTY_PATH;
class BaseController {

	public $smarty;
	public $requestParameter;
	public $userAUTH;	
	public $userROLE;
	public $userEMAIL;
	public $objAuthenticateManager;
	public function __construct($module,$action='') {
		session_start();
		//Smarty Object
		$this->smarty = new Smarty;

		//Prepare Request Parameter.
		foreach($_GET as $key=>$val) {
			$this->requestParameter[$key] = is_array($val)?$val:trim($val);
		}
		foreach($_POST as $key=>$val) {
			$this->requestParameter[$key] = is_array($val)?$val:trim($val);
		}
		foreach($_FILES as $key=>$val) {
			$this->requestParameter[$key] = $val; //name, type, tmp_name, error, size
		}
		$this->smarty->assign('CSS_PATH',CSS_PATH);
		$this->smarty->assign('IMG_PATH',IMG_PATH);
		$this->smarty->assign('JS_PATH',JS_PATH);
		$this->smarty->assign('DOMAIN',DOMAIN);
		$this->smarty->assign('ACTION',$action);
		//Authentication ..
		$this->objAuthenticateManager = NCConfigFactory::getInstance()->getAuthenticateManager();
		if($action != 'login' && $action != 'logout' && $action !='404') {
			if($this->objAuthenticateManager->authenticate()) {
				$this->smarty->assign("userAUTH",1);
				$this->smarty->assign("userROLE",$this->objAuthenticateManager->getRole());
				$this->smarty->assign("userEMAIL",$this->objAuthenticateManager->getEmail());
				$this->userAUTH = 1;
				$this->userROLE = $this->objAuthenticateManager->getRole();
				$this->userEMAIL = $this->objAuthenticateManager->getEmail();
			}
			else {
				$this->smarty->display('login.html');
				die;
			}
		}	
		//Role Assignment | Start
		$objRoleManager = NCConfigFactory::getInstance()->getRoleManager();
		$allowed = $objRoleManager->isAllowed($this->objAuthenticateManager->getRole(),$module,$action);
		if(!$allowed) {
			$errorM = 'Action Not Allowed. <br/>Your account is not currently set up to allow this function. <br/>Please contact your ADMIN User';
			$this->smarty->assign('error',$errorM);
			$this->smarty->display('error.html');
			die;
		}
		$this->smarty->assign('privilegeList',$objRoleManager->getPrivilegeList());
		//Role Assignment | End
	}
    protected function isActionAllowed($applicationId) {
		$role = $this->objAuthenticateManager->getRole();
		if($role=='ADMIN') {
			return true;
		}
		$objRoleManager = NCConfigFactory::getInstance()->getRoleManager();
        $applicationsOwned = $objRoleManager->getApplicationOwned($this->objAuthenticateManager->getEmail());
		if(!in_array($applicationId,$applicationsOwned)) {
			$errorM = 'Action Not Allowed. <br/>Your account is not currently set up to allow this function. <br/>Please contact your ADMIN User';
			$this->smarty->assign('error',$errorM);
			$this->smarty->display('error.html');
			die;
		}
    }
}
?>
