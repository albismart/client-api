<?php

$basePath = realpath(dirname(__FILE__));
$basePath = strstr($basePath, "client-api/", true) . "client-api";

include_once $basePath . "/bootstrap.php";
include_once snmp_path("/driver.php");

$hostname = (isset($_GET["hostname"])) ? $_GET["hostname"] : null;
$action = (isset($_GET["action"])) ? $_GET["action"] : "info";
$vendor = (isset($_GET["vendor"])) ? $_GET["vendor"] : "cisco";

Class Cmts extends SNMP_Driver {

	public $hostname, $vendor;

	public function __construct($hostname, $vendor) {
		parent::__construct();
		$this->hostname = $hostname;
		$this->vendor = $vendor;
		$cmtsMIB = include_once snmp_path("/cmts/vendors/{$vendor}.php");
		$this->mibs = array_merge($this->mibs, $cmtsMIB);
	}

	/***********************************************
	*	linuxIP/snmp/cmts/?hostname={hostname}
	*************************************************/
	public function info() {
		$infoStats = $this->read(array(
			'name' => $this->mibs['name'], 
			'description' => $this->mibs['description'], 
			'uptime' => $this->mibs['uptime'], 
			'cpuUsage' => $this->mibs['cpuUsage'], 
			'temperatureIn' => $this->mibs['temperatureIn'], 
			'temperatureOut' => $this->mibs['temperatureOut']
		));
		if($infoStats) {
			returnJson($infoStats);
		} else {
			echo "Operation failed";
		}
	}

}

$cmtsSNMPDriver = new Cmts($hostname, $vendor);
$cmtsSNMPDriver->$action();

?>