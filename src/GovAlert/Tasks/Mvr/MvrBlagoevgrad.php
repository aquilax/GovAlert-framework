<?php

namespace GovAlert\Tasks\Mvr;

class MvrBlagoevgrad extends Base
{
	protected $categoryPrefix = '[Благоевград] ';
	protected $sourceName = 'МВР Благоевград';
	protected $categoryName = 'новини';
	protected $categoryId = 2;
	protected $categoryURL = 'http://www.blagoevgrad.mvr.bg/Prescentar/Novini/default.htm';
	protected $categoryURLBase = 'http://www.blagoevgrad.mvr.bg';
	protected $tweetReTweet = false;
	protected $categoryExpectEmpty = false;
}