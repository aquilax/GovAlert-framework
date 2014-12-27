<?php

namespace GovAlert\Tasks\Mvr;

class MvrBlagoevgradIzdirvani extends Base
{
	protected $categoryPrefix = '[Благоевград] ';
	protected $sourceName = 'МВР Благоевград';
	protected $categoryName = 'издирвани';
	protected $categoryId = 3;
	protected $categoryURL = 'http://www.blagoevgrad.mvr.bg/Prescentar/Izdirvani_lica/default.htm';
	protected $categoryURLBase = 'http://www.blagoevgrad.mvr.bg';
	protected $tweetReTweet = 'lipsva';
	protected $categoryExpectEmpty = true;

} 