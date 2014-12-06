<?php

class MvrSilistra extends Mvr{

	protected $channelPrefix = '[Силистра] ';
	protected $sourceName = 'МВР Силистра';
	protected $channelName = 'новини';
	protected $channelId = 32;
	protected $channelURL = 'http://www.silistra.mvr.bg/Prescentar/Novini/default.htm';
	protected $channelURLBase = 'http://www.silistra.mvr.bg';
	protected $channelReTweet = false;
	protected $channelExpectEmpty = false;

}
