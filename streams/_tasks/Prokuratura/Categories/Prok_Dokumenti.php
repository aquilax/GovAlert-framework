<?php

class Prok_Dokumenti extends Prokuratura
{

	protected $categoryId = 1;
	protected $categoryName = 'документи';
	protected $categoryURL = 'http://www.prb.bg/main/bg/Documents/';

	function execute($html)
	{
		$items = $this->xpathDoc($html, "//div[@class='list-inner']");

		$query = array();
		foreach ($items as $item) {

			$date = trim($item->childNodes->item(3)->textContent);
			$date = mb_substr($date, 6, 4) . "-" . mb_substr($date, 3, 2) . "-" . mb_substr($date, 0, 2);
			if (strtotime($date) < strtotime("-2 months"))
				continue;
			$description = trim($item->childNodes->item(5)->textContent);
			$description = $this->cleanText($description);

			$title = trim($item->childNodes->item(1)->textContent);
			$title = $this->cleanTitle($title);
			$title = "Документ: " . $this->cleanText($title);

			$url = "http://www.prb.bg" . $item->childNodes->item(1)->firstChild->getAttribute("href");
			$hash = md5($url);

			$query[] = [
				'title' => $title,
				'description' => $description,
				'date' => $date,
				'url' => $url,
				'hash' => $hash,
			];

		}

		echo "Възможни " . count($query) . " нови документи\n";
		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids);
	}

} 