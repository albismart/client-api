<?php

$currentPath = realpath(dirname(__FILE__));
include_once $currentPath . "/../bootstrap.php";
include_once snmp_path("/driver.php");
include_once snmp_path("/devices/cmts.php");
include_once snmp_path("/devices/cablemodem.php");

$action = isset($_GET['action']) ? $_GET['action'] : "info";
$device = isset($_GET['device']) ? $_GET['device'] : "cmts";

$deviceReaderClass = ucfirst($device);
$deviceReader = new $deviceReaderClass;

switch ($action) {
	case "info":
		$deviceReader->info();
		break;
	
	default:
		# code...
		break;
}