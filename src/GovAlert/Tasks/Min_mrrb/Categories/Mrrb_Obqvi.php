<?php

class Mrrb_Obqvi extends Min_mrrb
{

	protected $categoryId = 0;
	protected $categoryName = 'обяви';
	protected $categoryURL = 'http://www.mrrb.government.bg/?controller=category&action=notice&catid=38';

	function execute($html)
	{
		$items = $this->getXPathItems($this->getXPath($html), "//div[@class='listCategoryArticles']");

		$query = array();
		foreach ($items as $item) {
			$date = Utils::bgMonth(trim($item->childNodes->item(2)->textContent));
			$date = mb_substr($date, 6, 4) . "-" . mb_substr($date, 3, 2) . "-" . mb_substr($date, 0, 2);
			if (strtotime($date) < strtotime("-1 month"))
				continue;
			$title = $item->childNodes->item(4)->textContent;
			$title = "Обява: " . $this->cleanText($title);
			$url = "http://www.mrrb.government.bg/" . $item->childNodes->item(4)->getAttribute("href");
			$hash = md5($url);

			$query[] = [
				'title' => $title,
				'description' => null,
				'date' => $date,
				'url' => $url,
				'hash' => $hash,
			];
		}
		return $query;
	}
} 