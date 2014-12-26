<?php

namespace GovAlert\Tasks\Mvr;

class MvrPazardjikIzdirvani extends Base
{

	protected $categoryPrefix = '[Пазарджик] ';
	protected $sourceName = 'МВР Пазарджик';
	protected $categoryName = 'изчезнали';
	protected $categoryId = 23;
	protected $categoryURL = 'http://pazardjik.mvr.bg/Prescentar/Izdirvani_lica/default.htm';
	protected $categoryURLBase = 'http://pazardjik.mvr.bg';
	protected $tweetReTweet = 'lipsva';
	protected $categoryExpectEmpty = true;

} 