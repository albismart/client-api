<?php

$basePath = realpath(dirname(__FILE__));
$basePath = strstr($basePath, "client-api/", true) . "client-api";

include_once $basePath . "/bootstrap.php";
include_once snmp_path("/driver.php");

Class Cmts extends SNMP_Driver {
	public function __construct() {
		parent::__construct();
		$this->hostname = (isset($_GET["hostname"])) ? $_GET["hostname"] : null;
		$this->vendor = (isset($_GET["vendor"])) ? $_GET["vendor"] : "cisco";
		$cmtsMIB = include_once snmp_path("/cmts/vendors/{$this->vendor}.php");
		$this->mibs = array_merge($this->mibs, $cmtsMIB);
	}

	/***********************************************
	*	linuxIP/snmp/cmts/?hostname={hostname}
	*************************************************/
	public function info() {
		$infoStats = $this->read(array(
			'name', 
			'description', 
			'objectID', 
			'contact', 
			'location', 
			'uptime', 
			'cpuUsage',
			'temperatureIn',
			'temperatureOut',
			'countInterfaces'
		));
		if($infoStats) {
			$infoStats['uptime'] = readableTimeticks($infoStats['uptime']/100);
			returnJson($infoStats);
		} else {
			echo "Operation failed";
		}
	}
	
	/***********************************************
	*	linuxIP/snmp/cmts/?hostname={hostname}&action=interfaces
	*************************************************/
	public function interfaces() {
		$interfacesInsight = $this->read(array(
			'interface.index[]', 
			'interface.description[]', 
			'interface.adminStatus[]', 
			'interface.operationStatus[]', 
			'interface.speed[]', 

		));
		if($interfacesInsight) {
			returnJson($interfacesInsight);
		} else {
			echo "Operation failed";
		}
	}

}

$action = (isset($_GET["action"])) ? $_GET["action"] : "info";
$cmtsSNMPDriver = new Cmts();
$cmtsSNMPDriver->$action();

?>