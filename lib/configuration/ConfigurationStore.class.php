<?php 
class ConfigurationStore {
	private $objDBConnection;
	public function __construct($objDBConnection) {
		$this->objDBConnection = new $objDBConnection;
	}
	public function addConfiguration($objConfiguration) {
		$sql = "insert into configuration(applicationId,serviceId,systemUserId,systemUserId2,configurationTag) values(:applicationId,:serviceId,:systemUserId,:systemUserId2,:configurationTag)";	
		$val = array('applicationId'=>$objConfiguration->getApplicationId(),'serviceId'=>$objConfiguration->getServiceId(),'systemUserId'=>$objConfiguration->getSystemUserId(),'systemUserId2'=>$objConfiguration->getSystemUserId2(),'configurationTag'=>$objConfiguration->getConfigurationTag());
		return $this->objDBConnection->query($sql,$val);
	}
	public function editConfiguration($objConfiguration) {
		$sql = "update configuration set applicationId=:applicationId,serviceId=:serviceId,systemUserId=:systemUserId,systemUserId2=:systemUserId2,configurationTag=:configurationTag where configurationId=:configurationId";	
		$val = array('configurationId'=>$objConfiguration->getConfigurationId(),'applicationId'=>$objConfiguration->getApplicationId(),'serviceId'=>$objConfiguration->getServiceId(),'systemUserId'=>$objConfiguration->getSystemUserId(),'systemUserId2'=>$objConfiguration->getSystemUserId2(),'configurationTag'=>$objConfiguration->getConfigurationTag());
		return $this->objDBConnection->query($sql,$val);
	}
	public function getConfiguration($configurationId) {
		$sql = "select * from configuration where configurationId = :configurationId";
		$val = array('configurationId'=>$configurationId);
		return $this->objDBConnection->row($sql,$val);
	}
	public function getApplicationConfiguration($applicationId) {
		$sql = "select * from configuration where applicationId = :applicationId";
		$val = array('applicationId'=>$applicationId);
		return $this->objDBConnection->query($sql,$val);
	}
	public function getSystemUserConfiguration($systemUserId) {
		$sql = "select * from configuration where systemUserId = :systemUserId";
		$val = array('systemUserId'=>$systemUserId);
		return $this->objDBConnection->query($sql,$val);
	}
	public function getServiceConfiguration($serviceId) {
		$sql = "select * from configuration where serviceId = :serviceId";
		$val = array('serviceId'=>$serviceId);
		return $this->objDBConnection->query($sql,$val);
	}
	public function deleteConfiguration($configurationId) {
		$sql = "delete from configuration where configurationId = :configurationId";
		$val = array('configurationId'=>$configurationId);
		return $this->objDBConnection->query($sql,$val);
	}
	public function listConfiguration() {
		$sql = "select * from configuration";
		return $this->objDBConnection->query($sql);
	}
	public function validateConfiguration($applicationId,$serviceId,$systemUserId,$systemUserId2) {
		$sql = "select * from configuration where applicationId=:applicationId and serviceId=:serviceId and systemUserId=:systemUserId and systemUserId2=:systemUserId2 limit 1";
		$val = array('applicationId'=>$applicationId,'serviceId'=>$serviceId,'systemUserId'=>$systemUserId,'systemUserId2'=>$systemUserId2);
		return $this->objDBConnection->row($sql,$val);
	}
	public function validateConfigurationTag($configurationTag) {
		$sql = "select * from configuration where configurationTag=:configurationTag limit 1";
		$val = array('configurationTag'=>$configurationTag);
		return $this->objDBConnection->row($sql,$val);
	}
}
