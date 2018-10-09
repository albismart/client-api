<?php
$hostname = (isset($_GET['hostname'])) ? $_GET['hostname'] : null;
$cmtsInfo = file_get_contents(apiRootURL("/snmp/cmts?hostname=" . $hostname));
$cmtsInfo = json_decode($cmtsInfo);
?>
<div class="columns">
	<div class="col-2">&nbsp;</div>
	<div class="col-8">
		<table class="intro">
			<thead>
				<tr>
					<th>Name</th>
					<th>Description</th>
					<th>Uptime</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $cmtsInfo->name; ?></td>
					<td><?php echo $cmtsInfo->description; ?></td>
					<td><?php echo $cmtsInfo->uptime; ?></td>
				</tr>
			</tbody>
		</table>
	</div>
</div>