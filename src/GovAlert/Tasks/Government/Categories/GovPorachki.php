<?php

class GovPorachki extends Government
{

	protected $categoryId = 6;
	protected $categoryName = 'съобщения за обществени поръчки';
	protected $categoryURL = 'http://www.government.bg/cgi-bin/e-cms/vis/vis.pl?s=001&p=0235&g=';

	function execute($html)
	{
		$items = $this->getXPathItems(
			$this->getXPath($html, 'cp1251'),
			"//table[.//a[@class='header']/text()='Обществени поръчки до 1.10.2014']//td[@valign='top']/a[@target='_self']"
		);

		$query = [];
		foreach ($items as $item) {
			$hash = md5($item->textContent);
			$title = $item->childNodes->item(1)->textContent;
			$title = Utils::cleanSpaces($title);
			$url = "http://www.government.bg" . $item->getAttribute("href");
			$query[] = [
				'title' => $title,
				'description' => null,
				'date' => null,
				'url' => $url,
				'hash' => $hash,
			];
			if (count($query) >= 20)
				break;
		}
		return $query;
	}
} 