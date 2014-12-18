<?php

namespace GovAlert\Tasks\Min_mh;

class Mh_Saobshteniq extends Base
{
	protected $categoryId = 0;
	protected $categoryName = 'съобщения';
	protected $categoryURL = 'http://www.mh.government.bg/AllMessages.aspx';

	function execute($html)
	{
		$items = $this->getXPathItems(
			$this->getXPath($html),
			"//table[@id='ctl00_ContentPlaceClient_gvMessages']//a"
		);

		$query = [];
		foreach ($items as $item) {
			$title = $item->textContent;
			$title = 'Съобщение: ' . $this->cleanText($title);
			$url = 'http://www.mh.government.bg/' . $item->getAttribute('href');
			$hash = md5($url);
			$query[] = [
				'title' => $title,
				'description' => null,
				'date' => $this->db->now(),
				'url' => $url,
				'hash' => $hash,
			];
			if (count($query) >= 20)
				break;
		}
		return $query;
	}
}