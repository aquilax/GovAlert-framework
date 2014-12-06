<?php

class MvrVracaIzdirvani extends Mvr {

	protected $channelPrefix = '[Враца] ';
	protected $sourceName = 'МВР Враца';
	protected $channelName = 'изчезнали';
	protected $channelId = 11;
	protected $channelURL = 'http://www.vratza.mvr.bg/Pressoffice/Izdirvani_lica/default.htm';
	protected $channelURLBase = 'http://www.vratza.mvr.bg';
	protected $channelReTweet = 'lipsva';
	protected $channelExpectEmpty = true;

}
