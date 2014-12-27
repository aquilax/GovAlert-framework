<?php

namespace GovAlert\Tasks\Cik;

class CikJalbi extends Base
{

	protected $categoryId = 4;
	protected $categoryName = 'жалби';
	protected $categoryURL = 'http://www.cik.bg/jalbi';

	function execute($html)
	{
		$items = $this->getXPathItems($this->getXPath($html), "//div[@class='block main-block']//td/a");

		$query = [];
		foreach ($items as $item) {
			$hash = md5($item->textContent);
			$title = $item->textContent;
			$title = $this->cleanText($title);
			$url = $item->getAttribute("href");
			if (mb_strpos($url, "http") === false)
				$url = "http://www.cik.bg$url";
			$query[] = [
				'title' => $title,
				'description' => null,
				'date' => null,
				'url' => $url,
				'hash' => $hash,
			];
		}
		return $query;
	}

	protected  function processItems(Array $query) {
		$this->logger->info('Възможни ' . count($query) . ' нови ' . $this->categoryName);
		$itemIds = $this->saveItems($query);
		if (count($itemIds) <= 5) {
			$this->queueTweets($itemIds);
		} else {
			$this->queueTextTweet("Публикувани са " . count($itemIds) . " нови документа във връзка с жалби", "http://www.cik.bg/reshenie");
		}
	}

} 