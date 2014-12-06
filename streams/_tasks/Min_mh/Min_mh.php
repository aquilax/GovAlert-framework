<?php

/*

0: съобщения http://www.mh.government.bg/AllMessages.aspx
1: новини http://www.mh.government.bg/News.aspx?pageid=401
2: проекти за нормативни актове http://www.mh.government.bg/Articles.aspx?lang=bg-BG&pageid=393
3: наредби http://www.mh.government.bg/Articles.aspx?lang=bg-BG&pageid=391
4: постановления http://www.mh.government.bg/Articles.aspx?lang=bg-BG&pageid=381
5: отчети http://www.mh.government.bg/Articles.aspx?lang=bg-BG&pageid=532&currentPage=1

*/

abstract class Min_mh extends Task
{
	protected $sourceId = 21;
	protected $sourceName = 'МЗ';

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
