<?php
$linuxInfo = file_get_contents( apiRootURL("/info") . "?api_key=" . config("api.key") );
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
					<th>Uptime <?php echo apiRequestAnchor("/info"); ?> </th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td><?php echo $linuxInfo->about->os; ?></td>
					<td><?php echo $linuxInfo->about->hostname; ?></td>
					<td>
						<?php if($linuxInfo->stats->uptime->days) { echo $linuxInfo->stats->uptime->days . " days, "; }
							  echo $linuxInfo->stats->uptime->hours . ":" . $linuxInfo->stats->uptime->minutes . ":" . $linuxInfo->stats->uptime->seconds; ?>
					</td>
				</tr>
			</tbody>
		</table>
	</div>
</div>