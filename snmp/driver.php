<?php

Class SNMP_Driver {
	protected $vendor, $hostname, $community, $writeCommunity, $timeout, $retries, $mibs;
	public function __construct() {
		$this->community = config("snmp.community");
		$this->writeCommunity = config("write.community") ? config("write.community") : $this->community;
		$this->timeout = config("snmp.timeout", 10000);
		$this->retries = config("snmp.retries", 2);
		$this->initializeMibs();
	}
	private function initializeMibs() {
		$this->mibs = $this->generalMibs();
		$vendorMibsFilePath = snmp_path("/vendors/". trim(strtolower($this->vendor)) . ".php");
		$vendorMibs = (file_exists($vendorMibsFilePath)) ? include_once $vendorMibsFilePath : null;
		foreach ($this->mibs as $mibKey => $mibValue) {
			if($mibValue!="vendorSpecified") continue;
			$this->mibs[$mibKey] = ($mibValue=="vendorSpecified" && $vendorMibs) ? $vendorMibs[$mibKey] : "Unsupported vendor.";
		}
	}
	public function read($data) {
		$results = array();
		if(is_string($data)) {
			$results[$objectID] = trim(shell_exec("snmpwalk -v 2c -Oqv -c {$this->community} {$this->hostname} {$objectID}"));
			return $results;
		}
		if(is_array($data)) {
			foreach($data as $objectID) {
				if(strpos($objectID, '[]') === false) {
					$results[$objectID] = trim(shell_exec("snmpwalk -v 2c -Oqv -c {$this->community} {$this->hostname} {$objectID}"));
				} else {
					$modoid = str_replace("[]","", $objectID);
					$res = shell_exec("snmpwalk -v2c -c {$this->community} {$this->hostname} {$modoid}");
					$res = explode("\n", $res);
					$values = array();
					foreach($res as $r) {
						$index = strstr($r, " = ", true);
						$index = explode(".", $index);
						$index = array_pop($index);
						$value = explode(": ", strstr($r, " = "));
						$value = array_pop($value);
						$values[$index] = $value;
					}
					$results[$objectID] = json_encode($values);
				}
			}
		}
		return $results;
	}
	public function write($data) {
		if(is_array($data)) {
			foreach ($data as $objectID => $updateValue) {
				if(empty($updateValue)) continue;
				$dataType = strstr($objectID, ":");
				$objectID = str_replace("_",".", strstr($objectID, ":", true));
				$r = shell_exec("snmpset -v2c -c {$this->writeCommunity} {$this->hostname} {$objectID} {$dataType} {$updateValue} ");
				return $r;
			}
		}
	}
	protected function generalMibs() {
		return [
			"cmtsName" => "1.3.6.1.2.1.1.5.0", 
			"cmtsDescription" => "1.3.6.1.2.1.1.1.0",
			"cmtsUptime" => "1.3.6.1.2.1.1.3.0",
			"cmtsCpuUsage" => "vendorSpecified",
			"cmtsTemperatureIn" => "vendorSpecified",
			"cmtsTemperatureOut" => "vendorSpecified",
		];
	}
}