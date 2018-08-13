<?php

/*
	This is the main file to order your linux server generate routine tasks. In which we've covered cable modem, cmts and other devices signal data. Also when not using OMAPI for DHCP operations it checks if there is a failure and fixes the known issues.
	In order to add client-api cronjob executions to your system follow these steps:
	1. `vi /etc/crontab` or `nano /etc/crontab` after your terminal editor launches
	2. At the end of the file add this line: `* * * * * root php /path-to-repo/client-api/.cronjob/index.php > /dev/null 2>&1`
	3. Save the file and exit the editor, in the next minute the cronjob tasks will be operational.
*/

$currentPath = realpath(dirname(__FILE__));
require_once $currentPath . "/../bootstrap.php";
require_once $currentPath . "/update.php";