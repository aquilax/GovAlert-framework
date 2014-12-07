<?php

class MvrPlevenIzdirvani extends Mvr
{

	protected $channelPrefix = '[Плевен] ';
	protected $sourceName = 'МВР Плевен';
	protected $channelName = 'изчезнали';
	protected $channelId = 27;
	protected $channelURL = 'http://www.pleven.mvr.bg/PressOffice/Wanted/default.htm';
	protected $channelURLBase = 'http://www.pleven.mvr.bg';
	protected $tweetReTweet = 'lipsva';
	protected $channelExpectEmpty = true;

}
