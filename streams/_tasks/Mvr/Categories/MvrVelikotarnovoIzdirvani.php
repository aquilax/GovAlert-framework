<?php

class MvrVelikotarnovoIzdirvani extends Mvr
{

	protected $channelPrefix = '[В.Търново] ';
	protected $sourceName = 'МВР В.Търново';
	protected $channelName = 'изчезнали';
	protected $channelId = 7;
	protected $channelURL = 'http://www.veliko-tarnovo.mvr.bg/Prescentar/Izdirvani_lica/';
	protected $channelURLBase = 'http://www.veliko-tarnovo.mvr.bg';
	protected $tweetReTweet = 'lipsva';
	protected $channelExpectEmpty = true;

}
