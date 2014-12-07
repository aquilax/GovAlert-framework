<?php

/*

0 http://www.comdos.bg/

*/

abstract class Comdos extends Task
{
	protected $sourceId = 5;
	protected $sourceName = 'КомДос';

	protected function cleanText($text)
	{
		$text = str_replace(" ", " ", $text);
		$text = mb_ereg_replace("[\n\r\t ]+", " ", $text);
		$text = mb_ereg_replace("(^\s+)|(\s+$)", "", $text);
		$text = html_entity_decode($text);
		return $text;
	}
}


