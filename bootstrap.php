<?php

$basePath = realpath(dirname(__FILE__));
require_once $basePath . "/helpers.php";

$config = file_exists(base_path("/config.php")) ? include_once base_path("/config.php") : null;


// Prep stuff for setup wizard
if(!$config) {
	$title = ($config) ? 'AlbiSmart - ClientAPI' : 'Setup — AlbiSmart - ClientAPI';
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
	$defaultDHCPConfiguration = '# DHCP Server Configuration file for route mode CMTS

authoritative;
option domain-name-servers 1.1.1.1,1.0.0.1;
option time-servers 172.22.0.13;
option log-servers 172.22.0.13;
option tftp-server-name "172.22.0.13";
ddns-update-style none;
min-lease-time 60;
default-lease-time 60;
max-lease-time 60;
log-facility local6;
option wpad code 252 = text;


option host-name = "cloud";
option space vsi;
option vsi.version code 6 = string;
option vsi.model code 9 = string;
option vsi.model_other code 201 = string;
option vsi.version_other code 202 = string;
option vsi-pkt code 43 = encapsulate vsi;

# Define option 122
option space docsis-mta;
option docsis-mta.dhcp-server-1 code 1 = ip-address;
option docsis-mta.dhcp-server-2 code 2 ;= ip-address;
option docsis-mta.provision-server code 3 = { integer 8, string };
option docsis-mta.as-req-as-rep-1 code 4 = { integer 32, integer 32, integer 32 };
option docsis-mta.as-req-as-rep-2 code 5 = { integer 32, integer 32, integer 32 };
option docsis-mta.krb-realm-name code 6 = string;
option docsis-mta.tgs-util code 7 = integer 8;
option docsis-mta.timer code 8 = integer 8;
option docsis-mta.ticket-ctrl-mask code 9 = integer 16;
option docsis-mta-pkt code 122 = encapsulate docsis-mta;


#Define option 43
option space vendorOptions;
option vendorOptions.deviceType code 2 = string;
option vendorOptions.serialNumber code 4 = string;
option vendorOptions.hardwareVersion code 5 = string;
option vendorOptions.softwareVersion code 6 = string;
option vendorOptions.bootRomVersion code 7 = string;
option vendorOptions.oui code 8 = string;
option vendorOptions.modelNumber code 9 = string;
option vendorOptions.docsisVendor code 10 = string;
#option vendorOptions.docsisVendor.version_other code 202 = string;
option vendorOptions-pkt code 43 = encapsulate vendorOptions;

stash-agent-options true;

option space myagent;
option myagent.circuit-id code 1 = text;
option myagent.remote-id code 2 = text;
option myagent.agent-id code 3 = text;
option myagent.DOCSIS-device-class code 4 = unsigned integer 32;
option myagent.link-selection code 5 = ip-address;
option myagent.subscriber-id code 6 = text;
option myagent.encapsulation code 82 = encapsulate myagent;';
}

?>