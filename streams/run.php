<?php
require_once('init.php');
if ($debug) {
	exit;
}
require_once(BASEPATH . "/_classes/twitter.php");

$logger->info('STREAMS');

$force = count($argv) > 1;
TaskManager::runTasks($db, $logger, $force );
postTwitter();

$logger->info('END');
