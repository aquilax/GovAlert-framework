<?php

namespace GovAlert\Tasks\Mvr;

class MvrPernik extends Base
{

	protected $categoryPrefix = '[Перник] ';
	protected $sourceName = 'МВР Перник';
	protected $categoryName = 'новини';
	protected $categoryId = 24;
	protected $categoryURL = 'http://www.pernik.mvr.bg/Prescentar/Novini/default.htm';
	protected $categoryURLBase = 'http://www.pernik.mvr.bg';
	protected $tweetReTweet = false;
	protected $categoryExpectEmpty = false;

}
