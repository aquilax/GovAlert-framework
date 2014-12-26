<?php

namespace GovAlert\Tasks\Mvr;

class MvrStaraZagoraIzdirvani extends Base
{

	protected $categoryPrefix = '[С.Загора] ';
	protected $sourceName = 'МВР С.Загора';
	protected $categoryName = 'изчезнали';
	protected $categoryId = 40;
	protected $categoryURL = 'http://www.starazagora.mvr.bg/PressOffice/Wanted/default.htm';
	protected $categoryURLBase = 'http://www.starazagora.mvr.bg';
	protected $tweetReTweet = 'lipsva';
	protected $categoryExpectEmpty = true;

}
