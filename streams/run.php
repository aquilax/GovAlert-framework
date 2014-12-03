<?php
require_once('init.php');
if ($debug) {
	exit;
}
require_once(BASEPATH . "/_classes/twitter.php");

$logger->info('STREAMS');

$force = count($argv) > 1;
$taskManager = new TaskManager($db, $logger);
$taskManager->runTasks($force);

$twitter = new Twitter();
$twitter->postTwitter();

$logger->info('END');
