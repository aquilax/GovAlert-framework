<?php

/*

0: обяви http://www.mrrb.government.bg/?controller=category&action=notice&catid=38
1: полезна информация http://www.mrrb.government.bg/?controller=category&catid=39

*/

abstract class Min_mrrb extends Task
{
	protected $sourceId = 10;
	protected $sourceName = 'МРРБ';

	protected function cleanText($text)
	{
		$text = html_entity_decode($text);
		$text = Utils::cleanSpaces($text);
		$text = Utils::fixCase($text);
		return $text;
	}

} 