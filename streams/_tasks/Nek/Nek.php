<?php

/*
links:
0: новини http://www.nek.bg/cgi?d=101
*/

abstract class Nek extends Task
{
	protected $sourceId = 6;
	protected $sourceName = 'НЕК';

	function cleanText($text)
	{
		$text = str_replace(" ", " ", $text);
		$text = mb_ereg_replace("[\n\r\t ]+", " ", $text);
		$text = mb_ereg_replace("(^\s+)|(\s+$)", "", $text);
		$text = html_entity_decode($text);
		return $text;
	}
}
