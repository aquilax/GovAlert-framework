<?php

/*

0: новини http://constcourt.bg/news
1: съобщения по дела http://constcourt.bg/caseannouncements

*/

abstract class Constcourt extends Task
{
	protected $sourceId = 8;
	protected $sourceName = 'Конституционен съд';

	protected function cleanText($text)
	{
		$text = Utils::cleanSpaces($text);
		$text = mb_ereg_replace("Конституционният? съд", "КС", $text, "im");
		$text = html_entity_decode($text);
		return $text;
	}
}
