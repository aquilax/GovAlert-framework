<?php

namespace GovAlert\Tasks\Mvr;

class MvrGabrovoIzdirvani extends Base
{
	protected $categoryPrefix = '[Габрово] ';
	protected $sourceName = 'МВР Габрово';
	protected $categoryName = 'изчезнали';
	protected $categoryId = 13;
	protected $categoryURL = 'http://www.gabrovo.mvr.bg/PressOffice/Wanted/default.htm';
	protected $categoryURLBase = 'http://www.gabrovo.mvr.bg';
	protected $tweetReTweet = 'lipsva';
	protected $categoryExpectEmpty = true;
}
