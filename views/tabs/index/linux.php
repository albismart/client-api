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