<?php

class MvrVelikotarnovo extends Mvr
{

	protected $channelPrefix = '[В.Търново] ';
	protected $sourceName = 'МВР В.Търново';
	protected $channelName = 'новини';
	protected $channelId = 6;
	protected $channelURL = 'http://www.veliko-tarnovo.mvr.bg/Prescentar/Novini/default.htm';
	protected $channelURLBase = 'http://www.veliko-tarnovo.mvr.bg';
	protected $tweetReTweet = false;
	protected $channelExpectEmpty = false;

}
