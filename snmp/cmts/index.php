<?php
error_reporting(0);
$basePath = realpath(dirname(__FILE__));
$basePath = strstr($basePath, "client-api/", true) . "client-api";

include_once $basePath . "/bootstrap.php";
include_once snmp_path("/driver.php");
validateApiRequest();

Class Cmts extends SNMP_Driver {
	public function __construct() {
		parent::__construct();
		$cmtsMIB = include_once snmp_path("/cmts/vendors/{$this->vendor}.php");
		$this->mibs = $this->mibs + $cmtsMIB;
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
			$interface->type = $this->read("interface.type", $interfaceIndex);
			$interface->description = $this->read("interface.description", $interfaceIndex);
			$interface->adminStatus = $this->read("interface.adminStatus", $interfaceIndex);
			$interface->operationStatus = $this->read("interface.operationStatus", $interfaceIndex);
			$interface->speed = $this->read("interface.speed", $interfaceIndex);
			if($interface->speed==4294967295) {
				$interface->speed = $this->read("interface.highSpeed", $interfaceIndex) * 1000000;
			}
			$interfaces[] = $interface;
		}
		
		returnJson($interfaces);
	}

	/***********************************************
	*	linuxIP/snmp/cmts/?hostname={hostname}&action=cablemodems
	*************************************************/
	public function cablemodems() {
		$cableModems = array();
		$cableModemList = $this->read('cmts.cableModem.mac[]', "", SNMP_VALUE_LIBRARY);

		foreach($cableModemList as $cableModemMac) {
			$cableModem = new stdClass;
			$cableModemMac = trim(str_replace("STRING:", "", $cableModemMac));
			$cmMacHexToDec = explode(":", $cableModemMac);
			foreach($cmMacHexToDec as $key => $value) {
				$cmMacHexToDec[$key] = hexdec($value);
			}
			$cmMacDecimal = implode(".", $cmMacHexToDec);
	
			$cableModemPtr = $this->read('cmts.cableModem.index', $cmMacDecimal);	

			$cableModem->ptr = $cableModemPtr;
			$cableModem->mac = strtoupper($cableModemMac);
			$cableModem->ip = $this->read('cmts.cableModem.ip', $cableModemPtr);
			$cableModem->status = $this->read('cmts.cableModem.status', $cableModemPtr);
			$cableModem->uptime = readableTimeticks($this->read('cmts.cableModem.uptime', $cableModemPtr)/100);
			
			$cableModems[] = $cableModem;
		}
		
		returnJson($cableModems);
	}
}

$action = (isset($_GET["action"])) ? $_GET["action"] : "info";
$cmtsSNMPDriver = new Cmts();
$cmtsSNMPDriver->$action();

?>