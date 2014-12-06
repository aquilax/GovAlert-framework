<?php

class CikDnevenRed extends Cik
{

	protected $categoryId = 2;
	protected $categoryName = 'дневен ред';
	protected $categoryURL = 'http://www.cik.bg/406';

	protected function execute($html)
	{
		$items = $this->xpathDoc($html, "//div[@class='block main-block']//li");

		$query = array();
		foreach ($items as $item) {
			$hash = md5($item->childNodes->item(0)->textContent);
			$date = $item->childNodes->item(0)->textContent;
			$date = mb_substr($date, mb_strpos($date, "/ ") + 2);
			$date = substr($date, 6, 4) . "-" . substr($date, 3, 2) . "-" . substr($date, 0, 2);
			if (strtotime($date) < strtotime("-1 month"))
				continue;
			$title = $item->childNodes->item(0)->textContent;
			$title = $this->cleanText($title);
			$title = mb_ereg_replace("/", "за", $title, "im");
			$url = $item->childNodes->item(0)->getAttribute("href");
			$query[] = [
				'title' => $title,
				'description' => null,
				'date' => null,
				'url' => 'http://www.cik.bg' . $url,
				'hash' => $hash,
			];

		}

		echo "Възможни " . count($query) . " нови записа за дневен ред\n";

		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids);
	}


} 