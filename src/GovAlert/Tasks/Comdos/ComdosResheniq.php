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

			$datePos = mb_strpos($text, " от ") + 4;
			$date = mb_substr($text, $datePos + 6, 4) . "-" . mb_substr($text, $datePos + 3, 2) . "-" . mb_substr($text, $datePos, 2);
			if (strtotime($date) < $this->timeDiff('-1 month')) {
				continue;
			}

			$url = "http://www.comdos.bg" . $item->getAttribute("href");
			$hash = md5($url);
			$title = "Решение №" . mb_substr($text, 13, $datePos - 17) . "/" . mb_substr($text, $datePos, 5) . " за " . mb_substr($text, $datePos + 16);
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