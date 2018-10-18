<?php

$basePath = realpath(dirname(__FILE__));
$basePath = strstr($basePath, "client-api/", true) . "client-api";

include_once $basePath . "/bootstrap.php";
validateApiRequest();

Class MachineInfo {

	public function info() {
		$machineInfo = array(
			"about"	=> $this->about(false),
			"stats"	=> $this->stats(false),
		);

		returnJson($machineInfo);
	}

	public function about($returnJson = true) {
		$about = array(
			"os"		=> php_uname("s") . " " . php_uname("r"), //OS and version
			"hostname"	=> php_uname("n"),
			"network"	=> $this->network(false)
		);

		if($returnJson) { 
			returnJson($about);
		} else {
			return $about;
		}
	}

	public function network($returnJson = true) {
		$networkInfo = (shell_exec("ifconfig eth0")) ? shell_exec("ifconfig eth0") : "";
		$networkInfo = array(
			"address" => mapNetworkInfo($networkInfo, "address"),
			"subnet"  => mapNetworkInfo($networkInfo, "subnet"),
			"gateway" => mapNetworkInfo($networkInfo, "gateway"),
		);

		if($returnJson) { 
			returnJson($networkInfo);
		} else {
			return $networkInfo;
		}
	}

	public function stats($returnJson = true) {
		$statsInfo = array(
			"uptime"	=> $this->uptime(false),
			"disk"		=> $this->disk(false),
			"memory"	=> $this->memory(false),
			"ports"		=> $this->ports(false),
		);

		if($returnJson) { 
			returnJson($statsInfo);
		} else {
			return $statsInfo;
		}
	}

	public function uptime($returnJson = true) {
		$uptimeInfo = readableTimeticks();

		if($returnJson) { 
			returnJson($uptimeInfo);
		} else {
			return $uptimeInfo;
		}
	}

	public function disk($returnJson = true) {
		$diskInfo = array(
			"total"	=> formatBytes(disk_total_space("/")),
			"free"	=> formatBytes(disk_free_space("/")),
			"used"	=> formatBytes((disk_total_space("/") - disk_free_space("/")) )
		);

		if($returnJson) { 
			returnJson($diskInfo);
		} else {
			return $diskInfo;
		}
	}

	public function memory($returnJson = true) {
		$memoryInfo = file_exists("/proc/meminfo") ? file("/proc/meminfo") : array(0,0);
		$memoryInfo = array(
			"total"	=> formatKiloBytes(intval(preg_replace("/[^0-9]/", "", $memoryInfo[0]))),
			"free"	=> formatKiloBytes(intval(preg_replace("/[^0-9]/", "", $memoryInfo[1]))),
			"used"	=> formatKiloBytes(intval(preg_replace("/[^0-9]/", "", $memoryInfo[0])) - intval(preg_replace("/[^0-9]/", "", $memoryInfo[1]))),
		);

		if($returnJson) { 
			returnJson($memoryInfo);
		} else {
			return $memoryInfo;
		}
	}

	public function ports($returnJson = true) {
		$portsInfo = array(
			"ftp"	=> (is_resource(@fsockopen("127.0.0.1", 21)) && getservbyport(21, "tcp")=="ftp") 		? true : false,
			"sftp"	=> (is_resource(@fsockopen("127.0.0.1", 22)) && getservbyport(22, "tcp")=="ssh") 		? true : false,
			"telnet"=> (is_resource(@fsockopen("127.0.0.1", 23)) && getservbyport(23, "tcp")=="telnet") 	? true : false,
			"snmp"	=> (is_resource(@fsockopen("127.0.0.1", 161)) && getservbyport(161, "udp")=="snmp") 	? true : false,
			"mysql"	=> (is_resource(@fsockopen("127.0.0.1", 3306)) && getservbyport(3306, "tcp")=="mysql")	? true : false,
		);

		if($returnJson) { 
			returnJson($portsInfo);
		} else {
			return $portsInfo;
		}
	}

}

$action = (isset($_GET["action"])) ? $_GET["action"] : "info";
$machineInfo = new MachineInfo();
$machineInfo->$action();

?>