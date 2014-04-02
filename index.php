<?php
//https://github.com/a1phanumeric/PHP-MySQL-Class/blob/master/class.MySQL.php
//https://github.com/indieteq/PHP-MySQL-PDO-Database-Class
include "config/config.php";
include "config/autoload.php";
try {
	//session_start();
	$module = $_GET['m'];
	$action = $_GET['a'];
	if(!$module) {
		$module = 'login';
	}
	if(!$action) {
		$action = 'index';
	}
	$allowedModules = array(
			'server'=>'ServerController',
			'service'=>'ServiceController',
			'login'=>'LoginController',
			'configuration'=>'ConfigurationController',
			'application'=>'ApplicationController',
			'systemUser'=>'SystemUserController',
			'configureApplication'=>'ConfigureApplicationController',
			'user'=>'UserController',
			'release'=>'ReleaseController',
			'report'=>'ReportController',
			'cron'=>'CronController',
			);
	$ModuleController = isset($allowedModules[$module])?$allowedModules[$module]:'';
	if($ModuleController){
		$selectedModule = $module;
	}
	else {
		$selectedModule = '';
	}
	switch($selectedModule) {
		case $module:
			$objApplicationController = new $ModuleController($module,$action);
			$objApplicationController->execute();
			break;
		default:
			$obj404 = new Page404Controller('404','404');
			$obj404->execute();
			break;
	}
}
catch(Exception $e) {
	echo $e->getMessage();
	die;
}
?>
