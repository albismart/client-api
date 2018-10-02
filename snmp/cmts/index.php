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
	protected $mibs;

	public function __construct($hostname, $vendor) {
		parent::__construct();
		$this->hostname = $hostname;
		$this->vendor = $vendor;
		$this->mibs = include_once snmp_path("cmts/vendors/{$vendor}.php");
	}

	/***********************************************
	*	linuxIP/snmp/?device=cmts&action=info&hostname={hostname}
	*************************************************/
	public function info() {
		$oidStatus = $this->read(array(
			'name' => $this->mibs['cmtsName'], 
			'description' => $this->mibs['cmtsDescription'], 
			'uptime' => $this->mibs['cmtsUptime'], 
			'cpuUsage' => $this->mibs['cmtsCpuUsage'], 
			'temperatureIn' => $this->mibs['cmtsTemperatureIn'], 
			'temperatureOut' => $this->mibs['cmtsTemperatureOut']
		));
		if($oidStatus) {
			return json_encode($oidStatus);
		} else {
			return "Operation failed";
		}
	}

}

$cmtsSNMPDriver = new Cmts($hostname, $vendor);
$cmtsSNMPDriver->$action();

?>