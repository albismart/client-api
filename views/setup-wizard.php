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
	<div class="columns"><div class="column col-12"><h3 class="text-normal" v-html="steps[currentStep].title"></h3></div></div>
	<div class="columns" v-if="currentStep==0"> <?php include_once views_path("/tabs/requirements.php"); ?> </div>
	<div class="columns" v-if="currentStep==1"> <?php include_once views_path("/tabs/dhcp.php"); ?> </div>
	<div class="columns" v-if="currentStep==2"> <?php include_once views_path("/tabs/pppoe.php"); ?> </div>
	<div class="columns" v-if="currentStep==3"> <?php include_once views_path("/tabs/snmp.php"); ?> </div>
	<div class="columns" v-if="currentStep==4"> <?php include_once views_path("/tabs/api.php"); ?> </div>
	<div class="columns mb">
		<div class="column col-12" style="margin-top:40px">
			<button v-if="currentStep!=finalStep" class="btn btn-lg btn-block btn-primary" @click="goNextStep">
				Next step <i class="icon icon-forward"></i> </button>
			<button v-else class="btn btn-lg btn-block btn-success" @click="goFinish">
				Create config file <i class="icon icon-check"></i> </button>
		</div>
	</div>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.40.0/codemirror.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/vue@2.5.17/dist/vue.js"></script>
<script>
	var editor = CodeMirror.fromTextArea(document.querySelector("textarea[name=dhcp-conf]"), { lineNumbers: true,matchBrackets: true });
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
			}
		},
		computed: {
			finalStep: function() {
				return this.steps.length - 1;
			}
		}
	})
</script>