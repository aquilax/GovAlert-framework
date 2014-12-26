<?php

class ParlParlamentarenKontrol extends Parliament
{

	protected $categoryId = 1;
	protected $categoryName = 'парламентарен контрол';
	protected $categoryURL = 'http://parliament.bg/bg/parliamentarycontrol';

	function execute($html)
	{
		if (mb_strpos($html, "Програмата ще бъде публикувана") !== false)
			return;

		$xpath = $this->xpathDoc($html);
		if (!$xpath) return;
		$items = $xpath->query("//div[@class='rightinfo']/ul[@class='frontList']/li/a");
		if (is_null($items)) return;

		$query = array();
		foreach ($items as $item) {
			$hash = md5($item->getAttribute("href"));
			$url = $item->getAttribute("href");
			$title = $item->textContent;
			$title = substr($title, 10) . " - програма за " . substr($title, 0, 2) . "." . substr($title, 3, 2) . "." . substr($title, 6, 4);
			$title = $this->cleanText($title);
			$query[] = [
				'title' => $title,
				'description' => null,
				'date' => $this->db->now(),
				'url' => 'http://parliament.bg' . $url,
				'hash' => $hash,
			];
		}
		return $query;
	}

} 