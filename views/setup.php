<?php if(!function_exists('base_path')) { header("HTTP/1.1 404 Not found"); exit(); } ?><html>
<head>
	<title>Setup — AlbiSmart - ClientAPI</title>
	<link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre.min.css">
	<link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre-exp.min.css">
	<link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre-icons.min.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.40.0/codemirror.min.css" />
	<style> .p0 { padding: 0; } .mtb { margin: 50px 0; } .mtb2 { margin: 25px 0; } .mb { margin-bottom: 50px; } .mt { margin-top: 50px; } header.navbar { background-image: linear-gradient(135deg, #667eea 0%, #764ba2 100%); height:60px;padding:20px;border-bottom:1px solid #f1e7f9;margin-bottom: 20px } header.navbar a { color: #fff !important;  } </style>
</head>
<body>
	<header class="navbar">
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
		<ul class="step mtb">
			<li class="step-item" :class="{active: s==currentStep}" v-for="(step, s) in steps" :key="s">
				<a href="#" @click.prevent="jumpStep(s)" class="tooltip" :data-tooltip="step.tooltip">{{step.label}}</a>
			</li>
		</ul>
		<div class="columns" v-if="currentStep!=null"><div class="column col-12"><h3 class="text-normal" v-html="steps[currentStep].title"></h3></div></div>
		<div class="columns">
			<div class="column col-12">
				<form method="POST" action="<?php echo $_SERVER['PHP_SELF']; ?>" ref="config">
					<input type="hidden" name="create-config" value="<?php echo md5(date('Y-m-d')); ?>">
					<input type="hidden" name="version" value="<?php echo $latestVersionObject->tag_name; ?>">
					<div class="columns" v-show="currentStep==0"> <?php include views_path("/tabs/setup/requirements.php"); ?> </div>
					<div class="columns" v-show="currentStep==1"> <?php include views_path("/tabs/setup/dhcp.php"); ?> </div>
					<div class="columns" v-show="currentStep==2"> <?php include views_path("/tabs/setup/pppoe.php"); ?> </div>
					<div class="columns" v-show="currentStep==3"> <?php include views_path("/tabs/setup/snmp.php"); ?> </div>
					<div class="columns" v-show="currentStep==4"> <?php include views_path("/tabs/setup/api.php"); ?> </div>
				</form>
			</div>
		</div>
		<div class="columns mtb2">
			<div class="column col-12 p0">
				<button v-if="currentStep!=finalStep && currentStep!=null" class="btn btn-lg btn-block btn-primary" @click="goNextStep">
					Next step <i class="icon icon-forward"></i> </button>
				<button v-if="currentStep==finalStep" class="btn btn-lg btn-block btn-success" @click="goFinish">
					Create config file <i class="icon icon-check"></i> </button>
			</div>
		</div>
	</div>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.40.0/codemirror.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/vue-codemirror@4.0.5/dist/vue-codemirror.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/vue@2.5.17/dist/vue.js"></script>
	<script>
		Vue.use(window.VueCodemirror);
		new Vue({
			el: '#app',
			data: function() {
				return {
					currentStep: 0,
					steps: [
						{ label: 'System', tooltip: 'Requirements status', title: 'Required services and extensions' },
						{ label: 'DHCP', tooltip: 'Configuration form', title: 'Enter your current DHCP Configuration' },
						{ label: 'PPPoE', tooltip: 'Freeradius and DB', title: 'Enter your freeradius database credentials' },
						{ label: 'SNMP', tooltip: 'Default fallbacks', title: 'General SNMP credentials for all coms' },
						{ label: 'API Setup', tooltip: 'Final settings', title: 'Set your API preferences' },
					],
					reqs: <?php echo json_encode($currentReqs); ?>
				}
			},
			methods: {
				jumpStep: function(step) {
					this.currentStep = step;
				},
				goNextStep: function() {
					this.currentStep++;
				},
				badge: function(value) {
					var badgeClass = (value) ? 'label-success' : 'label-warning';
					var icon = (value) ? value + '<i class="icon icon-check" style="margin-left:10px;margin-top:-5px"></i>' : '<i class="icon icon-cross"></i>';
					return '<span class="label label-rounded '+badgeClass+'">'+icon+'</span>';
				},
				textClass: function(value) {
					return (value) ? 'text-success' : 'text-warning';
				},
				goFinish: function() {
					this.$refs.config.submit();
				},
				updateDhcpConfInput: function(newValue) {
					this.$refs.dhcpconf.value = newValue;
				}
			},
			computed: {
				finalStep: function() {
					return this.steps.length - 1;
				}
			}
		})
	</script>
</body>
</html>