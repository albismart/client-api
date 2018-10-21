<?php

// Cisco Mib tree
$cisco = "1.3.6.1.4.1.9";

return array(
	"cmtsCpuUsage" => "{$cisco}.2.1.57.0",
	"cmtsTemperatureIn" => "{$cisco}.9.13.1.3.1.3.1",
	"cmtsTemperatureOut" => "{$cisco}.9.13.1.3.1.3.2",
	
	"about" => array(
		"description"			=> "1.3.6.1.2.1.1.1.0",
		"objectID"				=> "1.3.6.1.2.1.1.2.0",
		"contact"				=> "1.3.6.1.2.1.1.4.0",
		"name"					=> "1.3.6.1.2.1.1.5.0",
		"location"				=> "1.3.6.1.2.1.1.6.0",
		"services"				=> "1.3.6.1.2.1.1.7.0",
	),
	"stats" => array(
		"uptime"				=> "1.3.6.1.2.1.1.3.0:readableTimeticks",
		"countInterfaces"		=> "1.3.6.1.2.1.2.1.0",
	),
);