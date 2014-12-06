<?php

/*
Links
0: информация http://www.dans.bg/bg/component/bca-rss-syndicator/?feed_id=1
*/

abstract class Dans extends Task
{
	protected $sourceId = 17;
	protected $sourceName = 'ДАНС';

	protected function xpath($xml, $isHTML)
	{
		if (!$xml) {
			return null;
		}
		$doc = new DOMDocument("1.0", "UTF-8");
		$doc->preserveWhiteSpace = false;
		$doc->strictErrorChecking = false;
		$doc->encoding = 'UTF-8';
		if ($isHTML)
			$doc->loadHTML($xml);
		else
			$doc->loadXML($xml);
		return new DOMXpath($doc);
	}

} 