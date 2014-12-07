<?php

class TsoNovini extends Tso
{

	protected $categoryId = 0;
	protected $categoryName = 'новини';
	protected $categoryURL = 'http://www.tso.bg/default.aspx/novini/bg';

	function execute($html)
	{
		$xpath = $this->xpathDoc($html);
		if (!$xpath) return;
		$items = $xpath->query("//table[@id='ctl7_myDataList']//td");
		if (!$items) return;

		$query = array();
		foreach ($items as $item) {
			$title = $item->childNodes->item(1)->textContent;
			$title = "Новина: " . $this->cleanText($title);

			$description = $item->childNodes->item(4)->C14N();
			$description = mb_ereg_replace(" </", "</", mb_ereg_replace("> ", ">", $description));
			$description = mb_ereg_replace("\s?(title|name|style|class|id)=[\"'].*?[\"']\s?", "", $description);
			$description = mb_ereg_replace("<p>[  ]*</p>|<a>[  ]*</a>|<div>[  ]*</div>", "", $description);
			$description = $this->cleanText($description);

			$urlItems = $xpath->query(".//a", $item);
			if ($urlItems->length > 0) {
				$url = $urlItems->item(0)->getAttribute("href");
				$url = mb_strpos($url, "http") != 0 ? "http://www.tso.bg$url" : $url;
				$hash = md5($url);
			} else {
				$url = "http://www.tso.bg/default.aspx/novini/bg";
				$hash = md5($item->textContent);
			}

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