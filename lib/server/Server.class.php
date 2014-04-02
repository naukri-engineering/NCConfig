<?php
class Server {
	private $serverId;
	private $serverName;
	private $serverIP;
	private $serverRefName;

	public function getServerId() {
		return $this->serverId;
	}
	public function setServerId($serverId) {
		$this->serverId = $serverId;
	}
	public function getServerName() {
		return $this->serverName;
	}
	public function setServerName($serverName) {
		$this->serverName = $serverName;
	}
	public function getServerIP() {
		return $this->serverIP;
	}
	public function setServerIP($serverIP) {
		$this->serverIP = $serverIP;
	}
	public function getServerRefName() {
		return $this->serverRefName;
	}
	public function setServerRefName($serverRefName) {
		$this->serverRefName = $serverRefName;
	}
}
?>
