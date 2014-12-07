<?php

class MvrBlagoevgrad extends Mvr
{
	protected $channelPrefix = '[Благоевград] ';
	protected $sourceName = 'МВР Благоевград';
	protected $channelName = 'новини';
	protected $channelId = 2;
	protected $channelURL = 'http://www.blagoevgrad.mvr.bg/Prescentar/Novini/default.htm';
	protected $channelURLBase = 'http://www.blagoevgrad.mvr.bg';
	protected $tweetReTweet = false;
	protected $channelExpectEmpty = false;
}