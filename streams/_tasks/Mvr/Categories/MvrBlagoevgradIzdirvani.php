<?php

class MvrBlagoevgradIzdirvani extends Mvr
{

	protected $channelPrefix = '[Благоевград] ';
	protected $sourceName = 'МВР Благоевград';
	protected $channelName = 'издирвани';
	protected $channelId = 3;
	protected $channelURL = 'http://www.blagoevgrad.mvr.bg/Prescentar/Izdirvani_lica/default.htm';
	protected $channelURLBase = 'http://www.blagoevgrad.mvr.bg';
	protected $tweetReTweet = 'lipsva';
	protected $channelExpectEmpty = true;

} 