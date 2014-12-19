<?php

namespace GovAlert\Tasks\Mvr;

class MvrKardjali extends Base
{
	protected $categoryPrefix = '[Кърджали] ';
	protected $sourceName = 'МВР Кърджали';
	protected $categoryName = 'новини';
	protected $categoryId = 15;
	protected $categoryURL = 'http://www.kardjali.mvr.bg/PressOffice/News/default.htm';
	protected $categoryURLBase = 'http://www.kardjali.mvr.bg';
	protected $tweetReTweet = false;
	protected $categoryExpectEmpty = false;
}

