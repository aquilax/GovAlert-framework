<?php

class MvrTargovishte extends Mvr {

	protected $channelPrefix = '[Търговище] ';
	protected $sourceName = 'МВР Търговище';
	protected $channelName = 'новини';
	protected $channelId = 41;
	protected $channelURL = 'http://targovishte.mvr.bg/Prescentar/Novini/default.htm';
	protected $channelURLBase = 'http://targovishte.mvr.bg';
	protected $channelReTweet = false;
	protected $channelExpectEmpty = false;

}
