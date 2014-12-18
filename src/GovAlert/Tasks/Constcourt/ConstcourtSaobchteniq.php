<?php

namespace GovAlert\Tasks\Constcourt;

class ConstcourtSaobchteniq extends Base
{

	protected $categoryId = 1;
	protected $categoryName = 'съобщения';
	protected $categoryURL = 'http://constcourt.bg/caseannouncements';

	protected function execute($html)
	{
		$items = $this->getXPathItems($this->getXPath($html), "//div[@class='is-post is-post-excerpt']");

		$query = [];
		foreach ($items as $item) {
			$date = trim($item->childNodes->item(4)->textContent);
			$date = mb_substr($date, 6, 4) . "-" . mb_substr($date, 3, 2) . "-" . mb_substr($date, 0, 2);
			if (strtotime($date) < $this->timeDiff('-1 week'))
				continue;

			$title = $item->childNodes->item(1)->firstChild->firstChild->textContent;
			$title = $this->cleanText($title);
			$title = "Съобщение по дело: " . $title;

			$url = $item->childNodes->item(1)->firstChild->firstChild->getAttribute("href");
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