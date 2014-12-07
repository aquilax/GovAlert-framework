<?php

/*
0 законопроекти http://parliament.bg/bg/bills
1 програма парламентарен контрол http://parliament.bg/bg/parliamentarycontrol
2 програма пленарно заседание http://parliament.bg/bg/plenaryprogram
3 закони http://parliament.bg/bg/laws
4 документи за пленарна зала http://parliament.bg/bg/doc
5/6 решения http://parliament.bg/bg/desision/period
7 събития http://parliament.bg/bg/calendar
8/9 декларации http://parliament.bg/bg/declaration
10 нови комисии http://parliament.bg/bg/parliamentarycommittees

- комисии - заседания http://parliament.bg/bg/parliamentarycommittees/members/2289/sittings
- комисии - новини http://parliament.bg/bg/parliamentarycommittees/members/2289/news
- комисии - документи http://parliament.bg/bg/parliamentarycommittees/members/2289/documents
- комисии - доклади http://parliament.bg/bg/parliamentarycommittees/members/2290/reports/period/2014-11
- комисии - стенограми http://parliament.bg/bg/parliamentarycommittees/members/2289/steno/period/2014-11
*/

abstract class Parliament extends Task
{
	protected $sourceId = 4;
	protected $sourceName = 'НС';
	protected $tweetAccount = 'narodnosabranie';

	function xpathDoc($html)
	{
		if (!$html) {
			return null;
		}
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
