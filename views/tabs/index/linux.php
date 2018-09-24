<?php
$linuxInfo = file_get_contents("http://127.0.0.1" . str_replace("/index.php", '', $_SERVER['REQUEST_URI']) . "/info");
$linuxInfo = json_decode($linuxInfo);
?>
<style> table.intro { border: 1px solid #ddd; border-radius: 5px; font-family: sans-serif; border-spacing: 0; overflow: hidden; text-align: center; margin-top: 30px; } table.intro th { background: #ddd; color: #848181; padding: 10px; } table.intro td { padding: 10px; } </style>
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