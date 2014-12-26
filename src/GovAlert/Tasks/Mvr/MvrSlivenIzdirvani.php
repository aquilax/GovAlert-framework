<?php

namespace GovAlert\Tasks\Mvr;

class MvrSlivenIzdirvani extends Base
{

	protected $categoryPrefix = '[Сливен] ';
	protected $sourceName = 'МВР Сливен';
	protected $categoryName = 'изчезнали';
	protected $categoryId = 35;
	protected $categoryURL = 'http://sliven.mvr.bg/Prescentar/Izdirvani_lica/default.htm';
	protected $categoryURLBase = 'http://sliven.mvr.bg';
	protected $tweetReTweet = 'lipsva';
	protected $categoryExpectEmpty = true;

} 