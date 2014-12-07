<?php

class GovDokumenti extends Government
{

	protected $categoryId = 3;
	protected $categoryName = 'документи';
	protected $categoryURL = 'http://www.government.bg/cgi-bin/e-cms/vis/vis.pl?s=001&p=0211&g=';

	function execute($html)
	{
		$items = $this->getXPathItems(
			$this->getXPath($html, 'cp1251'),
			"//table[.//a[@class='header']/text()='Документи']//td[@valign='top']/a[@target='_self']"
		);

		$query = [];
		foreach ($items as $item) {
			$hash = md5($item->textContent);
			$title = $item->childNodes->item(1)->textContent;
			$title = Utils::cleanSpaces($title);
			$title = "Нов документ: " . Utils::fixCase($title);
			$url = "http://www.government.bg" . $item->getAttribute("href");
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