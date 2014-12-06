<?php

class CikPrincipniResheniq extends Cik{

	protected $categoryId = 5;
	protected $categoryName = 'принципни решения';
	protected $categoryURL = 'http://www.cik.bg/reshenie_principni';

	function execute($html)
	{
		$items = $this->xpathDoc($html, "//div[@class='block main-block']//li");

		$query = array();
		foreach ($items as $item) {
			$hash = md5($item->childNodes->item(0)->textContent);
			$date = trim($item->childNodes->item(0)->textContent);
			$date = mb_substr($date, mb_strpos($date, "/ ") + 2);
			$date = substr($date, 6, 4) . "-" . substr($date, 3, 2) . "-" . substr($date, 0, 2);
			if (strtotime($date) < strtotime("-1 month"))
				continue;
			$description = $item->childNodes->item(2)->textContent;
			$description = mb_ereg_replace("ОТНОСНО:? ?", "", $description, "im");
			$description = $this->cleanText($description);
			$title = $item->childNodes->item(0)->textContent;
			$title = $this->cleanTitle($title);
			$title = $this->cleanText($title);
			$title = $title . " - " . $description;
			$url = $item->childNodes->item(0)->getAttribute("href");
			$query[] = array($title, $description, $date, "http://www.cik.bg$url", $hash);
		}

		echo "Възможни " . count($query) . " нови принципни решения\n";

		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids);
	}

} 