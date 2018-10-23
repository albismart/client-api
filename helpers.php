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
function config($index = null, $default = null) {
	global $config;
	$configArrayWalker = $config;
	if($index) {
		if(is_string($index)) {
			$indexes = strpos($index, '.') !== false ? explode('.', $index) : 
					   (strpos($index, '/') !== false ? explode('/', $index) : null);
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

	return (!empty($configArrayWalker)) ? $configArrayWalker : $default;
}

/*
apiLatestVersionObject: performs http request to github to obtain latest version details of this project
https://developer.github.com/v3/repos/releases/#get-the-latest-release
View the latest published full release for the repository. Draft releases and prereleases are not returned by this endpoint.
*/
function apiLatestVersionObject($index = null) {
	$opts = array('http' => array( "method" => "GET", "header" => array("User-Agent: PHP")));
	$context = stream_context_create($opts);
	$releaseUrl = (config("api.releaseUrl")) ? config("api.releaseUrl") : 'https://api.github.com/repos/albismart/client-api/releases/latest';
	$latestVersionObject = file_get_contents($releaseUrl, false, $context);
	$latestVersionObject = json_decode($latestVersionObject);
	return ($index) && isset($latestVersionObject->{$index}) ? $latestVersionObject->{$index} : $latestVersionObject;
}

/*
timeticks: milliseconds to readable time.
https://stackoverflow.com/questions/38907572/how-to-display-system-uptime-in-php
Gets the uptime counted in milliseconds and then returns formatted array.
*/
function readableTimeticks($timeticks = null) {
	if($timeticks==null) {
		$timeticks = @file_get_contents('/proc/uptime');
	} else {
		$timeticks = $timeticks / 100;
	}
	$totalTime = floatval($timeticks);
	$seconds = floor(fmod($totalTime, 60)); $totalTime = (int)($totalTime / 60); if($seconds<10) { $seconds = "0{$seconds}"; }
	$minutes = $totalTime % 60; $totalTime = (int)($totalTime / 60); if($minutes<10) { $minutes = "0{$minutes}"; }
	$hours = $totalTime % 24; $totalTime = (int)($totalTime / 24); if($hours<10) { $hours = "0{$hours}"; }
	$days = $totalTime;
	return array("days" => $days, "hours" => $hours, "minutes" => $minutes, "seconds" => $seconds);
}

/*
apiRootURL: returns the root URL of the api located on this server.
http://php.net/manual/en/reserved.variables.server.php
Using server and execution environment information returns url to api.
*/
function apiRootURL($path = null, $httpHost = false) {
	$reqURI = $_SERVER['REQUEST_URI'];
	$uriBeforeQuestionMark = strstr($reqURI, "?", true);
	$reqURI = ($uriBeforeQuestionMark) ? $uriBeforeQuestionMark : $reqURI;
	$reqURI = str_replace("/index.php", "", $reqURI);
	$apiRootUrl = ($httpHost==true) ? "http://" . $_SERVER['HTTP_HOST'] : "http://localhost";
	$apiRootUrl.= rtrim($reqURI,"/");
	return ($path) ? $apiRootUrl . $path : $apiRootUrl;
}

/*
returnJson: in case the result param is an json_encoded string we echo the results.
http://php.net/manual/en/function.header.php
With the results variable type we alter the headers and deliver the data.
*/
function returnJson($result) {
	$jsonResult = json_encode($result);
	if(is_string($jsonResult) && strlen($jsonResult)>0) {
		header('Content-Type: application/json');
		echo $jsonResult;
	} else {
		header("HTTP/1.1 400 Malformed results");
		echo "The result was malformed.";
		var_dump($result);
	}
	exit();
}

/*
formatBytes: many OID's return limit values in bits therefore before formating in bytes you should divide them by 8.
https://stackoverflow.com/questions/2510434/format-bytes-to-kilobytes-megabytes-gigabytes
Provided with the bytes the function returns a higher unit result for effortless perception.
*/
function formatBytes($valueInBytes) {
    $higherUnits = array('B', 'KB', 'MB', 'GB', 'TB'); 
    $valueInBytes = max($valueInBytes, 0);
    $pow = floor(($valueInBytes ? log($valueInBytes) : 0) / log(1000)); 
    $pow = min($pow, count($higherUnits) - 1);
	$valueInBytes /= pow(1000, $pow);
    return round($valueInBytes, 2) . ' ' . $higherUnits[$pow]; 
}

/*
formatKiloBytes: alias helper for formatBytes converting KiloBytes to Bytes then to higher units.
*/
function formatKiloBytes($valueInMegs) {
	$valueInBytes = $valueInMegs * 1000;
	return formatBytes($valueInBytes);
}

/*
formatMegaBytes: alias helper for formatBytes converting Megs to Bytes then to higher units.
*/
function formatMegaBytes($valueInMegs) {
	$valueInBytes = $valueInMegs * 1000000;
	return formatBytes($valueInBytes);
}

/*
array_merge_recursive_ex: merges recursively 2 arrays returning the merge in the end.
https://stackoverflow.com/a/25712428/905650
*/
function array_merge_recursive_ex(array & $array1, array & $array2) {
    $merged = $array1;
    foreach ($array2 as $key => & $value) {
        if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
            $merged[$key] = array_merge_recursive_ex($merged[$key], $value);
        } else if (is_numeric($key)) {
			if (!in_array($value, $merged)) { $merged[] = $value; }
        } else {
			$merged[$key] = $value;
		}
    }
    return $merged;
}

/*
validateApiRequest: simply check if api_key param is set and validate if it matches with current config.
*/
function validateApiRequest() {
	if( !isset($_GET['api_key']) && !isset($_POST['api_key']) ) {
		header("HTTP/1.1 401 Unauthorized request");
		exit();
	} else {
		$apiKey = (isset($_GET['api_key'])) ? $_GET['api_key'] : $_GET['api_key'];
		if($apiKey!=config("api.key")) {
			header("HTTP/1.1 401 Unauthorized request");
			exit();
		}
	}
}

/*
apiRequestAnchor: return the link to api request example.
*/
function apiRequestAnchor($apiPath) {
	$apiPath .= (strpos($apiPath, "?") === false) ? "?" : "&";
	return '<a href="'.apiRootURL($apiPath, true) . "api_key=" . config("api.key") .'" title="Open new tab" target="_blank" class="new-tab-anchor"> API Request </a>';
}

/*
mapNetworkInfo: parsing network info via ifconfig.
*/
function mapNetworkInfo($networkInfo, $info) {
	$map = array('address'=>'inet addr:', 'subnet'=>'Mask:', 'gateway'=>'Bcast:');
	$result = explode($map[$info], $networkInfo); 
	$result = explode(" ", array_pop($result));
	$result = array_shift($result);
	return trim($result);
}


/*
requestParam: function to get request param.
*/
function requestParam($key, $default = null) {
	if(isset($_GET[$key])) {
		return urldecode($_GET[$key]);
	}
	if(isset($_POST[$key])) {
		return $_POST[$key];
	}
	return $default;
}

/*
decimalMacFromHex: function to get request param.
*/
function decimalMacFromHex($hexMac) {
	$cmMacHexToDec = explode(":", $cableModemMac);
	foreach($cmMacHexToDec as $key => $value) {
		$cmMacHexToDec[$key] = hexdec($value);
	}
	return implode(".", $cmMacHexToDec);
}

/******** PATHS ********/
function base_path($path = null) {
	$base_path = realpath(dirname(__FILE__));
	return ($path) ? $base_path . $path : $base_path;
}

function dhcp_path($path = null) {
	$dhcp_path = base_path('/dhcp');
	return ($path) ? $dhcp_path . $path : $dhcp_path;
}

function info_path($path = null) {
	$info_path = base_path('/info');
	return ($path) ? $info_path . $path : $info_path;
}

function pppoe_path($path = null) {
	$pppoe_path = base_path('/pppoe');
	return ($path) ? $pppoe_path . $path : $pppoe_path;
}

function snmp_path($path = null) {
	$snmp_path = base_path('/snmp');
	return ($path) ? $snmp_path . $path : $snmp_path;
}

function views_path($path = null) {
	$views_path = base_path('/views');
	return ($path) ? $views_path . $path : $views_path;
}

function config_path($path = null) {
	$config_path = base_path('/config');
	return ($path) ? $config_path . $path : $config_path;
}

/******** Config file formatting helper ********/
function configFileContent($post) {
	$autoUpdates = (isset($post['autoUpdates'])) ? 'true' : 'false' ;
	$indexPage = (isset($post['indexPage'])) ? 'true' : 'false' ;
	return '<?php

return array(
	"api" => array(
		"key" => "'.$post['apikey'].'",
		"version" => "'.$post['version'].'",
		"autoUpdates" => '.$autoUpdates.',
		"indexPage" => '.$indexPage.',
		"releaseUrl" => "https://api.github.com/repos/albismart/client-api/releases/latest"
	),
	"database" => array(
		"host" => "'.$post['dbhost'].'",
		"port" => "'.$post['dbport'].'",
		"name" => "'.$post['dbname'].'",
		"username" => "'.$post['dbusername'].'",
		"password" => "'.$post['dbpassword'].'"
	),
	"snmp" => array(
		"community" => "'.$post['community'].'",
		"wcommunity" => "'.$post['wcommunity'].'",
		"timeout" => "'.$post['timeout'].'",
		"retries" => "'.$post['retries'].'"
	)
);

?>';
}

?>