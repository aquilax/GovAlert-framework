<?php

namespace GovAlert\Tasks\Mvr;

class MvrKiustendil extends Base
{
	protected $categoryPrefix = '[Кюстендил] ';
	protected $sourceName = 'МВР Кюстендил';
	protected $categoryName = 'новини';
	protected $categoryId = 17;
	protected $categoryURL = 'http://www.kustendil.mvr.bg/PressOffice/News/default.htm';
	protected $categoryURLBase = 'http://www.kustendil.mvr.bg';
	protected $tweetReTweet = false;
	protected $categoryExpectEmpty = false;
}
