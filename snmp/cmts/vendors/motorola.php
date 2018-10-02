<?php

// Motorola Mib tree
$motorola = "1.3.6.1.4.1.4981";

return array(
	"cmtsName" => "1.3.6.1.2.1.1.5.0", 
	"cmtsDescription" => "1.3.6.1.2.1.1.1.0",
	"cmtsUptime" => "1.3.6.1.2.1.1.3.0",

	/* Motorola Specified */
	"cmtsCpuUsage" => "{$motorola}.1.20.1.1.1.7.8.1",
	"cmtsTemperatureIn" => "{$motorola}.5.1.2.4.8.1",
	"cmtsTemperatureOut" => "{$motorola}.5.1.2.4.8.2",
);