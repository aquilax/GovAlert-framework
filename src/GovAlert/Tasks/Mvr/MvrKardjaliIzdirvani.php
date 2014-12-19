<?php

namespace GovAlert\Tasks\Mvr;

class MvrKardjaliIzdirvani extends Base
{
	protected $categoryPrefix = '[Кърджали] ';
	protected $sourceName = 'МВР Кърджали';
	protected $categoryName = 'изчезнали';
	protected $categoryId = 16;
	protected $categoryURL = 'http://www.kardjali.mvr.bg/PressOffice/Izirva_se/default.htm';
	protected $categoryURLBase = 'http://www.kardjali.mvr.bg';
	protected $tweetReTweet = 'lipsva';
	protected $categoryExpectEmpty = true;
}
