<?php

ini_set('user_agent', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.152 Safari/537.36 (email=yurukov@gmail.com; reason=scraping data,please contact if any issues)');
ini_set('default_socket_timeout', 30);
set_time_limit(0);
date_default_timezone_set('Europe/Sofia');
mb_internal_encoding("UTF-8");
mb_regex_encoding("UTF-8");

define('BASEPATH', __DIR__);
require_once(BASEPATH . '/_config/Config.php');
$classesBase = BASEPATH . Config::get('classesBase');

require_once($classesBase . '/Database.php');
require_once($classesBase . '/Logger.php');
require_once($classesBase . '/TaskManager.php');
require_once($classesBase . '/Utils.php');
require_once($classesBase . '/Task.php');
require_once($classesBase . '/Images.php');

$logger = new Logger(Config::get('debugLevel'));
$db = new Database(Config::get('db'), $logger);
