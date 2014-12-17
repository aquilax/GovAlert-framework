<?php

class MvrBlagoevgrad extends Mvr
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