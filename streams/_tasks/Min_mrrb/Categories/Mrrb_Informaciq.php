<?php

/**
 * Created by PhpStorm.
 * User: aquilax
 * Date: 12/6/14
 * Time: 5:32 AM
 */
class Mrrb_Informaciq extends Min_mrrb
{

	protected $categoryId = 1;
	protected $categoryName = 'полезна информация';
	protected $categoryURL = 'http://www.mrrb.government.bg/?controller=category&catid=39';

	function execute($html)
	{
		$items = $this->xpathDoc($html, "//div[@class='listCategoryArticles']");

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

		echo "Възможни " . count($query) . " нови съобщения за полезна информация\n";
		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids);
	}
} 