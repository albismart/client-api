<?php

if(!function_exists('base_path')) { header("HTTP/1.1 404 Not found"); exit(); }

Class SNMP_Driver {

	public $hostname, $vendor;
	protected $community, $writeCommunity, $timeout, $retries, $mibs;

	public function __construct($hostname = null, $community = null, $writeCommunity = null) {
		// Device details based on request
		if($hostname!=null) {
			$this->hostname = $hostname;
		} else {
			$this->hostname = (isset($_GET["hostname"])) ? $_GET["hostname"] : null;	
		}

		// Set defaults by config
		$this->community = ($community) ? $community : config("snmp.community");
		$this->writeCommunity = ($writeCommunity) ? $writeCommunity : config("snmp.wcommunity", $this->community);
		$this->timeout = config("snmp.timeout", 100000);
		$this->retries = config("snmp.retries", 2);
		if(!is_array($this->mibs)) { $this->mibs = include snmp_path("/albismart-mib.php"); }

		if($this->hostname) {
			$sysDescription = $this->read("about.description");		
			if(!$sysDescription) {
				//"Host ({$this->hostname}) is offline or SNMP doesn't appear to be running.";
			}

			$this->vendor = 'cisco';
			$lowerCaseSysDescription = strtolower($sysDescription);

			if (strpos($lowerCaseSysDescription, 'arris') !== false) {
				$this->vendor = 'arris';
				if(strpos($lowerCaseSysDescription, 'model: bsr') !== false) {
					$this->vendor = 'motorola';
				}
			}
		}
	}

	protected function setupCommunity($read, $write) {
		$this->community($read);
		$this->writeCommunity($write);
	}

	public function mibs() {
		returnJson($this->mibs);
	}

	public function customread() {
		if(!isset($_GET['oid'])) { returnJson(null); }

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
		if(strpos($oidIndex, '[R]') !== false) {
			$oidIndex = str_replace("[R]", "", $oidIndex);
			$suffix = "[R]";
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
		if(strpos($oid, '[R]') !== false) {
			$snmpMethod = "snmprealwalk";
			$oid = str_replace("[R]","", $oid);
		}

		$parsed = new stdClass;
		$parsed->filter = (function_exists($filter)) ? $filter : null;
		$parsed->snmpMethod = $snmpMethod;
		$parsed->snmpParams = array($this->hostname, $this->community, $oid, $this->timeout, $this->retries);

		return $parsed;
	}

}

?>