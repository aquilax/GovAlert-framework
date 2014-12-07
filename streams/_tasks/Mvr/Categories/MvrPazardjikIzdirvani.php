<?php

class MvrPazardjikIzdirvani extends Mvr
{

	protected $channelPrefix = '[Пазарджик] ';
	protected $sourceName = 'МВР Пазарджик';
	protected $channelName = 'изчезнали';
	protected $channelId = 23;
	protected $channelURL = 'http://pazardjik.mvr.bg/Prescentar/Izdirvani_lica/default.htm';
	protected $channelURLBase = 'http://pazardjik.mvr.bg';
	protected $tweetReTweet = 'lipsva';
	protected $channelExpectEmpty = true;

} 