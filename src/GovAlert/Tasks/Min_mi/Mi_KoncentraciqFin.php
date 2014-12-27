<?php

namespace GovAlert\Tasks\Min_mi;

class Mi_KoncentraciqFin extends Base
{

	protected $categoryId = 6;
	protected $categoryName = 'концентрация фин.ср.';
	protected $categoryURL = 'http://www.mi.government.bg/bg/themes/nalichie-na-koncentraciya-na-finansovi-sredstva-1314-441.html';

	function execute($html)
	{
		$items = $this->getXPathItems($this->getXPath($html), "//div[@id='description']//p[a]");
		$query = array();
		foreach ($items as $item) {
			$title = $item->textContent;
			$title = $this->cleanText($title);
			$title = mb_strtolower($title);
			$title = 'Наличие на концентрация на финансови средства ' . $title;

			$url = 'http://www.mi.government.bg/' . $item->firstChild->getAttribute('href');
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
