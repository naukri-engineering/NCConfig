<?php 
class ConfigurationPathStore {
	private $objDBConnection;
	public function __construct($objDBConnection) {
		$this->objDBConnection = new $objDBConnection;
	}
	public function addConfigurationPath($objConfigurationPath) {
		$sql = "insert ignore into configurationPath(applicationId,configPath) values(:applicationId,:configPath)";	
		$val = array('configPath'=>$objConfigurationPath->getConfigurationPath(),'applicationId'=>$objConfigurationPath->getApplicationId());
		return $this->objDBConnection->query($sql,$val);
	}
	public function editConfigurationPath($objConfigurationPath) {
		$sql = "update configurationPath set configPath=:configPath where applicationId=:applicationId";	
		$val = array('applicationId'=>$objConfigurationPath->getApplicationId(),'configPath'=>$objConfigurationPath->getConfigurationPath());
		return $this->objDBConnection->query($sql,$val);
	}
	public function getConfigurationPath($applicationId) {
		$sql = "select * from configurationPath where applicationId = :applicationId";
		$val = array('applicationId'=>$applicationId);
		return $this->objDBConnection->row($sql,$val);
	}
	public function deleteConfigurationPath($applicationId) {
		$sql = "delete from configurationPath where applicationId = :applicationId";
		$val = array('applicationId'=>$applicationId);
		return $this->objDBConnection->query($sql,$val);
	}
	public function listConfigurationPath() {
		$sql = "select * from configurationPath";
		return $this->objDBConnection->query($sql);
	}
}
