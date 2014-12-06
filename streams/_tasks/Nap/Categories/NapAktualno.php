<?php

class NapAktualno extends Nap
{

	protected $categoryId = 0;
	protected $categoryName = 'съобщения';
	protected $categoryURL = 'http://www.nap.bg/page?id=223';

	function execute($html)
	{
		$items = $this->xpathDoc($html, "//div[@id='column2']//li[@class='news']");

		$query = array();
		foreach ($items as $item) {


			$date = $item->childNodes->item(1)->textContent;
			$date = Utils::bgMonth($date);
			$date = mb_substr($date, 6, 4) . "-" . mb_substr($date, 3, 2) . "-" . mb_substr($date, 0, 2);
			if (strtotime($date) < strtotime("-2 week"))
				continue;

			$url = $item->childNodes->item(0)->getAttribute("onclick");
			$url = "http://www.nap.bg" . substr($url, 12, strpos($url, "'", 12) - 12);
			$hash = md5($url);

			$title = $item->childNodes->item(0)->textContent;
			$title = $this->cleanText($title);
			$query[] = array($title, null, $date, $url, $hash);
		}

		echo "Възможни " . count($query) . " нови съобщения\n";

		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids);
	}


} 