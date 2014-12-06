<?php

abstract class Adminreg extends Task
{

	protected $sourceId = 16;
	protected $sourceName = 'АдминРег';

	protected function xpath($html)
	{
		if (!$html) {
			return null;
		}
		$html = mb_convert_encoding($html, 'HTML-ENTITIES', "cp1251");
		$doc = new DOMDocument("1.0", "cp1251");
		$doc->preserveWhiteSpace = false;
		$doc->strictErrorChecking = false;
		$doc->encoding = 'UTF-8';
		$doc->loadHTML($html);
		return new DOMXpath($doc);
	}

}