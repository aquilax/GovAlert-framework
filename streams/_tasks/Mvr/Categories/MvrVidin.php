<?php

class MvrVidin extends Mvr
{

	protected $categoryPrefix = '[Видин] ';
	protected $sourceName = 'МВР Видин';
	protected $categoryName = 'новини';
	protected $categoryId = 8;
	protected $categoryURL = 'http://www.vidin.mvr.bg/PressOffice/News/default.htm';
	protected $categoryURLBase = 'http://www.vidin.mvr.bg';
	protected $tweetReTweet = false;
	protected $categoryExpectEmpty = false;

} 