<?php

class ParlZakoni extends Parliament
{

	protected $categoryId = 3;
	protected $categoryName = 'закони';
	protected $categoryURL = 'http://parliament.bg/bg/laws';
	protected $tweetReTweet = nill;

	function execute($html)
	{
		$xpath = $this->xpathDoc($html);
		if (!$xpath) return;
		$items = $xpath->query("//table[@class='billsresult']//tr[not(@class)]");
		if (is_null($items)) return;

		$query = array();
		foreach ($items as $item) {
			$hash = md5($item->childNodes->item(0)->childNodes->item(1)->getAttribute("href"));
			$date = trim($item->childNodes->item(2)->textContent);
			$date = substr($date, 6, 4) . "-" . substr($date, 3, 2) . "-" . substr($date, 0, 2);
			if (strtotime($date) < strtotime("-1 month"))
				continue;
			$url = $item->childNodes->item(0)->childNodes->item(1)->getAttribute("href");
			$title_c = $item->childNodes->item(4)->textContent;
			$title_c = $this->cleanText($title_c);
			$title = $item->childNodes->item(0)->textContent;
			if (mb_strlen($title) > 88)
				$title = mb_ereg_replace("Закон за изменение и допълнение", "ЗИД", $title, "im");
			$title = "ДВ-$title_c/ " . $this->cleanText($title);
			$query[] = [
				'title' => $title,
				'description' => null,
				'date' => Utils::now(),
				'url' => 'http://parliament.bg' . $url,
				'hash' => $hash,
			];
		}
		return $query;
	}


}