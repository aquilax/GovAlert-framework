<?php

ini_set('user_agent', 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/33.0.1750.152 Safari/537.36 (email=yurukov@gmail.com; reason=scraping data,please contact if any issues)');
ini_set('default_socket_timeout', 30);
set_time_limit(0);
date_default_timezone_set('Europe/Sofia');
mb_internal_encoding("UTF-8");
mb_regex_encoding("UTF-8");

$loader = require __DIR__ . "/vendor/autoload.php";

$classesBase = \GovAlert\Config::get('classesBase');

$logger = new \GovAlert\Common\Logger(Config::get('debugLevel'));
$db = new \GovAlert\Common\Database(\GovAlert\Config::get('db'), $logger);
$debug = false;