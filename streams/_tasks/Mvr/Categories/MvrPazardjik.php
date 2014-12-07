<?php

class MvrPazardjik extends Mvr
{

	protected $channelPrefix = '[Пазарджик] ';
	protected $sourceName = 'МВР Пазарджик';
	protected $channelName = 'новини';
	protected $channelId = 22;
	protected $channelURL = 'http://pazardjik.mvr.bg/Prescentar/Novini/default.htm';
	protected $channelURLBase = 'http://pazardjik.mvr.bg';
	protected $tweetReTweet = false;
	protected $channelExpectEmpty = false;

} 