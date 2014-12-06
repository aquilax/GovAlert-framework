<?php

class MvrSilistraIzdirvani extends Mvr
{

	protected $channelPrefix = '[Силистра] ';
	protected $sourceName = 'МВР Силистра';
	protected $channelName = 'изчезнали';
	protected $channelId = 33;
	protected $channelURL = 'http://www.silistra.mvr.bg/Prescentar/Izdirvani_lica/default.htm';
	protected $channelURLBase = 'http://www.silistra.mvr.bg';
	protected $channelReTweet = 'lipsva';
	protected $channelExpectEmpty = true;

}
