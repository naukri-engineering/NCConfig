<?php
class ServiceType {
	private $serviceTypeId;
	private $serviceTypeName;

	public function getServiceTypeId() {
		return $this->serviceTypeId;
	}
	public function setServiceTypeId($serviceTypeId) {
		$this->serviceTypeId = $serviceTypeId;
	}
	public function getServiceTypeName() {
		return $this->serviceTypeName;
	}
	public function setServiceTypeName($serviceTypeName) {
		$this->serviceTypeName = $serviceTypeName;
	}
}
?>
