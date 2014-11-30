<?php
require_once('init.php');
if ($debug) {
	exit;
}
require_once(BASEPATH . "/_classes/twitter.php");

echo "\n\nSTREAMS ".date("r")."\n";
runTasks(count($argv)>1);
postTwitter();
echo "END\n";
