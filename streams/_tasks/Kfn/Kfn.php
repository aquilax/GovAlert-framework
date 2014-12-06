<?php

/*
links:
0: новини http://www.fsc.bg/Novini-bg
2: анализи http://www.fsc.bg/Analizi-na-KFN-bg-29
*/

abstract class Kfn extends Task
{
	protected $sourceId = 22;
	protected $sourceName = 'КФН';

	protected function xpathDoc($html, $q)
	{
		if (!$html)
			return array();
		$html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
		$doc = new DOMDocument("1.0", "UTF-8");
		$doc->preserveWhiteSpace = false;
		$doc->strictErrorChecking = false;
		$doc->encoding = 'UTF-8';
		$doc->loadHTML($html);
		$xpath = new DOMXpath($doc);

		$items = $xpath->query($q);
		return is_null($items) ? array() : $items;
	}


	protected function cleanText($text)
	{
		$text = str_replace(" ", " ", $text);
		$text = mb_ereg_replace("[\n\r\t ]+", " ", $text);
		$text = mb_ereg_replace("(^\s+)|(\s+$)", "", $text);
		$text = html_entity_decode($text);
		return $text;
	}

} 