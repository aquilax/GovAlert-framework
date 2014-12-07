<?php

class MvrVidin extends Mvr
{

	protected $channelPrefix = '[Видин] ';
	protected $sourceName = 'МВР Видин';
	protected $channelName = 'новини';
	protected $channelId = 8;
	protected $channelURL = 'http://www.vidin.mvr.bg/PressOffice/News/default.htm';
	protected $channelURLBase = 'http://www.vidin.mvr.bg';
	protected $tweetReTweet = false;
	protected $channelExpectEmpty = false;

} 