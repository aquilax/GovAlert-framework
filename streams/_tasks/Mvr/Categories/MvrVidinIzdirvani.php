<?php

class MvrVidinIzdirvani extends Mvr
{

	protected $channelPrefix = '[Видин] ';
	protected $sourceName = 'МВР Видин';
	protected $channelName = 'изчезнали';
	protected $channelId = 9;
	protected $channelURL = 'http://www.vidin.mvr.bg/Pressoffice/Izdirvani_lica/default.htm';
	protected $channelURLBase = 'http://www.vidin.mvr.bg';
	protected $tweetReTweet = 'lipsva';
	protected $channelExpectEmpty = true;

}
