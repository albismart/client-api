<?php
$hostname = (isset($_GET['hostname'])) ? $_GET['hostname'] : null;
$cmtsInfoUrl = apiRootURL("/snmp/cmts?hostname=" . $hostname);
$cmtsInfo = file_get_contents($cmtsInfoUrl);
$cmtsInfo = json_decode($cmtsInfo);

$jsCmtsInfoUrl = "http://".$_SERVER['HTTP_HOST'] . strstr(str_replace("/index.php","",$_SERVER['REQUEST_URI']),"?",true);
$jsCmtsInfoUrl.= "snmp/cmts?hostname=" . $hostname;
?>
<div class="columns">
	<div class="col-2">&nbsp;</div>
	<div class="col-8">
		<table class="intro">
			<thead>
				<tr>
					<th>Name</th>
					<th>Uptime</th>
					<th>CPU Usage</th>
					<th>Temperature IN</th>
					<th>Temperature Out</th>
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
						if($cmtsInfo->uptime->hours) { echo $cmtsInfo->uptime->hours; }
						if($cmtsInfo->uptime->minutes) { echo ":" . $cmtsInfo->uptime->minutes; }
						if($cmtsInfo->uptime->seconds) { echo ":" . $cmtsInfo->uptime->seconds; }
					?></span></td>
					<td><span id="cmtsCpuUsage"><?php echo $cmtsInfo->cpuUsage; ?></span>%</td>
					<td><span id="cmtsTemperatureIn"><?php echo $cmtsInfo->temperatureIn; ?></span> °C</td>
					<td><span id="cmtsTemperatureOut"><?php echo $cmtsInfo->temperatureOut; ?></span> °C</td>
				</tr>
				<tr id="cmtsDetails" style="display:none">
					<td colspan="5" style="border-top: 1px solid #ccc;padding: 20px;line-height:30px">
						<?php
							echo str_replace(",","<br/>",$cmtsInfo->description) . "<hr/>";
							if($cmtsInfo->objectID) { echo $cmtsInfo->objectID . "<hr/>"; }
							if($cmtsInfo->contact) { echo $cmtsInfo->contact . "<hr/>"; }
							if($cmtsInfo->location) { echo $cmtsInfo->location . "<hr/>"; }
							if($cmtsInfo->services) { echo $cmtsInfo->services; }
						?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>
<script>
function httpGetAsync(theUrl, callback)
{
    var xmlHttp = new XMLHttpRequest();
    xmlHttp.onreadystatechange = function() { 
        if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
            callback(xmlHttp.responseText);
    }
    xmlHttp.open("GET", theUrl, true); // true for asynchronous 
    xmlHttp.send(null);
}
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
</script>