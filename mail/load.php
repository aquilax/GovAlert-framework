<?php
$path = __DIR__;
require_once($path . '/../streams/init.php');
require_once($path . '/../streams/_tasks/Strategy.php');

$strategy = new Strategy($db, $logger);
$strategy->strategy_processUrl('http://www.strategy.bg/PublicConsultations/View.aspx?lang=bg-BG&Id=1234');

