<?php

class Prok_Konkursi extends Prokuratura
{

	protected $categoryId = 2;
	protected $categoryName = 'конкурси';
	protected $categoryURL = 'http://www.prb.bg/main/bg/konkursi';

	function execute($html)
	{
		$items = $this->getXPathItems($this->getXPath($html), "//div[@class='list-inner']");

		$query = array();
		foreach ($items as $item) {

			$date = trim($item->childNodes->item(3)->textContent);
			$date = mb_substr($date, 6, 4) . "-" . mb_substr($date, 3, 2) . "-" . mb_substr($date, 0, 2);
			if (strtotime($date) < $this->timeDiff('-2 weeks'))
				continue;
			$description = trim($item->childNodes->item(5)->textContent);
			$description = $this->cleanText($description);

			$title = trim($item->childNodes->item(1)->textContent);
			$title = $this->cleanTitle($title);
			$title = "Конкурс: " . $title;
			// TODO: Figure this out
			$url = "http://www.prb.bg" . $item->childNodes->item(1 + ($hasimage ? 2 : 0))->firstChild->getAttribute("href");
			$hash = md5($url);

			$query[] = [
				'title' => $title,
				'description' => $description,
				'date' => $date,
				'url' => $url,
				'hash' => $hash,
			];

		}
		return $query;
	}
} 