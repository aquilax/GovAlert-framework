<?php

namespace GovAlert\Tasks\Min_mi;

class Mi_Makrobiuletin extends Base
{
	protected $categoryId = 4;
	protected $categoryName = 'макробюлетин';
	protected $categoryURL = 'http://www.mi.government.bg/bg/pages/macrobulletin-79.html';

	function execute($html)
	{
		$items = $this->getXPathItems($this->getXPath($html), "//div[@class='contentColumn']//a");

		$query = array();
		foreach ($items as $item) {
			$title = $item->textContent;
			$title = $this->cleanText($title);
			$title = mb_strtolower($title);
			$title = 'Основни макроикономически показатели за ' . $title;

			$url = 'http://www.mi.government.bg/' . $item->getAttribute('href');
			$hash = md5($url);

			$query[] = [
				'title' => $title,
				'description' => null,
				'date' => $this->db->now(),
				'url' => $url,
				'hash' => $hash,
			];
		}
		return $query;
	}
} 