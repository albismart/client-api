<?php

// Arris Mib tree
$arris = "1.3.6.1.4.1.4998";

return array(
	"cmtsName" => "1.3.6.1.2.1.1.5.0", 
	"cmtsDescription" => "1.3.6.1.2.1.1.1.0",
	"cmtsUptime" => "1.3.6.1.2.1.1.3.0",

	/* Arris Specified */
	"cmtsCpuUsage" => "{$arris}.1.1.5.3.1.1.1.8.2",
	"cmtsTemperatureIn" => "{$arris}.1.1.10.1.4.2.1.29.1.20",
	"cmtsTemperatureOut" => "{$arris}.1.1.10.1.4.2.1.29.1.20",
);