<?php

if(!function_exists('base_path')) { exit(); }

Class SNMP_Driver {

	public $hostname, $vendor;
	protected $community, $writeCommunity, $timeout, $retries, $mibs;

	public function __construct() {
		// Device details based on request
		$this->hostname = (isset($_GET["hostname"])) ? $_GET["hostname"] : null;
		$this->vendor = (isset($_GET["vendor"])) ? $_GET["vendor"] : "cisco";

		// Set defaults by config
		$this->community = config("snmp.community");
		$this->writeCommunity = config("snmp.wcommunity") ? config("snmp.wcommunity") : $this->community;
		$this->timeout = config("snmp.timeout", 100000);
		$this->retries = config("snmp.retries", 2);
		$this->mibs = include_once snmp_path("/albismart-mib.php");
	}

	public function mibs() {
		returnJson($this->mibs);
	}

	public function customread() {
		if(!isset($_GET['oid'])) { returnJson(); }

		$index 		= (isset($_GET['index']))	? $_GET['index']  : "";
		$readMethod = (isset($_GET['method']))	? $_GET['method'] : SNMP_VALUE_PLAIN;
		$oidsToRead = (isset($_GET['oid']))		? $_GET['oid']	  : null;

		if($oidsToRead) {
			if(is_string($oidsToRead)) {
				$results = $this->read($oidsToRead, $index, $readMethod);
			}
			if(is_array($oidsToRead)) {
				$results = array();
				foreach($oidsToRead as $oidToRead) {
					$results[$oidToRead] = $this->read($oidToRead, $index, $readMethod);
				}
			}

			returnJson($results);
		}
	}

	public function read($oid, $index = "", $readValueMethod = SNMP_VALUE_PLAIN) {
		snmp_set_valueretrieval($readValueMethod);
		$oidsToRead = $this->oidTreeFinder($oid);
		if(is_string($oidsToRead)) {
			$parsed = $this->oidParser($oidsToRead, $index);
			$rawData = call_user_func_array($parsed->snmpMethod, $parsed->snmpParams);
			$result = ($parsed->filter) ? call_user_func($parsed->filter, $rawData) : $rawData;
			return $result;
		}
		if(is_array($oidsToRead)) {
			return $this->oidStackRead($oidsToRead, $index);
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

	protected function oidTreeFinder($oidIndex) {
		$focusedTree = null;

		$suffix = "";
		if(strpos($oidIndex, '[]') !== false) {
			$oidIndex = str_replace("[]", "", $oidIndex);
			$suffix = "[]";
		}
		$indexes = strpos($oidIndex, '.') !== false ? explode('.', $oidIndex) : 
				  (strpos($oidIndex, '/') !== false ? explode('/', $oidIndex) : null);
		if($indexes) {
			$searchMibs = $this->mibs;
			while(count($indexes)!=0) {
				$shiftedIndex = array_shift($indexes);
				if(isset($searchMibs[$shiftedIndex])) {
					$searchMibs = $searchMibs[$shiftedIndex];
				} else {
					$indexes = array();
				}
			}
			$focusedTree = $searchMibs;
			if (is_string($focusedTree)) {
				$focusedTree = $focusedTree . $suffix;
			}
		} else {
			if(isset($this->mibs[$oidIndex])) {
				$focusedTree = $this->mibs[$oidIndex];
			}
		}

		return $focusedTree;
	}

	protected function oidStackRead($oids, $index = "") {
		$oidsRead = array();
		if (!is_array($oids)) { return FALSE;}

		foreach ($oids as $oidKey => $oidsToRead) { 
			if (is_array($oidsToRead)) { $oidsRead[$oidKey] = $this->oidStackRead($oidsToRead); }
			if (is_string($oidsToRead)) {
				$parsed = $this->oidParser($oidsToRead, $index);
				$rawData = call_user_func_array($parsed->snmpMethod, $parsed->snmpParams);
				$oidsRead[$oidKey] = ($parsed->filter) ? call_user_func($parsed->filter, $rawData) : $rawData;
			}
		}
		return $oidsRead;
	}

	protected function oidParser($oid, $index = "") {
		$filter = (strpos($oid, ":")!==false) ? ltrim(substr($oid, strpos($oid, ":"), strlen($oid)-1),":") : null;

		$oid = (strpos($oid, $filter)!==false) ? rtrim(str_replace($filter,"", $oid),":") : $oid;
		$oid = (strpos($oid, ".{index}")!==false) ? str_replace(".{index}","", $oid) : $oid;
		$oid = ($index!="") ? $oid . "." . $index : $oid;

		$snmpMethod = "snmpget";
		if(strpos($oid, '[]') !== false) { 
			$snmpMethod = "snmpwalk";
			$oid = str_replace("[]","", $oid);
		}

		$parsed = new stdClass;
		$parsed->filter = (function_exists($filter)) ? $filter : null;
		$parsed->snmpMethod = $snmpMethod;
		$parsed->snmpParams = array($this->hostname, $this->community, $oid, $this->timeout, $this->retries);

		return $parsed;
	}

}

?>