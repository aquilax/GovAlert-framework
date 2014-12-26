<?php

namespace GovAlert\Tasks\Mvr;

class MvrVelikotarnovo extends Base
{

	protected $categoryPrefix = '[В.Търново] ';
	protected $sourceName = 'МВР В.Търново';
	protected $categoryName = 'новини';
	protected $categoryId = 6;
	protected $categoryURL = 'http://www.veliko-tarnovo.mvr.bg/Prescentar/Novini/default.htm';
	protected $categoryURLBase = 'http://www.veliko-tarnovo.mvr.bg';
	protected $tweetReTweet = false;
	protected $categoryExpectEmpty = false;

}
