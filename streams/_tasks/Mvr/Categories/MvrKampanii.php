<?php

class MvrKampanii extends Mvr
{

	protected $categoryPrefix = '';
	protected $sourceName = 'МВР';
	protected $categoryName = 'кампании';
	protected $categoryId = 1;
	protected $categoryURL = 'http://press.mvr.bg/Kampanii/default.htm';
	protected $categoryURLBase = 'http://press.mvr.bg';
	protected $tweetReTweet = false;
	protected $categoryExpectEmpty = false;

}
