<?php

class MvrMontanaIzdirvani extends Mvr
{

	protected $channelPrefix = '[Монтана] ';
	protected $sourceName = 'МВР Монтана';
	protected $channelName = 'изчезнали';
	protected $channelId = 21;
	protected $channelURL = 'http://www.montana.mvr.bg/PressOffice/Wanted/default.htm';
	protected $channelURLBase = 'http://www.montana.mvr.bg';
	protected $tweetReTweet = 'lipsva';
	protected $channelExpectEmpty = true;

}