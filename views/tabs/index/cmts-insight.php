<?php

// CMTS Hostname from previous form
$hostname = (isset($_GET['hostname'])) ? $_GET['hostname'] : null;

// CMTS Info API Call example
$cmtsInfoUrl = apiRootURL("/snmp/cmts?hostname=" . $hostname) . "&api_key=" . config("api.key");
$cmtsInfo = file_get_contents($cmtsInfoUrl);
$cmtsInfo = json_decode($cmtsInfo);
 
// CMTS Interfaces API Call example
$cmtsInterfacesUrl = apiRootURL("/snmp/cmts?hostname=" . $hostname . "&action=interfaces") . "&api_key=" . config("api.key");
$cmtsInterfaces = file_get_contents($cmtsInterfacesUrl);
$cmtsInterfaces = json_decode($cmtsInterfaces);

// CMTS CableModems API Call example
$cmtsCableModemsUrl = apiRootURL("/snmp/cmts?hostname=" . $hostname . "&action=cablemodems") . "&api_key=" . config("api.key");
$cmtsCableModems = file_get_contents($cmtsCableModemsUrl);
$cmtsCableModems = json_decode($cmtsCableModems);

// Modem Status String representations
$modemStatus = array("Other", "Ranging", "Ranging Aborted", "Ranging ✔", "IP ✔", "Registration ✔", "Access Denied", "Operational", "Registered BPI ...");
$modemStatusClass = array( 1 => "danger", 3 => "danger", 7 => "danger", 2 => "warning", 4 => "warning", 5 => "warning", 9 => "warning", 6 => "success", 8 => "success");

?>
<div class="columns">
	<div class="col-2">&nbsp;</div>
	<div class="col-8">
		<table class="card">
			<thead class="header">
				<tr>
					<th>Name</th>
					<th>Uptime</th>
					<th>CPU Usage</th>
					<th>Temperature IN</th>
					<th>Temperature Out</th>
					<th>Total interfaces</th>
					<th>Total Cable Modems <?php echo apiRequestAnchor("/snmp/cmts?hostname={$hostname}"); ?></th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>
						<button type="button" onclick="toggleCmtsDetails()" id="cmtsDetailsToggler" style="padding:5px 10px;background: #fff;border:1px solid #ddd;cursor:pointer">▼</button>
						<?php echo $cmtsInfo->name; ?>
					</td>
					<td><span id="cmtsUptime"><?php
						if($cmtsInfo->uptime->days) { echo $cmtsInfo->uptime->days . " days, "; }
						echo $cmtsInfo->uptime->hours . ":" . $cmtsInfo->uptime->minutes . ":" . $cmtsInfo->uptime->seconds;
					?></span></td>
					<td><span id="cmtsCpuUsage"><?php echo $cmtsInfo->cpuUsage; ?></span>%</td>
					<td><span id="cmtsTemperatureIn"><?php echo $cmtsInfo->temperatureIn; ?></span> °C</td>
					<td><span id="cmtsTemperatureOut"><?php echo $cmtsInfo->temperatureOut; ?></span> °C</td>
					<td><span id="cmtsCountInterfaces"><?php echo $cmtsInfo->countInterfaces; ?></span></td>
					<td><?php echo count($cmtsCableModems); ?></td>
				</tr>
				<tr id="cmtsDetails" style="display:none">
					<td colspan="7" style="border-top: 1px solid #ccc;padding: 20px;line-height:30px">
						<?php
							echo str_replace(",","<br/>",$cmtsInfo->description) . "<hr/>";
							if($cmtsInfo->objectID) { echo $cmtsInfo->objectID . "<hr/>"; }
							if($cmtsInfo->contact) { echo $cmtsInfo->contact . "<hr/>"; }
							if($cmtsInfo->location) { echo $cmtsInfo->location; }
						?>
					</td>
				</tr>
			</tbody>
		</table>		
		
		<table class="card">
			<thead class="header" onclick="toggleNext(this)">
				<tr>
					<th colspan="4" class="noselect">
						Interfaces (<?php echo $cmtsInfo->countInterfaces; ?>)
						<?php echo apiRequestAnchor("/snmp/cmts?hostname={$hostname}&action=interfaces"); ?>
					</th>
				</tr>
			</thead>
			<tbody style="display:none">
				<td colspan="4">
					<input onkeypress="filterList('interface')" onkeyup="filterList()" onblur="filterList()" placeholder="Search ..." style="width:98%;padding:8px;border-radius:3px;border:1px solid #bbb" />
				</td>
				<?php $c = 0; if($cmtsInterfaces && is_array($cmtsInterfaces)) { foreach($cmtsInterfaces as $cmtsInterface) { $c++; ?>
					<?php if($c==1) { echo '<tr>'; } ?>
						<td onclick="focusInterface()" class="interface toggleOffCanvas <?php echo strtolower(trim(str_replace("/","-",$cmtsInterface->description))); ?>">
							<font style="font-size:16px"> <?php echo $cmtsInterface->description; ?> </font>
							<div style="margin-top:10px">
								<span class="badge chain-border <?php echo ($cmtsInterface->adminStatus==1) ? 'success' : (($cmtsInterface->status==2) ? 'danger' : 'warning'); ?> "> Admin </span>
								<span class="badge chain-border <?php echo ($cmtsInterface->operationStatus==1) ? 'success' : (($cmtsInterface->operationStatus==2) ? 'danger' : 'warning'); ?> "> Operation </span>
								<span class="badge info"><?php echo formatBytes($cmtsInterface->speed); ?></span>
							</div>
						</td>
					<?php if($c==4) { echo '</tr>'; $c=0; } ?>
				<?php } } ?>
			</tbody>
		</table>
	
		<table class="card">
			<thead class="header" onclick="toggleNext(this)">
				<tr>
					<th colspan="4" class="noselect">
						Cable Modems (<?php echo count($cmtsCableModems); ?>)
						<?php echo apiRequestAnchor("/snmp/cmts?hostname={$hostname}&action=cablemodems"); ?>
					</th>
				</tr>
			</thead>
			<tbody style="display:none">
				<td colspan="4">
					<input onkeypress="filterList('cablemodem')" onkeyup="filterList()" onblur="filterList()" placeholder="Search ..." style="width:98%;padding:8px;border-radius:3px;border:1px solid #bbb" />
				</td>
				<?php $m = 0; if($cmtsCableModems && is_array($cmtsCableModems)) { foreach($cmtsCableModems as $cableModem) { $m++; ?>
					<?php if($m==1) { echo '<tr>'; } ?>
						<td class="cablemodem toggleOffCanvas <?php echo strtolower(strtolower(str_replace(":","-",$cableModem->mac))); ?>" onclick="focusCableModem()">
							<font style="font-size:16px"><?php echo $cableModem->mac; ?> </font> <br/>
							<font style="font-size:16px"><?php echo $cableModem->ip; ?> </font> <br/>
							<span class="badge <?php echo $modemStatusClass[$cableModem->status]; ?> chain-border"> <?php echo $modemStatus[$cableModem->status-1]; ?> </span>
							<span class="badge warning">
								<?php $uptime = $cableModem->uptime;
								if($cableModem->uptime->days) { echo $cableModem->uptime->days . " days, "; }
								echo $cableModem->uptime->hours . ":" . $cableModem->uptime->minutes . ":" . $cableModem->uptime->seconds; ?>
							</span>
						</td>
					<?php if($m==4) { echo '</tr>'; $m=0; } ?>
				<?php } } ?>
			</tbody>
		</table>
	
	</div>
