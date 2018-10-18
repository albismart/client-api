<?php if(!function_exists('base_path')) { header("HTTP/1.1 404 Not found"); exit(); } ?>
<div class="column col-12">
	<div class="card">
		<div class="card-body">
			<div class="form-group">
				<label class="form-label" for="community">Default community</label>
				<input class="form-input" name="community" id="community" placeholder="secretword">
			</div>
			<div class="form-group">
				<label class="form-label" for="wcommunity">Write community</label>
				<input class="form-input" name="wcommunity" id="wcommunity" placeholder="another secretword (optional)">
			</div>
			<div class="form-group">
				<label class="form-label" for="timeout">Request timeout duration (milliseconds)</label>
				<input class="form-input" name="timeout" id="timeout" placeholder="2000">
			</div>
			<div class="form-group">
				<label class="form-label" for="retries">Number of retries if timeouts occur</label>
				<input class="form-input" name="retries" id="retries" placeholder="2">
			</div>
		</div>
	</div>
</div>