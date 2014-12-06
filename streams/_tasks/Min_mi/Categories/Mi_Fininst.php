<?php

class Mi_Fininst extends Min_mi{

	protected $categoryId = 5;
	protected $categoryName = 'избор фин.инст.';
	protected $categoryURL = 'http://www.mi.government.bg/bg/themes/prilagane-na-pravilata-za-izbor-na-finansovi-institucii-1313-441.html';

	function execute($html)
	{
		$items = $this->xpathDoc($html, "//div[@id='description']//p[a]");

		$query = array();
		foreach ($items as $item) {
			$title = $item->textContent;
			$title = $this->cleanText($title);
			$title = mb_strtolower($title);
			$title = "Прилагане на правилата за избор на финансови институции $title";

			$url = "http://www.mi.government.bg/" . $item->firstChild->getAttribute("href");
			$hash = md5($url);

			$query[] = array($title, null, 'now', $url, $hash);
		}

		echo "Възможни " . count($query) . " нови избор фин.инст.\n";
		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids);
	}
} 