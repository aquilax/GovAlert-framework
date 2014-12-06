<?php
class MvrDobrich extends Mvr {

	protected $channelPrefix = '[Добрич] ';
	protected $sourceName = 'МВР Добрич';
	protected $channelName = 'новини';
	protected $channelId = 14;
	protected $channelURL = 'http://dobrich.mvr.bg/Prescentar/Novini/default.htm';
	protected $channelURLBase = 'http://dobrich.mvr.bg';
	protected $channelReTweet = false;
	protected $channelExpectEmpty = false;

} 