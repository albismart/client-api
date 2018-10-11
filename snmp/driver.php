<?php

if(!function_exists('base_path')) { exit(); }

Class SNMP_Driver {
	
	public $hostname, $vendor;
	protected $community, $writeCommunity, $timeout, $retries, $mibs;
	
	public function __construct() {
		$this->community = config("snmp.community");
		$this->writeCommunity = config("snmp.wcommunity") ? config("snmp.wcommunity") : $this->community;
		$this->timeout = config("snmp.timeout", 100000);
		$this->retries = config("snmp.retries", 2);
		$this->mibs = include_once snmp_path("/albismart-mib.php");
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
					$results[$resultKey] = snmpget($this->hostname, $this->community, $objectID, $this->timeout, $this->retries);
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
			return snmpset($this->hostname, $this->writeCommunity, $objectID, $dataType, $updateValue);
		}
		if(is_array($data)) {
			// Array Data pattern: [{ObjectID}:{DataType}] => {UpdateValue}
			$bulkWritesResults = array();
			foreach ($data as $objectID => $updateValue) {
				if(empty($updateValue)) continue;
				$dataType = strstr($objectID, ":");
				$objectID = str_replace("_",".", strstr($objectID, ":", true));
				$bulkWritesResults[$objectID] = snmpset($this->hostname, $this->writeCommunity, $objectID, $dataType, $updateValue);
			}
			return $bulkWritesResults;
		}
	}

}