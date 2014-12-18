<?php

namespace GovAlert\Tasks\Min_mi;

use GovAlert\Common\Utils;

class Mi_Obsajdane extends Base
{
	protected $categoryId = 3;
	protected $categoryName = 'обществено обсъждане';
	protected $categoryURL = 'http://www.mi.government.bg/bg/discussion-news-0.html';

	function execute($html)
	{
		$items = $this->getXPathItems($this->getXPath($html), "//div[@class='col2']/div[@class='row']");

		$query = [];
		foreach ($items as $item) {
			$date = trim($item->childNodes->item(1)->textContent);
			$date = Utils::bgMonth($date);
			$date = mb_substr($date, 6, 4) . '-' . mb_substr($date, 3, 2) . '-' . mb_substr($date, 0, 2);
			if (strtotime($date) < $this->timeDiff('-1 week')) {
				continue;
			}
			$title = $item->childNodes->item(3)->firstChild->textContent;
			$title = mb_ereg_replace('МИЕ предлага за обществено обсъждане проект', 'Проект', $title, 'im');
			$title = 'Обществено обсъждане: ' . $this->cleanText($title);

			$url = 'http://www.mi.government.bg' . $item->childNodes->item(3)->firstChild->getAttribute('href');
			$hash = md5($url);

			$description = $item->childNodes->item(5)->textContent;
			$description = $this->cleanText($description);

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