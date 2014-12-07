<?php

class MvrPleven extends Mvr
{

	protected $channelPrefix = '[Плевен] ';
	protected $sourceName = 'МВР Плевен';
	protected $channelName = 'новини';
	protected $channelId = 26;
	protected $channelURL = 'http://www.pleven.mvr.bg/PressOffice/News/default.htm';
	protected $channelURLBase = 'http://www.pleven.mvr.bg';
	protected $tweetReTweet = false;
	protected $channelExpectEmpty = false;

} 