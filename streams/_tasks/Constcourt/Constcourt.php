<?php

/*

0: новини http://constcourt.bg/news
1: съобщения по дела http://constcourt.bg/caseannouncements

*/

class Constcourt extends Task
{
	protected $sourceId = 8;
	protected $sourceName = 'Конституционен съд';

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
		$text = Utils::cleanSpaces($text);
		$text = mb_ereg_replace("Конституционният? съд", "КС", $text, "im");
		$text = html_entity_decode($text);
		return $text;
	}
}
