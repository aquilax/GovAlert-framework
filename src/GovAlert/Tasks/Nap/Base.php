<?php

/*
links:
0: новини http://www.nap.bg/page?id=223
*/

namespace GovAlert\Tasks\Nap;

abstract class Base extends \GovAlert\Tasks\Task
{
	protected $sourceId = 23;
	protected $sourceName = 'НАП';

	function cleanText($text)
	{
		$text = str_replace(" ", " ", $text);
		$text = mb_ereg_replace("[\n\r\t ]+", " ", $text);
		$text = mb_ereg_replace("(^\s+)|(\s+$)", "", $text);
		$text = html_entity_decode($text);
		return $text;
	}

}