<?php

class MvrKiustendil extends Mvr
{

	protected $channelPrefix = '[Кюстендил] ';
	protected $sourceName = 'МВР Кюстендил';
	protected $channelName = 'новини';
	protected $channelId = 17;
	protected $channelURL = 'http://www.kustendil.mvr.bg/PressOffice/News/default.htm';
	protected $channelURLBase = 'http://www.kustendil.mvr.bg';
	protected $tweetReTweet = false;
	protected $channelExpectEmpty = false;

}
