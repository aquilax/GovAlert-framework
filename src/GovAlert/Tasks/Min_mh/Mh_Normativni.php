<?php

namespace GovAlert\Tasks\Min_mh;

class Mh_Normativni extends Base
{
	protected $categoryId = 2;
	protected $categoryName = 'нормативни актове';
	protected $categoryURL = 'http://www.mh.government.bg/Articles.aspx?lang=bg-BG&pageid=393';

	function execute($html)
	{
		$items = $this->getXPathItems(
			$this->getXPath($html),
			"//table[@id='ctl00_ContentPlaceClient_ucArticlesList_gvArticles']//tr[not(@class)]/td"
		);

		$query = [];
		foreach ($items as $item) {
			if ($item->childNodes->length < 4) {
				continue;
			}
			$date = $item->childNodes->item(3)->textContent;
			$date = $this->cleanText($date);
			$date = explode(".", $date);
			$date = substr($date[2], 0, 4) . "-" . $date[1] . "-" . substr($date[0], -2);

			if (strtotime($date) < $this->timeDiff('-1 month')) {
				continue;
			}
			$title = $item->childNodes->item(1)->textContent;
			$title = $this->cleanText($title);

			$url = $item->childNodes->item(1)->getAttribute('href');
			$urlStart = strpos($url, 'Articles.aspx');
			$url = substr($url, $urlStart, strpos($url, '"', $urlStart) - $urlStart);
			$url = 'http://www.mh.government.bg/' . $url;
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