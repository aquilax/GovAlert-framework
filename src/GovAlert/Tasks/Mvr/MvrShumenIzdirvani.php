<?php

namespace GovAlert\Tasks\Mvr;

class MvrShumenIzdirvani extends Base
{

	protected $categoryPrefix = '[Шумен] ';
	protected $sourceName = 'МВР Шумен';
	protected $categoryName = 'изчезнали';
	protected $categoryId = 44;
	protected $categoryURL = 'http://www.shumen.mvr.bg/Prescentar/Izdirvani_lica/default.htm';
	protected $categoryURLBase = 'http://www.shumen.mvr.bg';
	protected $tweetReTweet = 'lipsva';
	protected $categoryExpectEmpty = true;

}
