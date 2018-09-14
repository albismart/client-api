<?php

	case 'do-nas':

		$delete = urldecode($_GET['delete']);
		$oldip = urldecode($_GET['oldip']);
		$ip = urldecode($_GET['ip']);
		$name = urldecode($_GET['name']);
		$secret = urldecode($_GET['secret']);
		$community = urldecode($_GET['community']);
		$description = urldecode($_GET['description']);

		mysql_connect("localhost", "albismart", DBPASS) or die(mysql_error());
		mysql_select_db("albismart") or die(mysql_error());

		$findIp = ($oldip) ? $oldip : $ip;
		$currentNas = mysql_query("SELECT id FROM nas WHERE nasname='{$findIp}' LIMIT 1");
		if(mysql_num_rows($currentNas)==0) $currentNas = null;
		if($currentNas) { $nasId = mysql_result($currentNas,0); }

		if(!$currentNas) {
			mysql_query("INSERT INTO nas (nasname, shortname,secret,community	,description) VALUES ('{$ip}','{$name}','{$secret}','{$community}','{$description}')");
			exit();
		}

		if(isset($nasId) && $delete==1) {
			mysql_query("DELETE FROM nas WHERE id='{$nasId}' ");
			exit();
		}

		if(isset($nasId)) {
			mysql_query("UPDATE nas SET nasname='{$ip}',shortname='{$name}',secret='{$secret}',community='{$community}',description='{$description}' WHERE id = '{$nasId}' ");
			exit();
		}

	break;


	case 'online-feed':

		$connection = mysql_connect("localhost", "albismart", DBPASS);
		if($connection) {
			$onlineRecords = array();
			mysql_select_db("albismart") or exit();
			$records = mysql_query("SELECT
				radacct.username,
				radacct.nasipaddress,
				radacct.acctstarttime,
				radacct.acctsessiontime,
				radacct.acctinputoctets,
				radacct.acctoutputoctets,
				radacct.calledstationid,
				radacct.callingstationid,
				radacct.framedipaddress,
				aid.id
			FROM radacct INNER JOIN aid ON aid.username = radacct.username
			WHERE (radacct.AcctStopTime IS NULL) ORDER BY radacct.RadAcctId ASC");

			while ($record = mysql_fetch_array($records)) {
				$onlineRecord = new stdClass();
				$onlineRecord->username = $record['username'];
				$onlineRecord->nas = $record['nasipaddress'];
				$onlineRecord->startTime = $record['acctstarttime'];
				$onlineRecord->onlineTime = ($record['acctsessiontime']>0) ? readableSeconds($record['acctsessiontime']) : 0;
				$onlineRecord->download = ($record['acctoutputoctets']>0) ? bytesToHigher($record['acctoutputoctets']) : 0;
				$onlineRecord->upload = ($record['acctinputoctets']>0) ? bytesToHigher($record['acctinputoctets']) : 0;
				$onlineRecord->mac = $record['callingstationid'];
				$onlineRecord->ip = $record['framedipaddress'];
				$onlineRecord->int = $record['calledstationid'];
				$onlineRecords[$record['id']] = $onlineRecord;
			}

			echo json_encode($onlineRecords);
		}

	break;

	case 'online-status':
		if(!isset($_GET['user'])) exit();
		$user = urldecode($_GET['user']);
		$connection = mysql_connect("localhost", "albismart", DBPASS);
		if($connection) {
			mysql_select_db("albismart") or exit();
			$records = mysql_query("SELECT username	FROM radacct WHERE (acctstoptime IS NULL) AND username = '{$user}' LIMIT 1");
			if(mysql_num_rows($records)>0) echo "ONLINE";
		}
	break;

	case 'pppoe-traffic':
		if(!isset($_GET['username'])) die("Missing username!");
		$connection = mysql_connect("localhost", "albismart", DBPASS);
		if($connection) {

			$year = date("Y");
			$group = "YEAR";
			if(isset($_GET['year'])) :
				$year = mysql_real_escape_string($_GET['year']);
				$group = "MONTH";
			endif;

			if(isset($_GET['month'])) :
				$year .= "-" . mysql_real_escape_string($_GET['month']);
				$group = "DAY";
			endif;

			if(isset($_GET['day'])) $year .= "-" . mysql_real_escape_string($_GET['day']);

			$periodcond = " AND acctstarttime RLIKE '{$year}' ";
			$usernamecond = " username='".mysql_real_escape_string($_GET['username'])."' ";

			if(!isset($_GET['day'])) {
				$onlineRecords = array();
				mysql_select_db("albismart") or exit();
				$records = mysql_query("SELECT acctstarttime,username, SUM( acctsessiontime ) AS onlinetime, SUM( acctinputoctets ) AS upload,SUM(acctoutputoctets) AS download,SUM(acctinputoctets)+SUM(acctoutputoctets) as total
				FROM radacct WHERE {$usernamecond} {$periodcond} GROUP BY {$group}(acctstarttime)");
				while ($record = mysql_fetch_array($records)) {
					$onlineRecord = new stdClass();
					$onlineRecord->onlineTime = ($record['onlinetime']>0) ? readableSeconds($record['onlinetime']) : 0;
					$onlineRecord->download = ($record['download']>0) ? bytesToHigher($record['download']) : 0;
					$onlineRecord->upload = ($record['upload']>0) ? bytesToHigher($record['upload']) : 0;
					$onlineRecord->total = ($record['total']>0) ? bytesToHigher($record['total']) : 0;
					$onlineRecord->period = substr($record['acctstarttime'],0,4);
					if(isset($_GET['year'])) $onlineRecord->period = substr($record['acctstarttime'],0,7);
					if(isset($_GET['month'])) $onlineRecord->period = substr($record['acctstarttime'],0,10);
					array_push($onlineRecords,$onlineRecord);
				}
			} else {
				$onlineRecords = array();
				mysql_select_db("albismart") or exit();
				$records = mysql_query("SELECT radacctid,username,acctstarttime as startTime,acctstoptime as endTime,acctsessiontime as onlinetime,acctoutputoctets as download,acctinputoctets as upload,(acctoutputoctets+acctinputoctets) as total,framedipaddress as ip,callingstationid as mac,nasipaddress as nas,calledstationid as interface
				FROM radacct WHERE {$usernamecond} {$periodcond}");
				while ($record = mysql_fetch_array($records)) {
					$onlineRecord = new stdClass();
					$onlineRecord->ip = $record["ip"];
					$onlineRecord->mac = $record["mac"];
					$onlineRecord->nas = $record["nas"];
					$onlineRecord->interface = $record["interface"];
					$onlineRecord->onlineTime = ($record['onlinetime']>0) ? readableSeconds($record['onlinetime']) : 0;
					$onlineRecord->download = ($record['download']>0) ? bytesToHigher($record['download']) : 0;
					$onlineRecord->upload = ($record['upload']>0) ? bytesToHigher($record['upload']) : 0;
					$onlineRecord->total = ($record['total']>0) ? bytesToHigher($record['total']) : 0;
					$onlineRecord->period = $record['startTime'] . " - " .$record['endTime'];
					array_push($onlineRecords,$onlineRecord);
				}
			}

			echo json_encode($onlineRecords);
		}
	break;