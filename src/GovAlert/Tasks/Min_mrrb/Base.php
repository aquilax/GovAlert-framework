<?php

/*

0: обяви http://www.mrrb.government.bg/?controller=category&action=notice&catid=38
1: полезна информация http://www.mrrb.government.bg/?controller=category&catid=39

*/

namespace GovAlert\Tasks\Min_mrrb;

abstract class Base extends \GovAlert\Tasks\Task
{
	protected $sourceId = 10;
	protected $sourceName = 'МРРБ';
}
