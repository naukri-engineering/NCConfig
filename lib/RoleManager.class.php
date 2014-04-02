<?php
class RoleManager {
	private $privilegeList;
	public function __construct() {
		$this->privilegeList = $this->privilegeList();
	}
	public function isAllowed($role,$module,$action) {
		if($role == 'ADMIN') {
			return true;
		}
		if($module == 'report' || $module == 'login' || $module =='404') {
			return true;
		}
		elseif(in_array($role,$this->privilegeList[$module][$action])) {
			return true;
		}
		else {
			return false;
		}
	}
	public function getPrivilegeList() {
		return $this->privilegeList();
	}
	public function getApplicationOwned($email) {
		$app = array();
		$objApplicationManager = NCConfigFactory::getInstance()->getApplicationManager();
		$applications = $objApplicationManager->getUserApplication($email);
		foreach($applications as $application) {
			$app[] = $application['applicationId'];
		}
		return $app;
	}
	private function privilegeList() {
		$privilege = array();
		$privilege['server']['add'] = array('ADMIN','OPERATIONS');
		$privilege['server']['edit'] = array('ADMIN','OPERATIONS');
		$privilege['server']['list'] = array('ADMIN','READ_ONLY','APP_MANAGER','OPERATIONS');
		$privilege['server']['view'] = array('ADMIN','READ_ONLY','APP_MANAGER','OPERATIONS');
		$privilege['server']['delete'] = array('ADMIN');
		$privilege['server']['validateServerName'] = array('ADMIN','OPERATIONS');
		$privilege['server']['validateServerIP'] = array('ADMIN','OPERATIONS');
		$privilege['server']['validateServerRefName'] = array('ADMIN','OPERATIONS');

		$privilege['service']['add'] = array('ADMIN','OPERATIONS');
		$privilege['service']['delete'] = array('ADMIN');
		$privilege['service']['serviceDetail'] = array('ADMIN','OPERATIONS');
		$privilege['service']['validateServiceRefName'] = array('ADMIN','OPERATIONS');
		$privilege['service']['validateService'] = array('ADMIN','OPERATIONS');

		$privilege['configuration']['add'] = array('ADMIN','OPERATIONS');
		$privilege['configuration']['edit'] = array('ADMIN','OPERATIONS');
		$privilege['configuration']['list'] = array('ADMIN','READ_ONLY','APP_MANAGER','OPERATIONS');
		$privilege['configuration']['delete'] = array('ADMIN','OPERATIONS');
		$privilege['configuration']['validateConfigurationTag'] = array('ADMIN','OPERATIONS');
		$privilege['configuration']['validateConfiguration'] = array('ADMIN','OPERATIONS');

		$privilege['application']['add'] = array('ADMIN');
		$privilege['application']['edit'] = array('ADMIN');
		$privilege['application']['list'] = array('ADMIN','READ_ONLY','APP_MANAGER','OPERATIONS');
		$privilege['application']['delete'] = array('ADMIN');
		$privilege['application']['validateApplicationName'] = array('ADMIN');
		$privilege['application']['applicationDetail'] = array('ADMIN');

		$privilege['systemUser']['add'] = array('ADMIN','OPERATIONS');
		$privilege['systemUser']['edit'] = array('ADMIN','OPERATIONS');
		$privilege['systemUser']['list'] = array('ADMIN','READ_ONLY','APP_MANAGER','OPERATIONS');
		$privilege['systemUser']['delete'] = array('ADMIN','OPERATIONS');
		$privilege['systemUser']['systemUserDetail'] = array('ADMIN','OPERATIONS');
		$privilege['systemUser']['validateSystemUserRefName'] = array('ADMIN','OPERATIONS');
		$privilege['systemUser']['showPassword'] = array('ADMIN','OPERATIONS');

		$privilege['configureApplication']['add'] = array('ADMIN','OPERATIONS');
		$privilege['configureApplication']['edit'] = array('ADMIN','OPERATIONS');
		$privilege['configureApplication']['addConfigFile'] = array('ADMIN','APP_MANAGER');
		$privilege['configureApplication']['deleteConfigFile'] = array('ADMIN','APP_MANAGER');
		$privilege['configureApplication']['downloadConfigFile'] = array('ADMIN','READ_ONLY','APP_MANAGER');
		$privilege['configureApplication']['view'] = array('ADMIN','READ_ONLY','APP_MANAGER','OPERATIONS');
		$privilege['configureApplication']['validateConfigureApplication'] = array('ADMIN','APP_MANAGER','OPERATIONS');

		$privilege['user']['add'] = array('ADMIN');
		$privilege['user']['list'] = array('ADMIN','READ_ONLY','APP_MANAGER','OPERATIONS');
		$privilege['user']['delete'] = array('ADMIN');
		$privilege['user']['role'] = array('ADMIN');
		$privilege['user']['validateEmail'] = array('ADMIN');
		$privilege['user']['changePassword'] = array('ADMIN','READ_ONLY','APP_MANAGER','OPERATIONS');

		$privilege['release']['list'] = array('ADMIN','READ_ONLY','APP_MANAGER','OPERATIONS');
		$privilege['release']['changeConfigFile'] = array('ADMIN','OPERATIONS');
		$privilege['release']['downloadConfigFile'] = array('ADMIN','READ_ONLY','APP_MANAGER','OPERATIONS');
		$privilege['release']['deploy'] = array('ADMIN','OPERATIONS');
		$privilege['release']['readLogFile'] = array('ADMIN','OPERATIONS');

		$privilege['cron']['list'] = array('ADMIN','READ_ONLY','APP_MANAGER','OPERATIONS');
		$privilege['cron']['viewLog'] = array('ADMIN','READ_ONLY','APP_MANAGER','OPERATIONS');
		$privilege['cron']['viewGraph'] = array('ADMIN','READ_ONLY','APP_MANAGER','OPERATIONS');
		$privilege['cron']['delete'] = array('ADMIN','APP_MANAGER');
		$privilege['cron']['comment'] = array('ADMIN','APP_MANAGER');
		$privilege['cron']['edit'] = array('ADMIN','APP_MANAGER');
		$privilege['cron']['deploy'] = array('ADMIN','APP_MANAGER','OPERATIONS');
		$privilege['cron']['add'] = array('ADMIN','APP_MANAGER');

		return $privilege;
	}	
}
?>
