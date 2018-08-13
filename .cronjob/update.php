<?php

/* Time after time checkcing the api version for automatic updates */
if( config('api.autoUpdates')==true ) {
	$latestVersionObject = apiLatestVersionObject();
	if(config('api.version') != $latestVersionObject->tag_name) {
		echo "Downloading new version";
		file_put_contents("latest-release.zip", file_get_contents($latestVersionObject->zipball_url, false, $context));
	}
}