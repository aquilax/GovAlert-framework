<?php

class Kfn_Analizi extends Kfn{

	protected $categoryId = 1;
	protected $categoryName = 'анализи';
	protected $categoryURL = 'http://www.fsc.bg/Analizi-na-KFN-bg-29';

	function execute($html)
	{
		$items = $this->xpathDoc($html, "//div[@id='page_29_files']//li/a");

		$query = array();
		foreach ($items as $item) {
			if (count($query) > 10)
				break;
			$url = "http://www.fsc.bg" . $item->getAttribute("href");
			$hash = md5($url);

			$title = $item->childNodes->length > 0 ? $item->firstChild->textContent : $item->textContent;
			$title = "Анализ: " . $this->cleanText($title);
			$query[] = array($title, null, 'now', $url, $hash);
		}

		echo "Възможни " . count($query) . " нови анализи\n";

		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids);
	}
} 