<?php
class ServiceTypeManager {
	private $objServiceTypeStore;
	public function __construct($objServiceTypeStore) {
		$this->objServiceTypeStore = $objServiceTypeStore;
	}
	public function listServiceType() {
		return $this->objServiceTypeStore->listServiceType();		
	}
}
?>
