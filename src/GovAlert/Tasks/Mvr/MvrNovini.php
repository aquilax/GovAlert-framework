<?php

namespace GovAlert\Tasks\Mvr;

class MvrNovini extends Base
{

	protected $categoryPrefix = '';
	protected $sourceName = 'МВР';
	protected $categoryName = 'новини';
	protected $categoryId = 0;
	protected $categoryURL = 'http://press.mvr.bg/default.htm';
	protected $categoryURLBase = 'http://press.mvr.bg';
	protected $tweetReTweet = 'govalerteu';
	protected $categoryExpectEmpty = false;

} 