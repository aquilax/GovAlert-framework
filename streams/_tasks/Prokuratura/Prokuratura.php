<?php

/*

0: новини http://www.prb.bg/main/bg/News/
1: документи http://www.prb.bg/main/bg/Documents/
2: конкурс http://www.prb.bg/main/bg/konkursi
3: галерия http://www.prb.bg/main/bg/gallery/

*/


abstract class Prokuratura extends Task
{

	protected $sourceId = 13;
	protected $sourceName = 'Прокуратура';

	function xpathDoc($html, $q)
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

	function cleanTitle($title)
	{
		if (mb_substr($title, -1) == ".") {
			$title = mb_substr($title, 0, mb_strlen($title) - 1);
		}
		$title = mb_ereg_replace("Република България", "РБ", $title, "im");
		$title = mb_ereg_replace("Р България", "РБ", $title, "im");
		$title = mb_ereg_replace("„|“", "", $title, "im");
		$title = mb_ereg_replace("Народно(то)? събрание", "НС", $title, "im");
		$title = mb_ereg_replace("Министерски(ят)? съвет", "МС", $title, "im");
		$title = mb_ereg_replace("(ИЗБИРАТЕЛНИ КОМИСИИ)|(избирателна комисия)", "ИК", $title, "im");
		$title = mb_ereg_replace("ОБЯВЛЕНИЕОТНОСНО:?|ОТНОСНО:?|С Ъ О Б Щ Е Н И Е|СЪОБЩЕНИЕ|г\.|ч\.|\\\\|„|\"|'", "", $title, "im");
		return $title;
	}

	function cleanText($text)
	{
		$text = Utils::cleanSpaces($text);
		$text = html_entity_decode($text);
		return $text;
	}
}

