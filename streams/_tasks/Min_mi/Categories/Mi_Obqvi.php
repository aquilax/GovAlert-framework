<?php

class Mi_Obqvi extends Min_mi
{

	protected $categoryId = 0;
	protected $categoryName = 'обяви';
	protected $categoryURL = 'http://www.mi.government.bg/bg/competitions-c38-1.html';

	function execute($html)
	{
		$items = $this->xpathDoc($html, "//div[@class='col2']/div[@class='row']");

		$query = array();
		foreach ($items as $item) {
			$date = trim($item->childNodes->item(4)->childNodes->item(1)->textContent);
			$date = mb_substr($date, 6, 4) . "-" . mb_substr($date, 3, 2) . "-" . mb_substr($date, 0, 2);
			if (strtotime($date) < strtotime("-1 month"))
				continue;
			$title = $item->childNodes->item(1)->childNodes->item(2)->textContent;
			$title = "Обява: " . $this->cleanText($title);
			$url = "http://www.mi.government.bg" . $item->childNodes->item(1)->childNodes->item(2)->getAttribute("href");
			$hash = md5($url);
			$query[] = array($title, null, $date, $url, $hash);
		}

		echo "Възможни " . count($query) . " нови обяви\n";
		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids);
	}
} 