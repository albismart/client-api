<?php

Class Cmts extends SNMP_Driver {
	/***********************************************
	*	linuxIP/snmp/cmts/info/{mac}
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
}

?>