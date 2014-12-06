<?php

class MvrVarna extends Mvr
{

	protected $channelPrefix = '[Варна] ';
	protected $sourceName = 'МВР Варна';
	protected $channelName = 'новини';
	protected $channelId = 5;
	protected $channelURL = 'http://varna.mvr.bg/Prescentar/Novini/default.htm';
	protected $channelURLBase = 'http://varna.mvr.bg';
	protected $channelReTweet = false;
	protected $channelExpectEmpty = false;

}
