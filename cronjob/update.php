<?php

$currentPath = realpath(dirname(__FILE__));
include_once $currentPath . "/init.php";

/* Time after time checkcing the api version for automatic updates */
if( config('api.autoUpdates')==true ) {
	$opts = array('http' => array( "method" => "GET", "header" => array("User-Agent: PHP")));
	$context = stream_context_create($opts);
	$latestVersionObject = file_get_contents("https://api.github.com/repos/albismart/client-api/releases/latest", false, $context);
	$latestVersionObject = json_decode($latestVersionObject);
	if(config('api.version') != $latestVersionObject->tag_name) {
		echo "Downloading new version";
		file_put_contents("latest-release.zip", file_get_contents($latestVersionObject->zipball_url, false, $context));
	}
}