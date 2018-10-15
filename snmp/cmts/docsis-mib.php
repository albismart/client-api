<?php

/* 
All devices Object ID's from general Mib Tree.
https://kb.paessler.com/en/topic/653-how-do-snmp-mibs-and-oids-work
A detailed answer on how SNMP and its MIBs and OIDs work.
*/

$docsDev = "1.3.6.1.2.1.69";

return array(
	"docsIfCmtsCmPtr" => "1.3.6.1.2.1.10.127.1.3.7.1.2", 
	
	"docsIfDocsisBaseCapability" => "1.3.6.1.2.1.10.127.1.1.5", 
	
	"docsDevDateTime" => "1.3.6.1.2.1.69.1.1.2", 
	"docsDevEvFirstTime" => "1.3.6.1.2.1.69.1.5.8.1.2", 
	"docsDevEvLastTime" => "1.3.6.1.2.1.69.1.5.8.1.3", 
	"docsDevEvCounts" => "1.3.6.1.2.1.69.1.5.8.1.4", 
	"docsDevEvLevel" => "1.3.6.1.2.1.69.1.5.8.1.5", 
	"docsDevEvId" => "1.3.6.1.2.1.69.1.5.8.1.6", 
	"docsDevEvText" => "1.3.6.1.2.1.69.1.5.8.1.7", 
    "docsDevEvSyslogAddressType" => "1.3.6.1.2.1.69.1.5.9", 
	"docsDevEvSyslogAddress" => "1.3.6.1.2.1.69.1.5.10", 

	"docsDevSwFilename" => "1.3.6.1.2.1.69.1.3.2", 
	"docsDevSwAdminStatus" => "1.3.6.1.2.1.69.1.3.3",
	"docsDevSwOperStatus" => "1.3.6.1.2.1.69.1.3.4", 
	"docsDevSwCurrentVers" => "1.3.6.1.2.1.69.1.3.5", 
	"docsDevSwServerAddressType" => "1.3.6.1.2.1.69.1.3.6", 
	"docsDevSwServerAddress" => "1.3.6.1.2.1.69.1.3.7", 
	"docsDevSwServerTransportProtocol" => "1.3.6.1.2.1.69.1.3.8", 

	"docsIfSigQExtUnerroreds" => "1.3.6.1.2.1.10.127.1.1.4.1.8.{index}:type=129" // docsCableUpstream(129)
	"docsIfSigQExtCorrecteds" => "1.3.6.1.2.1.10.127.1.1.4.1.9.{index}:type=129" // docsCableUpstream(129)
	"docsIfSigQExtUncorrectables" => "1.3.6.1.2.1.10.127.1.1.4.1.10.{index}:type=129" // docsCableUpstream(129)
	"docsIfSigQSignalNoise" => "1.3.6.1.2.1.10.127.1.1.4.1.5.{index}:type=129" // docsCableUpstream(129)
	"docsIfSigQMicroreflections" => "1.3.6.1.2.1.10.127.1.1.4.1.6.{index}:type=129" // docsCableUpstream(129)
	"docsIfSigQUnerroreds" => "1.3.6.1.2.1.10.127.1.1.4.1.2.{index}:type=129" // docsCableUpstream(129)

// downstreams
"docsIfDownChannelId" => "1.3.6.1.2.1.10.127.1.1.1.1.1", 
"docsIfDownChannelFrequency" => "1.3.6.1.2.1.10.127.1.1.1.1.2", 
"docsIfDownChannelWidth" => "1.3.6.1.2.1.10.127.1.1.1.1.3", 
"docsIfDownChannelModulation" => "1.3.6.1.2.1.10.127.1.1.1.1.4", 
"docsIfDownChannelInterleave" => "1.3.6.1.2.1.10.127.1.1.1.1.5", 
"docsIfDownChannelPower" => "1.3.6.1.2.1.10.127.1.1.1.1.6", 
"docsIfDownChannelAnnex" => "1.3.6.1.2.1.10.127.1.1.1.1.7", 
"docsIfDownChannelStorageType" => "1.3.6.1.2.1.10.127.1.1.1.1.8", 	

// upstreams
"docsIfUpChannelId" => "1.3.6.1.2.1.10.127.1.1.2.1.1", 
"docsIfUpChannelFrequency" => "1.3.6.1.2.1.10.127.1.1.2.1.2", 
"docsIfUpChannelWidth" => "1.3.6.1.2.1.10.127.1.1.2.1.3", 
"docsIfUpChannelModulationProfile" => "1.3.6.1.2.1.10.127.1.1.2.1.4", 
"docsIfUpChannelSlotSize" => "1.3.6.1.2.1.10.127.1.1.2.1.5", 
"docsIfUpChannelTxTimingOffset" => "1.3.6.1.2.1.10.127.1.1.2.1.6", 
"docsIfUpChannelRangingBackoffStart" => "1.3.6.1.2.1.10.127.1.1.2.1.7", 
"docsIfUpChannelRangingBackoffEnd" => "1.3.6.1.2.1.10.127.1.1.2.1.8", 
"docsIfUpChannelTxBackoffStart" => "1.3.6.1.2.1.10.127.1.1.2.1.9", 
"docsIfUpChannelTxBackoffEnd" => "1.3.6.1.2.1.10.127.1.1.2.1.10", 
"docsIfUpChannelType" => "1.3.6.1.2.1.10.127.1.1.2.1.15", 
"docsIfUpChannelStatus" => "1.3.6.1.2.1.10.127.1.1.2.1.18", 
"docsIfUpChannelPreEqEnable" => "1.3.6.1.2.1.10.127.1.1.2.1.19", 

// modulation profile .225.x
"docsIfCmtsModControl" => "1.3.6.1.2.1.10.127.1.3.5.1.3", 
"docsIfCmtsModType" => "1.3.6.1.2.1.10.127.1.3.5.1.4", 
"docsIfCmtsModPreambleLen" => "1.3.6.1.2.1.10.127.1.3.5.1.5", 
"docsIfCmtsModFECErrorCorrection" => "1.3.6.1.2.1.10.127.1.3.5.1.7", 
"docsIfCmtsModFECCodewordLength" => "1.3.6.1.2.1.10.127.1.3.5.1.8", 
"docsIfCmtsModScramblerSeed" => "1.3.6.1.2.1.10.127.1.3.5.1.9", 
"docsIfCmtsModMaxBurstSize" => "1.3.6.1.2.1.10.127.1.3.5.1.10", 
"docsIfCmtsModGuardTimeSize" => "1.3.6.1.2.1.10.127.1.3.5.1.11", 
"docsIfCmtsModLastCodewordShortened" => "1.3.6.1.2.1.10.127.1.3.5.1.12", 
"docsIfCmtsModScrambler" => "1.3.6.1.2.1.10.127.1.3.5.1.13", 
"docsIfCmtsModByteInterleaverDepth" => "1.3.6.1.2.1.10.127.1.3.5.1.14", 
"docsIfCmtsModByteInterleaverBlockSize" => "1.3.6.1.2.1.10.127.1.3.5.1.15", 
"docsIfCmtsModPreambleType" => "1.3.6.1.2.1.10.127.1.3.5.1.16", 
"docsIfCmtsModTcmErrorCorrectionOn" => "1.3.6.1.2.1.10.127.1.3.5.1.17", 
"docsIfCmtsModScdmaInterleaverStepSize" => "1.3.6.1.2.1.10.127.1.3.5.1.18", 
"docsIfCmtsModScdmaSpreaderEnable" => "1.3.6.1.2.1.10.127.1.3.5.1.19", 
"docsIfCmtsModScdmaSubframeCodes" => "1.3.6.1.2.1.10.127.1.3.5.1.20", 
"docsIfCmtsModChannelType" => "1.3.6.1.2.1.10.127.1.3.5.1.21", 
"docsIfCmtsModStorageType" => "1.3.6.1.2.1.10.127.1.3.5.1.22", 

"docsIfCmtsChannelUtilizationInterval" => "1.3.6.1.2.1.10.127.1.3.8", 
"docsIfCmtsChannelUtUtilization" => "1.3.6.1.2.1.10.127.1.3.9.1.3", 
?>