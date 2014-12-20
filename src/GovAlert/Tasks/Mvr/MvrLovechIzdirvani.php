<?php

namespace GovAlert\Tasks\Mvr;

class MvrLovechIzdirvani extends Base
{

	protected $categoryPrefix = '[Ловеч] ';
	protected $sourceName = 'МВР Ловеч';
	protected $categoryName = 'изчезнали';
	protected $categoryId = 19;
	protected $categoryURL = 'http://www.lovech.mvr.bg/PressOffice/Wanted/default.htm';
	protected $categoryURLBase = 'http://www.lovech.mvr.bg';
	protected $tweetReTweet = 'lipsva';
	protected $categoryExpectEmpty = true;

}
