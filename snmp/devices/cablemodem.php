<?php

class Cablemodem extends SNMP_Driver {
	/***********************************************
	*	linuxIP/snmp/cablemodem/info/{mac}
	*************************************************/
	public function info() {
		$ip = (isset($_GET['ip'])) ? $_GET['ip'] : null;
		if($ip) {
			$oidStatus = $this->read(array('name' => $this->mibs['cmtsName'], 
									  'description' => $this->mibs['cmtsDescription'], 
									  'uptime' => $this->mibs['cmtsUptime'], 
									  'cpuUsage' => $this->mibs['cpuUsage'], 
									  'temperatureIn' => $this->mibs['cmtsTemperatureIn'], 
									  'temperatureOut' => $this->mibs['cmtsTemperatureOut']));
			if($oidStatus) {
				echo json_encode($oidStatus);
			} else {
				echo "Operation failed";
			}
	}
	}
	/***********************************************
	*	linuxIP/snmp/modem/logs/{mac}
	*************************************************/
	public function logs() {
		$ip = urldecode($_GET['ip']);
		if($ip) {
			$result = shell_exec("snmpbulkwalk -v2c -c public -m all -Onvq {$ip} .1.3.6.1.2.1.69.1.5.8.1.7");
			if(is_array($result)) $result = implode("<br/>", $result);
			$result = str_replace("\n", "<br/>", $result);
			echo $result;
		}
	}
}

?>