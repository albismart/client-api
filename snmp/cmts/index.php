<?php

Class Cmts extends SNMP_Driver {
	/***********************************************
	*	linuxIP/snmp/?device=cmts&action=info&hostname={hostname}
	*************************************************/
	public function info() {
		$oidStatus = $this->read(array(
			'name' => $this->mibs['cmtsName'], 
			'description' => $this->mibs['cmtsDescription'], 
			'uptime' => $this->mibs['cmtsUptime'], 
			'cpuUsage' => $this->mibs['cmtsCpuUsage'], 
			'temperatureIn' => $this->mibs['cmtsTemperatureIn'], 
			'temperatureOut' => $this->mibs['cmtsTemperatureOut']
		));
		if($oidStatus) {
			return json_encode($oidStatus);
		} else {
			return "Operation failed";
		}
	}
}

?>