<?php

class MvrHaskovo extends Mvr
{

	protected $channelPrefix = '[Хасково] ';
	protected $sourceName = 'МВР Хасково';
	protected $channelName = 'новини';
	protected $channelId = 42;
	protected $channelURL = 'http://haskovo.mvr.bg/Prescentar/Novini/default.htm';
	protected $channelURLBase = 'http://haskovo.mvr.bg';
	protected $tweetReTweet = false;
	protected $channelExpectEmpty = false;

}
