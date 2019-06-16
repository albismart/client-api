<?php

if(!function_exists('base_path')) { header("HTTP/1.1 404 Not found"); exit(); }

Class CableModem_SNMP_Driver extends SNMP_Driver {
	protected $identity;

	public function __construct($hostname = null, $community = "public", $writeCommunity = "private") {
		$modemAddress = (isset($_GET['mac'])) ? strtolower($_GET['mac']) : null;
		if(!$modemAddress) returnJson(null);

		$sanitizedModemAddress = strtolower($modemAddress);
		$sanitizedModemAddress = str_replace(":","", $sanitizedModemAddress);
		
		$modemData = new \stdClass;
		$modemData->mac = $modemAddress;

		$cmMacHexToDec = explode(":", $modemAddress);
		foreach($cmMacHexToDec as $key => $value) {
			$cmMacHexToDec[$key] = hexdec($value);
		}
		$modemData->dmac = implode(".", $cmMacHexToDec);

		if(isset($_GET['cmts'])) {
			$cmtses = explode(",", $_GET['cmts']);
			foreach($cmtses as $cmts_hostname) {
				if(isset($modemData->ptr)) { continue; }
				$cmtsSnmpDriver = new Cmts_SNMP_Driver($cmts_hostname);
				$foundCableModemPtr = $cmtsSnmpDriver->read('cmts.cableModem.identity.index', $modemData->dmac);
				if($foundCableModemPtr) {
					$modemData->ptr = $foundCableModemPtr;
					$modemData->cmts = $cmts_hostname;
					$modemData->ip = $cmtsSnmpDriver->read('cmts.cableModem.identity.ip', $modemData->ptr);
				}
			}
		}

		$this->hostname = $modemData->ip;
		$this->identity = $modemData;

		parent::__construct($this->hostname, $community, $writeCommunity);

		if($this->vendor) {
			$cableModemVendorMIB = include snmp_path("/cablemodem/vendors/{$this->vendor}.php");
			if(is_array($this->mibs)) {
				$this->mibs = array_merge_recursive_ex($this->mibs, $cableModemVendorMIB);
			}
		}
	}


	/***********************************************
	*	linuxIP/snmp/cablemodem/info/{mac}&action=identity
	*************************************************/
	public function identity() {
		returnJson($this->identity);
	}

	/***********************************************
	*	linuxIP/snmp/cablemodem/info/{mac}
	*************************************************/
	public function info() {
		
		$modemInfo = array(
			"identity" => $this->identity,
			"about" => $this->read("about"),
			"stats" => $this->read("stats"),
		);

		returnJson($modemInfo);
	}

	/***********************************************
	*	linuxIP/snmp/cablemodem/about/{mac}
	*************************************************/
	public function about() {
		returnJson($this->read("about"));
	}

	/***********************************************
	*	linuxIP/snmp/cablemodem/stats/{mac}
	*************************************************/
	public function stats() {
		returnJson($this->read("stats"));
	}

	/***********************************************
	*	linuxIP/snmp/cablemodem/insight/{mac}
	*************************************************/
	public function insight() {

		$insightData = new \stdClass;

		$insightData->identity = $this->identity;

		if($this->identity->cmts) {
			$cmtsSnmpDriver = new Cmts_SNMP_Driver($this->identity->cmts);
			$insightData->state = new \stdClass;
			$insightData->state->status = new \stdClass;
			$insightData->state->status->source = $this->identity->cmts;
			$insightData->state->status->value = $cmtsSnmpDriver->read('cmts.cableModem.status', $this->identity->ptr);

			$insightData->state->operational = new \stdClass;
			$insightData->state->operational->source = $this->hostname;
			$insightData->state->operational->value = $this->read('docsis.cableModem.status');
			$insightData->state->operational->config = $this->read('docsis.cableModem.configFilename');


			$downstreamChannelIndex = $cmtsSnmpDriver->read('cmts.cableModem.downstreamChannel', $this->identity->ptr);

			$insightData->primaryDownstream = new \stdClass;
			$insightData->primaryDownstream->source = $this->identity->cmts;
			$insightData->primaryDownstream->index = $downstreamChannelIndex;
			$insightData->primaryDownstream->name = $cmtsSnmpDriver->read('interface.description', $downstreamChannelIndex);

			$preSnrValue = $cmtsSnmpDriver->read('docsis.cmts.cableModem.upstreamChannelsD3[R]', $this->identity->ptr);

			if($preSnrValue) {
				$snrValues = array();

				foreach($preSnrValue as $interface => $snrValue) {
					$interfaceIndex = explode(".", $interface);
					$interfaceIndex = array_pop($interfaceIndex);
					$interfaceName = $cmtsSnmpDriver->read('interface.description', $interfaceIndex);

					$snrValueObject = new \stdClass;
					$snrValueObject->source = $this->identity->cmts;
					$snrValueObject->index = $interfaceIndex;
					$snrValueObject->name = $interfaceName;
					$snrValueObject->snr = $snrValue;

					$snrValues[] = $snrValueObject;
				}

				$insightData->upstreamChannels = $snrValues;
			} else {
				
				//Docsis 2.0 Fallback

				$preSnrValue = $cmtsSnmpDriver->read('interface.upstreamChannels[R]', $this->identity->ptr);
				$snrValues = array();
				
				foreach($preSnrValue as $interface => $snrValue) {
					$interfaceIndex = explode(".", $interface);
					$interfaceIndex = array_pop($interfaceIndex);
					$interfaceName = $cmtsSnmpDriver->read('interface.description', $interfaceIndex);

					$snrValueObject = new \stdClass;
					$snrValueObject->source = $this->identity->cmts;
					$snrValueObject->index = $interfaceIndex;
					$snrValueObject->name = $interfaceName;
					$snrValueObject->snr = $snrValue;

					$snrValues[] = $snrValueObject;
				}

				$insightData->upstreamChannels = $snrValues[0];

			}

			$preDSValue = $this->read("docsis.interface.downstreamChannel.frequency[R]");
			if($preDSValue) {
				$freqValues = array();
				
				foreach($preDSValue as $interface => $frequency) {
					$interfaceIndex = explode(".", $interface);
					$interfaceIndex = array_pop($interfaceIndex);
					$interfaceName = $this->read('interface.description', $interfaceIndex);
					$interfacePower = $this->read('docsis.interface.downstreamChannel.power', $interfaceIndex);
					$interfaceSnr = $this->read('docsis.interface.snr', $interfaceIndex);
					$interfaceMr = $this->read('docsis.interface.mr', $interfaceIndex);

					$freqValueObject = new \stdClass;
					$freqValueObject->source = $this->hostname;
					$freqValueObject->index = $interfaceIndex;
					$freqValueObject->name = $interfaceName;
					$freqValueObject->frequency = $frequency;
					$freqValueObject->power = $interfacePower;
					$freqValueObject->snr = $interfaceSnr;
					$freqValueObject->mr = $interfaceMr;

					$freqValues[] = $freqValueObject;
				}
	
				$insightData->downstreamChannels = $freqValues;
			}

			$preUsFreqValue = $this->read("docsis.interface.upstreamChannel.frequency[R]");
			if($preUsFreqValue) {
				$i = 0;
				foreach($preUsFreqValue as $interface => $frequency) {
					$interfaceIndex = explode(".", $interface);
					$interfaceIndex = array_pop($interfaceIndex);
					$insightData->upstreamChannels[$i]->frequency = $frequency;
					
					if($insightData->state->operational->value>1) {
						$insightData->upstreamChannels[$i]->power = $this->read("docsis.interface.upstreamChannel.powerD3", $interfaceIndex);
					} else {
						$insightData->upstreamChannels[$i]->power = $this->read("docsis.interface.upstreamChannel.power", $interfaceIndex);
					}
					
					$i++;
				}
			}

			exec("ping -c 1 " . $this->identity->ip . " | head -n 2 | tail -n 1 | awk '{print $7}'", $ping_time);
			$insightData->ping = ltrim(strstr($ping_time[0], "="),"=") . " MS";
		}

		returnJson($insightData);
	}

}

?>