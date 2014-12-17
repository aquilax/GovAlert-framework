<?php

class Prok_Novini extends Prokuratura
{

	protected $categoryId = 0;
	protected $categoryName = 'новини';
	protected $categoryURL = 'http://www.prb.bg/main/bg/News/';

	function execute($html)
	{
		$items = $this->getXPathItems($this->getXPath($html), "//div[@class='list-inner']");

		$query = array();
		foreach ($items as $item) {
			$hasimage = $item->childNodes->item(1)->nodeName == "a";

			$date = trim($item->childNodes->item(3 + ($hasimage ? 2 : 0))->textContent);
			$date = mb_substr($date, 6, 4) . "-" . mb_substr($date, 3, 2) . "-" . mb_substr($date, 0, 2);
			if (strtotime($date) < strtotime("-2 weeks"))
				continue;
			$description = trim($item->childNodes->item(5 + ($hasimage ? 2 : 0))->textContent);
			$description = $this->cleanText($description);

			$title = trim($item->childNodes->item(1 + ($hasimage ? 2 : 0))->textContent);
			$title = $this->cleanTitle($title);
			$title = $this->cleanText($title);

			$url = "http://www.prb.bg" . $item->childNodes->item(1)->firstChild->getAttribute("href");
			$hash = md5($url);
			$media = null;
			if ($hasimage) {
				$imageurl = $item->childNodes->item(1)->firstChild->getAttribute("src");
				$imageurl = mb_ereg_replace("logo", "big", $imageurl, "im");
				$imageurl = "http://www.prb.bg$imageurl";
				$imagetitle = trim($item->childNodes->item(3)->textContent);
				$imagetitle = $this->cleanTitle($imagetitle);
				$imagetitle = $this->cleanText($imagetitle);
				$media = array("image" => array($this->loadItemImage($imageurl, []), $imagetitle));
			}

			$query[] = [
				'title' => $title,
				'description' => $description,
				'date' => $date,
				'url' => $url,
				'hash' => $hash,
				'media' => $media,
			];
		}
		return $query;
	}
} 