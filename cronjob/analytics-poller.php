<?php

$currentPath = realpath(dirname(__FILE__));
require_once $currentPath . "/../bootstrap.php";

include snmp_path("/driver.php");
include snmp_path("/cmts/driver.php");
include snmp_path("/cablemodem/driver.php");


$cmtses = json_decode('[{"ip":"172.22.0.22","label":"uBR10k","community":"albismart","range":"10.5.0.0\/16","cperanges":[],"brand":"cisco","model":"ubr10k","cmtsIp":"172.22.0.22"}]');

$start = microtime(true);
foreach($cmtses as $cmts) {
    $cmtsSnmpDriver = new Cmts_SNMP_Driver($cmts->ip);
    $onlineCableModemList = $cmtsSnmpDriver->read("cmts.onlineCMList[R]", SNMP_VALUE_LIBRARY);

    if($onlineCableModemList==false || (is_string($onlineCableModemList) && strpos($onlineCableModemList, 'No Such Instance') !== false)) {
        continue;
    }

    $cableModemsAnalytics = array();
    foreach($onlineCableModemList as $cableModemPtr => $cableModemIP) {
        $cableModemPtr = explode(".", $cableModemPtr);
        $cableModemPtr = array_pop($cableModemPtr);

        $cableModemStatus = $cmtsSnmpDriver->read("cmts.cableModem.status", $cableModemPtr);
        if($cableModemStatus!=6) { continue; }

        snmp_set_valueretrieval(SNMP_VALUE_LIBRARY);
        $cableModemMac = snmpget($cableModemIP, "public", "1.3.6.1.2.1.2.2.1.6.2");

        $cableModemMac = ltrim(strstr($cableModemMac, ": "), ": ");
        $cableModemMacOctets = explode(":", $cableModemMac);
        foreach($cableModemMacOctets as $key => $cableModemMacOctet) {
            if(strlen($cableModemMacOctet)==1) {
                $cableModemMacOctets[$key] = "0" . $cableModemMacOctet;
            }
        }
        $cableModemMac = implode(":", $cableModemMacOctets);

        $cableModem = new \stdClass;
        $cableModem->identity = new \stdClass;
        $cableModem->identity->ip = $cableModemIP;
        $cableModem->identity->ptr = $cableModemPtr;
        $cableModem->identity->mac = $cableModemMac;
        $cableModem->identity->cmts = $cmts->ip;

        $cmMacHexToDec = explode(":", $cableModemMac);
		foreach($cmMacHexToDec as $key => $value) {
			$cmMacHexToDec[$key] = hexdec($value);
		}
        $cableModem->identity->dmac = implode(".", $cmMacHexToDec);
        
        $cableModemSnmpDriver = new CableModem_SNMP_Driver($cableModemIP, "public", "private", $cableModem->identity);

        $cableModem->about = $cableModemSnmpDriver->read("about");

        $cableModem->state = new \stdClass;
        $cableModem->state->status = new \stdClass;
        $cableModem->state->status->source = $cableModem->identity->cmts;
        $cableModem->state->status->value = $cmtsSnmpDriver->read('cmts.cableModem.status', $cableModem->identity->ptr);

        $cableModem->state->operational = new \stdClass;
        $cableModem->state->operational->source = $cableModem->identity->ip;
        $cableModem->state->operational->value = $cableModemSnmpDriver->read('docsis.cableModem.status');
        $cableModem->state->operational->config = $cableModemSnmpDriver->read('docsis.cableModem.configFilename');


        $downstreamChannelIndex = $cmtsSnmpDriver->read('cmts.cableModem.downstreamChannel', $cableModem->identity->ptr);

        $cableModem->primaryDownstream = new \stdClass;
        $cableModem->primaryDownstream->source = $cableModem->identity->cmts;
        $cableModem->primaryDownstream->index = $downstreamChannelIndex;
        $cableModem->primaryDownstream->name = $cmtsSnmpDriver->read('interface.description', $downstreamChannelIndex);

        $preSnrValue = $cmtsSnmpDriver->read('docsis.cmts.cableModem.upstreamChannelsD3[R]', $cableModem->identity->ptr);

        if($preSnrValue) {
            $snrValues = array();

            foreach($preSnrValue as $interface => $snrValue) {
                $interfaceIndex = explode(".", $interface);
                $interfaceIndex = array_pop($interfaceIndex);
                $interfaceName = $cmtsSnmpDriver->read('interface.description', $interfaceIndex);

                $snrValueObject = new \stdClass;
                $snrValueObject->source = $cableModem->identity->cmts;
                $snrValueObject->index = $interfaceIndex;
                $snrValueObject->name = $interfaceName;
                $snrValueObject->snr = $snrValue;

                $snrValues[] = $snrValueObject;
            }

            $cableModem->upstreamChannels = $snrValues;
        } else {
            
            //Docsis 2.0 Fallback

            $preSnrValue = $cmtsSnmpDriver->read('interface.upstreamChannels[R]', $cableModem->identity->ptr);
            $snrValues = array();
            
            foreach($preSnrValue as $interface => $snrValue) {
                $interfaceIndex = explode(".", $interface);
                $interfaceIndex = array_pop($interfaceIndex);
                $interfaceName = $cmtsSnmpDriver->read('interface.description', $interfaceIndex);

                $snrValueObject = new \stdClass;
                $snrValueObject->source = $cableModem->identity->cmts;
                $snrValueObject->index = $interfaceIndex;
                $snrValueObject->name = $interfaceName;
                $snrValueObject->snr = $snrValue;

                $snrValues[] = $snrValueObject;
            }

            $cableModem->upstreamChannels = $snrValues[0];

        }

        $preDSValue = $cableModemSnmpDriver->read("docsis.interface.downstreamChannel.frequency[R]");
        if($preDSValue) {
            $freqValues = array();
            
            foreach($preDSValue as $interface => $frequency) {
                $interfaceIndex = explode(".", $interface);
                $interfaceIndex = array_pop($interfaceIndex);
                $interfaceName = $cableModemSnmpDriver->read('interface.description', $interfaceIndex);
                $interfacePower = $cableModemSnmpDriver->read('docsis.interface.downstreamChannel.power', $interfaceIndex);
                $interfaceSnr = $cableModemSnmpDriver->read('docsis.interface.snr', $interfaceIndex);
                $interfaceMr = $cableModemSnmpDriver->read('docsis.interface.mr', $interfaceIndex);

                $freqValueObject = new \stdClass;
                $freqValueObject->source = $cableModem->identity->ip;
                $freqValueObject->index = $interfaceIndex;
                $freqValueObject->name = $interfaceName;
                $freqValueObject->frequency = $frequency;
                $freqValueObject->power = $interfacePower;
                $freqValueObject->snr = $interfaceSnr;
                $freqValueObject->mr = $interfaceMr;

                $freqValues[] = $freqValueObject;
            }

            $cableModem->downstreamChannels = $freqValues;
        }

        $preUsFreqValue = $cableModemSnmpDriver->read("docsis.interface.upstreamChannel.frequency[R]");
        if($preUsFreqValue) {
            $i = 0;
            foreach($preUsFreqValue as $interface => $frequency) {
                $interfaceIndex = explode(".", $interface);
                $interfaceIndex = array_pop($interfaceIndex);
                $cableModem->upstreamChannels[$i]->frequency = $frequency;
                
                if($cableModem->state->operational->value>1) {
                    $cableModem->upstreamChannels[$i]->power = $cableModemSnmpDriver->read("docsis.interface.upstreamChannel.powerD3", $interfaceIndex);
                } else {
                    $cableModem->upstreamChannels[$i]->power = $cableModemSnmpDriver->read("docsis.interface.upstreamChannel.power", $interfaceIndex);
                }
                
                $i++;
            }
        }

        exec("ping -c 1 " . $cableModem->identity->ip . " | head -n 2 | tail -n 1 | awk '{print $7}'", $ping_time);
        $cableModem->ping = ltrim(strstr($ping_time[0], "="),"=") . " MS";
    



        $cableModemsAnalytics[] = $cableModem;
    }

    file_put_contents(data_path("/cablemodem-analytics.json"), json_encode($cableModemsAnalytics));
}

$end = microtime(true);

$execution_time = ($end - $start);
echo 'Total Execution Time: '.$execution_time.' Seconds';