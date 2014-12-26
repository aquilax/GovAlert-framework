<?php

namespace GovAlert\Tasks\Mvr;

class MvrVidinIzdirvani extends Base
{

	protected $categoryPrefix = '[Видин] ';
	protected $sourceName = 'МВР Видин';
	protected $categoryName = 'изчезнали';
	protected $categoryId = 9;
	protected $categoryURL = 'http://www.vidin.mvr.bg/Pressoffice/Izdirvani_lica/default.htm';
	protected $categoryURLBase = 'http://www.vidin.mvr.bg';
	protected $tweetReTweet = 'lipsva';
	protected $categoryExpectEmpty = true;

}
