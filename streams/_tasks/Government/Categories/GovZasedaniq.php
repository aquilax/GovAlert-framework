<?php

class GovZasedaniq extends Government
{

	protected $categoryId = 0;
	protected $categoryName = 'заседания';
	protected $categoryURL = 'http://www.government.bg/cgi-bin/e-cms/vis/vis.pl?s=001&p=0225&g=';

	function execute($html)
	{
		$items = $this->getXPathItems(
			$this->getXPath($html, 'cp1251'),
			"//td[@valign='top' and starts-with(./a/font/text(),'Дневен ред')]"
		);

		echo "Открити " . $items->length . " заседания\n";

		$query = array();
		foreach ($items as $item) {
			$hash = md5($item->childNodes->item(0)->childNodes->item(1)->textContent);
			$date = $item->childNodes->item(0)->childNodes->item(1)->textContent;
			$date = mb_substr($date, mb_strrpos($date, "на ") + 3, 10);
			$date = mb_substr($date, 6, 4) . "-" . mb_substr($date, 3, 2) . "-" . mb_substr($date, 0, 2);
			if (strtotime($date) < strtotime("-2 day")) {
				continue;
			}
			$title = $item->childNodes->item(0)->childNodes->item(1)->textContent;
			$title = Utils::fixCase($title);
			$title = mb_ereg_replace("Министерския съвет", "МС", $title, "im");
			$url = "http://www.government.bg" . $item->childNodes->item(0)->getAttribute("href");
			$query[] = [
				'title' => $title,
				'description' => null,
				'date' => null,
				'url' => $url,
				'hash' => $hash,
			];
		}

		echo "Възможни " . count($query) . " нови заседания\n";
		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids, 'GovBulgaria', true);
	}


} 