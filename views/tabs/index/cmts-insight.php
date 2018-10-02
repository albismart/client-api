<?php
$hostname = (isset($_GET['hostname'])) ? $_GET['hostname'] : null;
$linuxInfo = file_get_contents(apiRootURL("/snmp/cmts?hostname=" . $hostname));
$linuxInfo = json_decode($linuxInfo);
?>
<div class="columns">
	<div class="col-2">&nbsp;</div>
	<div class="col-8">
		<table class="intro">
			<thead>
				<tr>
					<th>Operative System</th>
					<th>Hostname</th>
					<th>Uptime</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td>OS</td>
					<td>HN</td>
					<td>UT</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>