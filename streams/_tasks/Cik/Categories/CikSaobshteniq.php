<?php

class CikSaobshteniq extends Cik
{

	protected $categoryId = 0;
	protected $categoryName = 'съобщения';
	protected $categoryURL = 'http://www.cik.bg/';


	function execute($html)
	{
		$items = $this->xpathDoc($html, "//div[@class='item']");

		$query = [];
		foreach ($items as $item) {
			$hash = md5($item->textContent);
			$date = trim($item->childNodes->item(1)->textContent);
			$date = mb_substr($date, 6, 4) . "-" . mb_substr($date, 3, 2) . "-" . mb_substr($date, 0, 2);
			if (strtotime($date) < strtotime("-1 month"))
				continue;
			$item->removeAttribute("class");
			$item->removeChild($item->childNodes->item(1));
			$item->removeChild($item->childNodes->item(0));
			$description = $item->C14N();
			$description = mb_ereg_replace(" </", "</", mb_ereg_replace("> ", ">", $description));
			$description = mb_ereg_replace("\s?(title|name|style|class|id)=[\"'].*?[\"']\s?", "", $description);
			$description = mb_ereg_replace("<p>[  ]*</p>|<a>[  ]*</a>|<div>[  ]*</div>", "", $description);
			$description = $this->cleanText($description);
			$title = $item->textContent;
			$title = $this->cleanTitle($title);
			$title = "Съобщение: " . $this->cleanText($title);
			$query[] = array($title, $description, $date, 'http://www.cik.bg/', $hash);
		}

		$this->logger->info('Възможни ' . count($query) . ' нови съобщения');

		$itemIds = $this->saveItems($query);
		$this->queueTweets($itemIds);
	}

} 