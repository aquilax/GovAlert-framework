<?php

class MvrPernik extends Mvr{

	protected $channelPrefix = '[Перник] ';
	protected $sourceName = 'МВР Перник';
	protected $channelName = 'новини';
	protected $channelId = 24;
	protected $channelURL = 'http://www.pernik.mvr.bg/Prescentar/Novini/default.htm';
	protected $channelURLBase = 'http://www.pernik.mvr.bg';
	protected $channelReTweet = false;
	protected $channelExpectEmpty = false;

}
