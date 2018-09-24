<?php

$currentPath = realpath(dirname(__FILE__));
include_once $currentPath . "/../bootstrap.php";
include_once snmp_path("/driver.php");

$action = isset($_GET['action']) ? $_GET['action'] : "info";
$device = isset($_GET['device']) ? $_GET['device'] : "cmts";
$hostname = isset($_GET['hostname']) ? $_GET['hostname'] : null;

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