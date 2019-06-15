<?php

$basePath = realpath(dirname(__FILE__));
$basePath = strstr($basePath, "client-api/", true) . "client-api";

include_once $basePath . "/bootstrap.php";
include_once snmp_path("/driver.php");
validateApiRequest();

Class CableModem_SNMP_Driver extends SNMP_Driver {
	public function __construct() {
		parent::__construct();
		$cmtsMIB = include_once snmp_path("/cablemodem/vendors/{$this->vendor}.php");
		$this->mibs = array_merge_recursive_ex($this->mibs, $cmtsMIB);
	}

	/***********************************************
	*	linuxIP/snmp/cablemodem/info/{mac}
	*************************************************/
	public function info() {
		$cmtsInfo = array(
			"about" => $this->read("about"),
			"stats" => $this->read("stats"),
		);

		returnJson($cmtsInfo);
	}

}

$action = (isset($_GET["action"])) ? $_GET["action"] : "info";
$cableModemSnmpDriver = new CableModem_SNMP_Driver();
$cableModemSnmpDriver->$action();

?>