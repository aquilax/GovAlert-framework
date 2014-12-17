<?php

class CikProtokol extends Cik
{

	protected $categoryId = 3;
	protected $categoryName = 'протоколи';
	protected $categoryURL = 'http://www.cik.bg/405';

	function execute($html)
	{
		$items = $this->getXPathItems($this->getXPath($html), "//div[@class='block main-block']//li");

		$query = [];
		foreach ($items as $item) {
			$hash = md5($item->childNodes->item(0)->textContent);
			$title = $item->childNodes->item(0)->textContent;
			$title = $this->cleanText($title);
			$title = mb_ereg_replace("/", "за", $title, "im");
			$url = $item->childNodes->item(0)->getAttribute("href");
			$query[] = [
				'title' => $title,
				'description' => null,
				'date' => null,
				'url' => 'http://www.cik.bg' . $url,
				'hash' => $hash,
			];

		}
		return $query;
	}

} 