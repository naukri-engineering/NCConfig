<?php 
class ServerStore {
	private $objDBConnection;
	public function __construct($objDBConnection) {
		$this->objDBConnection = new $objDBConnection;
	}
	public function addServer($objServer) {
		$sql = "insert into server(serverName,serverIP,serverRefname) values(:serverName,:serverIP,:serverRefName)";	
		$val = array('serverName'=>$objServer->getServerName(),'serverIP'=>$objServer->getServerIP(),'serverRefName'=>$objServer->getServerRefName());
		return $this->objDBConnection->query($sql,$val);
	}
	public function editServer($objServer) {
		$sql = "update server set serverName=:serverName,serverIP=:serverIP,serverRefname=:serverRefName where serverId=:serverId";	
		$val = array('serverId'=>$objServer->getServerId(),'serverName'=>$objServer->getServerName(),'serverIP'=>$objServer->getServerIP(),'serverRefName'=>$objServer->getServerRefName());
		return $this->objDBConnection->query($sql,$val);
	}
	public function getServer($serverId) {
		$sql = "select * from server where serverId = :serverId";
		$val = array('serverId'=>$serverId);
		return $this->objDBConnection->row($sql,$val);
	}
	public function deleteServer($serverId) {
		$sql = "delete from server where serverId = :serverId";
		$val = array('serverId'=>$serverId);
		return $this->objDBConnection->query($sql,$val);
	}
	public function listServer() {
		$sql = "select * from server";
		return $this->objDBConnection->query($sql);
	}
	public function validateServerName($serverName) {
		$sql = "select * from server where serverName=:serverName limit 1";
		$val = array('serverName'=>$serverName);
		return $this->objDBConnection->row($sql,$val);
	}
	public function validateServerIP($serverIP) {
		$sql = "select * from server where serverIP=:serverIP limit 1";
		$val = array('serverIP'=>$serverIP);
		return $this->objDBConnection->row($sql,$val);
	}
	public function validateServerRefName($serverRefName) {
		$sql = "select * from server where serverRefName=:serverRefName limit 1";
		$val = array('serverRefName'=>$serverRefName);
		return $this->objDBConnection->row($sql,$val);
	}

}
