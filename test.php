<?php
require_once('init.php');
$debug = 1;

if ($argc < 3) {
	die('Необходимите аргументи са lib task delay' . PHP_EOL);
}

$taskManager = new \GovAlert\Common\TaskManager($db, $logger);
$taskManager->runTask($argv[1], $argv[2], $argv[3], false);
