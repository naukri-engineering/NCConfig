<?php
class ServerValidator extends BaseValidator {
	private $errorArray;
	public function __construct() {
		$this->errorArray = array();
	}
	public function addValidation($objServer) {
        $serverName = $objServer->getServerName();
        if(!$this->blankCheck($serverName)) {
            $this->errorArray['serverName'] = 'Please provide server name';
        }
        elseif(!$this->maxLengthCheck($serverName,50)) {
            $this->errorArray['serverName'] = 'Max length 50 characters long';
        }
        $serverIP = $objServer->getServerIP();
        if(!$this->blankCheck($serverIP)) {
            $this->errorArray['serverIP'] = 'Please provide server IP';
        }
        elseif(!$this->maxLengthCheck($serverIP,15)) {
            $this->errorArray['serverIP'] = 'Max length 15 characters long';
        }
        $serverRefName = $objServer->getServerRefName();
        if(!$this->blankCheck($serverRefName)) {
            $this->errorArray['serverRefName'] = 'Please provide server Ref name';
        }
        elseif(!$this->maxLengthCheck($serverRefName,50)) {
            $this->errorArray['serverRefName'] = 'Max length 50 characters long';
        }
        $objServerManager = NCConfigFactory::getInstance()->getServerManager();
        $status = $objServerManager->validateServerRefName($serverRefName);
        if(!$status)
            $this->errorArray['serverRefName'] = 'Server Ref name already exists';
		$status = $objServerManager->validateServerIP($serverIP);
        if(!$status)
            $this->errorArray['serverIP'] = 'Server IP already exists';
		$status = $objServerManager->validateServerName($serverName);
        if(!$status)
            $this->errorArray['serverName'] = 'Server name already exists';
		return $this->errorArray;
	}
    public function editValidation($objServer) {
        $serverName = $objServer->getServerName();
        if(!$this->blankCheck($serverName)) {
            $this->errorArray['serverName'] = 'Please provide server name';
        }
        elseif(!$this->maxLengthCheck($serverName,50)) {
            $this->errorArray['serverName'] = 'Max length 50 characters long';
        }
        $serverIP = $objServer->getServerIP();
        if(!$this->blankCheck($serverIP)) {
            $this->errorArray['serverIP'] = 'Please provide server IP';
        }
        elseif(!$this->maxLengthCheck($serverIP,15)) {
            $this->errorArray['serverIP'] = 'Max length 15 characters long';
        }
        $serverRefName = $objServer->getServerRefName();
        if(!$this->blankCheck($serverRefName)) {
            $this->errorArray['serverRefName'] = 'Please provide server Ref name';
        }
        elseif(!$this->maxLengthCheck($serverRefName,50)) {
            $this->errorArray['serverRefName'] = 'Max length 50 characters long';
        }
		return $this->errorArray;
	}
	public function deleteValidation($serverId) {
		$ApplicationServerMapManager    = NCConfigFactory::getInstance()->getApplicationServerMapManager();
		$ServiceManager					= NCConfigFactory::getInstance()->getServiceManager();
		if($ApplicationServerMapManager->getServerApplicationMap($serverId))
			return $this->errorArray['error'] = 'ERROR';
		if($ServiceManager->listService($serverId))
			return $this->errorArray['error'] = 'ERROR';
		return $this->errorArray;
	}
}
?>
