<?php

namespace GovAlert\Tasks\Government;

class GovNovini extends Base
{

	protected $categoryId = 4;
	protected $categoryName = 'новини';
	protected $categoryURL = 'http://www.government.bg/cgi-bin/e-cms/vis/vis.pl?s=001&p=0213&g=';

	function execute($html)
	{
		$doc = $this->getXPath($html, 'cp1251');
		$items = $this->getXPathItems($doc, "//table[@cellpadding=1]");

		$query = [];
		foreach ($items as $item) {
			$innerItems = $this->getXPathItems($doc, ".//td", $item);
			if ($innerItems->length != 4)
				continue;

			$date = $innerItems->item(1)->textContent;
			$date = Utils::bgMonth($date);
			$date = mb_substr($date, 6, 4) . "-" . mb_substr($date, 3, 2) . "-" . mb_substr($date, 0, 2);
			if (strtotime($date) < $this->timeDiff('-1 week'))
				continue;

			$url = "http://www.government.bg" . $innerItems->item(0)->firstChild->getAttribute("href");
			$hash = md5($url);

			$description = null;
			$media = null;
			$htmlSub = $this->loadURL($url, 3);
			$xpathSub = $this->getXPath($htmlSub, 'cp1251');
			$itemSub = $this->getXPathItems(
				$xpathSub,
				"//table[./tbody/tr/td/font[@style='FONT-SIZE: 11px; TEXT-TRANSFORM: uppercase']]"
			);

			if ($itemSub->length > 0) {
				$description = $itemSub->item(0)->C14N();
				$description = mb_ereg_replace(" </", "</", mb_ereg_replace("> ", ">", $description));
				$description = mb_ereg_replace("\s?(title|name|style|class|id)=[\"'].*?[\"']\s?", "", $description);
				$description = mb_ereg_replace("<p>[  ]*</p>|<a>[  ]*</a>|<div>[  ]*</div>", "", $description);
				$description = Utils::cleanSpaces($description);
				$description = html_entity_decode($description);

				$itemImages = $this->getXPathItems(
					$xpathSub,
					".//img",
					$itemSub->item(0)
				);

				if ($itemImages->length > 0) {
					$media = array("image" => array());
					foreach ($itemImages as $itemImg) {
						$imageURL = $itemImg->getAttribute("src");
						if (strpos($imageURL, "government.bg") === false)
							$imageURL = "http://www.government.bg/" . $imageURL;
						$imageURL = mb_ereg_replace("images", "bigimg", $imageURL, "im");
						$imageTitle = trim($itemImg->getAttribute("alt"));
						$imageTitle = Utils::cleanSpaces($imageTitle);
						$media["image"][] = array($this->loadItemImage($imageURL, []), $imageTitle);
					}
				}
			}

			$title = $innerItems->item(0)->firstChild->textContent;
			$title = Utils::cleanSpaces($title);

			$query[] = [
				'title' => $title,
				'description' => $description,
				'date' => $date,
				'url' => $url,
				'hash' => $hash,
			];
		}
		return $query;
	}
} 