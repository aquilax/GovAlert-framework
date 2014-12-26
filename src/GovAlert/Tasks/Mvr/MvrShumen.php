<?php

namespace GovAlert\Tasks\Mvr;

class MvrShumen extends Base
{

	protected $categoryPrefix = '[Шумен] ';
	protected $sourceName = 'МВР Шумен';
	protected $categoryName = 'новини';
	protected $categoryId = 43;
	protected $categoryURL = 'http://www.shumen.mvr.bg/Prescentar/Novini/default.htm';
	protected $categoryURLBase = 'http://www.shumen.mvr.bg';
	protected $tweetReTweet = false;
	protected $categoryExpectEmpty = false;

}
