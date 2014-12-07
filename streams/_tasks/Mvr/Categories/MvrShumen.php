<?php

class MvrShumen extends Mvr
{

	protected $channelPrefix = '[Шумен] ';
	protected $sourceName = 'МВР Шумен';
	protected $channelName = 'новини';
	protected $channelId = 43;
	protected $channelURL = 'http://www.shumen.mvr.bg/Prescentar/Novini/default.htm';
	protected $channelURLBase = 'http://www.shumen.mvr.bg';
	protected $tweetReTweet = false;
	protected $channelExpectEmpty = false;

}
