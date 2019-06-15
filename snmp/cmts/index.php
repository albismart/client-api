<?php

$basePath = realpath(dirname(__FILE__));
$basePath = strstr($basePath, "client-api/", true) . "client-api";

include_once $basePath . "/bootstrap.php";
include_once snmp_path("/driver.php");
include_once snmp_path("/cmts/driver.php");
validateApiRequest();

$action = (isset($_GET["action"])) ? $_GET["action"] : "info";
$cmtsSnmpDriver = new Cmts_SNMP_Driver();
$cmtsSnmpDriver->$action();

?>