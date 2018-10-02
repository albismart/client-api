<?php 

include_once "bootstrap.php"; 

if(!$config) {
	if(!isset($_POST['create-config'])) {
		include_once views_path("/pages/setup.php");	
	} else {
		file_put_contents(config_path("/config.php"), configFileContent($_POST));
		file_put_contents(config_path("/dhcp.conf"), $_POST['dhcpconf']);
		header("Location: " . apiRootURL());
	}
} else {
	if(isset($config['api']) && isset($config['api']['indexPage']) && $config['api']['indexPage']==true) {
		include_once views_path("/pages/index.php"); 
	} else {
		include_once views_path("/pages/denied.php");
	}
}

?>