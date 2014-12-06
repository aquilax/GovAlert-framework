<?php

/*
Links
0: заседания http://www.government.bg/cgi-bin/e-cms/vis/vis.pl?s=001&p=0225&g=
1: решения http://www.government.bg/cgi-bin/e-cms/vis/vis.pl?s=001&p=0228&g=
2: събития http://www.government.bg/cgi-bin/e-cms/vis/vis.pl?s=001&p=0217&g=
3: документ http://www.government.bg/cgi-bin/e-cms/vis/vis.pl?s=001&p=0211&g=
4: водещи новини http://www.government.bg/cgi-bin/e-cms/vis/vis.pl?s=001&p=0213&g=
5: новини http://www.government.bg/cgi-bin/e-cms/vis/vis.pl?s=001&p=0212&g=
6: обществени поръчки http://www.government.bg/cgi-bin/e-cms/vis/vis.pl?s=001&p=0235&g=
*/

abstract class Government extends Task
{

	protected $sourceId = 3;
	protected $sourceName = 'кабинета';

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

	protected function xpathDoc($html, $q)
	{
		$xpath = $this->xpath($html);

		if ($xpath == null)
			return array();

		$items = $xpath->query($q);
		return is_null($items) ? array() : $items;
	}

} 