<?php

Class SNMP_Driver {
	protected $vendor, $hostname, $community, $writeCommunity, $timeout, $retries, $mibs;
	public function __construct($hostname, $vendor) {
		$this->hostname = $hostname;
		$this->vendor = $vendor;
		$this->community = config("snmp.community");
		$this->writeCommunity = config("write.community") ? config("write.community") : $this->community;
		$this->timeout = config("snmp.timeout", 10000);
		$this->retries = config("snmp.retries", 2);
		$this->initializeMibs();
	}
	private function initializeMibs() {
		$this->mibs = $this->generalMibs();
		$vendorMibsFilePath = snmp_path("/devices/vendors/". trim(strtolower($this->vendor)) . ".php");
		$vendorMibs = (file_exists($vendorMibsFilePath)) ? include_once $vendorMibsFilePath : null;
		foreach ($this->mibs as $mibKey => $mibValue) {
			if($mibValue!="vendorSpecified") continue;
			$this->mibs[$mibKey] = ($mibValue=="vendorSpecified" && $vendorMibs) ? $vendorMibs[$mibKey] : "Unsupported vendor.";
		}
	}
	public function read($data) {
		snmp_set_valueretrieval(SNMP_VALUE_PLAIN);
		if(is_string($data)) {
			return snmpget($this->hostname, $this->community, $data, $this->timeout, $this->retries);
		}
		if(is_array($data)) {
			$results = array();
			foreach($data as $key => $objectID) {
				$resultKey = (is_numeric($key)) ? $objectID : $key;
				if(strpos($objectID, '[]') === false) {
					$results[$resultKey] = snmpwalk($this->hostname, $this->community, $objectID, $this->timeout, $this->retries);
				} else {
					$plainObjectID = str_replace("[]","", $objectID);
					$result = snmpwalk($this->hostname, $this->community, $plainObjectID, $this->timeout, $this->retries);
					$result = explode("\n", $result);
					$values = array();
					foreach($result as $r) {
						$index = strstr($r, " = ", true);
						$index = explode(".", $index);
						$index = array_pop($index);
						$value = explode(": ", strstr($r, " = "));
						$value = array_pop($value);
						$values[$index] = $value;
					}
					$results[$resultKey] = json_encode($values);
				}
			}
			return $results;
		}
	}
	public function write($data) {
		if(is_string($data)) {
			// String Data pattern: {ObjectID}:{DataType}={UpdateValue}
			$objectID = str_replace("_",".", strstr($data, ":", true));
			$dataType = strstr($data, ":"); $dataType = strstr($dataType, "=", true);
			$updateValue = strstr($data, "=");
			snmpset($this->hostname, $this->writeCommunity, $objectID, $dataType, $updateValue);
		}
		if(is_array($data)) {
			// Array Data pattern: [{ObjectID}:{DataType}] => {UpdateValue}
			foreach ($data as $objectID => $updateValue) {
				if(empty($updateValue)) continue;
				$dataType = strstr($objectID, ":");
				$objectID = str_replace("_",".", strstr($objectID, ":", true));
				snmpset($this->hostname, $this->writeCommunity, $objectID, $dataType, $updateValue);
			}
		}
	}
	protected function generalMibs() {
		return array(
			"cmtsName" => "1.3.6.1.2.1.1.5.0", 
			"cmtsDescription" => "1.3.6.1.2.1.1.1.0",
			"cmtsUptime" => "1.3.6.1.2.1.1.3.0",
			"cmtsCpuUsage" => "vendorSpecified",
			"cmtsTemperatureIn" => "vendorSpecified",
			"cmtsTemperatureOut" => "vendorSpecified",
		);
	}
}