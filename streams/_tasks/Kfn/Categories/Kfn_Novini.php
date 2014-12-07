<?php

class Kfn_Novini extends Kfn
{

	protected $categoryId = 0;
	protected $categoryName = 'новини';
	protected $categoryURL = 'http://www.fsc.bg/Novini-bg';


	function execute($html)
	{
		$items = $this->xpathDoc($html, "//div[@class='s_news_listing']//h3/a");

		$query = [];
		foreach ($items as $item) {
			if (count($query) > 10)
				break;
			$url = "http://www.fsc.bg" . $item->getAttribute("href");
			$hash = md5($url);

			$title = $item->textContent;
			$title = $this->cleanText($title);
			$query[] = [
				'title' => $title,
				'description' => null,
				'date' => Utils::now(),
				'url' => $url,
				'hash' => $hash,
			];
		}
		return $query;
	}


} 