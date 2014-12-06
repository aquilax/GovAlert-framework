<?php

/*

0 http://www.comdos.bg/

*/

abstract class Comdos extends Task
{
	protected $sourceId = 5;
	protected $sourceName = 'КомДос';

	/**
	 * @param $html
	 * @param $q
	 * @return array|DOMNodeList
	 * @throws Exception
	 */
	protected function xpathDoc($html, $q)
	{
		if (!$html) {
			throw new Exception('Empty HTML passed');
		}
		$html = mb_convert_encoding($html, 'HTML-ENTITIES', "UTF-8");
		$doc = new DOMDocument("1.0", "UTF-8");
		$doc->preserveWhiteSpace = false;
		$doc->strictErrorChecking = false;
		$doc->encoding = 'UTF-8';
		$doc->loadHTML($html);
		$xpath = new DOMXpath($doc);

		$items = $xpath->query($q);
		if (is_null($items)) {
			throw new Exception('Invalid HTML passed');
		}
		return $items;
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


