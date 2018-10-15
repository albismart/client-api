<?php if(!function_exists('base_path')) { exit(); } ?><html>
<head>
	<title> Network Insight - ClientAPI - AlbiSmart </title>
	<style> body, html { margin: 0; } header { height: 50px; margin-bottom: 20px; padding: 10px; box-shadow: 1px 1px 5px rgba(0,0,0,0.4); } header#main { position:fixed; z-index: 999; width: 100%; background: #fff; } header img { margin: 5px; } header h3 { float: right; background: #fdf1d4; padding: 15px; margin:0; margin-right: 20px; border-radius: 5px; font-family: monospace; font-weight: normal; border: 1px solid #e8ce91; } .container { width:100%; position: relative; top: 71px; padding-bottom: 50px; } .columns { width: 100%; clear: both; } [class^="col-"] { float: left; } .col-1 { width: 8.3%; } .col-2 { width: 16.6%; } .col-3 { width: 25%; } .col-4 { width: 33%; } .col-5 { width: 41.6%; } .col-6 { width: 50%; } .col-7 { width: 58.3%; } .col-8 { width: 60%; } .col-9 { width: 75%; } .col-10 { width: 83.3%; } .col-11 { width: 91.7%; } .col-12 { width: 100%; } .search { margin-top: 50px; position: relative; } .search input { height: 80px; line-height: 80px; font-size: 36px; width: 100%; padding: 15px; border-radius: 10px; border: 1px solid #ddd; } .search button { position: absolute; right: 0; top: 0; z-index: 9; height: 80px; font-size: 36px; border-top-right-radius: 5px; border-bottom-right-radius: 5px; border: 1px solid #ddd; padding: 15px 50px; cursor: pointer; } small { font-family: sans-serif; color: #a5a5a5; } input:focus { outline: none; } button:focus,button:active { outline: none; } table { width: 100%; } .card { position:relative; box-shadow: 0 2px 4px 0 rgba(0,0,0,0.2); transition: 0.3s; border: 1px solid #ddd; border-radius: 5px; font-family: sans-serif; border-spacing: 0; overflow: hidden; text-align: center; margin-top: 30px; } .card .header { font-weight:bold; background: #ddd; color: #848181; padding: 10px; } .card th,.card td,.card .body { padding: 10px; } .card td { border-bottom: 1px solid #ddd; } .card td:not(:last-of-type) { border-right: 1px solid #ddd; } .card:hover { box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2); } .noselect { -webkit-touch-callout: none; -webkit-user-select: none; -khtml-user-select: none; -moz-user-select: none; -ms-user-select: none; user-select: none; cursor:pointer; } .new-tab-anchor { font-size: 11px; position: absolute; right:-3px; top:-3px; color: #7408a1; padding: 5px; border-radius: 5px; border: 1px solid #ccc; background: #fff; } .badge { padding: 2px 4px; font-size: 13px; margin-top: 5px; color: #000; } .badge.success { background: #cefcbc; border: 1px solid #8ec48d; } .badge.danger { background: #ffe1e1; border: 1px solid #ff6e6e; } .badge.warning { background: #fffbdd; border: 1px solid #ffe66e; } .badge.info { background: #d6f3f6; border: 1px solid #327ec0; } .chain-border { border-right: 0 !important; } .toggleOffCanvas { cursor: pointer; transition: background-color 0.5s ease; } .toggleOffCanvas:hover { background-color: #9cd4fc; } #offCanvas { display: none; width: 100%; position: fixed; top: 71px; overflow: hidden; } #offCanvasOverlay {  background: #000; opacity: 0; transition: opacity 0.2s ease; width: 100%; height: 100%; position: absolute; left: 0; z-index: 1; } #offCanvasContent { width: 30%; height: 100%; background: #fff; position: absolute; top: 0; right: -40%; transition: right 0.6s ease; z-index: 2; } </style>
</head>
<body onload="initPage()" onresize="initPage()">
	<header id="main">
		<a href="http://albismart.com" target="_blank">
			<img src="logo.svg" alt="AlbiSmart" height="40px" />
		</a>
		<h3> Warning: this is a public page! </h3>
	</header>
	
	<div class="container">
		<?php
			if(!isset($_GET['hostname'])) {
				include_once views_path("/tabs/index/linux.php");
				include_once views_path("/tabs/index/search.php");
			} else {
				include_once views_path("/tabs/index/cmts-insight.php");
			}
		?>
		<div style="clear:both"></div>
	</div>
	
	<div id="offCanvas">
		<div id="offCanvasContent"></div>
		<div id="offCanvasOverlay" onclick="blurOffCanvas()"></div>
	</div>
	
<script>
function initPage() {
	document.getElementById("offCanvas").style.height = (window.innerHeight - 71) + "px";
	
	
}

function blurOffCanvas() {
	document.getElementById("offCanvasContent").style.right = "-40%";
	document.getElementById("offCanvasOverlay").style.opacity = "0";
	setTimeout( function(){ 
		document.getElementById("offCanvas").style.display = "none";
	}, 500);
}


function focusOffCanvas() {
	console.log("Openning offcanvas");
	document.getElementById("offCanvas").style.display = "block";
	setTimeout( function(){ 
		document.getElementById("offCanvasContent").style.right = "0";
		document.getElementById("offCanvasOverlay").style.opacity = "0.3";
	}, 150);
}

function httpGetAsync(theUrl, callback) {
	var xmlHttp = new XMLHttpRequest();
	xmlHttp.onreadystatechange = function() { 
		if (xmlHttp.readyState == 4 && xmlHttp.status == 200)
			callback(xmlHttp.responseText);
	}
	xmlHttp.open("GET", theUrl, true); // true for asynchronous 
	xmlHttp.send(null);
}
function toggleNext() {
	var target = window.event.target;
	var tableList = target.parentElement.parentElement.nextSibling.nextSibling;
	tableList.style.display = (tableList.style.display == "none") ? "table-row-group" : "none";
}
</script>
</body>
</html>