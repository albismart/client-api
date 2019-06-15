<?php

if(!function_exists('base_path')) { header("HTTP/1.1 404 Not found"); exit(); }

Class Cmts_SNMP_Driver extends SNMP_Driver {
	public function __construct($hostname = null, $community = null, $writeCommunity = null) {
		parent::__construct($hostname, $community, $writeCommunity);
		if($this->vendor) {
			$cmtsVendorMIB = include snmp_path("/cmts/vendors/{$this->vendor}.php");
			if(is_array($this->mibs)) {
				$this->mibs = array_merge_recursive_ex($this->mibs, $cmtsVendorMIB);
			}
		}
	}

	/***********************************************
	*	serverIP/snmp/cmts/?hostname={hostname}
	*************************************************/
	public function info() {
		$cmtsInfo = array(
			"about" => $this->read("about"),
			"stats" => $this->read("stats"),
		);

		returnJson($cmtsInfo);
	}

	/***********************************************
	*	serverIP/snmp/cmts/?hostname={hostname}&action=about
	*************************************************/
	public function about() {
		returnJson($this->read("about"));
	}

	/***********************************************
	*	serverIP/snmp/cmts/?hostname={hostname}&action=stats
	*************************************************/
	public function stats() {
		returnJson($this->read("stats"));
	}

	/***********************************************
	*	serverIP/snmp/cmts/?hostname={hostname}&action=interfaces
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
			if($interface->speed=="4.29 GB") {
				$interface->speed = $this->read("interface.highSpeed", $interfaceIndex);
			}
			$interfaces[] = $interface;
		}

		returnJson($interfaces);
	}

	/***********************************************
	*	serverIP/snmp/cmts/?hostname={hostname}&action=cablemodems
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
			$cableModem->mac = strtolower($cableModemMac);
			$cableModem->dmac = $cmMacDecimal;
			$cableModem->ip = $this->read('cmts.cableModem.ip', $cableModemPtr);
			$cableModem->status = $this->read('cmts.cableModem.status', $cableModemPtr);
			$cableModem->uptime = $this->read('cmts.cableModem.uptime', $cableModemPtr);

			$cableModems[] = $cableModem;
		}

		returnJson($cableModems);
	}

	/***********************************************
	*	serverIP/snmp/cmts/?hostname={hostname}&action=cablemodem&mac={mac}
	*************************************************/
	public function cablemodem() {
		if(!isset($_GET['mac'])) return;

		$cableModem = new stdClass;
		$cableModemMac = strtolower($_GET['mac']);
		$cmMacHexToDec = explode(":", $cableModemMac);
		foreach($cmMacHexToDec as $key => $value) {
			$cmMacHexToDec[$key] = hexdec($value);
		}
		$cmMacDecimal = implode(".", $cmMacHexToDec);

		$cableModemPtr = $this->read('cmts.cableModem.index', $cmMacDecimal);

		$cableModem->ptr = $cableModemPtr;
		$cableModem->mac = $cableModemMac;
		$cableModem->dmac = $cmMacDecimal;
		$cableModem->ip = $this->read('cmts.cableModem.ip', $cableModem->ptr);
		$cableModem->status = $this->read('cmts.cableModem.status', $cableModem->ptr);
		$cableModem->uptime = $this->read('cmts.cableModem.uptime', $cableModem->ptr);

		returnJson($cableModem);
	}
}

?>