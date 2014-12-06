<?php

class MvrMontana extends Mvr
{

	protected $channelPrefix = '[Монтана] ';
	protected $sourceName = 'МВР Монтана';
	protected $channelName = 'новини';
	protected $channelId = 20;
	protected $channelURL = 'http://www.montana.mvr.bg/PressOffice/News/default.htm';
	protected $channelURLBase = 'http://www.montana.mvr.bg';
	protected $channelReTweet = false;
	protected $channelExpectEmpty = false;

}
