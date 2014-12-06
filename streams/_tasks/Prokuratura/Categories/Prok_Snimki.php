<?php

class Prok_Snimki extends Prokuratura
{

	protected $categoryId = 3;
	protected $categoryName = 'галерии';
	protected $categoryURL = 'http://www.prb.bg/main/bg/gallery/';

	function execute($html)
	{
		$items = $this->xpathDoc($html, "//div[@class='list-inner']");

		$query = array();
		foreach ($items as $item) {
			$date = trim($item->childNodes->item(5)->textContent);
			$date = mb_substr($date, 6, 4) . "-" . mb_substr($date, 3, 2) . "-" . mb_substr($date, 0, 2);
			if (strtotime($date) < strtotime("-2 weeks"))
				continue;

			$title = trim($item->childNodes->item(3)->textContent);
			$title = $this->cleanTitle($title);
			$title = "Снимки: " . $title;
			$title = $this->cleanText($title);

			$url = "http://www.prb.bg" . $item->childNodes->item(1)->getAttribute("href");
			$hash = md5($url);
			$media = array("image" => array());
			$mhtml = $this->loadURL($url);
			if (!$mhtml)
				continue;

			$mitems = $this->xpathDoc($mhtml, "//a[@class='thumb']");
			foreach ($mitems as $mitem) {
				$imageurl = $mitem->getAttribute("href");
				$imageurl = "http://www.prb.bg$imageurl";
				$imageurl = str_replace(array("logo", "pic"), "big", $imageurl);
				$imageurl = $this->loadItemImage($imageurl, []);
				if ($imageurl)
					$media["image"][] = array($imageurl);
			}

			if (count($media["image"]) == 0)
				$media = null;

			$query[] = array($title, null, $date, $url, $hash, $media);
		}

		echo "Възможни " . count($query) . " нови галерии\n";
		$itemids = $this->saveItems($query);
		$this->queueTweets($itemids);
	}

} 