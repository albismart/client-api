<?php

$basePath = realpath(dirname(__FILE__));
$basePath = strstr($basePath, "client-api/", true) . "client-api";

include_once $basePath . "/bootstrap.php";
include_once pppoe_path("/mysql.php");
validateApiRequest();

Class PPPoE_MySQL_Driver extends MySQL_Driver {

	public function update() {
		$username = requestParam("username");
		$password = requestParam("password");
		$albismart_id = requestParam("albismart_id");
		$limit = requestParam("limit");
		$simuse = requestParam("simuse");
		$mac = requestParam("mac");
		$pool = requestParam("pool");
		$cpeip = requestParam("cpeip");

		if($username && $password) {
			$this->remove("radcheck")->where("username", $username)->where("attribute", "Cleartext-Password")->all();
			$authColumns = array("username" => $username, "attribute" => "Cleartext-Password", "op" => ":=", "value" => $password);
			$this->create("radcheck")->columns($authColumns)->save();
		}

		if($username && $limit) {
			$this->remove("radreply")->where("username", $username)->all();
			$limitColumns = array("username" => $username, "attribute" => "Mikrotik-Rate-Limit", "op" => "=", "value" => $limit);
			$this->create("radreply")->columns($limitColumns)->save();
		}

		if($username && $albismart_id) {
			$this->remove("aid")->where("username", $username)->all();
			$aidColumns = array("username" => $username, "id" => $albismart_id);
			$this->create("aid")->columns($aidColumns)->save();
		}

		if($username && $simuse) {
			$this->remove("radcheck")->where("username", $username)->where("attribute", "Simultaneous-Use")->all();
			$simUseColumns = array("username" => $username, "attribute" => "Simultaneous-Use", "op" => ":=", "value" => $simuse);
			$this->create("radcheck")->columns($simUseColumns)->save();
		}

		if($username && $pool) {
			$this->remove("radreply")->where("username", $username)->where("attribute", "Framed-Pool")->all();
			$poolColumns = array("username" => $username, "attribute" => "Framed-Pool", "op" => "=", "value" => $pool);
			$this->create("radreply")->columns($poolColumns)->save();
		}

		if($username && isValidMacAddress($mac)) {
			$this->remove("radcheck")->where("username", $username)->where("attribute", "Calling-Station-Id")->all();
			$macColumns = array("username" => $username, "attribute" => "Calling-Station-Id", "op" => ":=", "value" => $mac);
			$this->create("radcheck")->columns($macColumns)->save();
		}

		if($username && isValidIPAddress($cpeip)) {
			$this->remove("radreply")->where("username", $username)->where("attribute", "Framed-IP-Address")->all();
			$cpeipColumns = array("username" => $username, "attribute" => "Framed-IP-Address", "op" => "=", "value" => $cpeip);
			$this->create("radreply")->columns($cpeipColumns)->save();
		}

		$this->disconnect(false);
		
		returnJson(true);
	}

	public function remove() {
		$username = requestParam("username");

		if($username) {
			$this->remove("radcheck")->where("username", $username)->all();
			$this->remove("radreply")->where("username", $username)->all();
			$this->remove("aid")->where("username", $username)->all();

			$this->disconnect(false);
			returnJson(true);
		}

		returnJson("");
	}

	public function disconnect($returnJson = true) {
		$username = requestParam("username");

		if($username) {
			$activeConnections = $this->get("nas")->innerJoin("radacct ON nas.nasname = radacct.nasipaddress")->select("radacct.nasipaddress as nasip, nas.secret as secret")->isNull("acctstoptime")->where("radacct.username", $username)->all();
			if(is_array($activeConnections)) {
				foreach($activeConnections as $activeConnection) {
					$disconnectCommand = "echo User-Name={$username} | radclient -x ";
					$disconnectCommand.= $activeConnection->nasip . ":1700 disconnect " . $activeConnection->secret;
					exec($disconnectCommand);
				}
			}

			if($returnJson) { returnJson(true); }
		} else { returnJson(""); }
	}

	public function online() {
		$username = (isset($_GET['username'])) ? urldecode($_GET['username']) : null;
		if($username) {
			$result = $this->get("radacct")->select("acctstarttime as connectedAt")->isNull("acctstoptime")->where("username", $username)->first("acctstarttime");
		} else {
			$columns = array(
				"radacct.username",
				"radacct.nasipaddress as nas",
				"radacct.acctsessiontime as onlineTime",
				"radacct.acctoutputoctets as download",
				"radacct.acctinputoctets as upload",
				"radacct.callingstationid as mac",
				"radacct.framedipaddress as ip",
				"radacct.calledstationid as interface",
				"aid.id as albismart_id",
			);
			$result = $this->get("radacct")->innerJoin("aid ON aid.username = radacct.username")
						   ->columns($columns)->isNull("radacct.AcctStopTime")->order("radacct.RadAcctId", "ASC")->all();
		}

		returnJson($result);
	}

	public function nas() {
		if(!requestParam("ip")) {
			returnJson($this->get("nas")->all());
		}

		$nas = $this->get("nas")->where("nasname", requestParam("ip"))->first();
		if(!isset($_POST["ip"])) { returnJson($nas); }

		if(requestParam("delete") && $nas) {
			$result = ($this->remove("nas")->where("nasname", $nas->nasname)->save()) ? $nas : null;
			returnJson($result);
		}

		$nasData = array();
		$nasData['nasname'] = ($nas && requestParam("newIp")) ? requestParam("newIp") : requestParam("ip");
		if(requestParam("name")) { $nasData['shortname'] = requestParam("name"); }
		if(requestParam("secret")) { $nasData['secret'] = requestParam("secret"); }
		if(requestParam("community")) { $nasData['community'] = requestParam("community"); }
		if(requestParam("description")) { $nasData['description'] = requestParam("description"); }

		if($nas) {
			if($nas->nasname!=$nasData['nasname'] && $this->get("nas")->where("nasname", $nasData['nasname'])->first()) { returnJson(""); }
			$this->update("nas")->values($nasData)->where("nasname", $nasData['nasname'])->save();
			returnJson($this->get("nas")->where("nasname", $nasData['nasname'])->first());
		} else {
			if($this->get("nas")->where("nasname", $nasData['nasname'])->first()) { returnJson(""); }
			$this->create("nas")->values($nasData)->save();
			$nas = $this->get("nas")->where("nasname", $nasData['nasname'])->first();
			returnJson($nas);
		}
	}

	public function traffic() {
		$username = requestParam("username");
		$timeframe = (requestParam("year")) ? requestParam("year") : date("Y");
		if(requestParam("month")) { $timeframe .= "-" . requestParam("month"); }
		if(requestParam("day")) { $timeframe .= "-" . requestParam("day"); }
		$group = (requestParam("month")) ? "DAY" : (requestParam("year") ? "MONTH" : "YEAR");
		$timeframeSubstr = (requestParam("month")) ? "1,10" : (requestParam("year") ? "1,7" : "1,4");

		$columns = array(
			"acctstarttime AS connectedAt",
			"SUM(acctsessiontime) AS onlineTime", 
			"SUM(acctinputoctets) AS upload",
			"SUM(acctoutputoctets) AS download",
			"SUM(acctinputoctets) + SUM(acctoutputoctets) as total",
			"SUBSTR(acctstarttime, {$timeframeSubstr}) as timeframe",
		);

		$this->get("radacct")->columns($columns)->where("username", $username)->where("acctstarttime", $timeframe, "RLIKE")->group($group . "(acctstarttime)");

		if(requestParam("day")) {
			$columns = array(
				"acctstarttime AS connectedAt",
				"acctstoptime AS disconnectedAt", 
				"acctsessiontime AS onlineTime",
				"acctinputoctets AS upload",
				"acctoutputoctets AS download",
				"acctinputoctets + acctoutputoctets as total",
				"framedipaddress as ip",
				"callingstationid as mac",
				"nasipaddress as nas",
				"calledstationid as interface",
				"CONCAT(acctstarttime, ' - ', acctstoptime) as timeframe",
			);
			$this->get("radacct")->columns($columns)->where("username", $username)->where("acctstarttime", $timeframe, "RLIKE");
		}

		returnJson($this->fetchAll());
	}

}

$action = (isset($_GET["action"])) ? $_GET["action"] : "info";
$pppoeMySQLDriver = new PPPoE_MySQL_Driver();
$pppoeMySQLDriver->$action();

?>