<?php

class Mh_Normativni extends Min_mh
{

	protected $categoryId = 2;
	protected $categoryName = 'нормативни актове';
	protected $categoryURL = 'http://www.mh.government.bg/Articles.aspx?lang=bg-BG&pageid=393';

	function execute($html)
	{
		$items = $this->xpathDoc($html, "//table[@id='ctl00_ContentPlaceClient_ucArticlesList_gvArticles']//tr[not(@class)]/td");

		$query = array();
		foreach ($items as $item) {
			$date = $item->childNodes->item(3)->textContent;
			$date = $this->cleanText($date);
			$date = explode(".", $date);
			$date = substr($date[2], 0, 4) . "-" . $date[1] . "-" . substr($date[0], -2);

			if (strtotime($date) < strtotime("-1 month"))
				continue;

			$title = $item->childNodes->item(1)->textContent;
			$title = $this->cleanText($title);

			$url = $item->childNodes->item(1)->getAttribute("href");
			$urlstart = strpos($url, 'Articles.aspx');
			$url = substr($url, $urlstart, strpos($url, '"', $urlstart) - $urlstart);
			$url = "http://www.mh.government.bg/$url";
			$hash = md5($url);

			$query[] = [
				'title' => $title,
				'description' => null,
				'date' => $date,
				'url' => $url,
				'hash' => $hash,
			];
		}

		echo "Възможни " . count($query) . " нови нормативни актове\n";

		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids);
	}

} 