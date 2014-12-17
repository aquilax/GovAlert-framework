<?php

namespace GovAlert\Tasks\Comdos;

class ComdosResheniq extends Base
{

	protected $categoryId = 5;
	protected $categoryName = 'решения';
	protected $categoryURL = 'http://www.comdos.bg/';

	function execute($html)
	{
		$items = $this->getXPathItems($this->getXPath($html), "//div[@class='contentWrapper']//a");

		$query = [];
		foreach ($items as $item) {
			$text = $item->textContent;
			$text = $this->cleanText($text);

			$url = "http://www.comdos.bg" . $item->getAttribute("href");

			$hash = md5($url);

			$datepos = mb_strpos($text, " от ") + 4;
			$date = mb_substr($text, $datepos + 6, 4) . "-" . mb_substr($text, $datepos + 3, 2) . "-" . mb_substr($text, $datepos, 2);
			if (strtotime($date) < strtotime("-1 month"))
				continue;

			$title = "Решение №" . mb_substr($text, 13, $datepos - 17) . "/" . mb_substr($text, $datepos, 5) . " за " . mb_substr($text, $datepos + 16);
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