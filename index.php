<?php
$currentPath = realpath(dirname(__FILE__));
include_once $currentPath . "/bootstrap.php";
$title = ($config) ? 'AlbiSmart - ClientAPI' : 'Setup — AlbiSmart - ClientAPI';
$latestVersionObject = apiLatestVersionObject();
$currentReqs = array( 
	'php' => array(
		'version' => phpversion(),
		'json' => phpversion('json'),
		'snmp' => phpversion('snmp'),
		'mysql' => phpversion('mysqli'),
		'mysqlPdo' => phpversion('pdo_mysql'),
	), 'freeradius' => shell_exec('freeradius -v') );
?><html>
<head>
	<title><?php echo $title; ?></title>
	<?php if(!$config) { ?>
		<link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre.min.css">
		<link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre-exp.min.css">
		<link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre-icons.min.css">
	<?php } ?>
</head>
<body>
	<header class="navbar" style="height:60px;padding:20px;border-bottom:1px solid #f1e7f9;margin-bottom: 20px;">
		<section class="navbar-section">
			<a href="https://github.com/albismart/client-api" target="_blank" class="navbar-brand mr-2">
				<i class="icon icon-link"></i> AlbiSmart - ClientAPI</a>
		</section>
		<section class="navbar-section">
			<a href="<?php echo $latestVersionObject->html_url; ?>" target="_blank" class="btn btn-link">
				<i class="icon icon-flag"></i> Installation page (<?php echo $latestVersionObject->tag_name; ?>)</a>
		</section>
	</header>

	<div class="container grid-lg" id="app">
		<ul class="step" style="margin: 50px 0">
			<li class="step-item" :class="{active: s==currentStep}" v-for="(step, s) in steps" :key="s">
				<a href="#" @click.prevent="jump(s)" class="tooltip" :data-tooltip="'Step '+ (s+1)">{{step}}</a>
			</li>
		</ul>
		<div class="columns"><div class="column col-12"><h3 class="text-normal" v-html="stepTitles[currentStep]"></h3></div></div>
		
		<div class="columns" v-if="currentStep==0">
			<div class="column col-6">
				<div class="card">
					<div class="card-body">
						<ul>
							<li> 
								<font :class="textClass(reqs.php.version)">PHP 5.4> </font> <font v-html="badge(reqs.php.version)"></font>
								<ul>
								  <li :class="textClass(reqs.php.json)"> JSON Extension <font v-html="badge(reqs.php.json)"></font></li>
								  <li :class="textClass(reqs.php.snmp)"> SNMP Extension <font v-html="badge(reqs.php.snmp)"></font></li>
								  <li :class="textClass(reqs.php.mysql)"> MySQL Extension <font v-html="badge(reqs.php.mysql)"></font></li>
								  <li :class="textClass(reqs.php.mysqlPdo)"> MySQL PDO Extension  <font v-html="badge(reqs.php.mysqlPdo)"></font></li>
								</ul>
							</li>
						</ul>
					</div>
				</div>
			</div>
			<div class="column col-6">
				<div class="card">
					<div class="card-body">
						<ul>
							<li><a href="https://freeradius.org" target="_blank">FreeRadius</a></li>
							<li><a href="https://www.isc.org/downloads/dhcp/" target="_blank">DHCP Service</a></li>
							<li><a href="https://en.wikipedia.org/wiki/OMAPI" target="_blank"> OMAPI</a> </li>
							<li><a href="https://linux.die.net/man/1/omshell" target="_blank"> OMSHELL </a></li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<h4 class="text-normal" v-else> Coming soon ... </h4>
	</div>
	<?php if(!$config) { ?>
		<script src="https://cdn.jsdelivr.net/npm/vue@2.5.17/dist/vue.js"></script>
		<script>
			new Vue({
				el: '#app',
				data: function() {
					return {
						currentStep: 0,
						steps: ['System Requirements', 'DHCP Configuration', 'PPPoE Configuration', 'SNMP Configuration', 'API Setup'],
						stepTitles: [
							'Recommended and actual versions', 'Enter your current DHCP Configuration', 
							'Enter your current PPPoE Configuration', 'SNMP Credentials and Configuration',
							'API Related Configurations'
						],
						reqs: <?php echo json_encode($currentReqs); ?>
					}
				},
				methods: {
					jump: function(step) {
						this.currentStep = step;
					},
					badge: function(value) {
						var badgeClass = (value) ? 'label-success' : 'label-warning';
						var icon = (value) ? value + '<i class="icon icon-check" style="margin-left:10px;margin-top:-5px"></i>' : '<i class="icon icon-cross"></i>';
						return '<span class="label label-rounded '+badgeClass+'">'+icon+'</span>';
					},
					textClass: function(value) {
						return (value) ? 'text-success' : 'text-warning';
					}
				}
			})
		</script>
	<?php } ?>
</body>
</html>