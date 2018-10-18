<?php if(!function_exists('base_path')) { header("HTTP/1.1 404 Not found"); exit(); } ?>
<div class="column col-12">
	<div class="card">
		<div class="card-body">
			<codemirror :options="{lineNumbers: true}" @input="updateDhcpConfInput"></codemirror>
			<input ref="dhcpconf" type="hidden" name="dhcpconf" />
		</div>
	</div>
</div>