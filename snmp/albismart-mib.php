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
		"index"					=> "1.3.6.1.2.1.2.2.1.1.{index}",
		"description"			=> "1.3.6.1.2.1.2.2.1.2.{index}",
		"type"					=> "1.3.6.1.2.1.2.2.1.3.{index}",
		"mtu"					=> "1.3.6.1.2.1.2.2.1.4.{index}",
		"speed"					=> "1.3.6.1.2.1.2.2.1.5.{index}",
		"physicalAddress"		=> "1.3.6.1.2.1.2.2.1.6.{index}",
		"adminStatus"			=> "1.3.6.1.2.1.2.2.1.7.{index}",
		"operationStatus"		=> "1.3.6.1.2.1.2.2.1.8.{index}",
		"lastChange"			=> "1.3.6.1.2.1.2.2.1.9.{index}",
		"inOctets"				=> "1.3.6.1.2.1.2.2.1.10.{index}",
		"inUnicastPackets"		=> "1.3.6.1.2.1.2.2.1.11.{index}",
		"inNotUnicastPackets"	=> "1.3.6.1.2.1.2.2.1.12.{index}",
		"inDiscards"			=> "1.3.6.1.2.1.2.2.1.13.{index}",
		"inErrors"				=> "1.3.6.1.2.1.2.2.1.14.{index}",
		"inUnkownProtos"		=> "1.3.6.1.2.1.2.2.1.15.{index}",
		"outOctets"				=> "1.3.6.1.2.1.2.2.1.16.{index}",
		"outUnicastPackets"		=> "1.3.6.1.2.1.2.2.1.17.{index}",
		"outNotUnicastPackets"	=> "1.3.6.1.2.1.2.2.1.18.{index}",
		"outDiscards"			=> "1.3.6.1.2.1.2.2.1.19.{index}",
		"outErrors"				=> "1.3.6.1.2.1.2.2.1.20.{index}",
	),
	"cablemodem" => array(
		"index"					=> "1.3.6.1.2.1.10.127.1.3.7.1.2.{index}",
		"mac"					=> "1.3.6.1.2.1.10.127.1.3.3.1.2.{index}",
		"ip"					=> "1.3.6.1.2.1.10.127.1.3.3.1.3.{index}",
		"status"				=> "1.3.6.1.2.1.10.127.1.3.3.1.9.{index}",
		"uptime"				=> "1.3.6.1.2.1.10.127.1.3.3.1.22.{index}",
	)
);

?>
