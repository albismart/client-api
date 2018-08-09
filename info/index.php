<?php

// Providing some Machine Info at a glance
$meminfo = file_exists("/proc/meminfo") ? file("/proc/meminfo") : 0;
$networkInfo = shell_exec("ifconfig -a");
$linuxInfo = array(
	"os" => php_uname("s") . " " . php_uname("r"), //OS and version
	"hostname" => php_uname("n"),
	"totalDisk" => disk_total_space("/") / 1024, // Returns kilobytes
	"freeDisk" => disk_free_space("/") / 1024, // Returns kilobytes
	"totalMemory" => intval(preg_replace("/[^0-9]/", "", $meminfo[0])), // Returns kilobytes
	"freeMemory" => intval(preg_replace("/[^0-9]/", "", $meminfo[1])), // Returns kilobytes
	"ftp" => (getservbyport(21, "tcp")=="ftp") ? true : false, // Checking if port is default
	"sftp" => (getservbyport(22, "tcp")=="ssh") ? true : false, // Checking if port is default
	"telnet" => (getservbyport(23, "tcp")=="telnet") ? true : false, // Checking if port is default
	"snmp" => (getservbyport(161, "udp")=="snmp") ? true : false, // Checking if port is default
	"address" => mapNetworkInfo($networkInfo, "address"), 
	"subnet" => mapNetworkInfo($networkInfo, "subnet"), 
	"gateway" => mapNetworkInfo($networkInfo, "gateway"), 
);

function mapNetworkInfo($networkInfo, $info) {
	$map = array('address'=>'inet addr:', 'subnet'=>'Mask:', 'gateway'=>'Bcast:');
	$result = explode($map[$info], $networkInfo); 
	$result = explode(" ", array_pop($result));
	$result = array_shift($result);
	return trim($result);
}

echo json_encode($linuxInfo);