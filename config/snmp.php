<?php

return [
	'retries' => 5,
	// timeout in microseconds, 1000000 equals 1 second.
	'timeout' => 1000000,

	'v3' => [
		'sec_level' 	=> 'authPriv',
		'auth_protocol' => 'SHA',
		'priv_protocol' => 'AES',
	],

    "cmtses" => [
        // example
        "cmts-1" => [
            "host" => "localhost",
            "version" => "v1",
            "credentials" => [
                "read" => "admin",
                "write" => "admin",
            ]
        ]
    ],
    /*
    |--------------------------------------------------------------------------
    | SNMP aliases
    |--------------------------------------------------------------------------
    |
    */
    'aliases' => [
        "about" => array(
            "description"           => "1.3.6.1.2.1.1.1.0",
            "objectID"              => "1.3.6.1.2.1.1.2.0",
            "contact"               => "1.3.6.1.2.1.1.4.0",
            "name"                  => "1.3.6.1.2.1.1.5.0",
            "location"              => "1.3.6.1.2.1.1.6.0",
            "services"              => "1.3.6.1.2.1.1.7.0",
        ),
        "stats" => array(
            "uptime"                => "1.3.6.1.2.1.1.3.0",
            "countInterfaces"       => "1.3.6.1.2.1.2.1.0",
        ),
        "interface" => array(
            "index"                 => "1.3.6.1.2.1.2.2.1.1.{index}",
            "description"           => "1.3.6.1.2.1.2.2.1.2.{index}",
            "type"                  => "1.3.6.1.2.1.2.2.1.3.{index}",
            "mtu"                   => "1.3.6.1.2.1.2.2.1.4.{index}",
            "speed"                 => "1.3.6.1.2.1.2.2.1.5.{index}",
            "highSpeed"             => "1.3.6.1.2.1.31.1.1.1.15.{index}",
            "physicalAddress"       => "1.3.6.1.2.1.2.2.1.6.{index}",
            "adminStatus"           => "1.3.6.1.2.1.2.2.1.7.{index}",
            "operationStatus"       => "1.3.6.1.2.1.2.2.1.8.{index}",
            "lastChange"            => "1.3.6.1.2.1.2.2.1.9.{index}",
            "inOctets"              => "1.3.6.1.2.1.2.2.1.10.{index}",
            "highInOctets"          => "1.3.6.1.2.1.31.1.1.1.6.{index}",
            "inUnicastPackets"      => "1.3.6.1.2.1.2.2.1.11.{index}",
            "highInUnicastPackets"  => "1.3.6.1.2.1.31.1.1.1.7.{index}",
            "inNotUnicastPackets"   => "1.3.6.1.2.1.2.2.1.12.{index}",
            "inDiscards"            => "1.3.6.1.2.1.2.2.1.13.{index}",
            "inErrors"              => "1.3.6.1.2.1.2.2.1.14.{index}",
            "inUnkownProtos"        => "1.3.6.1.2.1.2.2.1.15.{index}",
            "outOctets"             => "1.3.6.1.2.1.2.2.1.16.{index}",
            "highOutOctets"         => "1.3.6.1.2.1.31.1.1.1.10.{index}",
            "outUnicastPackets"     => "1.3.6.1.2.1.2.2.1.17.{index}",
            "highOutUnicastPackets" => "1.3.6.1.2.1.31.1.1.1.11.{index}",
            "outNotUnicastPackets"  => "1.3.6.1.2.1.2.2.1.18.{index}",
            "outDiscards"           => "1.3.6.1.2.1.2.2.1.19.{index}",
            "outErrors"             => "1.3.6.1.2.1.2.2.1.20.{index}",
        ),
        "cmts" => array(
            "onlineCMList"          => "1.3.6.1.2.1.10.127.1.3.3.1.3",
            "cableModem" => array(
                "identity" => array(
                    "index"             => "1.3.6.1.2.1.10.127.1.3.7.1.2.{index}",
                    "mac"               => "1.3.6.1.2.1.10.127.1.3.3.1.2.{index}",
                    "ip"                => "1.3.6.1.2.1.10.127.1.3.3.1.3.{index}",
                ),
                "status"            => "1.3.6.1.2.1.10.127.1.3.3.1.9.{index}",
                "uptime"            => "1.3.6.1.2.1.10.127.1.3.3.1.22.{index}",
                "downstreamChannel" => "1.3.6.1.2.1.10.127.1.3.3.1.4.{index}",
            )
        ),
        "docsis" => array(
            "serialNumber"          => "1.3.6.1.2.1.69.1.1.4.0",
            "eventsLog"             => "1.3.6.1.2.1.69.1.5.8.1.7",
            "version"               => "1.3.6.1.2.1.10.127.1.1.5",
            "dateTime"              => "1.3.6.1.2.1.69.1.1.2",
            "software" => array(
                "version"           => "1.3.6.1.2.1.69.1.3.5",
                "upgradePath"       => "1.3.6.1.2.1.69.1.3.2",
                "upgradePermission" => "1.3.6.1.2.1.69.1.3.3",
                "upgradeStatus"     => "1.3.6.1.2.1.69.1.3.4",
                "upgradeServer" => array(
                    "addressType"   => "1.3.6.1.2.1.69.1.3.6",
                    "address"       => "1.3.6.1.2.1.69.1.3.7",
                    "protocol"      => "1.3.6.1.2.1.69.1.3.8",
                )
            ),
            "logServer" => array(
                "addressType"       => "1.3.6.1.2.1.69.1.5.9",
                "address"           => "1.3.6.1.2.1.69.1.5.10",
            ),
            "logEvent"  => array(
                "id"                => "1.3.6.1.2.1.69.1.5.8.1.6",
                "createdDatetime"   => "1.3.6.1.2.1.69.1.5.8.1.2",
                "updatedDatetime"   => "1.3.6.1.2.1.69.1.5.8.1.3",
                "countEntries"      => "1.3.6.1.2.1.69.1.5.8.1.4",
                "priority"          => "1.3.6.1.2.1.69.1.5.8.1.5",
                "description"       => "1.3.6.1.2.1.69.1.5.8.1.7",
            ),
            "interface" => array(
                "totalCodeWords"            => "1.3.6.1.2.1.10.127.1.1.4.1.8.{index}", 
                "correctedCodeWords"        => "1.3.6.1.2.1.10.127.1.1.4.1.9.{index}",
                "uncorrectedCodeWorks"      => "1.3.6.1.2.1.10.127.1.1.4.1.10.{index}", 
                "snr"                       => "1.3.6.1.2.1.10.127.1.1.4.1.5.{index}", 
                "mr"                        => "1.3.6.1.2.1.10.127.1.1.4.1.6.{index}", 
                "upstreamChannel" => array(
                    "index"                 => "1.3.6.1.2.1.10.127.1.1.2.1.1",
                    "frequency"             => "1.3.6.1.2.1.10.127.1.1.2.1.2",
                    "width"                 => "1.3.6.1.2.1.10.127.1.1.2.1.3",
                    "modulationProfile"     => "1.3.6.1.2.1.10.127.1.1.2.1.4",
                    "slotSize"              => "1.3.6.1.2.1.10.127.1.1.2.1.5",
                    "txTimingOffset"        => "1.3.6.1.2.1.10.127.1.1.2.1.6",
                    "rangingBackOffStart"   => "1.3.6.1.2.1.10.127.1.1.2.1.7",
                    "rangingBackOffEnd"     => "1.3.6.1.2.1.10.127.1.1.2.1.8",
                    "txBackOffStart"        => "1.3.6.1.2.1.10.127.1.1.2.1.9",
                    "txBackOffEnd"          => "1.3.6.1.2.1.10.127.1.1.2.1.10",
                    "type"                  => "1.3.6.1.2.1.10.127.1.1.2.1.15",
                    "status"                => "1.3.6.1.2.1.10.127.1.1.2.1.18",
                    "preEqEnable"           => "1.3.6.1.2.1.10.127.1.1.2.1.19",
                    "powerD3"               => "1.3.6.1.4.1.4491.2.1.20.1.2.1.1.{index}",
                    "power"                 => "1.3.6.1.2.1.10.127.1.2.2.1.3.2.{index}",
                ),
                "downstreamChannel"         => array(
                    "index"                 => "1.3.6.1.2.1.10.127.1.1.1.1.1",
                    "frequency"             => "1.3.6.1.2.1.10.127.1.1.1.1.2",
                    "width"                 => "1.3.6.1.2.1.10.127.1.1.1.1.3",
                    "modulation"            => "1.3.6.1.2.1.10.127.1.1.1.1.4",
                    "interleave"            => "1.3.6.1.2.1.10.127.1.1.1.1.5",
                    "power"                 => "1.3.6.1.2.1.10.127.1.1.1.1.6",
                    "annex"                 => "1.3.6.1.2.1.10.127.1.1.1.1.7",
                    "storageType"           => "1.3.6.1.2.1.10.127.1.1.1.1.8",
                ),
                
            ),
            "cmts" => array(
                "channelUtilizationInterval"        => "1.3.6.1.2.1.10.127.1.3.8",
                "channelUtUtilization"              => "1.3.6.1.2.1.10.127.1.3.9.1.3",
                "modulation" => array(
                    "control"                       => "1.3.6.1.2.1.10.127.1.3.5.1.3",
                    "type"                          => "1.3.6.1.2.1.10.127.1.3.5.1.4",
                    "premableLength"                => "1.3.6.1.2.1.10.127.1.3.5.1.5",
                    "forwardErrorCorrection"        => "1.3.6.1.2.1.10.127.1.3.5.1.7",
                    "forwardErrorCorrectionLength"  => "1.3.6.1.2.1.10.127.1.3.5.1.8",
                    "scramblerSeed"                 => "1.3.6.1.2.1.10.127.1.3.5.1.9",
                    "maxBurstSize"                  => "1.3.6.1.2.1.10.127.1.3.5.1.10",
                    "guardTimeSize"                 => "1.3.6.1.2.1.10.127.1.3.5.1.11",
                    "lastCodewordShortened"         => "1.3.6.1.2.1.10.127.1.3.5.1.12",
                    "scrambler"                     => "1.3.6.1.2.1.10.127.1.3.5.1.13",
                    "byteInterleaverDepth"          => "1.3.6.1.2.1.10.127.1.3.5.1.14",
                    "byteInterleaverBlockSize"      => "1.3.6.1.2.1.10.127.1.3.5.1.15",
                    "premableType"                  => "1.3.6.1.2.1.10.127.1.3.5.1.16",
                    "tcmErrorCorrectionOn"          => "1.3.6.1.2.1.10.127.1.3.5.1.17",
                    "scdmaInterleaverStepSize"      => "1.3.6.1.2.1.10.127.1.3.5.1.18",
                    "scdmaSpreaderEnable"           => "1.3.6.1.2.1.10.127.1.3.5.1.19",
                    "scdmaSubframeCodes"            => "1.3.6.1.2.1.10.127.1.3.5.1.20",
                    "channelType"                   => "1.3.6.1.2.1.10.127.1.3.5.1.21",
                    "storageType"                   => "1.3.6.1.2.1.10.127.1.3.5.1.22",
                ),
                "cableModem" => array(
                    "upstreamChannelsD3"            => "1.3.6.1.4.1.4491.2.1.20.1.4.1.4.{index}",
                    "upstreamChannels"              => "1.3.6.1.2.1.10.127.1.3.3.1.5.{index}",
                )
            ),
            "cableModem" => array(
                "status"                    => "1.3.6.1.4.1.4491.2.1.20.1.1.1.1.2.{index}",
                "configFilename"            => "1.3.6.1.2.1.69.1.4.5.0.{index}",
                "cpehosts"                  => "1.3.6.1.2.1.17.4.3.1.3",
                "macAddress"                => "1.3.6.1.2.1.2.2.1.6.2"
            )
        )
]
];