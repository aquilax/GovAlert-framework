<?php

class MvrSliven extends Mvr {

	protected $channelPrefix = '[Сливен] ';
	protected $sourceName = 'МВР Сливен';
	protected $channelName = 'новини';
	protected $channelId = 34;
	protected $channelURL = 'http://sliven.mvr.bg/Prescentar/Novini/default.htm';
	protected $channelURLBase = 'http://sliven.mvr.bg';
	protected $channelReTweet = false;
	protected $channelExpectEmpty = false;

}
