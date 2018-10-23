<?php

$basePath = realpath(dirname(__FILE__));
$basePath = strstr($basePath, "client-api/", true) . "client-api";

include_once $basePath . "/bootstrap.php";
include_once pppoe_path("/mysql.php");
validateApiRequest();

Class PPPoE_MySQL_Driver extends MySQL_Driver {

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

	public function nases() {
		returnJson($this->get("nas")->all());
	}

	public function nas() {
		$method = (isset($_POST["ip"])) ? "create" : "get";
		$nas = $this->get("nas")->where("nasname", requestParam("ip"))->first();
		if($method=="get") { returnJson($nas); }
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

}

$action = (isset($_GET["action"])) ? $_GET["action"] : "info";
$pppoeMySQLDriver = new PPPoE_MySQL_Driver();
$pppoeMySQLDriver->$action();

?>