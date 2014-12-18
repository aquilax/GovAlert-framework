<?php

namespace GovAlert\Tasks\Min_mrrb;

class Mrrb_Informaciq extends Base
{

	protected $categoryId = 1;
	protected $categoryName = 'полезна информация';
	protected $categoryURL = 'http://www.mrrb.government.bg/?controller=category&catid=39';

	function execute($html)
	{
		$items = $this->getXPathItems($this->getXPath($html), "//div[@class='listCategoryArticles']");

		$query = array();
		foreach ($items as $item) {
			$title = $item->childNodes->item(2)->textContent;
			$title = "Информация: " . $this->cleanText($title);
			$url = "http://www.mrrb.government.bg/" . $item->childNodes->item(2)->getAttribute("href");
			$hash = md5($url);
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
}
