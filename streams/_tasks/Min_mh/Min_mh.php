<?php

/*

0: съобщения http://www.mh.government.bg/AllMessages.aspx
1: новини http://www.mh.government.bg/News.aspx?pageid=401
2: проекти за нормативни актове http://www.mh.government.bg/Articles.aspx?lang=bg-BG&pageid=393
3: наредби http://www.mh.government.bg/Articles.aspx?lang=bg-BG&pageid=391
4: постановления http://www.mh.government.bg/Articles.aspx?lang=bg-BG&pageid=381
5: отчети http://www.mh.government.bg/Articles.aspx?lang=bg-BG&pageid=532&currentPage=1

*/

abstract class Min_mh extends Task
{
	protected $sourceId = 21;
	protected $sourceName = 'МЗ';
}
