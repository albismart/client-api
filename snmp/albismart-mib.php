<?php

/*
All devices Object ID's from general Mib Tree.
https://kb.paessler.com/en/topic/653-how-do-snmp-mibs-and-oids-work
A detailed answer on how SNMP and its MIBs and OIDs work.
*/

$generalTree = "1.3.6.1.2.1.1";

return array(
	"description"		=> "1.3.6.1.2.1.1.1.0",
	"objectID"			=> "1.3.6.1.2.1.1.2.0",
	"uptime"			=> "1.3.6.1.2.1.1.3.0",
	"contact"			=> "1.3.6.1.2.1.1.4.0",
	"name"				=> "1.3.6.1.2.1.1.5.0",
	"location"			=> "1.3.6.1.2.1.1.6.0",
	"services"			=> "1.3.6.1.2.1.1.7.0",
	"countInterfaces"	=> "1.3.6.1.2.1.2.1.0",
	"interface" => array(
		"index"					=> "1.3.6.1.2.1.2.2.1.1.{lastIndex}",
		"description"			=> "1.3.6.1.2.1.2.2.1.2.{lastIndex}",
		"type"					=> "1.3.6.1.2.1.2.2.1.3.{lastIndex}",
		"mtu"					=> "1.3.6.1.2.1.2.2.1.4.{lastIndex}",
		"speed"					=> "1.3.6.1.2.1.2.2.1.5.{lastIndex}",
		"physicalAddress"		=> "1.3.6.1.2.1.2.2.1.6.{lastIndex}",
		"adminStatus"			=> "1.3.6.1.2.1.2.2.1.7.{lastIndex}",
		"operationStatus"		=> "1.3.6.1.2.1.2.2.1.8.{lastIndex}",
		"lastChange"			=> "1.3.6.1.2.1.2.2.1.9.{lastIndex}",
		"inOctets"				=> "1.3.6.1.2.1.2.2.1.10.{lastIndex}",
		"inUnicastPackets"		=> "1.3.6.1.2.1.2.2.1.11.{lastIndex}",
		"inNotUnicastPackets"	=> "1.3.6.1.2.1.2.2.1.12.{lastIndex}",
		"inDiscards"			=> "1.3.6.1.2.1.2.2.1.13.{lastIndex}",
		"inErrors"				=> "1.3.6.1.2.1.2.2.1.14.{lastIndex}",
		"inUnkownProtos"		=> "1.3.6.1.2.1.2.2.1.15.{lastIndex}",
		"outOctets"				=> "1.3.6.1.2.1.2.2.1.16.{lastIndex}",
		"outUnicastPackets"		=> "1.3.6.1.2.1.2.2.1.17.{lastIndex}",
		"outNotUnicastPackets"	=> "1.3.6.1.2.1.2.2.1.18.{lastIndex}",
		"outDiscards"			=> "1.3.6.1.2.1.2.2.1.19.{lastIndex}",
		"outErrors"				=> "1.3.6.1.2.1.2.2.1.20.{lastIndex}",
	)
);

?>
