<?php

class MvrPazardjik extends Mvr
{

	protected $categoryPrefix = '[Пазарджик] ';
	protected $sourceName = 'МВР Пазарджик';
	protected $categoryName = 'новини';
	protected $categoryId = 22;
	protected $categoryURL = 'http://pazardjik.mvr.bg/Prescentar/Novini/default.htm';
	protected $categoryURLBase = 'http://pazardjik.mvr.bg';
	protected $tweetReTweet = false;
	protected $categoryExpectEmpty = false;

} 