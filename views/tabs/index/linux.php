<?php
echo apiRootURL("/info");
$linuxInfo = file_get_contents(apiRootURL("/info"));
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
					<td><?php echo $linuxInfo->os; ?></td>
					<td><?php echo $linuxInfo->hostname; ?></td>
					<td>
						<?php echo ($linuxInfo->uptime->days) ? $linuxInfo->uptime->days . " days, " : "";
							  echo ($linuxInfo->uptime->hours<10) ? "0" . $linuxInfo->uptime->hours : $linuxInfo->uptime->hours;
							  echo ($linuxInfo->uptime->minutes<10) ? ":0" . $linuxInfo->uptime->minutes : ":" . $linuxInfo->uptime->minutes;
							  echo ($linuxInfo->uptime->seconds<10) ? ":0" . floor($linuxInfo->uptime->seconds) : ":" . floor($linuxInfo->uptime->seconds); ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>