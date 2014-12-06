<?php

/*

0: обяви http://www.mi.government.bg/bg/competitions-c38-1.html
1: Продажба на активи http://www.mi.government.bg/bg/competitions-c37-1.html
2: други http://www.mi.government.bg/bg/competitions-c42-1.html
3: обществено обсъждане http://www.mi.government.bg/bg/discussion-news-0.html
4: макробюлетин http://www.mi.government.bg/bg/pages/macrobulletin-79.html
5: избор на финансови институции http://www.mi.government.bg/bg/themes/prilagane-na-pravilata-za-izbor-na-finansovi-institucii-1313-441.html
6: концентрация на фин. средства http://www.mi.government.bg/bg/themes/nalichie-na-koncentraciya-na-finansovi-sredstva-1314-441.html

*/


abstract class Min_mi extends Task
{
	protected $sourceId = 11;
	protected $sourceName = 'МИЕ';

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