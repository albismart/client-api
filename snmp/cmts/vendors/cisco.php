<?php

// Cisco Mib tree
$cisco = "1.3.6.1.4.1.9";

return array(
	"cmtsName" => "1.3.6.1.2.1.1.5.0", 
	"cmtsDescription" => "1.3.6.1.2.1.1.1.0",
	"cmtsUptime" => "1.3.6.1.2.1.1.3.0",
	
	/* Cisco Specified */
	"cmtsCpuUsage" => "{$cisco}.2.1.57.0",
	"cmtsTemperatureIn" => "{$cisco}.9.13.1.3.1.3.1",
	"cmtsTemperatureOut" => "{$cisco}.9.13.1.3.1.3.2",
);