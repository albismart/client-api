<?php include_once snmp_path("/driver.php");
	include_once snmp_path("/devices/cmts.php");
	$cmtsSnmpReader = new Cmts($_GET['ip']);
	var_dump($cmtsSnmpReader->info());
?>
Test