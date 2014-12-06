<?php

class Mi_Makrobiuletin extends Min_mi{

	protected $categoryId = 4;
	protected $categoryName = 'макробюлетин';
	protected $categoryURL = 'http://www.mi.government.bg/bg/pages/macrobulletin-79.html';

	function execute($html)
	{
		$items = $this->xpathDoc($html, "//div[@class='contentColumn']//a");

		$query = array();
		foreach ($items as $item) {
			$title = $item->textContent;
			$title = $this->cleanText($title);
			$title = mb_strtolower($title);
			$title = "Основни макроикономически показатели за $title";

			$url = "http://www.mi.government.bg/" . $item->getAttribute("href");
			$hash = md5($url);

			$query[] = array($title, null, 'now', $url, $hash);
		}

		echo "Възможни " . count($query) . " нови макробюлетина\n";

		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids);
	}

	// TODO: Figure this out
	protected function loader($categoryId, $categoryURL) {
		return $this->loadURL($categoryURL);
	}
} 