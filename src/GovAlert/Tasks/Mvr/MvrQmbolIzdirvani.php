<?php

namespace GovAlert\Tasks\Mvr;

class MvrQmbolIzdirvani extends Base
{

	protected $categoryPrefix = '[Ямбол] ';
	protected $sourceName = 'МВР Ямбол';
	protected $categoryName = 'изчезнали';
	protected $categoryId = 46;
	protected $categoryURL = 'http://www.yambol.mvr.bg/Izdirvani_lica/default.htm';
	protected $categoryURLBase = 'http://www.yambol.mvr.bg';
	protected $tweetReTweet = 'lipsva';
	protected $categoryExpectEmpty = true;

}