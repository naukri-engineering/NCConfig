<?php
class ReportManager {
	private $objReportStore;
	public function __construct($objReportStore) {
		$this->objReportStore = $objReportStore;
	}
	public function connectionGraph($applicationId,$serviceTypeId) {
		return $this->objReportStore->connectionGraph($applicationId,$serviceTypeId);
	}
	public function reportData($type,$id) {
		return $this->objReportStore->reportData($type,$id);
	}
}
?>
