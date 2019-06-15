<?php

// Motorola Mib tree
$motorola = "1.3.6.1.4.1.4981";

return array(
	"analytics" => array(
		// IF-MIB::ifType.3 = INTEGER: docsCableDownstream(128)
		"docsIfDownChannelId" => "1.3.6.1.2.1.10.127.1.1.1.1.1.{$index}", // DOCS-IF-MIB::docsIfDownChannelId.48 = INTEGER: 49
		"docsIfDownChannelFrequency" => "1.3.6.1.2.1.10.127.1.1.1.1.2.{$index}", // DOCS-IF-MIB::docsIfDownChannelFrequency.3 = INTEGER: 466000000 hertz
		"docsIfDownChannelWidth" => "1.3.6.1.2.1.10.127.1.1.1.1.3.{$index}", 	// DOCS-IF-MIB::docsIfDownChannelWidth.3 = INTEGER: 8000000 hertz
		"docsIfDownChannelModulation" => "1.3.6.1.2.1.10.127.1.1.1.1.4.{$index}", // DOCS-IF-MIB::docsIfDownChannelModulation.3 = INTEGER: qam256(4)
		"docsIfDownChannelInterleave" => "1.3.6.1.2.1.10.127.1.1.1.1.5.{$index}", // DOCS-IF-MIB::docsIfDownChannelInterleave.62 = INTEGER: taps12increment17(8)
		"docsIfDownChannelPower" => "1.3.6.1.2.1.10.127.1.1.1.1.6.{$index}", // DOCS-IF-MIB::docsIfDownChannelPower.3 = INTEGER: 22.8 dBmV
		"docsIfDownChannelAnnex" => "1.3.6.1.2.1.10.127.1.1.1.1.7.{$index}", // DOCS-IF-MIB::docsIfDownChannelAnnex.3 = INTEGER: annexA(3)
		"docsIfSigQExtUnerroreds" => "1.3.6.1.2.1.10.127.1.1.4.1.8.{$index}", // DOCS-IF-MIB::docsIfSigQExtUnerroreds.3 = Counter64: 638956385 codewords
		"docsIfSigQExtCorrecteds" => "1.3.6.1.2.1.10.127.1.1.4.1.9.{$index}", // DOCS-IF-MIB::docsIfSigQExtCorrecteds.3 = Counter64: 0 codewords
		"docsIfSigQExtUncorrectables" => "1.3.6.1.2.1.10.127.1.1.4.1.10.{$index}", // DOCS-IF-MIB::docsIfSigQExtUncorrectables.48 = Counter64: 0 codewords
		"docsIfSigQSignalNoise" => "1.3.6.1.2.1.10.127.1.1.4.1.5.{$index}", // DOCS-IF-MIB::docsIfSigQSignalNoise.3 = INTEGER: 47.8 TenthdB
		"docsIfSigQMicroreflections" => "1.3.6.1.2.1.10.127.1.1.4.1.6.{$index}", // DOCS-IF-MIB::docsIfSigQMicroreflections.3 = INTEGER: 39 -dBc

		//	IF-MIB::ifType.4 = INTEGER: docsCableUpstream(129)
		"docsIfUpChannelId" => "1.3.6.1.2.1.10.127.1.1.2.1.1.{$index}", // DOCS-IF-MIB::docsIfUpChannelId.80 = INTEGER: 1
		"docsIfUpChannelFrequency" => "1.3.6.1.2.1.10.127.1.1.2.1.2.{$index}", // DOCS-IF-MIB::docsIfUpChannelFrequency.4 = INTEGER: 29800000 hertz
		"docsIfUpChannelWidth" => "1.3.6.1.2.1.10.127.1.1.2.1.3.{$index}", // DOCS-IF-MIB::docsIfUpChannelWidth.4 = INTEGER: 6400000 hertz
		"docsIfUpChannelSlotSize" => "1.3.6.1.2.1.10.127.1.1.2.1.5.{$index}", // DOCS-IF-MIB::docsIfUpChannelSlotSize.4 = Gauge32: 1 ticks
		"docsIfUpChannelType" => "1.3.6.1.2.1.10.127.1.1.2.1.15.{$index}", // DOCS-IF-MIB::docsIfUpChannelType.4 = INTEGER: atdma(2)
		"docsIfUpChannelPreEqEnable" => "1.3.6.1.2.1.10.127.1.1.2.1.19.{$index}", // DOCS-IF-MIB::docsIfUpChannelPreEqEnable.4 = INTEGER: true(1)

		"docsIfDocsisBaseCapability" => "1.3.6.1.2.1.10.127.1.1.5.0", // DOCS-IF-MIB::docsIfDocsisBaseCapability.0 = INTEGER: docsis30(4)
		"docsIfCmStatusValue" => "1.3.6.1.2.1.10.127.1.2.2.1.1.2", // DOCS-IF-MIB::docsIfCmStatusValue.2 = INTEGER: operational(12)
		"docsIfCmStatusTxPower" => "1.3.6.1.2.1.10.127.1.2.2.1.3.2", // DOCS-IF-MIB::docsIfCmStatusTxPower.2 = INTEGER: 29.0 TenthdBmV
		"docsIfCmStatusModulationType" => "1.3.6.1.2.1.10.127.1.2.2.1.16.2", // DOCS-IF-MIB::docsIfCmStatusModulationType.2 = INTEGER: atdma(2)
		"docsBpi2CmPrivacyEnable" => "1.3.6.1.2.1.10.127.6.1.1.1.1.1.2", // DOCS-BPI2-MIB::docsBpi2CmPrivacyEnable.2 = INTEGER: true(1)
		"docsBpi2CmAuthState" => "1.3.6.1.2.1.10.127.6.1.1.1.1.3.2", // DOCS-BPI2-MIB::docsBpi2CmAuthState.2 = INTEGER: authorized(3)
	)
);