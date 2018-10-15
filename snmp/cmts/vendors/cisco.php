<?php

// Cisco Mib tree
$cisco = "1.3.6.1.4.1.9";

return array(
	"cpuUsage" => "{$cisco}.2.1.57.0",
	"temperatureIn" => "{$cisco}.9.13.1.3.1.3.1",
	"temperatureOut" => "{$cisco}.9.13.1.3.1.3.2",

	// // docsCableMaclayer(127)
	"cdxCmtsCmTotal" => "{$cisco}.9.116.1.3.3.1.4.{index}:type=127"  // docsCableMaclayer(127)
	"cdxCmtsCmActive" => "{$cisco}.9.116.1.3.3.1.5.{index}:type=127" // docsCableMaclayer(127) 
	"cdxCmtsCmRegistered" => "{$cisco}.9.116.1.3.3.1.6.{index}:type=127" // docsCableMaclayer(127) 

	"cdxIfUpChannelWidth" => "{$cisco}.9.116.1.4.1.1.1.{index}:type=129" // docsCableUpstream(129)
	"cdxIfUpChannelModulationProfile" => "{$cisco}.9.116.1.4.1.1.2.{index}:type=129" // docsCableUpstream(129)
	"cdxIfUpChannelCmTotal" => "{$cisco}.9.116.1.4.1.1.3.{index}:type=129" // docsCableUpstream(129)
	"cdxIfUpChannelCmActive" => "{$cisco}.9.116.1.4.1.1.4.{index}:type=129" // docsCableUpstream(129)
	"cdxIfUpChannelCmRegistered" => "{$cisco}.9.116.1.4.1.1.5.{index}:type=129" // docsCableUpstream(129)
	"cdxIfUpChannelInputPowerLevel" => "{$cisco}.9.116.1.4.1.1.6.{index}:type=129" // docsCableUpstream(129)
	"cdxIfUpChannelAvgUtil" => "{$cisco}.9.116.1.4.1.1.7.{index}:type=129" // docsCableUpstream(129)
	"cdxIfUpChannelCmRegistered" => "{$cisco}.9.116.1.4.1.1.5.{index}:type=129" // docsCableUpstream(129)

);

?>