<?php
class ServerManager {
	private $objServerStore;
	public function __construct($objServerStore) {
		$this->objServerStore = $objServerStore;
	}
	public function addServer($objServer) {
		return $this->objServerStore->addServer($objServer);		
	}
	public function editServer($objServer) {
		return $this->objServerStore->editServer($objServer);		
	}
	public function getServer($serverId) {
		return $this->objServerStore->getServer($serverId);
	}
	public function deleteServer($serverId) {
		return $this->objServerStore->deleteServer($serverId);
	}
	public function listServer() {
		return $this->objServerStore->listServer();
	}
	public function validateServerName($serverName) {
		$status = $this->objServerStore->validateServerName($serverName);
		if($status)
			return false;
		return true;
	}
	public function validateServerIP($serverIP) {
		$status = $this->objServerStore->validateServerIP($serverIP);
		if($status)
			return false;
		return true;
	}
	public function validateServerRefName($serverRefName) {
		$status = $this->objServerStore->validateServerRefName($serverRefName);
		if($status)
			return false;
		return true;
	}
}
?>
