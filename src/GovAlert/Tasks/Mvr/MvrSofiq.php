<?php

namespace GovAlert\Tasks\Mvr;

class MvrSofiq extends Base
{

	protected $categoryPrefix = '[София] ';
	protected $sourceName = 'МВР София';
	protected $categoryName = 'новини';
	protected $categoryId = 38;
	protected $categoryURL = 'http://www.odmvr-sofia.mvr.bg/Prescentar/Novini/default.htm';
	protected $categoryURLBase = 'http://www.odmvr-sofia.mvr.bg';
	protected $tweetReTweet = false;
	protected $categoryExpectEmpty = false;

}
