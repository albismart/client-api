<?php

if(!function_exists('base_path')) { header("HTTP/1.1 404 Not found"); exit(); }

Class CableModem_SNMP_Driver extends SNMP_Driver {
	protected $identity;

	public function __construct($hostname = null, $community = "public", $writeCommunity = "private") {
		$modemAddress = (isset($_GET['mac'])) ? strtolower($_GET['mac']) : null;
		if(!$modemAddress) returnJson(null);

		$sanitizedModemAddress = strtolower($modemAddress);
		$sanitizedModemAddress = str_replace(":","", $sanitizedModemAddress);
		
		$modemData = new \stdClass;
		$modemData->mac = $modemAddress;

		$cmMacHexToDec = explode(":", $modemAddress);
		foreach($cmMacHexToDec as $key => $value) {
			$cmMacHexToDec[$key] = hexdec($value);
		}
		$modemData->dmac = implode(".", $cmMacHexToDec);

		if(isset($_GET['cmts'])) {
			$cmtses = explode(",", $_GET['cmts']);
			foreach($cmtses as $cmts_hostname) {
				if(isset($modemData->ptr)) { continue; }
				$cmtsSnmpDriver = new Cmts_SNMP_Driver($cmts_hostname);
				$foundCableModemPtr = $cmtsSnmpDriver->read('cmts.cableModem.index', $modemData->dmac);
				if($foundCableModemPtr) {
					$modemData->ptr = $foundCableModemPtr;
					$modemData->cmts = $cmts_hostname;
					$modemData->ip = $cmtsSnmpDriver->read('cmts.cableModem.ip', $modemData->ptr);
				}
			}
		}

		$this->hostname = $modemData->ip;
		$this->identity = $modemData;

		parent::__construct($this->hostname, $community, $writeCommunity);

		if($this->vendor) {
			$cableModemVendorMIB = include snmp_path("/cablemodem/vendors/{$this->vendor}.php");
			if(is_array($this->mibs)) {
				$this->mibs = array_merge_recursive_ex($this->mibs, $cableModemVendorMIB);
			}
		}
	}


	/***********************************************
	*	linuxIP/snmp/cablemodem/info/{mac}&action=identity
	*************************************************/
	public function identity() {
		returnJson($this->identity);
	}

	/***********************************************
	*	linuxIP/snmp/cablemodem/info/{mac}
	*************************************************/
	public function info() {
		
		$modemInfo = array(
			"identity" => $this->identity,
			"about" => $this->read("about"),
			"stats" => $this->read("stats"),
		);

		returnJson($modemInfo);
	}

	/***********************************************
	*	linuxIP/snmp/cablemodem/about/{mac}
	*************************************************/
	public function about() {
		returnJson($this->read("about"));
	}

	/***********************************************
	*	linuxIP/snmp/cablemodem/stats/{mac}
	*************************************************/
	public function stats() {
		returnJson($this->read("stats"));
	}

	/***********************************************
	*	linuxIP/snmp/cablemodem/insight/{mac}
	*************************************************/
	public function insight() {

		$insightData = new \stdClass;

		if(isset($_GET['cmts'])) {
			$cmtses = explode(",", $_GET['cmts']);
			foreach($cmtses as $cmts_hostname) {
				if(isset($modemData->ptr)) { continue; }
				$cmtsSnmpDriver = new Cmts_SNMP_Driver($cmts_hostname);
				$foundCableModemPtr = $cmtsSnmpDriver->read('cmts.cableModem.index', $modemData->dmac);
				if($foundCableModemPtr) {
					$modemData->ptr = $foundCableModemPtr;
					$modemData->cmts = $cmts_hostname;
					$modemData->ip = $cmtsSnmpDriver->read('cmts.cableModem.ip', $modemData->ptr);
				}
			}
		}
		
		$modemInfo = array(
			"identity" => $this->identity,
			"about" => $this->read("about"),
			"stats" => $this->read("stats"),
		);

		returnJson($modemInfo);
	}

}

?>