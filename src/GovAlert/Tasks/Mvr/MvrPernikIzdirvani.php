<?php

namespace GovAlert\Tasks\Mvr;

class MvrPernikIzdirvani extends Base
{

	protected $categoryPrefix = '[Перник] ';
	protected $sourceName = 'МВР Перник';
	protected $categoryName = 'изчезнали';
	protected $categoryId = 25;
	protected $categoryURL = 'http://www.pernik.mvr.bg/Prescentar/Izdirvani_lica/default.htm';
	protected $categoryURLBase = 'http://www.pernik.mvr.bg';
	protected $tweetReTweet = 'lipsva';
	protected $categoryExpectEmpty = true;

}
