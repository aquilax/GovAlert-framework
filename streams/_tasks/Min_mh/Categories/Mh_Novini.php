<?php

class Mh_Novini extends Min_mh{

	protected $categoryId = 1;
	protected $categoryName = 'новини';
	protected $categoryURL = 'http://www.mh.government.bg/News.aspx?pageid=401';

	function execute($html)
	{
		$items = $this->xpathDoc($html, "//table[@id='ctl00_ContentPlaceClient_ucNewsList_gvwNews']//tr[not(@class)]/td");

		$query = array();
		foreach ($items as $item) {
			$date = $item->childNodes->item(3)->textContent;
			$date = $this->cleanText($date);
			$date = explode(".", $date);
			$date = substr($date[2], 0, 4) . "-" . $date[1] . "-" . $date[0];

			if (strtotime($date) < strtotime("-1 month"))
				continue;

			$title = $item->childNodes->item(1)->textContent;
			$title = $this->cleanText($title);

			$description = $item->childNodes->item(5)->textContent;
			$description = $this->cleanText($description);

			if (mb_strlen($title) < 25)
				$title .= " " . $description;

			$url = $item->childNodes->item(1)->getAttribute("href");
			$urlstart = strpos($url, 'News.aspx');
			$url = substr($url, $urlstart, strpos($url, '"', $urlstart) - $urlstart);
			$url = "http://www.mh.government.bg/$url";
			$hash = md5($url);

			$media = null;
			if ($item->childNodes->item(5)->childNodes->item(1)->nodeName == "input") {
				$imageurl = $item->childNodes->item(5)->childNodes->item(1)->getAttribute("src");
				$imageurl = "http://www.mh.government.bg/$imageurl";
				$imageurl = mb_ereg_replace("small", "large", $imageurl, "im");
				$media = array("image" => array($this->loadItemImage($imageurl, []), null));
			}

			$query[] = array($title, $description, $date, $url, $hash, $media);
		}

		echo "Възможни " . count($query) . " нови новини\n";

		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids);
	}

} 