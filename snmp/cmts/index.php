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
		$interfaces = array();
		$interfacesIndexes = $this->read('interface.index[]');
		foreach($interfacesIndexes as $interfaceIndex) {
			$interface = new stdClass;
	
			$interface->index = $interfaceIndex;
			$interface->type = $this->read("interface.type",SNMP_VALUE_PLAIN, $interfaceIndex);
			$interface->description = $this->read("interface.description",SNMP_VALUE_PLAIN, $interfaceIndex);
			$interface->adminStatus = $this->read("interface.adminStatus",SNMP_VALUE_PLAIN, $interfaceIndex);
			$interface->operationStatus = $this->read("interface.operationStatus",SNMP_VALUE_PLAIN, $interfaceIndex);
			$interface->speed = $this->read("interface.speed",SNMP_VALUE_PLAIN, $interfaceIndex);
			$interfaces[] = $interface;
		}
		
		returnJson($interfaces);
	}

	/***********************************************
	*	linuxIP/snmp/cmts/?hostname={hostname}&action=cablemodems
	*************************************************/
	public function cablemodems() {
		$cableModems = array();
		$cableModemList = $this->read('cablemodem.mac[]', SNMP_VALUE_LIBRARY);

		foreach($cableModemList as $cableModemMac) {
			$cableModem = new stdClass;
			$cableModemMac = trim(str_replace("STRING:", "", $cableModemMac));
			$cmMacHexToDec = explode(":", $cableModemMac);
			foreach($cmMacHexToDec as $key => $value) {
				$cmMacHexToDec[$key] = hexdec($value);
			}
			$cmMacDecimal = implode(".", $cmMacHexToDec);
	
			$cableModemPtr = $this->read('cablemodem.index', SNMP_VALUE_PLAIN, $cmMacDecimal);	

			$cableModem->ptr = $cableModemPtr;
			$cableModem->mac = strtoupper($cableModemMac);
			$cableModem->ip = $this->read('cablemodem.ip', SNMP_VALUE_PLAIN, $cableModemPtr);
			$cableModem->status = $this->read('cablemodem.status', SNMP_VALUE_PLAIN, $cableModemPtr);
			$cableModem->uptime = readableTimeticks($this->read('cablemodem.uptime', SNMP_VALUE_PLAIN, $cableModemPtr));
			
			$cableModems[] = $cableModem;
		}
		
		returnJson($cableModems);
	}
}

$action = (isset($_GET["action"])) ? $_GET["action"] : "info";
$cmtsSNMPDriver = new Cmts();
$cmtsSNMPDriver->$action();

?>