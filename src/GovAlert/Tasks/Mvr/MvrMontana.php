<?php

namespace GovAlert\Tasks\Mvr;

class MvrMontana extends Base
{
	protected $categoryPrefix = '[Монтана] ';
	protected $sourceName = 'МВР Монтана';
	protected $categoryName = 'новини';
	protected $categoryId = 20;
	protected $categoryURL = 'http://www.montana.mvr.bg/PressOffice/News/default.htm';
	protected $categoryURLBase = 'http://www.montana.mvr.bg';
	protected $tweetReTweet = false;
	protected $categoryExpectEmpty = false;
}
