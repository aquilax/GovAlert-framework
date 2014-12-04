<?php
$path = __DIR__;
require_once($path . '/../streams/init.php');
require_once(BASEPATH . '_classes/twitter.php');

$mail = stream_get_contents(STDIN);

ob_start();

$logger->info('> Нов мейл');

if (strpos($mail,"From: info@strategy.bg")!==false) {
	$strategy = new Strategy($db, $logger);
	$strategy->mailStrategy($mail)
}

echo "done\n";

$contents = ob_get_flush();
file_put_contents("$path/../log/mail.log",$contents,FILE_APPEND);
