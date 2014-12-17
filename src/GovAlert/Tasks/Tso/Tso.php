<?php

/*
links:
0: новини http://www.tso.bg/default.aspx/novini/bg
1: съобщения http://www.tso.bg/default.aspx/saobshtenija/bg
*/

abstract class Tso extends Task
{

	protected $sourceId = 7;
	protected $sourceName = 'ЕСО';

	function xpathDoc($html)
	{
		if (!$html)
			return false;
		$html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
		$doc = new DOMDocument("1.0", "UTF-8");
		$doc->preserveWhiteSpace = false;
		$doc->strictErrorChecking = false;
		$doc->encoding = 'UTF-8';
		$doc->loadHTML($html);
		return new DOMXpath($doc);
	}


	function cleanText($text)
	{
		$text = str_replace(" ", " ", $text);
		$text = mb_ereg_replace("[\n\r\t ]+", " ", $text);
		$text = mb_ereg_replace("(^\s+)|(\s+$)", "", $text);
		$text = html_entity_decode($text);
		return $text;
	}
}

