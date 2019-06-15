<?php

$basePath = realpath(dirname(__FILE__));
$basePath = strstr($basePath, "client-api/", true) . "client-api";

include_once $basePath . "/bootstrap.php";
include_once snmp_path("/driver.php");
include_once snmp_path("/cmts/driver.php");
include_once snmp_path("/cablemodem/driver.php");
validateApiRequest();

$action = (isset($_GET["action"])) ? $_GET["action"] : "info";
$cableModemSnmpDriver = new CableModem_SNMP_Driver();
$cableModemSnmpDriver->$action();

?>