<div class="column col-12">
	<div class="card">
		<div class="card-body">
			<div class="form-group">
				<label class="form-label" for="apikey">Setup a secret API Key</label>
				<input class="form-input" type="text" id="apikey" value="<?php echo sha1(strtotime('now')); ?>">
			</div>
			<div class="form-group">
			  <label class="form-label">  Enable automatic updates from our repository on GitHub </label>
			  <label class="form-switch">
			    <input type="checkbox">
			    <i class="form-icon"></i>
			  </label>
			</div>
			<div class="form-group">
			  <label class="form-label">Enable API Index page to get quick insight from your network</label>
			  <label class="form-switch">
			    <input type="checkbox">
			    <i class="form-icon"></i>
			  </label>
			</div>
		</div>
	</div>
</div>