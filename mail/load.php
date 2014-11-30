<?php
$path = __DIR__;
require_once($path . '/../streams/init.php');
require_once(BASEPATH . '/_classes/twitter.php');
require_once($path . '/strategy/tasks.php');

strategy_processUrl('http://www.strategy.bg/PublicConsultations/View.aspx?lang=bg-BG&Id=1234');