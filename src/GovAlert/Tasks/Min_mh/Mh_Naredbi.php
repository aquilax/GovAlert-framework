<?php

namespace GovAlert\Tasks\Min_mh;

class Mh_Naredbi extends Base
{
	protected $categoryId = 3;
	protected $categoryName = 'наредби';
	protected $categoryURL = 'http://www.mh.government.bg/Articles.aspx?lang=bg-BG&pageid=391';

	function execute($html)
	{
		$items = $this->getXPathItems(
			$this->getXPath($html),
			"//table[@id='ctl00_ContentPlaceClient_ucArticlesList_gvArticles']//tr[not(@class)]/td/a[@class='list_article_title']"
		);

		$query = array();
		foreach ($items as $item) {
			$title = $item->textContent;
			$title = $this->cleanText($title);

			$url = $item->getAttribute('href');
			$urlStart = strpos($url, 'Articles.aspx');
			$url = substr($url, $urlStart, strpos($url, '"', $urlStart) - $urlStart);
			$url = 'http://www.mh.government.bg/' . $url;
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