<?php

class Mh_Saobshteniq extends Min_mh {

	protected $categoryId = 0;
	protected $categoryName = 'съобщения';
	protected $categoryURL = 'http://www.mh.government.bg/AllMessages.aspx';

	function execute($html)
	{
		$items = $this->xpathDoc($html, "//table[@id='ctl00_ContentPlaceClient_gvMessages']//a");

		$query = array();
		foreach ($items as $item) {
			$title = $item->textContent;
			$title = "Съобщение: " . $this->cleanText($title);
			$url = "http://www.mh.government.bg/" . $item->getAttribute("href");
			$hash = md5($url);
			$query[] = array($title, null, 'now', $url, $hash);
			if (count($query) >= 20)
				break;
		}

		echo "Възможни " . count($query) . " нови съобщения\n";

		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids);
	}
} 