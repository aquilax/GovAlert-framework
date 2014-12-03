<?php
require_once('init.php');
require_once(BASEPATH . '/_classes/twitter.php');
$debug = 1;

if ($argc < 3) {
	die('Необнодимите аргументи са lib task delay' . PHP_EOL);
}

$taskManager = new TaskManager($db, $logger);
$taskManager->runTask($argv[1], $argv[2], $argv[3], false);