</div>
<?php

// Real-time Cmts Info URL
$jsCmtsInfoUrl = "http://".$_SERVER['HTTP_HOST'] . strstr(str_replace("/index.php","",$_SERVER['REQUEST_URI']),"?",true);
$jsCmtsInfoUrl.= "snmp/cmts?hostname=" . $hostname . "&api_key=" . config("api.key");


?>
<script>

var requestLoop = setInterval(function(){
	httpGetAsync("<?php echo $jsCmtsInfoUrl; ?>", function(response) {
		var data = JSON.parse(response);
		var uptime = "";
		if(data.uptime.days) { uptime = uptime + data.uptime.days + " days, "; }
		if(data.uptime.hours) { uptime = uptime + data.uptime.hours + ":"; }
		if(data.uptime.minutes) { uptime = uptime + data.uptime.minutes + ":"; }
		if(data.uptime.seconds) { uptime = uptime + data.uptime.seconds; }

		document.getElementById("cmtsUptime").innerHTML = uptime;
		document.getElementById("cmtsCpuUsage").innerHTML = data.cpuUsage;
		document.getElementById("cmtsTemperatureIn").innerHTML = data.temperatureIn;
		document.getElementById("cmtsTemperatureOut").innerHTML = data.temperatureOut;
		document.getElementById("cmtsCountInterfaces").innerHTML = data.countInterfaces;
	});
}, 1000);
function toggleCmtsDetails() {
	if(document.getElementById("cmtsDetails").style.display == "none") {
		document.getElementById("cmtsDetails").style.display = "table-row";
		document.getElementById("cmtsDetailsToggler").innerHTML = "▲";
	} else {
		document.getElementById("cmtsDetailsToggler").innerHTML = "▼";
		document.getElementById("cmtsDetails").style.display = "none";
	}
}
function filterList(itemClass) {
	var target = window.event.target;
	var query = target.value;
	query = query.trim(); query = query.toLowerCase(); query = query.replace("/","-"); query = query.replace(":","-");
	var items = document.getElementsByClassName(itemClass);
	if(query.length>0) {	
		for(var i = 0;i<items.length;i++) { items[i].style.display = "none"; }
		var foundItems = document.querySelectorAll("[class*='" +itemClass+ " " +query+"']");
		for(var j = 0;j<foundItems.length;j++) { foundItems[j].style.display = "table-cell"; }
	} else {
		for(var i = 0;i<items.length;i++) { items[i].style.display = "table-cell"; }
	}
}
function focusInterface() {
	console.log("Focusing interface");
	focusOffCanvas();
}

function focusCableModem() {
	focusOffCanvas();
}
</script>