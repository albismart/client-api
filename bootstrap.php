<?php

$currentPath = realpath(dirname(__FILE__));

$config = ( file_exists( $currentPath . "/config.php" ) ) ? include_once $currentPath . "/config.php" : null;
require_once $currentPath . "/helpers.php";

?>