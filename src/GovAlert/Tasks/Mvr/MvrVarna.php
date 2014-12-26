<?php

namespace GovAlert\Tasks\Mvr;

class MvrVarna extends Base
{

	protected $categoryPrefix = '[Варна] ';
	protected $sourceName = 'МВР Варна';
	protected $categoryName = 'новини';
	protected $categoryId = 5;
	protected $categoryURL = 'http://varna.mvr.bg/Prescentar/Novini/default.htm';
	protected $categoryURLBase = 'http://varna.mvr.bg';
	protected $tweetReTweet = false;
	protected $categoryExpectEmpty = false;

}
