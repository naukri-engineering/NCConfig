<?php
class ApplicationServerMap {
	private $applicationId;
	private $serverId;
	public function getApplicationId() {
		return $this->applicationId;
	}
	public function setApplicationId($applicationId) {
		$this->applicationId = $applicationId;
	}
	public function getServerId() {
		return $this->serverId;
	}
	public function setServerId($serverId) {
		$this->serverId = $serverId;
	}
}
?>
