<?php

class GovNovini2 extends Government
{

	protected $categoryId = 5;
	protected $categoryName = 'новини';
	protected $categoryURL = 'http://www.government.bg/cgi-bin/e-cms/vis/vis.pl?s=001&p=0212&g=';

	function execute($html)
	{
		$xpath = $this->getXPath($html, 'cp1251');
		$items = $this->getXPathItems(
			$xpath,
			"//table[@cellpadding=1]"
		);

		echo "Открити " . $items->length . " новини\n";

		$query = array();
		foreach ($items as $item) {
			$inneritems = $this->getXPathItems($xpath, ".//td", $item);

			if ($inneritems->length != 4)
				continue;

			$date = $inneritems->item(1)->textContent;
			$date = Utils::bgMonth($date);
			$date = mb_substr($date, 6, 4) . "-" . mb_substr($date, 3, 2) . "-" . mb_substr($date, 0, 2);
			if (strtotime($date) < strtotime("-1 week"))
				continue;

			$url = "http://www.government.bg" . $inneritems->item(0)->firstChild->getAttribute("href");
			$hash = md5($url);
			if (!$this->checkHash($hash))
				continue;

			$description = null;
			$media = null;
			$htmlsub = $this->loadURL($url, 3);

			$xpathsub = $this->getXPath($htmlsub, 'cp1251');
			$itemsub = $this->getXPathItems(
				$xpath,
				"//table[./tbody/tr/td/font[@style='FONT-SIZE: 11px; TEXT-TRANSFORM: uppercase']]"
			);

			if ($itemsub->length > 0) {
				$description = $itemsub->item(0)->C14N();
				$description = mb_ereg_replace(" </", "</", mb_ereg_replace("> ", ">", $description));
				$description = mb_ereg_replace("\s?(title|name|style|class|id)=[\"'].*?[\"']\s?", "", $description);
				$description = mb_ereg_replace("<p>[  ]*</p>|<a>[  ]*</a>|<div>[  ]*</div>", "", $description);
				$description = Utils::cleanSpaces($description);
				$description = html_entity_decode($description);

				$itemimgs = $xpathsub->query(".//img", $itemsub->item(0));
				if ($itemimgs->length > 0) {
					$media = array("image" => array());
					foreach ($itemimgs as $itemimg) {
						$imageurl = $itemimg->getAttribute("src");
						if (strpos($imageurl, "government.bg") === false)
							$imageurl = "http://www.government.bg/" . $imageurl;
						$imageurl = mb_ereg_replace("images", "bigimg", $imageurl, "im");
						$imagetitle = trim($itemimg->getAttribute("alt"));
						$imagetitle = Utils::cleanSpaces($imagetitle);
						$media["image"][] = array($this->loadItemImage($imageurl, []), $imagetitle);
					}
				}
			}

			$title = $inneritems->item(0)->firstChild->textContent;
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