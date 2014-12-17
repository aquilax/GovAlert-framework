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
}
