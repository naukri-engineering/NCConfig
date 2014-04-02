<?php 
class ConfigurationFileStore {
	private $objDBConnection;
	public function __construct($objDBConnection) {
		$this->objDBConnection = new $objDBConnection;
	}
	public function addConfigurationFile($objConfigurationFile) {
		$sql = "insert ignore into configurationFile(applicationId,configFile) values(:applicationId,:configFile)";	
		$val = array('configFile'=>$objConfigurationFile->getConfigurationFile(),'applicationId'=>$objConfigurationFile->getApplicationId());
		return $this->objDBConnection->query($sql,$val);
	}
	public function editConfigurationFile($objConfigurationFile) {
		$sql = "update configurationFile set configFile=:configFile where applicationId=:applicationId";	
		$val = array('applicationId'=>$objConfigurationFile->getApplicationId(),'configFile'=>$objConfigurationFile->getConfigurationFile());
		return $this->objDBConnection->query($sql,$val);
	}
	public function getConfigurationFile($applicationId) {
		$sql = "select * from configurationFile where applicationId = :applicationId";
		$val = array('applicationId'=>$applicationId);
		return $this->objDBConnection->query($sql,$val);
	}
	public function deleteConfigurationFile($applicationId,$configFile) {
		if($configFile) {
			$sql = "delete from configurationFile where applicationId = :applicationId and configFile=:configFile";
			$val = array('applicationId'=>$applicationId,'configFile'=>$configFile);
		}
		else {
			$sql = "delete from configurationFile where applicationId = :applicationId";
			$val = array('applicationId'=>$applicationId);
		}
		return $this->objDBConnection->query($sql,$val);
	}
	public function listConfigurationFile() {
		$sql = "select * from configurationFile";
		return $this->objDBConnection->query($sql);
	}
}
