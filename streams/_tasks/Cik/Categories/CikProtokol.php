<?php

class CikProtokol extends Cik
{

	protected $categoryId = 3;
	protected $categoryName = 'протоколи';
	protected $categoryURL = 'http://www.cik.bg/405';

	function execute($html)
	{
		$items = $this->xpathDoc($html, "//div[@class='block main-block']//li");

		$query = array();
		foreach ($items as $item) {
			$hash = md5($item->childNodes->item(0)->textContent);
			$title = $item->childNodes->item(0)->textContent;
			$title = $this->cleanText($title);
			$title = mb_ereg_replace("/", "за", $title, "im");
			$url = $item->childNodes->item(0)->getAttribute("href");
			$query[] = array($title, null, null, "http://www.cik.bg$url", $hash);
		}

		echo "Възможни " . count($query) . " нови протокола\n";

		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids);
	}

} 