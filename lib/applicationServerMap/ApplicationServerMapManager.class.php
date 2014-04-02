<?php
class ApplicationServerMapManager {
	private $objApplicationServerMapStore;
	public function __construct($objApplicationServerMapStore) {
		$this->objApplicationServerMapStore = $objApplicationServerMapStore;
	}
	public function addApplicationServerMap($objApplicationServerMap) {
		return $this->objApplicationServerMapStore->addApplicationServerMap($objApplicationServerMap);		
	}
	public function editApplicationServerMap($objApplicationServerMap) {
		return $this->objApplicationServerMapStore->editApplicationServerMap($objApplicationServerMap);		
	}
	public function getApplicationServerMap($applicationId) {
		$serversArr = $this->serverList();
		$appServers = $this->objApplicationServerMapStore->getApplicationServerMap($applicationId);
		foreach($appServers as $key=>$appServer) {
			$appServers[$key]['serverName'] = $serversArr[$appServer['serverId']]['serverName'];
			$appServers[$key]['serverIP'] = $serversArr[$appServer['serverId']]['serverIP'];
			$appServers[$key]['serverLabel'] = $serversArr[$appServer['serverId']]['serverName'].'('.$serversArr[$appServer['serverId']]['serverRefName'].')';
		}
		return $appServers;	
	}
	public function getServerApplicationMap($serverId) {
		return $this->objApplicationServerMapStore->getServerApplicationMap($serverId);
	}
	public function deleteApplicationServerMap($applicationId) {
		return $this->objApplicationServerMapStore->deleteApplicationServerMap($applicationId);
	}
	public function listApplicationServerMap() {
		return $this->objApplicationServerMapStore->listApplicationServerMap();
	}
	private function serverList() {
		$objServerManager = NCConfigFactory::getInstance()->getServerManager();
		$servers = $objServerManager->listServer();
		$serversArr = array();
		foreach($servers as $server) {
			$serversArr[$server['serverId']] = $server;
		}
		return $serversArr;	
	}
}
?>
