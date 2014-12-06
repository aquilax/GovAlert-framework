<?php

class CikJalbi extends Cik
{

	protected $categoryId = 4;
	protected $categoryName = 'жалби';
	protected $categoryURL = 'http://www.cik.bg/jalbi';

	function execute($html)
	{
		$items = $this->xpathDoc($html, "//div[@class='block main-block']//td/a");

		$query = array();
		foreach ($items as $item) {
			$hash = md5($item->textContent);
			$title = $item->textContent;
			$title = $this->cleanText($title);
			$url = $item->getAttribute("href");
			if (mb_strpos($url, "http") === false)
				$url = "http://www.cik.bg$url";
			$query[] = array($title, null, null, $url, $hash);
		}

		echo "Възможни " . count($query) . " нови жалби\n";

		$itemids = $this->saveItems($query);
		if (count($itemids) <= 5)
			$this->queueTweets($itemids);
		else
			$this->queueTextTweet("Публикувани са " . count($itemids) . " нови документа във връзка с жалби", "http://www.cik.bg/reshenie");
	}


} 