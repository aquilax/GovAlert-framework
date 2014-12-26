<?php

namespace GovAlert\Tasks\Mvr;

class MvrRuse extends Base
{

	protected $categoryPrefix = '[Русе] ';
	protected $sourceName = 'МВР Русе';
	protected $categoryName = 'новини';
	protected $categoryId = 30;
	protected $categoryURL = 'http://www.ruse.mvr.bg/Prescentar/Novini/default.htm';
	protected $categoryURLBase = 'http://www.ruse.mvr.bg';
	protected $tweetReTweet = false;
	protected $categoryExpectEmpty = false;

}
