<?php

namespace GovAlert\Tasks\Cik;
use GovAlert\Common\Utils;

/*

0: съобщения http://www.cik.bg/
1: решения http://www.cik.bg/reshenie
2: дневен ред http://www.cik.bg/406
3: протоколи http://www.cik.bg/405
4: жалби http://www.cik.bg/jalbi
5: принципни решения http://www.cik.bg/reshenie_principni

*/


abstract class Base extends \GovAlert\Tasks\Task
{
	protected $sourceId = 1;
	protected $sourceName = 'ЦИК';

	protected function cleanTitle($title)
	{
		if (mb_substr($title, -1) == ".") {
			$title = mb_substr($title, 0, mb_strlen($title) - 1);
		}
		$title = mb_ereg_replace("Централната избирателна комисия", "ЦИК", $title, "im");
		$title = mb_ereg_replace("Република България", "РБ", $title, "im");
		$title = mb_ereg_replace("Народно(то)? събрание", "НС", $title, "im");
		$title = mb_ereg_replace("Министерски(ят)? съвет", "МС", $title, "im");
		$title = mb_ereg_replace("(ИЗБИРАТЕЛНИ КОМИСИИ)|(избирателна комисия)", "ИК", $title, "im");
		$title = mb_ereg_replace("№ ", "№", $title, "im");
		$title = mb_ereg_replace(" ?/ ?", "/", $title, "im");
		$title = mb_ereg_replace("ОБЯВЛЕНИЕОТНОСНО:?|ОТНОСНО:?|С Ъ О Б Щ Е Н И Е|СЪОБЩЕНИЕ|г\.|ч\.|\\\\|„|\"|'", "", $title, "im");
		return $title;
	}

	protected function cleanText($text)
	{
		$text = Utils::cleanSpaces($text);
		$text = html_entity_decode($text);
		return $text;
	}

}
