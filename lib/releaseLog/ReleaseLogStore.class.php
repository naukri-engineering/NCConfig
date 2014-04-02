<?php 
class ReleaseLogStore {
	private $objDBConnection;
	public function __construct($objDBConnection) {
		$this->objDBConnection = new $objDBConnection;
	}
	public function addReleaseLog($date,$release,$applicationId,$configFile,$email) {
		$sql = "insert ignore into releaseLog(`date`,`release`,`applicationId`,`configFile`,`email`) values(:date,:release,:applicationId,:configFile,:email)";
		$val = array('date'=>$date,'release'=>$release,'applicationId'=>$applicationId,'configFile'=>$configFile,'email'=>$email);
		return $this->objDBConnection->query($sql,$val);
	}
	public function addReleaseComment($date,$release,$time_stamp,$comment,$email) {
		$sql = "insert ignore into releaseComment(`date`,`release`,`time_stamp`,`comment`,`email`) values(:date,:release,:time_stamp,:comment,:email)";
		$val = array('date'=>$date,'release'=>$release,'time_stamp'=>$time_stamp,'comment'=>$comment,'email'=>$email);
		return $this->objDBConnection->query($sql,$val);
	}
	public function addReleaseDeployment($date,$release,$applicationId,$time_stamp,$status,$email) {
		$sql = "insert ignore into releaseDeployment(`date`,`release`,applicationId,`time_stamp`,`status`,`email`) values(:date,:release,:applicationId,:time_stamp,:status,:email)";
		$val = array('date'=>$date,'release'=>$release,'applicationId'=>$applicationId,'time_stamp'=>$time_stamp,'status'=>$status,'email'=>$email);
		return $this->objDBConnection->query($sql,$val);
	}
	public function updateReleaseDeploymentStatus($releaseId,$status) {
		$sql = "update releaseDeployment set status=:status where releaseId=:releaseId";
		$val = array('status'=>$status,'releaseId'=>$releaseId);
		return $this->objDBConnection->query($sql,$val);
	}
	public function getReleaseComment($date,$release) {
		$sql = "select comment from releaseComment where date=:date and `release`=:release";
		$val = array('date'=>$date,'release'=>$release);
		return $this->objDBConnection->row($sql,$val);	
	}
	public function getReleaseDeployment($release,$applicationId) {
		$sql = "select * from releaseDeployment where applicationId=:applicationId and `release`=:release order by time_stamp desc";
		$val = array('applicationId'=>$applicationId,'release'=>$release);
		return $this->objDBConnection->query($sql,$val);	
	}
	public function listReleaseLog($release='',$applicationId='') {
		$val = array();
		if($applicationId) {
			$sql = "select distinct applicationId,configFile from releaseLog where `applicationId`=:applicationId order by applicationId";
			$val = array('applicationId'=>$applicationId);
		}
		elseif($release) {
			$sql = "select distinct `release`,applicationId from releaseLog where `release`=:release order by `release`";
			$val = array('release'=>$release);
		}
		else {
			$sql = "select distinct date,`release` from releaseLog order by date desc,`release` limit 50";
		}
		return $this->objDBConnection->query($sql,$val);
	}
}
