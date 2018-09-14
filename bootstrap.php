<?php

$basePath = realpath(dirname(__FILE__));
require_once $basePath . "/helpers.php";

$config = file_exists(base_path("/config.php")) ? include_once base_path("/config.php") : null;
// Prep stuff for setup wizard
if(!$config) {
	$latestVersionObject = apiLatestVersionObject();
	$currentReqs = array( 
		'php' => array(
			'version' => phpversion(),
			'json' => phpversion('json'),
			'snmp' => phpversion('snmp'),
			'mysql' => phpversion('mysqli'),
			'mysqlPdo' => phpversion('pdo_mysql'),
		), 
		'freeradius' => shell_exec('freeradius -v'),
		'dhcp' => shell_exec('dhcpd -f'),
		'omshell' => shell_exec('omshell'),
	);
}

?>