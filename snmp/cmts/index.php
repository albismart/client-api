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

	/***********************************************
	*	linuxIP/snmp/cmts/?hostname={hostname}&action=cablemodems
	*************************************************/
	public function cablemodems() {
		$cableModemList = $this->read(array(
			'cablemodem.mac[]', 
			'cablemodem.ip[]', 
			'cablemodem.status[]', 
			'cablemodem.uptime[]', 
		), SNMP_VALUE_LIBRARY);
		if($cableModemList) {
			foreach($cableModemList["cablemodem.mac[]"] as $cmKey => $cmMac) {
				$cableModemList["cablemodem.mac[]"][$cmKey] = str_replace(" ",":", trim(str_replace("Hex-STRING:", "", $cmMac)));
			}
			foreach($cableModemList["cablemodem.ip[]"] as $cmKey => $cmIP) {
				$cableModemList["cablemodem.ip[]"][$cmKey] = trim(str_replace("IpAddress:", "", $cmIP));
			}
			foreach($cableModemList["cablemodem.status[]"] as $cmKey => $cmStatus) {
				$cableModemList["cablemodem.status[]"][$cmKey] = trim(str_replace("INTEGER:","", $cmStatus));
			}
			foreach($cableModemList["cablemodem.uptime[]"] as $cmKey => $cmUptime) {
				$cableModemList["cablemodem.uptime[]"][$cmKey] = readableTimeticks($cmUptime/100);
			}
			returnJson($cableModemList);
		} else {
			echo "Operation failed";
		}
	}
}

$action = (isset($_GET["action"])) ? $_GET["action"] : "info";
$cmtsSNMPDriver = new Cmts();
$cmtsSNMPDriver->$action();

?>