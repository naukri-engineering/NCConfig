<?php 
class ServiceTypeStore {
	private $objDBConnection;
	public function __construct($objDBConnection) {
		$this->objDBConnection = new $objDBConnection;
	}
	public function listServiceType() {
		$sql = "select * from serviceType";	
		return $this->objDBConnection->query($sql);
	}
}
