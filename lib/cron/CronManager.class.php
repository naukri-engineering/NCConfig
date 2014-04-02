<?php
class CronManager {
	private $objCronStore;
	public function __construct($objCronStore) {
		$this->objCronStore = $objCronStore;
	}
	public function addCron($objCron) {
		return $this->objCronStore->addCron($objCron);		
	}
	public function editCron($objCron) {
		return $this->objCronStore->editCron($objCron);		
	}
	public function getCron($cronId) {
		return $this->objCronStore->getCron($cronId);
	}
	public function deleteCron($cronId) {
		return $this->objCronStore->deleteCron($cronId);
	}
	public function commentCron($cronId,$active) {
		return $this->objCronStore->commentCron($cronId,$active);
	}
	public function listCronUser() {
		return $this->objCronStore->listCronUser();
	}
	public function listCron($objSearch) {
		$crons = $this->objCronStore->listCron($objSearch);
		foreach($crons as $key=>$cron) {
			$cronId = $cron['cronId'];
			$cronStatus = $this->getCronLog($cronId,1);
			$crons[$key]['cronStatus'] = $cronStatus[0]['cronStatus']; 
			$crons[$key]['startTime']  = $cronStatus[0]['startTime'];
			$crons[$key]['comment'] = json_encode(nl2br($cron['comment']));
		}
		return $crons;
	}
	public function getCronLog($cronId,$limit,$cronStatus='',$startDate='',$endDate='') {
		$logs = $this->objCronStore->getCronLog($cronId,$limit,$cronStatus,$startDate,$endDate);
		foreach($logs as $key=>$log) {
			$seconds = strtotime($log['endTime']) - strtotime($log['startTime']);
			if($seconds<0) {
				$seconds = 0;
			}
			$h = (int)($seconds / 3600);
			$m = (int)(($seconds - $h*3600) / 60);
			$s = (int)($seconds - $h*3600 - $m*60);
			$time = (($h)?(($h<10)?("0".$h):$h):"00").":".(($m)?(($m<10)?("0".$m):$m):"00").":".(($s)?(($s<10)?("0".$s):$s):"00");
			$logs[$key]['timeTaken'] = $time;
			$logs[$key]['timeTakenInSeconds'] = $seconds;
			$logs[$key]['timeTakenInMinutes'] = $seconds/60;
			$logs[$key]['cronStatus'] = trim($log['cronStatus']);
			$logs[$key]['cronOutput'] = json_encode(nl2br($log['cronOutput']));
		}
		return $logs;
	}
}
?>
