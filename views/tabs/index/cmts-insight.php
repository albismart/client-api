<?php include_once snmp_path("/driver.php");
	include_once snmp_path("/devices/cmts.php");
	$hostname = (isset($_GET['hostname'])) ? $_GET['hostname'] : null;
	$vendor = (isset($_GET['vendor'])) ? $_GET['vendor'] : null;
	$cmtsSnmpReader = new Cmts($hostname, $vendor);
	var_dump($cmtsSnmpReader->info());
?>
Test