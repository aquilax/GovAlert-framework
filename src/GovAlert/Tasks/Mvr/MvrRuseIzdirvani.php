<?php

namespace GovAlert\Tasks\Mvr;

class MvrRuseIzdirvani extends Base
{

	protected $categoryPrefix = '[Русе] ';
	protected $sourceName = 'МВР Русе';
	protected $categoryName = 'изчезнали';
	protected $categoryId = 31;
	protected $categoryURL = 'http://www.ruse.mvr.bg/Prescentar/Izdirvani_lica/default.htm';
	protected $categoryURLBase = 'http://www.ruse.mvr.bg';
	protected $tweetReTweet = 'lipsva';
	protected $categoryExpectEmpty = true;

}
