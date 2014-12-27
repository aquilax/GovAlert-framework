<?php

namespace GovAlert\Tasks\Mvr;

class MvrQmbol extends Base
{

	protected $categoryPrefix = '[Ямбол] ';
	protected $sourceName = 'МВР Ямбол';
	protected $categoryName = 'новини';
	protected $categoryId = 45;
	protected $categoryURL = 'http://www.yambol.mvr.bg/Prescentar/Novini/default.htm';
	protected $categoryURLBase = 'http://www.yambol.mvr.bg';
	protected $tweetReTweet = false;
	protected $categoryExpectEmpty = false;

}
