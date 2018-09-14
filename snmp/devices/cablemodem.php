<?php

class Cablemodem extends SNMP_Driver {
	/***********************************************
	*	linuxIP/snmp/cablemodem/info/{mac}
	*************************************************/
	public function info() {
		$ip = (isset($_GET['ip'])) ? $_GET['ip'] : null;
		if($ip) {
			$oidStatus = $this->read(['name' => $this->mibs['cmtsName'], 
									  'description' => $this->mibs['cmtsDescription'], 
									  'uptime' => $this->mibs['cmtsUptime'], 
									  'cpuUsage' => $this->mibs['cpuUsage'], 
									  'temperatureIn' => $this->mibs['cmtsTemperatureIn'], 
									  'temperatureOut' => $this->mibs['cmtsTemperatureOut']]);
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

/*
function disconnectModem($ip) { snmpset($ip, "private", ".1.3.6.1.2.1.69.1.1.3.0", "i",1); }
case 'signal-single':
		if(isset($_GET['wifi'])) {

				$username = urldecode($_GET['username']);
				$community = urldecode($_GET['community']);
				$oidList = oid_list("pppoe");

				$result = array();

				$connection = mysql_connect("localhost", "albismart", DBPASS);
				if($connection) {

					$onlineRecords = array();
					mysql_select_db("albismart") or exit();
					$records = mysql_query("SELECT framedipaddress,acctoutputoctets,acctinputoctets,nasipaddress,calledstationid FROM radacct WHERE (AcctStopTime IS NULL) AND username='$username' ORDER BY RadAcctId ASC LIMIT 1");

					$cip = ""; $download = 0; $upload = 0; $nas = ""; $interface = "";

					while ($record = mysql_fetch_array($records)) {
						$cip = $record['framedipaddress'];
						$download = $record['acctoutputoctets'];
						$upload = $record['acctinputoctets'];
						$nas = $record['nasipaddress'];
						$interface = $record['calledstationid'];
					}

					$interfaceList = array();
					$interfaceListCountRaw = shell_exec("snmpwalk -v 1 -On -c {$community} {$cip} IF-MIB::ifNumber.0");
					$interfaceListCount = explode("INTEGER: ", $interfaceListCountRaw); $interfaceListCount = array_pop($interfaceListCount);
					for($i=1;$i<=$interfaceListCount;$i++) {
						$interfaceNameRaw = shell_exec("snmpwalk -r1 -t1 -v 1 -On -c {$community} {$cip} IF-MIB::ifDescr.{$i}");
						$interfaceName = explode("STRING: ", $interfaceNameRaw); $interfaceName = array_pop($interfaceName);
						$interfaceList[trim($interfaceName)] = $i;
					}

					foreach ($oidList as $oid) {

						$oidvalue = null;

						preg_match_all('/( { ( (?: [^{}]* )* ) } )/x', $oid->oid, $params);
						$params = array_filter($params);

						if(count($params)>0) {
							$interfaceIndexReplace = array_shift(array_shift($params));
							$interfaceIndex = array_pop(array_pop($params));
							$oid->oid = str_replace($interfaceIndexReplace,$interfaceList[$interfaceIndex],$oid->oid);
						}

						if(strtolower($oid->oid)=="[upstreamchannel]") { $oidvalue = (isset($upchannel)) ? ($upchannel+1) : ""; }
						if(strtolower($oid->oid)=="[pip]") { $oidvalue = $cip; }
						if(strtolower($oid->oid)=="[download]") { $oidvalue = $download; }
						if(strtolower($oid->oid)=="[upload]") { $oidvalue = $upload; }
						if(strtolower($oid->oid)=="[nas]") { $oidvalue = $nas; }
						if(strtolower($oid->oid)=="[interface]") { $oidvalue = $interface; }

						if(!$oidvalue) {

							if(!isset($oid->snmp)) $oid->snmp = 'snmpget';

							if($oid->snmp=='snmpget') {
								$oidvalue = snmpget($cip, 'public', $oid->oid, $delay, $retry);
							} else {
								$oidvalueshell = shell_exec($oid->snmp." -v2c -c public -On -r 2 -t 2 {$cip} {$oid->oid}");

								if($oid->snmp=='snmpgetnext') {
									$nextoid = $oid->oid; $finalValue = "";

									while(strpos($nextoid, $oid->oid)!==false) {
										$getnextres = explode(" ", $oidvalueshell);
										$partialValue = $getnextres[1];

										if(trim($partialValue)!='other') {
											$nextoid = $getnextres[0];
											$finalValue .= trim($partialValue) .";";
											$oidvalueshell = shell_exec($oid->snmp." -v2c -c public -OnqU {$cip} {$nextoid}");
										}
									}
									$oidvalue = rtrim($finalValue, ";");
								} elseif($oid->snmp=='snmpbulkwalk') {
									$oidvalues = explode("\n", $oidvalueshell);
									foreach ($oidvalues as $ov) {
										if($ov) {
											$ov = explode(" = INTEGER:", $ov);
											$ov = array_pop($ov);
											$oidvalue .= trim($ov) . ";";
										}
									}
									$oidvalue = rtrim($finalValue, ";");
								}
							}
						}

						$oidvalue = explode("INTEGER: ", $oidvalue); $oidvalue = array_pop($oidvalue);
						$oidvalue = explode("STRING: ", $oidvalue); $oidvalue = array_pop($oidvalue);
						$oidvalue = explode("Gauge32: ", $oidvalue); $oidvalue = array_pop($oidvalue);
						$oidvalue = explode("Counter32: ", $oidvalue); $oidvalue = array_pop($oidvalue);
						$oidvalue = explode("Timeticks: ", $oidvalue); $oidvalue = array_pop($oidvalue);
						$oidvalue = str_replace('"', '', $oidvalue);
						$oidvalue = trim($oidvalue);

						$result[sanitize($oid->label)] = $oidvalue;
					}

				}

				echo json_encode($result); exit();

			} else {
				$cip = urldecode($_GET['ip']); $exip = explode('.',$cip);
				$dm = urldecode($_GET['dm']);
				$cmts = urldecode($_GET['cmtsIp']);
				$community = urldecode($_GET['community']);

				$docsis = 3;
				$cid = 'No';
				if($dm!='') {
					$cid = shell_exec("snmpget -v 2c -Oqv -c {$community} {$cmts} 1.3.6.1.2.1.10.127.1.3.7.1.2.$dm");
					$cid = rtrim($cid, "\n");
				}

				$oidList = oid_list();

				$result = array("cid"=>$cid);
				$usnrInterfaces = array();

				if($oidList) {

					foreach ($oidList as $oid) {

						if(substr($cid,0,2)=='No') {
							$result[sanitize($oid->label)] = "offline";
							continue;
						}

						$delay = 100000;
						$retry = 2;
						$oidvalue = null;

						if(strtolower($oid->oid)=="1.3.6.1.2.1.10.127.1.1.1.1.6.3" || strtolower($oid->oid)=="1.3.6.1.2.1.10.127.1.2.2.1.3.2") {
							$delay = 2000000;
							$retry = 3;
						}

						if(strtolower($oid->oid)=="[interface]") {
							if(count($usnrInterfaces)>0) {
								$interface_id_key = sanitize($oid->label)."_id";
								$upchannel = '';
								foreach ($usnrInterfaces as $usnrinid) {
									$result[$interface_id_key] .= $usnrinid . ";";
									$interfaceRaw = snmpget($cmts, $community, '1.3.6.1.2.1.2.2.1.2.'.$usnrinid, $delay, $retry);
									$interface = explode('STRING: ',$interfaceRaw);
									$uctemp = explode("upstream",$interface[1]);
									$uctemp = (isset($uctemp[1])) ? $uctemp[1] + 1 : "";
									if($uctemp!="") $upchannel .= $uctemp . ";";
									$oidvalue .= $interface[1]."|$usnrinid;";
								}
								$upchannel = rtrim($upchannel, ";");
								$oidvalue = rtrim($oidvalue, ";");
								$result[$interface_id_key] = rtrim($result[$interface_id_key], ";");
							} else {
								$interface_id = snmpget($cmts, $community, '1.3.6.1.2.1.10.127.1.3.3.1.5.'.$cid, $delay, $retry);
								$interface_id = explode("INTEGER:",$interface_id); $inid = trim($interface_id[1]);
								$interface_id_key = sanitize($oid->label)."_id";
								$result[$interface_id_key] = $inid;
								$interface_id = $inid;

								$interfaceRaw = snmpget($cmts, $community, '1.3.6.1.2.1.2.2.1.2.'.$inid, $delay, $retry);
								$interface = explode('STRING: ',$interfaceRaw);
								$upchannel = explode("upstream",$interface[1]);
								$upchannel = (isset($upchannel[1])) ? $upchannel[1] + 1 : "";

								$oidvalue = $interface[1].";$inid";
							}
						}

						if(isset($interface_id_key) && strpos($oid->oid,'{'.$interface_id_key.'}')!==false) $oid->oid = str_replace('{'.$interface_id_key.'}',$interface_id, $oid->oid);
						if(strpos($oid->oid,"{cid}")!==false) $oid->oid = str_replace("{cid}",$cid, $oid->oid);

						if(strtolower($oid->oid)=="[upstreamchannel]") { $oidvalue = (isset($upchannel)) ? $upchannel : ""; }
						if(strtolower($oid->oid)=="[cmip]") { $oidvalue = $cip; }

						if(strtolower($oid->oid)=="[ping]") {
							exec("ping -c 1 " . $cip . " | head -n 2 | tail -n 1 | awk '{print $7}'", $ping_time);
							$oidvalue = (is_array($ping_time[0])) ? implode(',',$ping_time[0]) : $ping_time[0];
							$oidvalue = explode("time=",$oidvalue); $oidvalue = array_pop($oidvalue);
						}

						if(strtolower($oid->oid)=="[cpe]") {
							$cpe_ip_status_raw = snmprealwalk($cip, 'public', "1.3.6.1.2.1.17.4.3.1.3",$delay,$retry);
							$oidvalue = array();

							foreach ($cpe_ip_status_raw as $key => $value) :
								 $cpe_ip_mac_decimal = explode("SNMPv2-SMI::mib-2.17.4.3.1.3.", $key); $cpe_ip_mac_decimal = $cpe_ip_mac_decimal[1];
								 $cpe_mac_raw = snmpget($cip, 'public', "1.3.6.1.2.1.17.4.3.1.1.{$cpe_ip_mac_decimal}",$delay,$retry);
								 $cpe_mac_raw = explode("Hex-STRING: ",$cpe_mac_raw);
								 $cpe_ip_address_raw = snmpget($cmts, $community, "1.3.6.1.4.1.9.9.116.1.3.1.1.3.{$cpe_ip_mac_decimal}");
								 $cpe_ip_address_raw = explode("IpAddress: ",$cpe_ip_address_raw);
								 $cpe_mac_raw = rtrim($cpe_mac_raw[1]);
								 $cpe_mac_raw = str_replace(" ", ":", $cpe_mac_raw);

								 $value = explode("INTEGER: ", $value); $value = array_pop($value);

								 $type = "Other";
 								 switch ($value) {
 								 	case 2: $type = "Invalid"; break;
 								 	case 3: $type = "External CPE"; break;
 								 	case 4: $type = "Internal CPE"; break;
 								 	case 5: $type = "Management"; break;
 								 }

								 $ipSet = new stdClass;
								 $ipSet->type = $type;
								 $ipSet->ip = $cpe_ip_address_raw[1];
								 $ipSet->mac = $cpe_mac_raw;

								 $oidvalue[] = $ipSet;
							endforeach;
						}


						if(strpos($oid->oid,'1.3.6.1.4.1.4491.2.1.20.1.4.1.4')!==false || strpos($oid->oid=='1.3.6.1.2.1.10.127.1.3.3.1.13')!==false) {
							if($docsis<=3) {
								if(strpos($oid->oid,'1.3.6.1.4.1.4491.2.1.20.1.4.1.4')!==false) $oid->oid = '[cmts].1.3.6.1.2.1.10.127.1.3.3.1.13.'.$cid;
							} else {
								if(strpos($oid->oid,'1.3.6.1.2.1.10.127.1.3.3.1.13')!==false) $oid->oid = '[cmts].1.3.6.1.4.1.4491.2.1.20.1.4.1.4.'.$cid;
							}
						}


						if(strpos($oid->oid,"[cmts].")!==false) {

							if(isset($oid->snmp) && $oid->snmp=='snmpbulkwalk') {
								$oid->oid = str_replace("[cmts].",$cmts." ", $oid->oid);
								$oidvalueshell = shell_exec($oid->snmp." -v2c -c {$community} -m all -r 2 -t 2 {$oid->oid}");
								$oidvalues = explode("\n", $oidvalueshell);
								foreach ($oidvalues as $ov) {
									if($ov) {
										$ov = str_replace("TenthdB",'', $ov);
										$ov = explode(" = INTEGER: ", $ov);
										if(strpos($oid->oid,'1.3.6.1.4.1.4491.2.1.20.1.4.1.4')!==false) {
											$if = array_shift($ov);
											$if = explode($cid.".",$if);
											$if = array_pop($if);
											$usnrInterfaces[] = trim($if);
										}
										$ov = array_pop($ov);
										$oidvalue .= trim($ov) . ";";
									}
								}
								$oidvalue = rtrim($oidvalue, ";");
							} else {
								$oid->oid = str_replace("[cmts].","", $oid->oid);
								$oidvalue = snmpget($cmts, $community, $oid->oid, $delay, $retry);
							}
						}

						if($oid->oid=='1.3.6.1.4.1.4491.2.1.20.1.2.1.1' || $oid->oid=='1.3.6.1.2.1.10.127.1.2.2.1.3.2') {
							if($docsis<=3) {
								if($oid->oid=='1.3.6.1.4.1.4491.2.1.20.1.2.1.1') $oid->oid = '1.3.6.1.2.1.10.127.1.2.2.1.3.2';
							} else {
								if($oid->oid=='1.3.6.1.2.1.10.127.1.2.2.1.3.2') $oid->oid = '1.3.6.1.4.1.4491.2.1.20.1.2.1.1';
							}
						}

						if(!$oidvalue) {

							if(!isset($oid->snmp)) $oid->snmp = 'snmpget';

							if($oid->snmp=='snmpget') {
								$oidvalue = snmpget($cip, 'public', $oid->oid, $delay, $retry);
							} else {
								$oidvalueshell = shell_exec($oid->snmp." -v2c -c public -m all -r 2 -t 2 {$cip} {$oid->oid}");

								if($oid->snmp=='snmpgetnext') {
									$nextoid = $oid->oid; $finalValue = "";

									while(strpos($nextoid, $oid->oid)!==false) {
										$getnextres = explode(" ", $oidvalueshell);
										$partialValue = $getnextres[1];

										if(trim($partialValue)!='other') {
											$nextoid = $getnextres[0];
											$finalValue .= trim($partialValue) .";";
											$oidvalueshell = shell_exec($oid->snmp." -v2c -c public -OnqU {$cip} {$nextoid}");
										}
									}
									$oidvalue = rtrim($finalValue, ";");
								} elseif($oid->snmp=='snmpbulkwalk') {
									$oidvalues = explode("\n", $oidvalueshell);
									foreach ($oidvalues as $ov) {
										if($ov) {
											$ov = explode(" = INTEGER:", $ov);
											$ov = array_pop($ov);
											$oidvalue .= trim($ov) . ";";
										}
									}
									$oidvalue = rtrim($oidvalue, ";");
								}
							}
						}

						if(!is_array($oidvalue)) { $oidvalue = explode("VENDOR:", $oidvalue); $oidvalue = array_pop($oidvalue); }
						if(!is_array($oidvalue)) { $oidvalue = explode("INTEGER: ", $oidvalue); $oidvalue = array_pop($oidvalue); }
						if(!is_array($oidvalue)) { $oidvalue = explode("STRING: ", $oidvalue); $oidvalue = array_pop($oidvalue); }
						if(!is_array($oidvalue)) { $oidvalue = explode("Gauge32: ", $oidvalue); $oidvalue = array_pop($oidvalue); }
						if(!is_array($oidvalue)) { $oidvalue = explode("Counter32: ", $oidvalue); $oidvalue = array_pop($oidvalue); }
						if(!is_array($oidvalue)) { $oidvalue = explode("Timeticks: ", $oidvalue); $oidvalue = array_pop($oidvalue); }
						if(!is_array($oidvalue)) { $oidvalue = str_replace('"', '', $oidvalue); }
						if(!is_array($oidvalue)) { $oidvalue = trim($oidvalue); }
						if(!is_array($oidvalue) && $oid->oid=='1.3.6.1.2.1.10.127.1.1.5.0') { $docsis = $oidvalue; }

						$result[sanitize($oid->label)] = $oidvalue;
					}

				}

				echo json_encode($result); exit();

			}
	break;
*/

?>