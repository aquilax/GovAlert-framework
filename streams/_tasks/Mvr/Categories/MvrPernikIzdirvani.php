<?php

class MvrPernikIzdirvani extends Mvr
{

	protected $channelPrefix = '[Перник] ';
	protected $sourceName = 'МВР Перник';
	protected $channelName = 'изчезнали';
	protected $channelId = 25;
	protected $channelURL = 'http://www.pernik.mvr.bg/Prescentar/Izdirvani_lica/default.htm';
	protected $channelURLBase = 'http://www.pernik.mvr.bg';
	protected $channelReTweet = 'lipsva';
	protected $channelExpectEmpty = true;

}
