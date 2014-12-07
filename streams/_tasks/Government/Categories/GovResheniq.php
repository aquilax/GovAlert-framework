<?php

class GovResheniq extends Government
{

	protected $categoryId = 1;
	protected $categoryName = 'решения';
	protected $categoryURL = 'http://www.government.bg/cgi-bin/e-cms/vis/vis.pl?s=001&p=0228&g=';

	protected function execute($html)
	{
		$items = $this->getXPathItems(
			$this->getXPath($html, 'cp1251'),
			"//table[.//a[@class='header']/text()='Решенията Накратко']//td[@valign='top']/p"
		);

		$query = [];
		foreach ($items as $item) {
			$hash = md5($item->textContent);
			$date = $item->lastChild->childNodes->item(0)->textContent;
			$date = Utils::bgMonth($date);
			$date = mb_substr($date, 6, 4) . "-" . mb_substr($date, 3, 2) . "-" . mb_substr($date, 0, 2);
			if (strtotime($date) < strtotime("-1 month"))
				continue;
			$title = $item->childNodes->item(2)->childNodes->item(0)->textContent;
			$title = Utils::cleanSpaces($title);
			$title = "Решение: " . Utils::fixCase($title);
			$url = "http://www.government.bg" . $item->childNodes->item(2)->getAttribute("href");
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
		$itemids = $this->saveItems($query);

		if (count($itemids) > 3) {
			$this->queueTextTweet("Достъпни са " . count($itemids) . " нови решения от последното заседание", "http://www.government.bg/cgi-bin/e-cms/vis/vis.pl?s=001&p=0228&g=", $this->tweetAccount, true);
		} else {
			$this->queueTweets($itemids, $this->tweetAccount, true);
		}
	}

} 