<?php

class MvrVraca extends Mvr
{

	protected $channelPrefix = '[Враца] ';
	protected $sourceName = 'МВР Враца';
	protected $channelName = 'новини';
	protected $channelId = 10;
	protected $channelURL = 'http://www.vratza.mvr.bg/PressOffice/News/default.htm';
	protected $channelURLBase = 'http://www.vratza.mvr.bg';
	protected $channelReTweet = false;
	protected $channelExpectEmpty = false;

} 