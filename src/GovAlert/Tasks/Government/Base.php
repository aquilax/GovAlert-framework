<?php

/*
Links
0: заседания http://www.government.bg/cgi-bin/e-cms/vis/vis.pl?s=001&p=0225&g=
1: решения http://www.government.bg/cgi-bin/e-cms/vis/vis.pl?s=001&p=0228&g=
2: събития http://www.government.bg/cgi-bin/e-cms/vis/vis.pl?s=001&p=0217&g=
3: документ http://www.government.bg/cgi-bin/e-cms/vis/vis.pl?s=001&p=0211&g=
4: водещи новини http://www.government.bg/cgi-bin/e-cms/vis/vis.pl?s=001&p=0213&g=
5: новини http://www.government.bg/cgi-bin/e-cms/vis/vis.pl?s=001&p=0212&g=
6: обществени поръчки http://www.government.bg/cgi-bin/e-cms/vis/vis.pl?s=001&p=0235&g=
*/

namespace GovAlert\Tasks\Government;

abstract class Base extends \GovAlert\Tasks\Task
{
	protected $sourceId = 3;
	protected $sourceName = 'кабинета';
	protected $tweetAccount = 'GovBulgaria';
} 