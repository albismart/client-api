<?php

$basePath = realpath(dirname(__FILE__));
$basePath = strstr($basePath, "client-api/", true) . "client-api";

include_once $basePath . "/bootstrap.php";
include_once dhcp_path("/omapi.php");
validateApiRequest();

Class DHCP_OMAPI_Driver extends OMAPI_Driver {
	
	public function info() {
		returnJson($this->connected);
	}
	
	public function create() {

		$ip = requestParam("ip");
		$mac = requestParam("mac");

		if($this->connected && isValidIPAddress($ip) && isValidMacAddress($mac)) {
			$this->write("new host", false);
			$this->write("set known=1");
			$this->write("set hardware-type=1");
			$this->write("set hardware-address=" . $mac);
			$this->write("set ip-address=" . $ip);
			$this->write("create");
			$result = (substr($this->status(), 0, 9)=="obj: host") ? true : false;
			$this->disconnect();

			returnJson($result);
		}

		returnJson("");
	}

	public function remove() {
		$mac = requestParam("mac");
		if($this->connected && isValidMacAddress($mac)) {
			$this->write("new host", false);
			$this->write("set hardware-address=" . $mac);
			$this->write("open");
			$this->write("remove");
			$result = $this->status();
			$result = (substr($this->status(), 0, 11)=="obj: <null>") ? true : false;
			$this->disconnect();

			returnJson($result);
		}

		returnJson("");
	}

}

$action = (isset($_GET["action"])) ? $_GET["action"] : "info";
$dhcpOmapiDriver = new DHCP_OMAPI_Driver();
$dhcpOmapiDriver->$action();

?>