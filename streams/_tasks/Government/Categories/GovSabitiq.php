<?php

class GovSabitiq extends Government{

	protected $categoryId = 2;
	protected $categoryName = 'събития';
	protected $categoryURL = 'http://www.government.bg/cgi-bin/e-cms/vis/vis.pl?s=001&p=0217&g=';

	protected function execute($html)
	{
		$items = $this->xpathDoc($html, "//td[.//a[@class='header']/text()='Предстоящи събития' and table/@bgcolor='#ffffff']//td[@valign='top']/a");

		echo "Открити " . $items->length . " събития\n";
		$query = array();
		foreach ($items as $item) {
			$hash = md5($item->textContent);
			$title = $item->childNodes->item(1)->textContent;
			$title = Utils::cleanSpaces($title);
			$title = "Събитие: " . Utils::fixCase($title);
			$url = "http://www.government.bg" . $item->getAttribute("href");
			$query[] = array($title, null, null, $url, $hash);
		}

		echo "Възможни " . count($query) . " нови събития\n";
		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids, 'GovBulgaria');
	}


} 