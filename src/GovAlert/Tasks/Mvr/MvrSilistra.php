<?php

namespace GovAlert\Tasks\Mvr;

class MvrSilistra extends Base
{

	protected $categoryPrefix = '[Силистра] ';
	protected $sourceName = 'МВР Силистра';
	protected $categoryName = 'новини';
	protected $categoryId = 32;
	protected $categoryURL = 'http://www.silistra.mvr.bg/Prescentar/Novini/default.htm';
	protected $categoryURLBase = 'http://www.silistra.mvr.bg';
	protected $tweetReTweet = false;
	protected $categoryExpectEmpty = false;

}
