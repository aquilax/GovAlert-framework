<?php

namespace GovAlert\Tasks\Cik;

class CikResheniq extends Base
{

	protected $categoryId = 1;
	protected $categoryName = 'решения';
	protected $categoryURL = 'http://www.cik.bg/reshenie';


	function execute($html)
	{
		$items = $this->getXPathItems($this->getXPath($html), "//div[@class='block main-block']//li");

		$query = [];
		foreach ($items as $item) {
			$hash = md5($item->childNodes->item(0)->textContent);
			$date = $item->childNodes->item(0)->textContent;
			$date = mb_substr($date, mb_strpos($date, "/ ") + 2);
			$date = substr($date, 6, 4) . "-" . substr($date, 3, 2) . "-" . substr($date, 0, 2);
			if (strtotime($date) < $this->timeDiff('-1 month'))
				continue;
			$description = $item->childNodes->item(2)->textContent;
			$description = mb_ereg_replace("ОТНОСНО:? ?", "", $description, "im");
			$description = $this->cleanText($description);
			$title = $item->childNodes->item(0)->textContent;
			$title = $this->cleanTitle($title);
			$title = $this->cleanText($title);
			$title = $title . " - " . $description;
			$url = $item->childNodes->item(0)->getAttribute("href");
			$query[] = [
				'title' => $title,
				'description' => $description,
				'date' => $date,
				'url' => 'http://www.cik.bg' . $url,
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
			$this->queueTextTweet("Преди минути са публикувани " . count($itemIds) . " нови решения ", "http://www.cik.bg/reshenie");
		}
	}


} 