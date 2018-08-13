<?php

/* 
isValidIPAddress: validates if a given IP address matches with the correspondent patterns of the regular IP addresses.
If the third parameter is passed as `true` the validation will run its course through IPV6 validation.
http://php.net/manual/en/function.filter-var.php - (PHP 5 >= 5.2.0, PHP 7)
filter_var — Filters a variable with a specified filter.
*/
function isValidIPAddress($ipAddress, $ipv6 = false) { 
	if($ipv6) {
		return filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
	} else {
		return filter_var($ipAddress, FILTER_VALIDATE_IP);
	}
}

/* 
isValidMacAddress: validates if a given MAC address matches with the correspondent patterns of the regular MAC address.
Basically we check if the MAC is a composition of 6 pairs of two-digit strings containing Hexadecimal values and joint with colons.
http://php.net/manual/en/function.preg-match.php - (PHP 4, PHP 5, PHP 7)
preg_match — Perform a regular expression match.
*/
function isValidMacAddress($macAddress) {
	return preg_match('/^(?:[0-9A-F]{2}[:]?){5}(?:[0-9A-F]{2}?)$/i', $macAddress); 
}

/*
config: retreives a configuration variable defined within the config.php based on the aimed index
http://php.net/manual/en/function.return.php - (PHP 4, PHP 5, PHP 7)
return — returns program control to the calling module.
*/
function config($index = null) {
	global $config;
	$configArrayWalker = $config;
	if($index) {
		if(is_string($index)) {
			$indexes = strpos($index, '.') !== false ? explode('.', $index) : 
					   (strpos($index, '/') !==false ? explode('/', $index) : null);
			if($indexes) {
				$index = $indexes;
			} else {
				if(isset($configArrayWalker[$index])) {
					$configArrayWalker = $configArrayWalker[$index];
				}
			}
		}
		if(is_array($index)) {
			foreach ($index as $value) {
				if(isset($configArrayWalker[$value])) {
					$configArrayWalker = $configArrayWalker[$value];
				}
			}
		}
	}

	return $configArrayWalker;
}

function apiLatestVersionObject($index = null) {
	$opts = array('http' => array( "method" => "GET", "header" => array("User-Agent: PHP")));
	$context = stream_context_create($opts);
	$releaseUrl = (config("api.releaseUrl")) ? config("api.releaseUrl") : 'https://api.github.com/repos/albismart/client-api/releases/latest';
	$latestVersionObject = file_get_contents($releaseUrl, false, $context);
	$latestVersionObject = json_decode($latestVersionObject);
	return ($index) && isset($latestVersionObject->{$index}) ? $latestVersionObject->{$index} : $latestVersionObject;
}