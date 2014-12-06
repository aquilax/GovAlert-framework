<?php

class GovDokumenti extends Government{

	protected $categoryId = 3;
	protected $categoryName = 'документи';
	protected $categoryURL = 'http://www.government.bg/cgi-bin/e-cms/vis/vis.pl?s=001&p=0211&g=';

	function execute($html)
	{
		$items = $this->xpathDoc($html, "//table[.//a[@class='header']/text()='Документи']//td[@valign='top']/a[@target='_self']");

		echo "Открити " . $items->length . " документи\n";
		$query = array();
		foreach ($items as $item) {
			$hash = md5($item->textContent);
			$title = $item->childNodes->item(1)->textContent;
			$title = Utils::cleanSpaces($title);
			$title = "Нов документ: " . Utils::fixCase($title);
			$url = "http://www.government.bg" . $item->getAttribute("href");
			$query[] = array($title, null, null, $url, $hash);
		}

		echo "Възможни " . count($query) . " нови документи\n";
		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids, 'GovBulgaria');
	}
} 