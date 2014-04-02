<?php 
class ServiceStore {
	private $objDBConnection;
	public function __construct($objDBConnection) {
		$this->objDBConnection = new $objDBConnection;
	}
	public function addService($objService) {
		$sql = "insert into service(serverId,serviceTypeId,servicePort,serviceDNS,serviceDNS2,serviceDNS3,serviceRefName) values(:serverId,:serviceTypeId,:servicePort,:serviceDNS,:serviceDNS2,:serviceDNS3,:serviceRefName)";
		$val = array('serverId'=>$objService->getServerId(),'serviceTypeId'=>$objService->getServiceTypeId(),'servicePort'=>$objService->getServicePort(),'serviceDNS'=>$objService->getServiceDNS(),'serviceDNS2'=>$objService->getServiceDNS2(),'serviceDNS3'=>$objService->getServiceDNS3(),'serviceRefName'=>$objService->getServiceRefName());
		return $this->objDBConnection->query($sql,$val);
	}
	public function editService($objService) {
		$sql = "update service set serviceTypeId=:serviceTypeId,servicePort=:servicePort,serviceDNS=:serviceDNS,serviceDNS2=:serviceDNS2,serviceDNS3=:serviceDNS3,serviceRefName=:serviceRefName where serviceId=:serviceId";
		$val = array('serviceId'=>$objService->getServiceId(),'serviceTypeId'=>$objService->getServiceTypeId(),'servicePort'=>$objService->getServicePort(),'serviceDNS'=>$objService->getServiceDNS(),'serviceDNS2'=>$objService->getServiceDNS2(),'serviceDNS3'=>$objService->getServiceDNS3(),'serviceRefName'=>$objService->getServiceRefName());
		return $this->objDBConnection->query($sql,$val);
	}
	public function getService($serviceId) {
		$sql = "select * from service where serviceId = :serviceId";
		$val = array('serviceId'=>$serviceId);
		return $this->objDBConnection->row($sql,$val);
	}
    public function deleteService($serviceId) {
        $sql = "delete from service where serviceId = :serviceId";
        $val = array('serviceId'=>$serviceId);
        return $this->objDBConnection->query($sql,$val);
    }
	public function listService($serverId=0) {
		$sql = "select * from service";
		if($serverId) {
			$sql .= " where serverId=:serverId";
			$val = array('serverId'=>$serverId);
			return $this->objDBConnection->query($sql,$val);
		}
		return $this->objDBConnection->query($sql);	
	}
	public function validateService($serverId,$serviceTypeId,$servicePort) {
		$sql = "select * from service where serverId=:serverId and serviceTypeId=:serviceTypeId and servicePort=:servicePort limit 1";
		$val = array('serverId'=>$serverId,'serviceTypeId'=>$serviceTypeId,'servicePort'=>$servicePort);
		return $this->objDBConnection->row($sql,$val);
	}
	public function validateServiceRefName($serviceRefName) {
		$sql = "select * from service where serviceRefName=:serviceRefName limit 1";
		$val = array('serviceRefName'=>$serviceRefName);
		return $this->objDBConnection->row($sql,$val);
	}
}
