<?php 
class CronStore {
	private $objDBConnection;
	public function __construct($objDBConnection) {
		$this->objDBConnection = new $objDBConnection;
	}
	/*
	Adds cron detail to the Database.
	Input : Cron Object
	Output: Status
	*/
	public function addCron($objCron) {
		$serverIds = $objCron->getServerId();
		foreach($serverIds as $serverId) {
			$sql = "insert ignore into cron(`applicationId`,`minute`,`hour`,`day`,`month`,`weekday`,`command`,`user`,`completionTime`,`comment`,`serverId`,`maxConcurrency`,`timeAlert`,`fromEmail`,`toEmail`,`cronAlias`,`cronOutput`,`updatedBy`) values(:applicationId,:minute,:hour,:day,:month,:weekday,:command,:user,:completionTime,:comment,:serverId,:maxConcurrency,:timeAlert,:fromEmail,:toEmail,:cronAlias,:cronOutput,:updatedBy)";	
			$val = array('applicationId'=>$objCron->getApplicationId(),'minute'=>$objCron->getMinute(),'hour'=>$objCron->getHour(),'day'=>$objCron->getDay(),'month'=>$objCron->getMonth(),'weekday'=>$objCron->getWeekday(),'command'=>$objCron->getCommand(),'user'=>$objCron->getUser(),'completionTime'=>$objCron->getCompletionTime(),'comment'=>$objCron->getComment(),'serverId'=>$serverId,'maxConcurrency'=>$objCron->getMaxConcurrency(),'timeAlert'=>$objCron->getTimeAlert(),'fromEmail'=>$objCron->getFromEmail(),'toEmail'=>$objCron->getToEmail(),'cronAlias'=>$objCron->getCronAlias(),'cronOutput'=>$objCron->getCronOutput(),'updatedBy'=>$objCron->getUpdatedBy());
			$this->objDBConnection->query($sql,$val);
		}
	}
	public function editCron($objCron) {
		$sql = "update cron set `applicationId`=:applicationId,`minute`=:minute,`hour`=:hour,`day`=:day,`month`=:month,`weekday`=:weekday,`command`=:command,`user`=:user,`completionTime`=:completionTime,`comment`=:comment,`serverId`=:serverId,`maxConcurrency`=:maxConcurrency,`timeAlert`=:timeAlert,`fromEmail`=:fromEmail,`toEmail`=:toEmail,`cronAlias`=:cronAlias,`cronOutput`=:cronOutput,updatedBy=:updatedBy where cronId=:cronId";	
		$val = array('cronId'=>$objCron->getCronId(),'applicationId'=>$objCron->getApplicationId(),'minute'=>$objCron->getMinute(),'hour'=>$objCron->getHour(),'day'=>$objCron->getDay(),'month'=>$objCron->getMonth(),'weekday'=>$objCron->getWeekday(),'command'=>$objCron->getCommand(),'user'=>$objCron->getUser(),'completionTime'=>$objCron->getCompletionTime(),'comment'=>$objCron->getComment(),'serverId'=>$objCron->getServerId(),'maxConcurrency'=>$objCron->getMaxConcurrency(),'timeAlert'=>$objCron->getTimeAlert(),'fromEmail'=>$objCron->getFromEmail(),'toEmail'=>$objCron->getToEmail(),'cronAlias'=>$objCron->getCronAlias(),'cronOutput'=>$objCron->getCronOutput(),'updatedBy'=>$objCron->getUpdatedBy());
		return $this->objDBConnection->query($sql,$val);
		
	}
	public function getCron($cronId) {
		$sql = "select * from cron where cronId=:cronId";
		$val = array('cronId'=>$cronId);
		return $this->objDBConnection->row($sql,$val);
	}
	public function deleteCron($cronId) {
		$sql = "delete from cron where cronId=:cronId";
		$val = array('cronId'=>$cronId);
		return $this->objDBConnection->query($sql,$val);
	}
	public function commentCron($cronId,$active) {
		$sql = "update cron set active=:active where cronId=:cronId";
		$val = array('active'=>$active,'cronId'=>$cronId);
		return $this->objDBConnection->query($sql,$val);
	}
	public function listCronUser() {
		$sql = "select * from cronUser";
		return $this->objDBConnection->query($sql);
	}
	public function listCron($objSearch) {
		$serverId = $objSearch->getServerId();
		$applicationId = $objSearch->getApplicationId();
		$user = $objSearch->getUser();
		$fromEmail = $objSearch->getFromEmail();
		$toEmail = $objSearch->getToEmail();
		$keyword = $objSearch->getKeyword();
		$completionTime = $objSearch->getCompletionTime();
		$maxConcurrency = $objSearch->getMaxConcurrency();
		$timeAlert = $objSearch->getTimeAlert();
		$active = $objSearch->getActive();
	
		$val = array();
		$sql = "select * from cron where ";
		if($serverId) {
			if(!is_array($serverId)) {
				$serverId = array($serverId);
			}
			$sql .="(";
			$i = 1;
			foreach($serverId as $sId) {
				$sql .= "serverId=:serverId$i or ";
				$val["serverId$i"] = $sId;
				$i++;
			}
			$sql = rtrim($sql,' or ');
			$sql .=") and ";
		}
        if($applicationId) {
            $sql .="(";
            $i = 1;
            foreach($applicationId as $aId) {
                $sql .= "applicationId=:applicationId$i or ";
                $val["applicationId$i"] = $aId;
                $i++;
            }
            $sql = rtrim($sql,' or ');
            $sql .=") and ";
        }
        if($user) {
            $sql .= "user=:user and ";
            $val['user'] = $user;
        }
		if($fromEmail) {
			$sql .= "fromEmail like :fromEmail and ";
			$val['fromEmail'] = "%$fromEmail%";
		}
		if($toEmail) {
			$sql .= "toEmail like :toEmail and ";
			$val['toEmail'] = "%$toEmail%";
		}
		if($keyword) {
			$sql .= "(command like :keyword1 or cronAlias like :keyword2 or comment like :keyword3) and ";
			$val['keyword1'] = "%$keyword%";
			$val['keyword2'] = "%$keyword%";
			$val['keyword3'] = "%$keyword%";
		}
		if($completionTime) {
			$sql .= "completionTime>=:completionTime and ";
			$val['completionTime'] = $completionTime;
		}
		if($maxConcurrency) {
			$sql .= "maxConcurrency>=:maxConcurrency and ";
			$val['maxConcurrency'] = $maxConcurrency;
		}
		if($timeAlert) {
			$sql .= "timeAlert>=:timeAlert and ";
			$val['timeAlert'] = $timeAlert;
		}
		if($active) {
			$sql .= "active=:active and ";
			$val['active'] = $active;
		}
		$sql .= "1";
		return $this->objDBConnection->query($sql,$val);
	}
	public function getCronLog($cronId,$limit,$cronStatus,$startDate,$endDate) {
		$val = array();
		$sql = "select * from cron_status where cronId=:cronId and ";
		if($cronStatus) {
			$sql .= "cronStatus=:cronStatus and ";
			$val['cronStatus'] = $cronStatus;
		}
		if($startDate) {
			$sql .= "startTime>=:startDate and ";
			$val['startDate'] = "$startDate 00:00:00";	
		}
		if($endDate) {
			$sql .= "startTime<=:endDate and ";
			$val['endDate'] = "$endDate 23:59:59";	
		}
		$sql .= "1 order by startTime desc limit :limit";
		$val['cronId'] = $cronId;
		$val['limit'] = $limit;
		return $this->objDBConnection->query($sql,$val);
	}
}
