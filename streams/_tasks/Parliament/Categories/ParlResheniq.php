<?php

class ParlResheniq extends Parliament {

	protected $categoryId = 5;
	protected $categoryName = 'решения';
	protected $categoryURL = 'http://parliament.bg/bg/desision/period';

	function execute($html)
	{
		$xpath = $this->xpathDoc($html);
		if (!$xpath) return;
		$items = $xpath->query("//div[@class='calendar_columns' and h4/text()='" . date("Y") . "']//li/a");
		if (is_null($items)) return;
		$lasturl = $items->item($items->length - 1)->getAttribute("href");

		$html = $this->loadURL("http://parliament.bg$lasturl", 6);
		$xpath = $this->xpathDoc($html);
		if (!$xpath) return;
		$items = $xpath->query("//div[@id='monthview']//li");
		if (is_null($items)) return;

		$query = array();
		foreach ($items as $item) {
			$hash = md5($item->childNodes->item(0)->getAttribute("href"));
			$date = trim($item->childNodes->item(1)->textContent);
			$date = substr($date, 8, 4) . "-" . substr($date, 5, 2) . "-" . substr($date, 2, 2);
			if (strtotime($date) < strtotime("-1 month"))
				continue;
			$url = $item->childNodes->item(0)->getAttribute("href");
			$title = $item->childNodes->item(0)->textContent;
			$title = $this->cleanText($title);
			$query[] = array($title, null, $date, "http://parliament.bg$url", $hash);
		}

		echo "Възможни " . count($query) . " нови решения\n";

		$query = array_reverse($query);
		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids, 'narodnosabranie');
	}

} 