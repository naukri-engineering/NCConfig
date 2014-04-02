<?php 
class ReportStore {
	private $objDBConnection;
	public function __construct($objDBConnection) {
		$this->objDBConnection = new $objDBConnection;
	}
	public function connectionGraph($applicationId,$serviceTypeId) {
		$sql = "select c.applicationId,server.serverId,server.serverName,serviceType.serviceTypeName,service.servicePort from configuration c,server,service,serviceType where server.serverId=service.serverId and c.serviceId=service.serviceId and service.serviceTypeId=serviceType.serviceTypeId";
		if($applicationId) {
			$sql .= " and c.applicationId=:applicationId";
			$val['applicationId'] = $applicationId;
		}
		if($serviceTypeId) {
			$sql .= " and serviceType.serviceTypeId=:serviceTypeId";
			$val['serviceTypeId'] = $serviceTypeId;
		}
		return $this->objDBConnection->query($sql,$val);
	}
	public function reportData($type,$id) {
		$sql = "select application.applicationId,serviceType.serviceTypeId,serviceType.serviceTypeName,application.applicationName,server.serverId,server.serverName,server.serverIP,configuration.serviceId,service.serviceRefName,service.servicePort,configuration.systemUserId,systemUser.systemUserRefName,systemUser.username from application,serviceType,configuration,service,systemUser,server where application.applicationId=configuration.applicationId and configuration.serviceId=service.serviceId and configuration.systemUserId=systemUser.systemUserId and service.serviceTypeId=serviceType.serviceTypeId and server.serverId=service.serverId";
		if($type=='application') {
			$sql .= " and application.applicationId=:applicationId";
			$val['applicationId'] = $id;
		}
		elseif($type=='service') {
			$sql .= " and service.serviceId=:serviceId";
			$val['serviceId'] = $id;
		}
		elseif($type=='server') {
			$sql .= " and server.serverId=:serverId";
			$val['serverId'] = $id;
		}
		elseif($type=='serviceType') {
			$sql .= " and serviceType.serviceTypeId=:serviceTypeId";
			$val['serviceTypeId'] = $id;
		}
		elseif($type=='systemUser') {
			$sql .= " and systemUser.systemUserId=:systemUserId";
			$val['systemUserId'] = $id;
		}
		return $this->objDBConnection->query($sql,$val);
	}
}
