<?php 
class ApplicationServerMapStore {
	private $objDBConnection;
	public function __construct($objDBConnection) {
		$this->objDBConnection = new $objDBConnection;
	}
	public function addApplicationServerMap($objApplicationServerMap) {
		$serverIds = $objApplicationServerMap->getServerId();
		foreach($serverIds as $serverId) {	
			$sql = "insert ignore into applicationServerMap(applicationId,serverId) values(:applicationId,:serverId)";	
			$val = array('serverId'=>$serverId,'applicationId'=>$objApplicationServerMap->getApplicationId());
			$this->objDBConnection->query($sql,$val);
		}
	}
	public function editApplicationServerMap($objApplicationServerMap) {
		$sql = "delete from applicationServerMap where applicationId=:applicationId";	
		$val = array('applicationId'=>$objApplicationServerMap->getApplicationId());
		$this->objDBConnection->query($sql,$val);
		$this->addApplicationServerMap($objApplicationServerMap);
	}
	public function getApplicationServerMap($applicationId) {
		$sql = "select * from applicationServerMap where applicationId = :applicationId";
		$val = array('applicationId'=>$applicationId);
		return $this->objDBConnection->query($sql,$val);
	}
	public function getServerApplicationMap($serverId) {
		$sql = "select * from applicationServerMap where serverId = :serverId";
		$val = array('serverId'=>$serverId);
		return $this->objDBConnection->query($sql,$val);
	}
	public function deleteApplicationServerMap($applicationId) {
		$sql = "delete from applicationServerMap where applicationId = :applicationId";
		$val = array('applicationId'=>$applicationId);
		return $this->objDBConnection->query($sql,$val);
	}
	public function listApplicationServerMap() {
		$sql = "select * from applicationServerMap";
		return $this->objDBConnection->query($sql);
	}
}
