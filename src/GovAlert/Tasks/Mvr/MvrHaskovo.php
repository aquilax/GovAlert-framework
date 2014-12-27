<?php

namespace GovAlert\Tasks\Mvr;

class MvrHaskovo extends Base
{
	protected $categoryPrefix = '[Хасково] ';
	protected $sourceName = 'МВР Хасково';
	protected $categoryName = 'новини';
	protected $categoryId = 42;
	protected $categoryURL = 'http://haskovo.mvr.bg/Prescentar/Novini/default.htm';
	protected $categoryURLBase = 'http://haskovo.mvr.bg';
	protected $tweetReTweet = false;
	protected $categoryExpectEmpty = false;

}
