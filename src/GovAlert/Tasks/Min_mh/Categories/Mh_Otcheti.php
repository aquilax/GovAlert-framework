<?php

class Mh_Otcheti extends Min_mh
{

	protected $categoryId = 5;
	protected $categoryName = 'отчети';
	protected $categoryURL = 'http://www.mh.government.bg/Articles.aspx?lang=bg-BG&pageid=532';

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

			$url = $item->getAttribute("href");
			$urlstart = strpos($url, 'Articles.aspx');
			$url = substr($url, $urlstart, strpos($url, '"', $urlstart) - $urlstart);
			$url = "http://www.mh.government.bg/$url";
			$hash = md5($url);

			$query[] = [
				'title' => $title,
				'description' => null,
				'date' => Utils::now(),
				'url' => $url,
				'hash' => $hash,
			];
		}
		return $query;
	}
} 