<?php

// Cisco Mib tree
$cisco = "1.3.6.1.4.1.9";

return array(
	"cpuUsage" => "{$cisco}.2.1.57.0",
	"temperatureIn" => "{$cisco}.9.13.1.3.1.3.1",
	"temperatureOut" => "{$cisco}.9.13.1.3.1.3.2",
	"interface" => array(
		"countCableModems" => "{$cisco}.9.116.1.3.3.1.4.{index}:{dependency}=127",
		"countActiveCableModems" => "{$cisco}.9.116.1.3.3.1.5.{index}:{dependency}=127",
		"countRegisteredCableModems" => "{$cisco}.9.116.1.3.3.1.5.{index}:{dependency}=127",
		"upstreamChannel" => array(
			"width" => "{$cisco}.9.116.1.4.1.1.1.{index}:{dependency}=129", // docsCableUpstream(129)
			"modulationProfile" => "{$cisco}.9.116.1.4.1.1.2.{index}:{dependency}=129", // docsCableUpstream(129)
			"countCableModems" => "{$cisco}.9.116.1.4.1.1.3.{index}:{dependency}=129", // docsCableUpstream(129)
			"countActiveCableModems" => "{$cisco}.9.116.1.4.1.1.4.{index}:{dependency}=129", // docsCableUpstream(129)
			"countRegisteredCableModems" => "{$cisco}.9.116.1.4.1.1.5.{index}:{dependency}=129", // docsCableUpstream(129)
			"inputPowerLevel" => "{$cisco}.9.116.1.4.1.1.6.{index}:{dependency}=129", // docsCableUpstream(129)
			"AverageUtilization" => "{$cisco}.9.116.1.4.1.1.7.{index}:{dependency}=129", // docsCableUpstream(129)
		)
	),
	"remoteQuery" => array(
		"cableModem" => array(
			"downstreamChannelPower" => "{$cisco}.10.59.1.2.1.1.1",
			"txStatusPower" => "{$cisco}.10.59.1.2.1.1.2",
			"upstreamChannelTxTimingOffset" => "{$cisco}.10.59.1.2.1.1.3",
			"singalNoiseRatio" => "{$cisco}.10.59.1.2.1.1.4",
			"microReflections" => "{$cisco}.10.59.1.2.1.1.5",
			"pollTime" => "{$cisco}.10.59.1.2.1.1.6",
		),
	),
);

?>