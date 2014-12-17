<?php

require_once('init.php');

if ($debug) {
	exit;
}

$logger->info('STREAMS');

$force = count($argv) > 1;
$taskManager = new \GovAlert\Common\TaskManager($db, $logger);
$taskManager->runTasks($force);

$twitter = new \GovAlert\Common\Twitter($db, $logger);
$twitter->postTwitter();

$logger->info('END');
