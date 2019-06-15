<?php

include_once "bootstrap.php";

if(!$config) {
	if(!isset($_POST['create-config'])) {
		include_once views_path("/setup.php");
	} else {
		file_put_contents(data_path("/config.php"), configFileContent($_POST));
		file_put_contents(data_path("/dhcp.conf"), $_POST['dhcpconf']);
		$apiRootURL = "http://" . $_SERVER['HTTP_HOST'] . str_replace("/index.php", "", $_SERVER['REQUEST_URI']);
		header("Location: " . $apiRootURL);
	}
} else {
	if(isset($config['api']) && isset($config['api']['indexPage']) && $config['api']['indexPage']==true) {
		include_once views_path("/index.php");
	} else {
		header("HTTP/1.1 404 Not found");
		exit();
	}
}

?>