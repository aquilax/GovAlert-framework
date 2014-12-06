<?php

class MvrKampanii extends Mvr
{

	protected $channelPrefix = '';
	protected $sourceName = 'МВР';
	protected $channelName = 'кампании';
	protected $channelId = 1;
	protected $channelURL = 'http://press.mvr.bg/Kampanii/default.htm';
	protected $channelURLBase = 'http://press.mvr.bg';
	protected $channelReTweet = false;
	protected $channelExpectEmpty = false;

}
