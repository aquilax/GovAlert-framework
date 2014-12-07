<?php

class MvrGabrovo extends Mvr
{

	protected $channelPrefix = '[Габрово] ';
	protected $sourceName = 'МВР Габрово';
	protected $channelName = 'новини';
	protected $channelId = 12;
	protected $channelURL = 'http://www.gabrovo.mvr.bg/PressOffice/News/default';
	protected $channelURLBase = 'http://www.gabrovo.mvr.bg';
	protected $tweetReTweet = false;
	protected $channelExpectEmpty = false;
} 