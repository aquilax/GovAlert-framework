<?php

namespace GovAlert\Tasks\Mvr;

class MvrSilistraIzdirvani extends Base
{

	protected $categoryPrefix = '[Силистра] ';
	protected $sourceName = 'МВР Силистра';
	protected $categoryName = 'изчезнали';
	protected $categoryId = 33;
	protected $categoryURL = 'http://www.silistra.mvr.bg/Prescentar/Izdirvani_lica/default.htm';
	protected $categoryURLBase = 'http://www.silistra.mvr.bg';
	protected $tweetReTweet = 'lipsva';
	protected $categoryExpectEmpty = true;

}
