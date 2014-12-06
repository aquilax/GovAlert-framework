<?php

class MvrRuse extends Mvr
{

	protected $channelPrefix = '[Русе] ';
	protected $sourceName = 'МВР Русе';
	protected $channelName = 'новини';
	protected $channelId = 30;
	protected $channelURL = 'http://www.ruse.mvr.bg/Prescentar/Novini/default.htm';
	protected $channelURLBase = 'http://www.ruse.mvr.bg';
	protected $channelReTweet = false;
	protected $channelExpectEmpty = false;

}
