<?php

namespace GovAlert\Tasks\Mvr;

class MvrGabrovo extends Base
{
	protected $categoryPrefix = '[Габрово] ';
	protected $sourceName = 'МВР Габрово';
	protected $categoryName = 'новини';
	protected $categoryId = 12;
	protected $categoryURL = 'http://www.gabrovo.mvr.bg/PressOffice/News/default';
	protected $categoryURLBase = 'http://www.gabrovo.mvr.bg';
	protected $tweetReTweet = false;
	protected $categoryExpectEmpty = false;
} 