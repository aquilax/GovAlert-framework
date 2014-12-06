<?php

class MvrSofiq extends Mvr
{

	protected $channelPrefix = '[София] ';
	protected $sourceName = 'МВР София';
	protected $channelName = 'новини';
	protected $channelId = 38;
	protected $channelURL = 'http://www.odmvr-sofia.mvr.bg/Prescentar/Novini/default.htm';
	protected $channelURLBase = 'http://www.odmvr-sofia.mvr.bg';
	protected $channelReTweet = false;
	protected $channelExpectEmpty = false;

}
