<?php
class ReleaseLogManager {
	private $objReleaseLogStore;
	public function __construct($objReleaseLogStore) {
		$this->objReleaseLogStore = $objReleaseLogStore;
	}
	public function addReleaseLog($date,$release,$applicationId,$configFile,$email) {
		return $this->objReleaseLogStore->addReleaseLog($date,$release,$applicationId,$configFile,$email);		
	}
	public function addReleaseComment($date,$release,$time_stamp,$releaseComment,$email) {
		return $this->objReleaseLogStore->addReleaseComment($date,$release,$time_stamp,$releaseComment,$email);
	}
	public function addReleaseDeployment($date,$release,$applicationId,$time_stamp,$status,$email) {
		return $this->objReleaseLogStore->addReleaseDeployment($date,$release,$applicationId,$time_stamp,$status,$email);
	}
	public function updateReleaseDeploymentStatus($releaseId,$status) {
		return $this->objReleaseLogStore->updateReleaseDeploymentStatus($releaseId,$status);
	}
	public function listReleaseLog($release='',$applicationId='') {
		$releases = $this->objReleaseLogStore->listReleaseLog($release,$applicationId);
		if($release) {
			//Application...
			$objApplicationManager = NCConfigFactory::getInstance()->getApplicationManager();
			$applications = $objApplicationManager->listApplication();
			$applicationsArr = array();
			foreach($applications as $application) {
				$applicationsArr[$application['applicationId']] = array('applicationName'=>$application['applicationName'],'applicationGroupName'=>$application['applicationGroupName']);
			}
			foreach($releases as $key=>$release) {
				$deployStatus = $this->objReleaseLogStore->getReleaseDeployment($release['release'],$release['applicationId']);				
				$releases[$key]['applicationName'] = $applicationsArr[$release['applicationId']]['applicationName'];
				$releases[$key]['applicationGroupName'] = $applicationsArr[$release['applicationId']]['applicationGroupName'];
				$releases[$key]['time_stamp'] = $deployStatus[0]['time_stamp'];
				$releases[$key]['status'] = $deployStatus[0]['status'];
			}
		}
		if(!$release && !$applicationId) {
			foreach($releases as $key=>$release) {
				$comment = $this->objReleaseLogStore->getReleaseComment($release['date'],$release['release']);
				if($comment) {
					$comment = $comment['comment'];
				}
				else {
					$comment = '';
				}
				$releases[$key]['comment'] = $comment;
			}
		}
		return $releases;
	}
}
?>
