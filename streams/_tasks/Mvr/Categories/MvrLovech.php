<?php

class MvrLovech extends Mvr
{

	protected $channelPrefix = '[Ловеч] ';
	protected $sourceName = 'МВР Ловеч';
	protected $channelName = 'новини';
	protected $channelId = 18;
	protected $channelURL = 'http://www.lovech.mvr.bg/PressOffice/News/default.htm';
	protected $channelURLBase = 'http://www.lovech.mvr.bg';
	protected $channelReTweet = false;
	protected $channelExpectEmpty = false;

}
