<?php

$basePath = realpath(dirname(__FILE__));
$basePath = strstr($basePath, "client-api/", true) . "client-api";

include_once $basePath . "/bootstrap.php";
include_once snmp_path("/driver.php");
validateApiRequest();

Class SNMP extends SNMP_Driver {}

$action = (isset($_GET["action"])) ? $_GET["action"] : "mibs";
$snmpDriver = new SNMP();
$snmpDriver->$action();

?>