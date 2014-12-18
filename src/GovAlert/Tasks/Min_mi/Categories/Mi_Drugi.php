<?php

class Mi_Drugi extends Min_mi
{

	protected $categoryId = 2;
	protected $categoryName = 'други';
	protected $categoryURL = 'http://www.mi.government.bg/bg/competitions-c42-1.html';


	function execute($html)
	{
		$items = $this->getXPathItems($this->getXPath($html), "//div[@class='col2']/div[@class='row']");

		$query = array();
		foreach ($items as $item) {
			$date = trim($item->childNodes->item(4)->childNodes->item(1)->textContent);
			$date = mb_substr($date, 6, 4) . "-" . mb_substr($date, 3, 2) . "-" . mb_substr($date, 0, 2);
			if (strtotime($date) < $this->timeDiff('-1 month'))
				continue;
			$title = $item->childNodes->item(1)->childNodes->item(2)->textContent;
			$title = $this->cleanText($title);
			$url = "http://www.mi.government.bg" . $item->childNodes->item(1)->childNodes->item(2)->getAttribute("href");
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