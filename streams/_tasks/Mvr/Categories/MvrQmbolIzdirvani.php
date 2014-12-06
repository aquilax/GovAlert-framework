<?php

class MvrQmbolIzdirvani extends Mvr {

	protected $channelPrefix = '[Ямбол] ';
	protected $sourceName = 'МВР Ямбол';
	protected $channelName = 'изчезнали';
	protected $channelId = 46;
	protected $channelURL = 'http://www.yambol.mvr.bg/Izdirvani_lica/default.htm';
	protected $channelURLBase = 'http://www.yambol.mvr.bg';
	protected $channelReTweet = 'lipsva';
	protected $channelExpectEmpty = true;

}