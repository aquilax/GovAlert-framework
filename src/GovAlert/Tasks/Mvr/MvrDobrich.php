<?php

namespace GovAlert\Tasks\Mvr;

class MvrDobrich extends Base
{
	protected $categoryPrefix = '[Добрич] ';
	protected $sourceName = 'МВР Добрич';
	protected $categoryName = 'новини';
	protected $categoryId = 14;
	protected $categoryURL = 'http://dobrich.mvr.bg/Prescentar/Novini/default.htm';
	protected $categoryURLBase = 'http://dobrich.mvr.bg';
	protected $tweetReTweet = false;
	protected $categoryExpectEmpty = false;

} 