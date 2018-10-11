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
	
	public function read($data, $readValueMethod = SNMP_VALUE_PLAIN) {
		snmp_set_valueretrieval($readValueMethod);
		if(is_string($data)) {
			return snmpget($this->hostname, $this->community, $this->oid($data), $this->timeout, $this->retries);
		}
		if(is_array($data)) {
			$results = array();
			foreach($data as $objectID) {
				if(strpos($objectID, '[]') === false) {
					if(is_array($this->oid($objectID))) {
						echo $objectID . "<hr/>";
					}
					$results[$objectID] = snmpget($this->hostname, $this->community, $this->oid($objectID), $this->timeout, $this->retries);
				} else {
					$plainObjectID = str_replace("[]","", $objectID);
					$result = snmpwalk($this->hostname, $this->community, $this->oid($plainObjectID), $this->timeout, $this->retries);
					$values = array();
					foreach($result as $r) {
						$values[] = $r;
					}
					$results[$objectID] = $values;
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
	
	protected function oid($index, $lastIndex = "") {
		$oidArrayWalker = $this->mibs;
		if(is_string($index)) {
			$indexes = strpos($index, '.') !== false ? explode('.', $index) : 
					   (strpos($index, '/') !== false ? explode('/', $index) : null);
			if($indexes) {
				$index = $indexes;
			} else {
				if(isset($oidArrayWalker[$index])) {
					$oidArrayWalker = $oidArrayWalker[$index];
				} else {
					$oidArrayWalker = null;
				}
			}
		}
		if(is_array($index)) {
			foreach ($index as $value) {
				if(isset($oidArrayWalker[$value])) {
					$oidArrayWalker = $oidArrayWalker[$value];
				}
			}
		}
		if(is_string($oidArrayWalker)) {
			$oidArrayWalker = str_replace(".{lastIndex}", $lastIndex, $oidArrayWalker);
		}
		return $oidArrayWalker;
	}

}