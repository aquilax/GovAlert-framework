<?php

namespace GovAlert\Tasks\Min_mh;

class Mh_Novini extends Base
{
	protected $categoryId = 1;
	protected $categoryName = 'новини';
	protected $categoryURL = 'http://www.mh.government.bg/News.aspx?pageid=401';

	function execute($html)
	{
		$items = $this->getXPathItems(
			$this->getXPath($html),
			"//table[@id='ctl00_ContentPlaceClient_ucNewsList_gvwNews']//tr[not(@class)]/td"
		);

		$query = [];
		foreach ($items as $item) {
			if ($item->childNodes->length < 4) {
				// Ignore pagination
				continue;
			}
			$date = $item->childNodes->item(3)->textContent;
			$date = $this->cleanText($date);
			$date = explode('.', $date);
			if (count($date) < 3) {
				// Non valid date string
				continue;
			}
			$date = substr($date[2], 0, 4) . '-' . $date[1] . '-' . $date[0];

			if (strtotime($date) < $this->timeDiff('-1 month')) {
				continue;
			}
			$title = $item->childNodes->item(1)->textContent;
			$title = $this->cleanText($title);

			$description = $item->childNodes->item(5)->textContent;
			$description = $this->cleanText($description);

			if (mb_strlen($title) < 25) {
				$title .= ' ' . $description;
			}
			$url = $item->childNodes->item(1)->getAttribute('href');
			$urlStart = strpos($url, 'News.aspx');
			$url = substr($url, $urlStart, strpos($url, '"', $urlStart) - $urlStart);
			$url = 'http://www.mh.government.bg/' .$url;
			$hash = md5($url);

			$media = null;
			if ($item->childNodes->item(5)->childNodes->item(1)->nodeName == 'input') {
				$imageURL = $item->childNodes->item(5)->childNodes->item(1)->getAttribute('src');
				$imageURL = 'http://www.mh.government.bg/' . $imageURL;
				$imageURL = mb_ereg_replace('small', 'large', $imageURL, 'im');
				$media = ["image" => [$this->loadItemImage($imageURL), null]];
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