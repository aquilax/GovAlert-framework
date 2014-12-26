<?php

namespace GovAlert\Tasks\Mvr;

class MvrPleven extends Base
{

	protected $categoryPrefix = '[Плевен] ';
	protected $sourceName = 'МВР Плевен';
	protected $categoryName = 'новини';
	protected $categoryId = 26;
	protected $categoryURL = 'http://www.pleven.mvr.bg/PressOffice/News/default.htm';
	protected $categoryURLBase = 'http://www.pleven.mvr.bg';
	protected $tweetReTweet = false;
	protected $categoryExpectEmpty = false;

} 