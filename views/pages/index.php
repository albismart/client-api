<?php if(!function_exists('base_path')) { exit(); } ?><html>
<head>
	<title> Network Insight - ClientAPI - AlbiSmart </title>
	<style> body, html { margin: 0; } header { height: 50px; margin-bottom: 20px; padding: 10px; box-shadow: 1px 1px 5px rgba(0,0,0,0.4); } header img { margin: 5px; } header h3 { float: right; background: #fdf1d4; padding: 15px; margin:0; border-radius: 5px; font-family: monospace; font-weight: normal; border: 1px solid #e8ce91; } .columns { width: 100%; clear: both; } [class^="col-"] { float: left; } .col-1 { width: 8.3%; } .col-2 { width: 16.6%; } .col-3 { width: 25%; } .col-4 { width: 33%; } .col-5 { width: 41.6%; } .col-6 { width: 50%; } .col-7 { width: 58.3%; } .col-8 { width: 60%; } .col-9 { width: 75%; } .col-10 { width: 83.3%; } .col-11 { width: 91.7%; } .col-12 { width: 100%; } .search { margin-top: 50px; position: relative; } .search input { height: 80px; line-height: 80px; font-size: 36px; width: 100%; padding: 15px; border-radius: 10px; border: 1px solid #ddd; } .search button { position: absolute; right: 0; top: 0; z-index: 9; height: 80px; font-size: 36px; border-top-right-radius: 5px; border-bottom-right-radius: 5px; border: 1px solid #ddd; padding: 15px 50px; cursor: pointer; } small { font-family: sans-serif; color: #a5a5a5; } input:focus { outline: none; } button:focus,button:active { outline: none; } </style>
</head>
<body>
	<header>
		<a href="http://albismart.com" target="_blank">
			<img src="logo.svg" alt="AlbiSmart" height="40px" />
		</a>
		<h3> Warning: this is a public page! </h3>
	</header>
	<?php
		if(!isset($_GET['ip'])) {
			include_once views_path("/tabs/index/search.php");
		} else {
			include_once views_path("/tabs/index/cmts-insight.php");
		}
	?>
</body>
</html>