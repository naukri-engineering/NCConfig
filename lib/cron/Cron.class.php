<?php
class Cron {
	private $cronId;
	private $applicationId;
	private $minute;
	private $hour;
	private $day;
	private $month;
	private $weekday;
	private $command;
	private $user;
	private $completionTime;
	private $comment;
	private $serverId;
	private $maxConcurrency;
	private $timeAlert;
	private $fromEmail;
	private $toEmail;
	private $cronAlias;
	private $cronOutput;
	private $updatedBy;
	public function getCronId() {
		return $this->cronId;
	}
	public function setCronId($cronId) {
		$this->cronId = $cronId;
	}
	public function getApplicationId() {
		return $this->applicationId;
	}
	public function setApplicationId($applicationId) {
		$this->applicationId = $applicationId;
	}
	public function getMinute() {
		return $this->minute;
	}
	public function setMinute($minute) {
		$this->minute = $minute;
	}
	public function getHour() {
		return $this->hour;
	}
	public function setHour($hour) {
		$this->hour = $hour;
	}
	public function getDay() {
		return $this->day;
	}
	public function setDay($day) {
		$this->day = $day;
	}
	public function getMonth() {
		return $this->month;
	}
	public function setMonth($month) {
		$this->month = $month;
	}
	public function getWeekday() {
		return $this->weekday;
	}
	public function setWeekday($weekday) {
		$this->weekday = $weekday;
	}
	public function getCommand() {
		return $this->command;
	}
	public function setCommand($command) {
		$this->command = $command;
	}
	public function getUser() {
		return $this->user;
	}
	public function setUser($user) {
		$this->user = $user;
	}
	public function getCompletionTime() {
		return 0;
		return $this->completionTime;
	}
	public function setCompletionTime($completionTime) {
		$this->completionTime = $completionTime;
	}
	public function getComment() {
		return $this->comment;
	}
	public function setComment($comment) {
		$this->comment = $comment;
	}
	public function getServerId() {
		return $this->serverId;
	}
	public function setServerId($serverId) {
		$this->serverId = $serverId;
	}
	public function getMaxConcurrency() {
		if(!$this->maxConcurrency)
			$this->maxConcurrency = 1;
		return $this->maxConcurrency;
	}
	public function setMaxConcurrency($maxConcurrency) {
		$this->maxConcurrency = $maxConcurrency;
	}
	public function getTimeAlert() {
		if(!$this->timeAlert)
			$this->timeAlert = 1;
		return $this->timeAlert;
	}
	public function setTimeAlert($timeAlert) {
		$this->timeAlert = $timeAlert;
	}
	public function getFromEmail() {
		return $this->fromEmail;
	}
	public function setFromEmail($fromEmail) {
		$this->fromEmail = $fromEmail;
	}
    public function getToEmail() {
        return $this->toEmail;
    }
    public function setToEmail($toEmail) {
        $this->toEmail = $toEmail;
    }
	public function getCronAlias() {
		return $this->cronAlias;
	}
	public function setCronAlias($cronAlias) {
		$this->cronAlias = $cronAlias;
	}
    public function getCronOutput() {
		if($this->cronOutput!='Y')
			return 'N';
        return $this->cronOutput;
    }
    public function setCronOutput($cronOutput) {
        $this->cronOutput = $cronOutput;
    }
	public function getUpdatedBy() {
		return $this->updatedBy;
	}
	public function setUpdatedBy($updatedBy) {
		$this->updatedBy = $updatedBy;
	}
}
?>
