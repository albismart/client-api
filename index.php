<?php 

include_once "bootstrap.php"; 

if(!$config) {
	if(!isset($_POST['create-config'])) {
		include_once views_path("/pages/setup.php");	
	} else {
		file_put_contents(base_path("/config.php"), configFileContent($_POST));
		file_put_contents(base_path("/dhcp.conf"), $_POST['dhcpconf']);
		header("Location: /index.php");
	}
} else {
	if(isset($config['api']) && isset($config['api']['indexPage']) && $config['api']['indexPage']==true) {
		include_once views_path("/pages/index.php"); 
	} else {
		include_once views_path("/pages/denied.php");
	}
}

?>