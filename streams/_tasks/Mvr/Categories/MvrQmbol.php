<?php

class MvrQmbol extends Mvr
{

	protected $channelPrefix = '[Ямбол] ';
	protected $sourceName = 'МВР Ямбол';
	protected $channelName = 'новини';
	protected $channelId = 45;
	protected $channelURL = 'http://www.yambol.mvr.bg/Prescentar/Novini/default.htm';
	protected $channelURLBase = 'http://www.yambol.mvr.bg';
	protected $tweetReTweet = false;
	protected $channelExpectEmpty = false;

}
