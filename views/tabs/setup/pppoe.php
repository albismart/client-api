<?php if(!function_exists('base_path')) { header("HTTP/1.1 404 Not found"); exit(); } ?>
<div class="column col-12">
	<div class="card">
		<div class="card-body">
			<div class="form-group">
				<div class="columns">
					<div class="column col-9">
						<label class="form-label" for="dbhost">Database host</label>
						<input class="form-input" name="dbhost" id="dbhost" placeholder="localhost">
					</div>
					<div class="column col-3">
						<label class="form-label" for="dbport">Database port</label>
						<input class="form-input" name="dbport" id="dbport" placeholder="3306">
					</div>
				</div>
			</div>
			<div class="form-group">
				<label class="form-label" for="dbname">Database name</label>
				<input class="form-input" name="dbname" id="dbname" placeholder="freeradius">
			</div>
			<div class="form-group">
				<label class="form-label" for="dbusername">Username</label>
				<input class="form-input" name="dbusername" id="dbusername" placeholder="freeradius">
			</div>
			<div class="form-group">
				<label class="form-label" for="dbpass">Password</label>
				<input class="form-input" name="dbpassword" id="dbpass" placeholder="secret">
			</div>
		</div>
	</div>
</div>