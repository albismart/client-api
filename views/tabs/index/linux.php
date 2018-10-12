<?php
$linuxInfo = file_get_contents(apiRootURL("/info"));
$linuxInfo = json_decode($linuxInfo);
?>
<div class="columns">
	<div class="col-2">&nbsp;</div>
	<div class="col-8">
		<table class="card">
			<thead class="header">
				<tr>
					<th>Operative System</th>
					<th>Hostname</th>
					<th>Uptime
						<a href="<?php echo apiRootURL(null, true); ?>/info" title="Open new tab" target="_blank" class="new-tab-anchor"> API Request </a>
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $linuxInfo->os; ?></td>
					<td><?php echo $linuxInfo->hostname; ?></td>
					<td>
						<?php if($linuxInfo->uptime->days) { echo $linuxInfo->uptime->days . " days, "; }
							  echo $linuxInfo->uptime->hours . ":" . $linuxInfo->uptime->minutes . ":" . $linuxInfo->uptime->seconds; ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>