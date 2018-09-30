<?php include_once snmp_path("/driver.php");
	include_once snmp_path("/devices/cmts.php");
	$hostname = (isset($_GET['hostname'])) ? $_GET['hostname'] : null;
	$vendor = (isset($_GET['vendor'])) ? $_GET['vendor'] : "cisco";
	$cmtsSnmpReader = new Cmts($hostname, $vendor);
	$cmtsInfo = $cmtsSnmpReader->info();
	echo "CMTS Info";
	var_dump($cmtsInfo);
?>