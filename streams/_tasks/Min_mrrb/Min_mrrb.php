<?php

/*

0: обяви http://www.mrrb.government.bg/?controller=category&action=notice&catid=38
1: полезна информация http://www.mrrb.government.bg/?controller=category&catid=39

*/

abstract class Min_mrrb extends Task
{
	protected $sourceId = 10;
	protected $sourceName = 'МРРБ';

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
		$text = html_entity_decode($text);
		$text = Utils::cleanSpaces($text);
		$text = Utils::fixCase($text);
		return $text;
	}

} 