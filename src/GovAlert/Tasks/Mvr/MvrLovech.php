<?php

namespace GovAlert\Tasks\Mvr;

class MvrLovech extends Base
{
	protected $categoryPrefix = '[Ловеч] ';
	protected $sourceName = 'МВР Ловеч';
	protected $categoryName = 'новини';
	protected $categoryId = 18;
	protected $categoryURL = 'http://www.lovech.mvr.bg/PressOffice/News/default.htm';
	protected $categoryURLBase = 'http://www.lovech.mvr.bg';
	protected $tweetReTweet = false;
	protected $categoryExpectEmpty = false;
}
