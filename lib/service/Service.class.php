<?php
class Service {
	private $serviceId;
	private $serviceTypeId;
	private $serverId;
	private $servicePort;
	private $serviceDNS;
	private $serviceDNS2;
	private $serviceDNS3;
	private $serviceRefName;

	public function getServiceId() {
		return $this->serviceId;
	}
	public function setServiceId($serviceId) {
		$this->serviceId = $serviceId;
	}
	public function getServiceTypeId() {
		return $this->serviceTypeId;
	}
	public function setServiceTypeId($serviceTypeId) {
		$this->serviceTypeId = $serviceTypeId;
	}
	public function getServerId() {
		return $this->serverId;
	}	
	public function setServerId($serverId) {
		$this->serverId = $serverId;
	}
	public function getServicePort() {
		return $this->servicePort;
	}
	public function setServicePort($servicePort) {
		$this->servicePort = $servicePort;
	}
	public function getServiceDNS() {
		return $this->serviceDNS;
	}
	public function setServiceDNS($serviceDNS) {
		$this->serviceDNS = $serviceDNS;
	}
    public function getServiceDNS2() {
        return $this->serviceDNS2;
    }
    public function setServiceDNS2($serviceDNS2) {
        $this->serviceDNS2 = $serviceDNS2;
    }
    public function getServiceDNS3() {
        return $this->serviceDNS3;
    }
    public function setServiceDNS3($serviceDNS3) {
        $this->serviceDNS3 = $serviceDNS3;
    }
	public function getServiceRefName() {
		return $this->serviceRefName;
	}
	public function setServiceRefName($serviceRefName) {
		$this->serviceRefName = $serviceRefName;
	}
}
?>
