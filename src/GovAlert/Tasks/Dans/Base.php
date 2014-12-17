<?php

/*
Links
0: информация http://www.dans.bg/bg/component/bca-rss-syndicator/?feed_id=1
*/

namespace GovAlert\Tasks\Dans;

abstract class Base extends \GovAlert\Tasks\Task
{
	protected $sourceId = 17;
	protected $sourceName = 'ДАНС';
} 