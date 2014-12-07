<?php
$path = __DIR__;
require_once($path . '/../streams/init.php');
require_once(BASEPATH . '_classes/Twitter.php');

$mail = stream_get_contents(STDIN);

ob_start();

$logger->info('> Нов мейл');

if (strpos($mail, "From: info@strategy.bg") !== false) {
	$strategy = new Strategy($db, $logger);
	$strategy->mailStrategy($mail);
}

$logger->info('done');

$contents = ob_get_flush();
file_put_contents("$path/../log/mail.log", $contents, FILE_APPEND);
