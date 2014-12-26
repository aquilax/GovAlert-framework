<?php

namespace GovAlert\Tasks\Mvr;

class MvrVidin extends Base
{

	protected $categoryPrefix = '[Видин] ';
	protected $sourceName = 'МВР Видин';
	protected $categoryName = 'новини';
	protected $categoryId = 8;
	protected $categoryURL = 'http://www.vidin.mvr.bg/PressOffice/News/default.htm';
	protected $categoryURLBase = 'http://www.vidin.mvr.bg';
	protected $tweetReTweet = false;
	protected $categoryExpectEmpty = false;

} 